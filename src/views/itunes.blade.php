{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>' !!}
<rss version="2.0" <?php foreach ($namespaces as $n) echo "\n\t" . $n; ?>>
    
<channel>
    <title>{{ $channel['title'] }}</title>
    <atom:link href="https://domain.tld/yourfeed" rel="self" type="application/rss+xml"/>
    <link>{{ $channel['rssLink'] }}</link>
    <description>{{ $channel['description'] }}</description>
    <lastBuildDate>{{ $channel['pubdate'] }}</lastBuildDate>
    <language>{{ $channel['lang'] }}</language>
    <sy:updatePeriod>hourly</sy:updatePeriod>
    <sy:updateFrequency>1</sy:updateFrequency>
    <itunes:summary>Summary</itunes:summary>
    <itunes:author>Author</itunes:author>
    <itunes:explicit>clean</itunes:explicit>
    <itunes:image
            href="{{ $channel['logo'] }}" />
    <itunes:owner>
        <itunes:name>Owner</itunes:name>
        <itunes:email>email@domain.tld</itunes:email>
    </itunes:owner>
    <managingEditor>email@domain.tld (domain.tld)</managingEditor>
    <copyright>Copyright Info</copyright>
    <itunes:subtitle>{{ $channel['subtitle'] }}</itunes:subtitle>
    <image>
        <title>{{ $channel['title'] }}</title>
        <url>{{ $channel['logo'] }}</url>
        <link>{{ $channel['rssLink'] }}</link>
    </image>
    <itunes:category text="Music"></itunes:category>
    <itunes:category text="Arts">
        <itunes:category text="Performing Arts"></itunes:category>
    </itunes:category>
    <itunes:category text="Technology">
        <itunes:category text="Podcasting"></itunes:category>
    </itunes:category>
    @foreach($items as $item)
        <item>
            <title>{!! $item['title'] !!}</title>
            <link>{{ $item['link'] }}</link>
            <pubDate>{{ $item['pubdate'] }}</pubDate>
            <dc:creator>Creator</dc:creator>
            <category>Music</category>
            <description>{!! strip_tags($item['description']) !!}</description>
            <itunes:image href="{{ trim($item['itemcover']) }}"/>
            @if (!empty($item['enclosure']))
                <enclosure
                @foreach ($item['enclosure'] as $k => $v)
                    {!! $k.'="'.$v.'" ' !!}
                        @endforeach
                />
            @endif
            <content:encoded><![CDATA[{!! strip_tags(html_entity_decode($item['content'])) !!}]]></content:encoded>
            <itunes:summary>{!! strip_tags(html_entity_decode($item['content'])) !!}</itunes:summary>
            <itunes:author>Author</itunes:author>
            <itunes:duration>{{ $item['duration'] }}</itunes:duration>
            <itunes:explicit>clean</itunes:explicit>
            <guid>{{ $item['enclosure']['url'] }}</guid>

        </item>
    @endforeach
</channel>
</rss>
