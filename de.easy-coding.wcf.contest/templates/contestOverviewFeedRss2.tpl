<?xml version="1.0" encoding="{@CHARSET}"?>
<rss version="2.0">
	<channel>
		<title>{lang}wcf.contest.feed.title{/lang}</title>
		<link>{@PAGE_URL}/</link>
		<description>{lang}wcf.contest.feed.description{/lang}</description>
		
		<pubDate>{@'r'|gmdate:TIME_NOW}</pubDate>
		<lastBuildDate>{@'r'|gmdate:TIME_NOW}</lastBuildDate>
		<generator>WoltLab Community Framework {@WCF_VERSION}</generator>
		<ttl>60</ttl>
		
		{foreach from=$entries item=entry}
			<item>
				<title>{$entry->subject}</title>
				<author>{$entry->username}</author>
				<link>{@PAGE_URL}/index.php?page=Contest&amp;contestID={@$entry->contestID}</link>
				<guid>{@PAGE_URL}/index.php?page=Contest&amp;contestID={@$entry->contestID}</guid>
				<pubDate>{@'r'|gmdate:$entry->time}</pubDate>
				<description><![CDATA[{@$entry->getFormattedMessage()}]]></description>
			</item>
		{/foreach}
	</channel>
</rss>
