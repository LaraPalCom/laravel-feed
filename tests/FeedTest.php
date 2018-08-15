<?php

namespace Tests;

use Roumen\Feed\Feed;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    /**
     * @var Feed
     */
    protected $feed;

    protected function setUp()
    {
        parent::setUp();

        $this->feed = new Feed();
    }

    public function testFeedAttributes()
    {
        $this->feed->title = 'TestTitle';
        $this->feed->subtitle = 'TestSubtitle';
        $this->feed->description = 'TestDescription';
        $this->feed->domain = 'https://damianoff.com/';
        $this->feed->link = 'https://damianoff.com/';
        $this->feed->ref = 'hub';
        $this->feed->logo = "https://damianoff.com/favicon.png";
        $this->feed->icon = "https://damianoff.com/favicon.png";
        $this->feed->pubdate = '2014-02-29 00:00:00';
        $this->feed->lang = 'en';
        $this->feed->copyright = 'All rights reserved by Foobar Corporation';
        $this->feed->color = '00FF00';
        $this->feed->cover = 'http://domain.tld/images/cover.png';
        $this->feed->ga = 'UA-1525185-18';
        $this->feed->related = false;
        $this->feed->duration = '00:00:00';

        $this->assertSame('TestTitle', $this->feed->title);
        $this->assertSame('TestSubtitle', $this->feed->subtitle);
        $this->assertSame('TestDescription', $this->feed->description);
        $this->assertSame('https://damianoff.com/', $this->feed->domain);
        $this->assertSame('https://damianoff.com/', $this->feed->link);
        $this->assertSame('hub', $this->feed->ref);
        $this->assertSame("https://damianoff.com/favicon.png", $this->feed->logo);
        $this->assertSame("https://damianoff.com/favicon.png", $this->feed->icon);
        $this->assertSame('2014-02-29 00:00:00', $this->feed->pubdate);
        $this->assertSame('en', $this->feed->lang);
        $this->assertSame('All rights reserved by Foobar Corporation', $this->feed->copyright);
        $this->assertSame('00FF00', $this->feed->color);
        $this->assertSame('http://domain.tld/images/cover.png', $this->feed->cover);
        $this->assertSame('UA-1525185-18', $this->feed->ga);
        $this->assertSame(false, $this->feed->related);
    }

    public function testFeedAdd()
    {
        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>', '<p>TestContent</p>', ['url' => 'http://foobar.dev/someThing.jpg','type' => 'image/jpeg'], 'testCategory', 'testSubtitle');
        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>');

        $items = $this->feed->getItems();

        $this->assertCount(2, $items);

        $this->assertSame('TestTitle', $items[0]['title']);
        $this->assertSame('TestAuthor', $items[0]['author']);
        $this->assertSame('TestUrl', $items[0]['link']);
        $this->assertSame('2014-02-29 00:00:00', $items[0]['pubdate']);
        $this->assertSame('<p>TestResume</p>', $items[0]['description']);
        $this->assertSame('<p>TestContent</p>', $items[0]['content']);
        $this->assertSame('http://foobar.dev/someThing.jpg', $items[0]['enclosure']['url']);
        $this->assertSame('testCategory', $items[0]['category']);
        $this->assertSame('testSubtitle', $items[0]['subtitle']);
    }

    public function testFeedAddItem()
    {
        $this->feed->addItem([
            'title' => 'TestTitle',
            'author' => 'TestAuthor',
            'link' => 'TestUrl',
            'pubdate' => '2014-02-29 00:00:00',
            'description' => '<p>TestResume</p>',
            'content' => '<p>TestContent</p>',
            'category' => 'testCategory',
            'enclosure' => ['url'=>'http://foobar.dev/someThing.jpg', 'type' => 'image/jpeg'],
            'duration'  => '00:00:00'
        ]);

        $this->feed->addItem([
            'title' => 'TestTitle2',
            'author' => 'TestAuthor2',
            'link' => 'TestUrl2',
            'pubdate' => '2014-02-29 00:00:00',
            'description' => '<p>TestResume2</p>'
        ]);

        // add multidimensional array
        $this->feed->addItem([
            [
                'title' => 'TestTitle3',
                'author' => 'TestAuthor3',
                'link' => 'TestUrl3',
                'pubdate' => '2014-02-29 00:00:00',
                'description' => '<p>TestResume3</p>'
            ],
            [
                'title' => 'TestTitle4',
                'author' => 'TestAuthor4',
                'link' => 'TestUrl4',
                'pubdate' => '2014-02-29 00:00:00',
                'description' => '<p>TestResume4</p>'
            ],
            [
                'title' => 'TestTitle5',
                'author' => 'TestAuthor5',
                'link' => 'TestUrl5',
                'pubdate' => '2014-02-29 00:00:00',
                'description' => '<p>TestResume5</p>'
            ]
        ]);

        // get items
        $items = $this->feed->getItems();

        // count items
        $this->assertCount(5, $items);

        // check items
        $this->assertSame('TestTitle', $items[0]['title']);
        $this->assertSame('TestAuthor', $items[0]['author']);
        $this->assertSame('TestUrl', $items[0]['link']);
        $this->assertSame('2014-02-29 00:00:00', $items[0]['pubdate']);
        $this->assertSame('<p>TestResume</p>', $items[0]['description']);
        $this->assertSame('<p>TestContent</p>', $items[0]['content']);
        $this->assertSame('http://foobar.dev/someThing.jpg', $items[0]['enclosure']['url']);
        $this->assertSame('testCategory', $items[0]['category']);
        $this->assertSame('TestTitle5', $items[4]['title']);
        $this->assertSame('00:00:00', $items[0]['duration']);
    }

    public function testFeedLink()
    {
        // default formats
        $this->assertSame('<link rel="alternate" type="application/atom+xml" href="http://domain.tld/feed">', Feed::link('http://domain.tld/feed', 'atom'));
        $this->assertSame('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed">', Feed::link('http://domain.tld/feed', 'rss'));

        // with custom type
        $this->assertSame('<link rel="alternate" type="text/xml" href="http://domain.tld/feed">', Feed::link('http://domain.tld/feed', 'text/xml'));

        // with title
        $this->assertSame('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed" title="Feed: RSS">', Feed::link('http://domain.tld/feed', 'rss', 'Feed: RSS'));

        // with title and lang
        $this->assertSame('<link rel="alternate" hreflang="en" type="application/atom+xml" href="http://domain.tld/feed" title="Feed: Atom">', Feed::link('http://domain.tld/feed', 'atom', 'Feed: Atom', 'en'));
    }
}
