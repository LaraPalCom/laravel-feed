<?php namespace Roumen\Feed;
/**
 * Feed generator class for laravel-feed package.
 *
 * @author Roumen Damianoff <roumen@dawebs.com>
 * @version 2.6.7
 * @link http://roumen.it/projects/laravel-feed
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use Config;
use Response;
use View;
use Cache;

class Feed
{

    public $items = array();
    public $title = 'My feed title';
    public $description = 'My feed description';
    public $link;
    public $logo;
    public $icon;
    public $pubdate;
    public $lang;
    public $charset = 'utf-8';
    public $ctype = 'application/atom+xml';

    /**
     * Returns new instance of Feed class
     *
     * @return Feed
     */
    public function make()
    {
        return new Feed();
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
     *
     * @return void
     */
    public function add($title, $author, $link, $pubdate, $description, $content='')
    {
        $this->items[] = array(
            'title' => $title,
            'author' => $author,
            'link' => $link,
            'pubdate' => $pubdate,
            'description' => $description,
            'content' => $content
        );
    }


    /**
     * Returns aggregated feed with all items from $items array
     *
     * @param string $format (options: 'atom', 'rss')
     * @param carbon|datetime|integer $cache (0 - turns off the cache)
     *
     * @return view
     */
    public function render($format = 'atom', $cache = 0, $key = 'laravel-feed')
    {
        if (empty($this->lang)) $this->lang = Config::get('application.language');
        if (empty($this->link)) $this->link = Config::get('application.url');
        if (empty($this->pubdate)) $this->pubdate = date('D, d M Y H:i:s O');

        $channel = array(
            'title'=>$this->title,
            'description'=>$this->description,
            'logo' => $this->logo,
            'icon' => $this->icon,
            'link'=>$this->link,
            'pubdate'=>$this->pubdate,
            'lang'=>$this->lang
        );

        if ($format == 'rss')
        {
            $this->ctype = 'application/rss+xml';

            $channel['title'] = html_entity_decode(strip_tags($channel['title']));
            $channel['description'] = html_entity_decode(strip_tags($channel['description']));

            foreach($this->items as $k => $v)
            {
                $this->items[$k]['description'] = html_entity_decode(strip_tags($this->items[$k]['description']));
                $this->items[$k]['title'] = html_entity_decode(strip_tags($this->items[$k]['title']));
            }
        }

        // cache check
        if ($cache > 0)
        {
            if (Cache::has($key))
            {
                return Response::make(Cache::get($key), 200, array('Content-type' => $this->ctype.'; charset='.$this->charset));
            } else
                {
                    Cache::put($key, View::make('feed::'.$format, array('items' => $this->items, 'channel' => $channel))->render(), $cache);

                    return Response::make(Cache::get($key), 200, array('Content-type' => $this->ctype.'; charset='.$this->charset));
                }

        } else if ($cache < 0)
            {
                return View::make('feed::'.$format, array('items' => $this->items, 'channel' => $channel))->render();
            } else
                {
                    return Response::make(View::make('feed::'.$format, array('items' => $this->items, 'channel' => $channel)), 200, array('Content-type' => $this->ctype.'; charset='.$this->charset));
                }

     }

}