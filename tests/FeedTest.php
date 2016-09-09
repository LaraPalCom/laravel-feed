<?php

class FeedTest extends Orchestra\Testbench\TestCase
{
    protected $feed;

    public function setUp()
    {
        parent::setUp();

        $this->feed = new Roumen\Feed\Feed;
    }

    public function testFeedAttributes()
    {
        $this->feed->title = 'TestTitle';
        $this->feed->description = 'TestDescription';
        $this->feed->domain = 'http://roumen.it/';
        $this->feed->link = 'http://roumen.it/';
        $this->feed->ref = 'hub';
        $this->feed->logo = "http://roumen.it/favicon.png";
        $this->feed->icon = "http://roumen.it/favicon.png";
        $this->feed->pubdate = '2014-02-29 00:00:00';
        $this->feed->lang = 'en';
        $this->feed->copyright = 'All rights reserved by Foobar Corporation';
        $this->feed->color = '00FF00';
        $this->feed->cover = 'http://domain.tld/images/cover.png';
        $this->feed->ga = 'UA-1525185-18';
        $this->feed->related = false;

        $this->assertEquals('TestTitle', $this->feed->title);
        $this->assertEquals('TestDescription', $this->feed->description);
        $this->assertEquals('http://roumen.it/', $this->feed->domain);
        $this->assertEquals('http://roumen.it/', $this->feed->link);
        $this->assertEquals('hub', $this->feed->ref);
        $this->assertEquals("http://roumen.it/favicon.png", $this->feed->logo);
        $this->assertEquals("http://roumen.it/favicon.png", $this->feed->icon);
        $this->assertEquals('2014-02-29 00:00:00', $this->feed->pubdate);
        $this->assertEquals('en', $this->feed->lang);
        $this->assertEquals('All rights reserved by Foobar Corporation', $this->feed->copyright);
        $this->assertEquals('00FF00', $this->feed->color);
        $this->assertEquals('http://domain.tld/images/cover.png', $this->feed->cover);
        $this->assertEquals('UA-1525185-18', $this->feed->ga);
        $this->assertEquals(false, $this->feed->related);
    }

    public function testFeedAdd()
    {
        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>', '<p>TestContent</p>', ['url' => 'http://foobar.dev/someThing.jpg','type' => 'image/jpeg'], 'testCategory');
        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>');

        $items = $this->feed->getItems();

        $this->assertCount(2, $items);

        $this->assertEquals('TestTitle', $items[0]['title']);
        $this->assertEquals('TestAuthor', $items[0]['author']);
        $this->assertEquals('TestUrl', $items[0]['link']);
        $this->assertEquals('2014-02-29 00:00:00', $items[0]['pubdate']);
        $this->assertEquals('<p>TestResume</p>', $items[0]['description']);
        $this->assertEquals('<p>TestContent</p>', $items[0]['content']);
        $this->assertEquals('http://foobar.dev/someThing.jpg', $items[0]['enclosure']['url']);
        $this->assertEquals('testCategory', $items[0]['category']);
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
            'enclosure' => ['url'=>'http://foobar.dev/someThing.jpg', 'type' => 'image/jpeg']
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
        $this->assertEquals('TestTitle', $items[0]['title']);
        $this->assertEquals('TestAuthor', $items[0]['author']);
        $this->assertEquals('TestUrl', $items[0]['link']);
        $this->assertEquals('2014-02-29 00:00:00', $items[0]['pubdate']);
        $this->assertEquals('<p>TestResume</p>', $items[0]['description']);
        $this->assertEquals('<p>TestContent</p>', $items[0]['content']);
        $this->assertEquals('http://foobar.dev/someThing.jpg', $items[0]['enclosure']['url']);
        $this->assertEquals('testCategory', $items[0]['category']);
        $this->assertEquals('TestTitle5', $items[4]['title']);
    }

    public function testFeedLink()
    {
        // default formats
        $this->assertEquals('<link rel="alternate" type="application/atom+xml" href="http://domain.tld/feed">', Roumen\Feed\Feed::link('http://domain.tld/feed', 'atom'));
        $this->assertEquals('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed">', Roumen\Feed\Feed::link('http://domain.tld/feed', 'rss'));

        // with custom type
        $this->assertEquals('<link rel="alternate" type="text/xml" href="http://domain.tld/feed">', Roumen\Feed\Feed::link('http://domain.tld/feed', 'text/xml'));

        // with title
        $this->assertEquals('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed" title="Feed: RSS">', Roumen\Feed\Feed::link('http://domain.tld/feed', 'rss', 'Feed: RSS'));

        // with title and lang
        $this->assertEquals('<link rel="alternate" hreflang="en" type="application/atom+xml" href="http://domain.tld/feed" title="Feed: Atom">', Roumen\Feed\Feed::link('http://domain.tld/feed', 'atom', 'Feed: Atom', 'en'));
    }

    public function testFeedCustomView()
    {
        // custom view (don't exists)
        $this->feed->setView('vendor.feed.test');
        $this->assertEquals('feed::vendor.feed.test', $this->feed->getView('vendor.feed.test'));

        // default
        $this->assertEquals('feed::atom', $this->feed->getView('atom'));
    }

    public function testGetRssLinkByDefault()
    {
        $requestUrl = 'http://real.domain.need.to.be.hidden/test.xml';
        $this->call('get', $requestUrl);

        $reflectionMethod = new ReflectionMethod(Roumen\Feed\Feed::class, 'getRssLink');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invokeArgs($this->feed, []);

        $this->assertEquals($requestUrl, $result);
    }

    public function testGetRssLinkWithDomainSetting()
    {
        $requestUrl = 'http://real.domain.need.to.be.hidden/test.xml';
        $this->call('get', $requestUrl);

        $this->feed->domain = 'http://rss.service.com/';

        $reflectionMethod = new ReflectionMethod(Roumen\Feed\Feed::class, 'getRssLink');
        $reflectionMethod->setAccessible(true);
        $result = $reflectionMethod->invokeArgs($this->feed, []);

        $this->assertEquals('http://rss.service.com/test.xml', $result);
    }
}
