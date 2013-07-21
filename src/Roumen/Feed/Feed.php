<?php namespace Roumen\Feed;
/**
 * Feed generator class for laravel4-feed package.
 *
 * @author Roumen Damianoff <roumen@dawebs.com>
 * @version 2.2
 * @link http://roumen.me/projects/laravel4-feed
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

use Config;
use Response;
use View;

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
     * Add new item to $items array
     *
     * @param string $title
     * @param string $author
     * @param string $link
     * @param string $pubdate
     * @param string $description
     *
     * @return void
     */
    public function add($title, $author, $link, $pubdate, $description)
    {
        $this->items[] = array(
            'title' => $title,
            'author' => $author,
            'link' => $link,
            'pubdate' => $pubdate,
            'description' => $description
        );
    }


    /**
     * Returns aggregated feed with all items from $items array
     *
     * @param string $format (options: 'atom', 'rss')
     *
     * @return view
     */
    public function render($format = 'atom')
    {
        if (empty($this->lang)) $this->lang = Config::get('application.language');
        if (empty($this->link)) $this->link = Config::get('application.url');
        if (empty($this->pubdate)) $this->pubdate = date('D, d M Y H:i:s O');
        if ($format == 'rss') $this->ctype = 'application/rss+xml';

        $channel = array(
            'title'=>$this->title,
            'description'=>$this->description,
            'logo' => $this->logo,
            'icon' => $this->icon,
            'link'=>$this->link,
            'pubdate'=>$this->pubdate,
            'lang'=>$this->lang
        );

        return Response::make(View::make('feed::'.$format, array('items' => $this->items, 'channel' => $channel) ), 200, array('Content-type' => $this->ctype.'; charset='.$this->charset));
    }

}