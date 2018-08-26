<?php namespace Laravelium\Feed;

/**
 * Feed generator class for laravel-feed package.
 *
 * @author Roumen Damianoff <roumen@damianoff.com>
 * @version 3.0
 * @link https://laravelium.com
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Filesystem\Filesystem as Filesystem;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactory;
use Illuminate\View\Factory as ViewFactory;

class Feed
{
	const DEFAULT_REF = 'self';

	/**
	 * CacheRepository instance
	 *
	 * @var CacheRepository $cache
	 */
	public $cache = null;

	/**
	 * ConfigRepository instance
	 *
	 * @var ConfigRepository $configRepository
	 */
	 protected $configRepository = null;

	 /**
	 * Filesystem instance
	 *
	 * @var Filesystem $file
	 */
	 protected $file = null;

	 /**
	 * ResponseFactory instance
	 *
	 * @var ResponseFactory $response
	 */
	 protected $response = null;

	 /**
	 * ViewFactory instance
	 *
	 * @var ViewFactory $view
	 */
	 protected $view = null;

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
	public $subtitle = 'My feed subtitle';

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
     * Item duration (optional)
     * @var string
     */
	public $duration;

	/**
	 * Using constructor we populate our model from configuration file
	 * and loading dependencies
	 *
	 * @param array $config
	 */
	public function __construct(array $config, CacheRepository $cache, ConfigRepository $configRepository, Filesystem $file, ResponseFactory $response, ViewFactory $view)
	{
		$this->cache = $cache;
		$this->configRepository = $configRepository;
		$this->file = $file;
		$this->response = $response;
		$this->view = $view;

	}

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
	 * @param string $subtitle (optional)
     * @param string $duration (optional)
	 *
	 * @return void
	 */
	public function add($title, $author, $link, $pubdate, $description, $content='', $enclosure = [], $category='', $subtitle='', $duration ='')
	{
		// append ... to description
		$append = '';
		// shortening the description
		if ($this->shortening)
		{
			if(strlen($description) > $this->shorteningLimit){
				//adds ... for shortened description
				$append = '...';
			}
			$description = mb_substr($description, 0, $this->shorteningLimit, 'UTF-8') . $append;
		}

		// add to items
		$this->setItem([
			'title' => htmlspecialchars(strip_tags($title), ENT_COMPAT, 'UTF-8'),
			'author' => $author,
			'link' => $link,
			'pubdate' => $pubdate,
			'description' => $description,
			'content' => $content,
			'enclosure' => $enclosure,
			'category' => $category,
			'subtitle' => htmlspecialchars(strip_tags($subtitle), ENT_COMPAT, 'UTF-8'),
      		'duration' => $duration
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
			if(strlen($item['description']) > $this->shorteningLimit){
				//adds '...'' for shortened description
				$append = '...';
			}

			$item['description'] = mb_substr($item['description'], 0, $this->shorteningLimit, 'UTF-8') . $append;
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
		if ($this->caching > 0 && $this->cache->has($this->cacheKey))
		{
			return $this->response->make($this->cache->get($this->cacheKey), 200, array('Content-Type' => $this->ctype.'; charset='.$this->charset));
		}

		if (empty($this->lang)) $this->lang = $this->configRepository->get('application.language');
		if (empty($this->link)) $this->link = $this->configRepository->get('application.url');
		if (empty($this->ref)) $this->ref = self::DEFAULT_REF;
		if (empty($this->pubdate)) $this->pubdate = date('D, d M Y H:i:s O');

		$channel = [
			'title'         =>  htmlspecialchars(strip_tags($this->title), ENT_COMPAT, 'UTF-8'),
			'subtitle'      =>  htmlspecialchars(strip_tags($this->subtitle), ENT_COMPAT, 'UTF-8'),
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
			$this->cache->put($this->cacheKey, $this->view->make($this->getView($this->customView), $viewData)->render(), $this->caching);

			return $this->response->make($this->cache->get($this->cacheKey), 200, array('Content-Type' => $this->ctype.'; charset='.$this->charset));
		}
		else
		{
			// if cache is 0 delete the key (if exists) and return response
			$this->clearCache();

			return $this->response->make($this->view->make($this->getView($this->customView), $viewData), 200, array('Content-Type' => $this->ctype.'; charset='.$this->charset));
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

		if ($this->cache->has($this->cacheKey))
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
		if ($this->isCached()) $this->cache->forget($this->cacheKey);
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
	 * @param string $view
	 *
	 * @return void
	 */
	public function getView($view=null)
	{
		// if a custom view is set
		if (null != $view)
		{
			return 'feed::'.$view;
		}
		else if (null != $this->customView)
		{
			return $this->customView;
		}
		else
		{
			return 'feed::atom';
		}

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

	// TODO : documentation
	public function getTextLimit()
	{
		return $this->shorteningLimit;
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

	// TODO : documentation
	public function getShortening()
	{
		return $this->shortening;
	}

	/**
	 * Format datetime string, timestamp integer or carbon object in valid feed format
	 *
	 * @param string/integer $date
	 *
	 * @return string
	 */
	public function formatDate($date, $format='atom')
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
	 * Getter for dateFormat
	 *
	 * @param string $format
	 *
	 * @return void
	 */
	public function getDateFormat()
	{
		return $this->dateFormat;
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
	public function getRssLink()
	{
		$rssLink = request()->url();

		if ( !empty($this->domain) ) 
		{
			$rssLink = sprintf('%s/%s', rtrim($this->domain, '/'), ltrim(request()->path(), '/'));
		}

		return $rssLink;
	}

	/**
	 * Returns $CacheKey value
	 *
	 * @return string
	 */
	public function getCacheKey()
	{
		return $this->cacheKey;
	}

	/**
	 * Returns $caching value
	 *
	 * @return string
	 */
	public function getCacheDuration()
	{
		return $this->caching;
	}

}