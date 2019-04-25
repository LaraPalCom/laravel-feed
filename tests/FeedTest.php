<?php namespace Laravelium\Feed\Test;

use Orchestra\Testbench\TestCase as TestCase;
use Laravelium\Feed\Feed;
use Laravelium\Feed\FeedServiceProvider;
use Carbon\Carbon;

class FeedTest extends TestCase
{
    protected $feed;
    protected $sp;

    protected function getPackageProviders($app)
    {
        return [FeedServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return ['Feed' => Feed::class];
    }

    public function setUp() : void
    {
        parent::setUp();

        $config = [
      'feed.use_cache' => false,
      'feed.cache_key' => 'Laravel.feed.',
      'feed.cache_duration' => 3600,
      'feed.testing' => true
    ];

        config($config);

        $this->feed = $this->app->make(Feed::class);
    }

    public function testMisc()
    {
        // test object initialization
        $this->assertInstanceOf(Feed::class, $this->feed);

        // test custom methods
        $this->assertEquals([FeedServiceProvider::class], $this->getPackageProviders($this->feed));
        $this->assertEquals(['Feed'=>Feed::class], $this->getPackageAliases($this->feed));

        // test FeedServiceProvider (fixes coverage of the class!)
        $this->sp = new FeedServiceProvider($this->feed);
        $this->assertEquals(['feed', Feed::class], $this->sp->provides());
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

        $this->assertEquals('TestTitle', $this->feed->title);
        $this->assertEquals('TestSubtitle', $this->feed->subtitle);
        $this->assertEquals('TestDescription', $this->feed->description);
        $this->assertEquals('https://damianoff.com/', $this->feed->domain);
        $this->assertEquals('https://damianoff.com/', $this->feed->link);
        $this->assertEquals('hub', $this->feed->ref);
        $this->assertEquals("https://damianoff.com/favicon.png", $this->feed->logo);
        $this->assertEquals("https://damianoff.com/favicon.png", $this->feed->icon);
        $this->assertEquals('2014-02-29 00:00:00', $this->feed->pubdate);
        $this->assertEquals('en', $this->feed->lang);
        $this->assertEquals('All rights reserved by Foobar Corporation', $this->feed->copyright);
        $this->assertEquals('00FF00', $this->feed->color);
        $this->assertEquals('http://domain.tld/images/cover.png', $this->feed->cover);
        $this->assertEquals('UA-1525185-18', $this->feed->ga);
        $this->assertEquals(false, $this->feed->related);

        $this->assertEquals(null, $this->feed->getCtype());
        $this->feed->setCtype('plain/text');
        $this->assertEquals('plain/text', $this->feed->getCtype());

        $this->assertEquals('laravel-feed', $this->feed->getCacheKey());
        $this->assertEquals(0, $this->feed->getCacheDuration());

        $this->feed->setCache(30, 'laravel-feed1');
        $this->assertEquals('laravel-feed1', $this->feed->getCacheKey());
        $this->assertEquals(30, $this->feed->getCacheDuration());
    }


    public function testFeedAdd()
    {
        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>', '<p>TestContent</p>', ['url' => 'http://foobar.dev/someThing.jpg','type' => 'image/jpeg'], 'testCategory', 'testSubtitle');
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
        $this->assertEquals('testSubtitle', $items[0]['subtitle']);
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
        $this->assertEquals('TestTitle', $items[0]['title']);
        $this->assertEquals('TestAuthor', $items[0]['author']);
        $this->assertEquals('TestUrl', $items[0]['link']);
        $this->assertEquals('2014-02-29 00:00:00', $items[0]['pubdate']);
        $this->assertEquals('<p>TestResume</p>', $items[0]['description']);
        $this->assertEquals('<p>TestContent</p>', $items[0]['content']);
        $this->assertEquals('http://foobar.dev/someThing.jpg', $items[0]['enclosure']['url']);
        $this->assertEquals('testCategory', $items[0]['category']);
        $this->assertEquals('TestTitle5', $items[4]['title']);
        $this->assertEquals('00:00:00', $items[0]['duration']);
    }

    public function testFeedLink()
    {
        // default formats
        $this->assertEquals('<link rel="alternate" type="application/atom+xml" href="http://domain.tld/feed">', Feed::link('http://domain.tld/feed', 'atom'));
        $this->assertEquals('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed">', Feed::link('http://domain.tld/feed', 'rss'));

        // with custom type
        $this->assertEquals('<link rel="alternate" type="text/xml" href="http://domain.tld/feed">', Feed::link('http://domain.tld/feed', 'text/xml'));

        // with title
        $this->assertEquals('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed" title="Feed: RSS">', Feed::link('http://domain.tld/feed', 'rss', 'Feed: RSS'));

        // with title and lang
        $this->assertEquals('<link rel="alternate" hreflang="en" type="application/atom+xml" href="http://domain.tld/feed" title="Feed: Atom">', Feed::link('http://domain.tld/feed', 'atom', 'Feed: Atom', 'en'));
    }

    public function testFeedCustomView()
    {
        $this->feed->setCustomView('vendor.feed.test0');
        $this->assertEquals('vendor.feed.test0', $this->feed->getCustomView());

        $this->feed->setCustomView(null);
        $this->assertEquals(null, $this->feed->getCustomView());
    }

    public function testFeedRender()
    {
        $response = $this->feed->render();
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/atom+xml; charset=utf-8', $response->headers->get('Content-Type'));

        $response = $this->feed->render('rss', 60, 'testFeed2');
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/rss+xml; charset=utf-8', $response->headers->get('Content-Type'));

        // nonexisting custom view, won't get an error, because will use the cached view from above
        $this->feed->setCustomView('vendor.feed.test2');
        $response = $this->feed->render('atom', 60, 'testFeed2');
        $this->assertEquals(200, $response->status());
        // returns wrong ctype, because will use the cached view/ctype for 'testFeed' key
        $this->assertEquals('application/rss+xml; charset=utf-8', $response->headers->get('Content-Type'));

        $this->feed->setCustomView(null);
        $response = $this->feed->render('atom', 0, 'testFeed2');
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/atom+xml; charset=utf-8', $response->headers->get('Content-Type'));

        $this->feed->setCtype('application/atom+json');
        $response = $this->feed->render('atom', 0, 'testFeed2');
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/atom+json; charset=utf-8', $response->headers->get('Content-Type'));
    }

    public function testIsCached()
    {
        $this->assertEquals(false, $this->feed->IsCached());

        $this->feed->setCache(69, 'TestKey');

        $this->assertEquals('TestKey', $this->feed->getCacheKey());
        $this->assertEquals(69, $this->feed->getCacheDuration());

        $this->feed->cache->put($this->feed->getCacheKey(), $this->feed->getItems(), $this->feed->getCacheDuration());

        $this->assertEquals(true, $this->feed->IsCached());

        $this->feed->setCache(0, 'TestKey');

        $this->assertEquals(false, $this->feed->IsCached());
    }

    public function testCacheIsNotClearedWhenSet()
    {
        $this->feed->setCache(69, 'TestKey');
        $this->feed->addItem([
          'title' => 'FirstTitle',
          'author' => 'TestAuthor',
          'link' => 'TestUrl',
          'pubdate' => '2014-02-29 00:00:00',
          'description' => '<p>TestResume</p>',
          'content' => '<p>TestContent</p>',
          'category' => 'testCategory',
          'enclosure' => ['url'=>'http://foobar.dev/someThing.jpg', 'type' => 'image/jpeg'],
          'duration'  => '00:00:00'
        ]);
        $response = $this->feed->render();
        $this->assertTrue(strpos($response, 'FirstTitle') >= 0);
        $this->assertFalse(strpos($response, 'SecondTitle'));

        $this->feed->addItem([
          'title' => 'SecondTitle',
          'author' => 'TestAuthor',
          'link' => 'TestUrl',
          'pubdate' => '2014-02-29 00:00:00',
          'description' => '<p>TestResume</p>',
          'content' => '<p>TestContent</p>',
          'category' => 'testCategory',
          'enclosure' => ['url'=>'http://foobar.dev/someThing.jpg', 'type' => 'image/jpeg'],
          'duration'  => '00:00:00'
        ]);

        $response = $this->feed->render();
        $this->assertTrue(strpos($response, 'FirstTitle') >= 0);
        $this->assertFalse(strpos($response, 'SecondTitle'));
    }

    public function testCacheIsClearedWhenNotSet()
    {
        $this->feed->addItem([
          'title' => 'FirstTitle',
          'author' => 'TestAuthor',
          'link' => 'TestUrl',
          'pubdate' => '2014-02-29 00:00:00',
          'description' => '<p>TestResume</p>',
          'content' => '<p>TestContent</p>',
          'category' => 'testCategory',
          'enclosure' => ['url'=>'http://foobar.dev/someThing.jpg', 'type' => 'image/jpeg'],
          'duration'  => '00:00:00'
        ]);
        $response = $this->feed->render();
        $this->assertTrue(strpos($response, 'FirstTitle') >= 0);
        $this->assertFalse(strpos($response, 'SecondTitle'));

        $this->feed->addItem([
          'title' => 'SecondTitle',
          'author' => 'TestAuthor',
          'link' => 'TestUrl',
          'pubdate' => '2014-02-29 00:00:00',
          'description' => '<p>TestResume</p>',
          'content' => '<p>TestContent</p>',
          'category' => 'testCategory',
          'enclosure' => ['url'=>'http://foobar.dev/someThing.jpg', 'type' => 'image/jpeg'],
          'duration'  => '00:00:00'
        ]);

        $response = $this->feed->render();
        $this->assertTrue(strpos($response, 'FirstTitle') >= 0);
        $this->assertTrue(strpos($response, 'SecondTitle') >= 0);
    }

    public function testGetRssLink()
    {
        $this->assertEquals('http://localhost', $this->feed->getRssLink());

        $this->feed->domain = 'https://testDomain.local';

        $this->assertEquals('https://testDomain.local/', $this->feed->getRssLink());
    }

    public function testGetDateFormat()
    {
        $this->assertEquals('datetime', $this->feed->getDateFormat());

        $this->feed->setDateFormat('carbon');

        $this->assertEquals('carbon', $this->feed->getDateFormat());

        $this->feed->setDateFormat('datetime');
        $date = "2018-06-11";
        $this->assertEquals("2018-06-11T00:00:00+00:00", $this->feed->formatDate($date, 'atom'));
        $this->assertEquals("Mon, 11 Jun 2018 00:00:00 +0000", $this->feed->formatDate($date, 'rss'));

        $this->feed->setDateFormat('timestamp');
        $date = strtotime("2018-06-11");
        $this->assertEquals("2018-06-11T00:00:00+00:00", $this->feed->formatDate($date, 'atom'));
        $this->assertEquals("Mon, 11 Jun 2018 00:00:00 +0000", $this->feed->formatDate($date, 'rss'));

        $this->feed->setDateFormat('carbon');
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2018-06-11 00:00:00');
        $this->assertEquals("2018-06-11T00:00:00+00:00", $this->feed->formatDate($date, 'atom'));
        $this->assertEquals("Mon, 11 Jun 2018 00:00:00 +0000", $this->feed->formatDate($date, 'rss'));
    }

    public function testGetNamespaces()
    {
        $this->assertEquals([], $this->feed->getNamespaces());

        $this->feed->addNamespace('testNamespace');

        $this->assertEquals(['testNamespace'], $this->feed->getNamespaces());
    }

    public function testShortening()
    {
        $this->assertEquals(false, $this->feed->getShortening());

        $this->feed->setShortening(true);

        $this->assertEquals(true, $this->feed->getShortening());
    }

    public function testTextlimit()
    {
        $this->assertEquals(150, $this->feed->getTextLimit());

        $this->feed->setTextLimit(10);

        $this->assertEquals(10, $this->feed->getTextLimit());

        $this->feed->setShortening(true);

        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>');

        $this->feed->addItem([
      'title' => 'TestTitle',
      'author' => 'TestAuthor',
      'link' => 'TestUrl',
      'pubdate' => '2014-02-29 00:00:00',
      'description' => '<p>Test2Resume</p>'
    ]);

        $items = $this->feed->getItems();

        $this->assertEquals('<p>TestRes...', $items[0]['description']);
        $this->assertEquals('<p>Test2Re...', $items[1]['description']);
    }
}
