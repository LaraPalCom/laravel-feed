{{ '<?xml version="1.0" encoding="UTF-8" ?>'."\n" }}
<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">
    <channel>
        <title><![CDATA[{{ $channel['title'] }}]]></title>
        <link>{{ $channel['link'] }}</link>
        <description><![CDATA[{{ $channel['description'] }}]]></description>
        @if (!empty($channel['logo']))
        <image>
            <url>{{ $channel['logo'] }}</url>
            <title><![CDATA[{{ $channel['title'] }}]]></title>
            <link>{{ $channel['link'] }}</link>
        </image>
        @endif
        <pubDate>{{ date('D, d M Y H:i:s O', strtotime($channel['pubdate'])) }}</pubDate>
        <generator>laravel-feed</generator>
        <docs>http://blogs.law.harvard.edu/tech/rss</docs>
        @foreach($items as $item)
        <item>
            <title><![CDATA[{{ $item['title'] }}]]></title>
            <author>{{ $item['author'] }}</author>
            <link>{{ $item['link'] }}</link>
            <guid isPermaLink="true">{{ $item['link'] }}</guid>
            <description><![CDATA[{{ $item['description'] }}]]></description>
            <pubDate>{{ date('D, d M Y H:i:s O', strtotime($item['pubdate'])) }}</pubDate>
        </item>
        @endforeach
    </channel>
</rss>