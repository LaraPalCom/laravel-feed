<?php

class FeedTest extends PHPUnit_Framework_TestCase
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
        $this->feed->link = 'http://roumen.it/';
        $this->feed->logo = "http://roumen.it/favicon.png";
        $this->feed->icon = "http://roumen.it/favicon.png";
        $this->feed->pubdate = '2014-02-29 00:00:00';
        $this->feed->lang = 'en';

        $this->assertEquals('TestTitle', $this->feed->title);
        $this->assertEquals('TestDescription', $this->feed->description);
        $this->assertEquals('http://roumen.it/', $this->feed->link);
        $this->assertEquals("http://roumen.it/favicon.png", $this->feed->logo);
        $this->assertEquals("http://roumen.it/favicon.png", $this->feed->icon);
        $this->assertEquals('2014-02-29 00:00:00', $this->feed->pubdate);
        $this->assertEquals('en', $this->feed->lang);
    }

    public function testFeedAdd()
    {
    	$this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>', '<p>TestContent</p>');
        $this->feed->add('TestTitle', 'TestAuthor', 'TestUrl', '2014-02-29 00:00:00', '<p>TestResume</p>');

        $this->assertCount(2, $this->feed->items);

        $this->assertEquals('TestTitle', $this->feed->items[0]['title']);
        $this->assertEquals('TestAuthor', $this->feed->items[0]['author']);
        $this->assertEquals('TestUrl', $this->feed->items[0]['link']);
        $this->assertEquals(date('c',strtotime("2014-02-29 00:00:00")), $this->feed->items[0]['pubdate']);
        $this->assertEquals('<p>TestResume</p>', $this->feed->items[0]['description']);
        $this->assertEquals('<p>TestContent</p>', $this->feed->items[0]['content']);
    }

    public function testFeedLink()
    {
        $this->assertEquals('<link rel="alternate" type="application/atom+xml" href="http://domain.tld/feed" />', $this->feed->link('http://domain.tld/feed', 'atom'));
        $this->assertEquals('<link rel="alternate" type="application/rss+xml" href="http://domain.tld/feed" />', $this->feed->link('http://domain.tld/feed', 'rss'));
    }

    public function testFeedRender()
    {
    	//
    }

}