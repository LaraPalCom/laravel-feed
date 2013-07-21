{{ '<?xml version="1.0" encoding="UTF-8" ?>'."\n" }}
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
    <channel>
        <title><![CDATA[{{ $channel['title'] }}]]></title>
        <link>{{ $channel['link'] }}</link>
        <description><![CDATA[{{ $channel['description'] }}]]></description>
        <atom:link href="{{ $channel['link'] }}" rel="self"></atom:link>
        @if (!empty($channel['logo']))
        <image>
            <url>{{ $channel['logo'] }}</url>
            <title><![CDATA[{{ $channel['title'] }}]]></title>
            <link>{{ $channel['link'] }}</link>
        </image>
        @endif
        <language>{{ $channel['lang'] }}</language>
        <lastBuildDate>{{ date('D, d M Y H:i:s O', strtotime($channel['pubdate'])) }}</lastBuildDate>
        @foreach($items as $item)
        <item>
            <title><![CDATA[{{ $item['title'] }}]]></title>
            <link>{{ $item['link'] }}</link>
            <guid isPermaLink="true">{{ $item['link'] }}</guid>
            <description><![CDATA[{{ $item['description'] }}]]></description>
            <dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">{{ $item['author'] }}</dc:creator>
            <pubDate>{{ date('D, d M Y H:i:s O', strtotime($item['pubdate'])) }}</pubDate>
        </item>
        @endforeach
    </channel>
</rss>