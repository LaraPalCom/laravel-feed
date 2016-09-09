<?php namespace Roumen\Feed;

/**
 * Feed generator class for laravel-feed package.
 *
 * @author Roumen Damianoff <roumen@crimsson.com>
 * @version 2.10.5
 * @link https://roumen.it/projects/laravel-feed
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class Feed
{
    const DEFAULT_REF = 'self';

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var string
     */
    public $title = 'My feed title';

    /**
     * @var string
     */
    public $description = 'My feed description';

    /**
     * @var string
     */
    public $domain;

    /**
     * @var string
     */
    public $link;

    /**
     * @var string
     */
    public $ref;

    /**
     * @var string
     */
    public $logo;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $cover;

    /**
     * @var string
     */
    public $color;

    /**
     * @var string
     */
    public $ga;

    /**
     * @var boolean
     */
    public $related = false;

    /**
     * @var string
     */
    public $copyright = '';

    /**
     * @var string
     */
    public $pubdate;

    /**
     * @var string
     */
    public $lang;

    /**
     * @var string
     */
    public $charset = 'utf-8';

    /**
     * @var string
     */
    public $ctype = null;

    /**
     * @var integer
     */
    private $caching = 0;

    /**
     * @var string
     */
    private $cacheKey = 'laravel-feed';

    /**
     * @var boolean
     */
    private $shortening = false;

    /**
     * @var integer
     */
    private $shorteningLimit = 150;

    /**
     * @var string
     */
    private $dateFormat = 'datetime';

    /**
     * @var array
     */
    private $namespaces = [];

    /**
     * @var string
     */
    private $customView = null;

    /**
     * Add new item to $items array
     *
     * @param string $title
     * @param string $author
     * @param string $link
     * @param string $pubdate
     * @param string $description
     * @param string $content
     * @param array $enclosure (optional)
     * @param string $category (optional)
     *
     * @return void
     */
    public function add($title, $author, $link, $pubdate, $description, $content='', $enclosure = [], $category='')
    {
        // shortening the description
        if ($this->shortening)
        {
            $description = mb_substr($description, 0, $this->shorteningLimit, 'UTF-8');
        }

        // add to items
        $this->setItem([
            'title' => $title,
            'author' => $author,
            'link' => $link,
            'pubdate' => $pubdate,
            'description' => $description,
            'content' => $content,
            'enclosure' => $enclosure,
            'category' => $category
        ]);
    }

    /**
     * Add new items to $items array
     *
     * @param array $a
     *
     * @return void
     */
    public function addItem(array $item)
    {
        // if is multidimensional
        if (array_key_exists(1, $item))
        {
            foreach ($item as $i)
            {
                $this->addItem($i);
            }

            return;
        }

        if ($this->shortening)
        {
            $item['description'] = mb_substr($item['description'], 0, $this->shorteningLimit, 'UTF-8');
        }

        $this->setItem($item);
    }

    /**
     * Returns aggregated feed with all items from $items array
     *
     * @param string $format (options: 'atom', 'rss')
     * @param carbon|datetime|integer $cache (0 - turns off the cache)
     * @param string $key
     *
     * @return view
     */
    public function render($format = null, $cache = null, $key = null)
    {

        if ($format == null && $this->customView == null) $format = "atom";
        if ($this->customView == null) $this->customView = $format;
        if ($cache != null) $this->caching = $cache;
        if ($key != null) $this->cacheKey = $key;

        if ($this->ctype == null)
        {
            ($format == 'rss') ? $this->ctype = 'application/rss+xml' : $this->ctype = 'application/atom+xml';
        }

        // if cache is on and there is cached feed => return it
        if ($this->caching > 0 && Cache::has($this->cacheKey))
        {
            return Response::make(Cache::get($this->cacheKey), 200, array('Content-Type' => $this->ctype.'; charset='.$this->charset));
        }

        if (empty($this->lang)) $this->lang = Config::get('application.language');
        if (empty($this->link)) $this->link = Config::get('application.url');
        if (empty($this->ref)) $this->ref = self::DEFAULT_REF;
        if (empty($this->pubdate)) $this->pubdate = date('D, d M Y H:i:s O');

        foreach($this->items as $k => $v)
        {
            $this->items[$k]['title'] = htmlspecialchars(strip_tags($this->items[$k]['title']), ENT_COMPAT, 'UTF-8');
            $this->items[$k]['pubdate'] = $this->formatDate($this->items[$k]['pubdate'], $format);
        }

        $channel = [
            'title'         =>  htmlspecialchars(strip_tags($this->title), ENT_COMPAT, 'UTF-8'),
            'description'   =>  $this->description,
            'logo'          =>  $this->logo,
            'icon'          =>  $this->icon,
            'color'         =>  $this->color,
            'cover'         =>  $this->cover,
            'ga'            =>  $this->ga,
            'related'       =>  $this->related,
            'rssLink'       =>  $this->getRssLink(),
            'link'          =>  $this->link,
            'ref'           =>  $this->ref,
            'pubdate'       =>  $this->formatDate($this->pubdate, $format),
            'lang'          =>  $this->lang,
            'copyright'     =>  $this->copyright
        ];

        $viewData = [
            'items'         => $this->items,
            'channel'       => $channel,
            'namespaces'    => $this->getNamespaces()
        ];

        // if cache is on put this feed in cache and return it
        if ($this->caching > 0)
        {
            Cache::put($this->cacheKey, View::make($this->getView($this->customView), $viewData)->render(), $this->caching);

            return Response::make(Cache::get($this->cacheKey), 200, array('Content-Type' => $this->ctype.'; charset='.$this->charset));
        }
        else if ($this->caching == 0)
        {
            // if cache is 0 delete the key (if exists) and return response
            $this->clearCache();

            return Response::make(View::make($this->getView($this->customView), $viewData), 200, array('Content-Type' => $this->ctype.'; charset='.$this->charset));
        }
        else if ($this->caching < 0)
        {
            // if cache is negative value delete the key (if exists) and return cachable object
            $this->clearCache();

            return View::make($this->getView($this->customView), $viewData)->render();
        }

     }

     /**
      * Create link
      *
      * @param string $url
      * @param string $type
      * @param string $title
      * @param string $lang
      *
      * @return string
      */
     public static function link($url, $type='atom', $title=null, $lang=null)
     {

        if ($type == 'rss') $type = 'application/rss+xml';
        if ($type == 'atom') $type = 'application/atom+xml';
        if ($title != null) $title = ' title="'.$title.'"';
        if ($lang != null) $lang = ' hreflang="'.$lang.'"';

        return '<link rel="alternate"'.$lang.' type="'.$type.'" href="'.$url.'"'.$title.'>';
     }

    /**
     * Check if feed is cached
     *
     * @return bool
     */
    public function isCached()
    {

        if (Cache::has($this->cacheKey))
        {
            return true;
        }

        return false;
    }

    /**
     * Clear the cache
     *
     * @return void
     */
    public function clearCache()
    {
        if ($this->isCached()) Cache::forget($this->cacheKey);
    }

    /**
     * Set cache duration and key
     *
     * @return void
     */
    public function setCache($duration=60, $key="laravel-feed")
    {
        $this->cacheKey = $key;
        $this->caching = $duration;

        if ($duration < 1) $this->clearCache();
    }

    /**
     * Get view name
     *
     * @param string $format
     *
     * @return void
     */
    public function getView($format)
    {
        // if a custom view is set
        if ($this->customView !== null && View::exists($this->customView))
        {
            return $this->customView;
        }

        // else return default view
        return 'feed::'.$format;
    }

    /**
     * Set Custom view
     *
     * @param string $name
     *
     * @return void
     */
    public function setView($name=null)
    {
        $this->customView = $name;
    }

    /**
     * Set maximum characters lenght for text shortening
     *
     * @param integer $l
     *
     * @return void
     */
    public function setTextLimit($l=150)
    {
        $this->shorteningLimit = $l;
    }

    /**
     * Turn on/off text shortening for item content
     *
     * @param boolean $b
     *
     * @return void
     */
    public function setShortening($b=false)
    {
        $this->shortening = $b;
    }

    /**
     * Format datetime string, timestamp integer or carbon object in valid feed format
     *
     * @param string/integer $date
     *
     * @return string
     */
    private function formatDate($date, $format='atom')
    {
        if ($format == "atom")
        {
            switch ($this->dateFormat)
            {
                case "carbon":
                    $date = date('c', strtotime($date->toDateTimeString()));
                    break;
                case "timestamp":
                    $date = date('c', strtotime('@'.$date));
                    break;
                case "datetime":
                    $date = date('c', strtotime($date));
                    break;
            }
        }
        else
        {
            switch ($this->dateFormat)
            {
                case "carbon":
                    $date = date('D, d M Y H:i:s O', strtotime($date->toDateTimeString()));
                    break;
                case "timestamp":
                    $date = date('D, d M Y H:i:s O', strtotime('@'.$date));
                    break;
                case "datetime":
                    $date = date('D, d M Y H:i:s O', strtotime($date));
                    break;
            }
        }

        return $date;
    }

    /**
     * Add namespace
     *
     * @param string $n
     *
     * @return void
     */
    public function addNamespace($n)
    {
        $this->namespaces[] = $n;
    }

    /**
     * Get all namespaces
     *
     * @param string $n
     *
     * @return void
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * Setter for dateFormat
     *
     * @param string $format
     *
     * @return void
     */
    public function setDateFormat($format="datetime")
    {
        $this->dateFormat = $format;
    }

    /**
     * Returns $items array
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Adds item to $items array
     *
     * @param array $item
     */
    public function setItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * Generate rss link
     *
     * @author Cara Wang <caraw@cnyes.com>
     * @since  2016/09/09
     */
    private function getRssLink()
    {
        $rssLink = Request::url();
        if (!empty($this->domain)) {
            $rssLink = sprintf('%s/%s', rtrim($this->domain, '/'), ltrim(Request::path(), '/'));
        }

        return $rssLink;
    }
}
