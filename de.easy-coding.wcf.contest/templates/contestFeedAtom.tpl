<?xml version="1.0" encoding="{@CHARSET}"?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title>{lang}wcf.contest.feed.title{/lang}</title>
	<id>{@PAGE_URL}/</id>
	<updated>{@'c'|gmdate:TIME_NOW}</updated>
	<link href="{@PAGE_URL}/" />
	<generator uri="http://www.woltlab.com/" version="{@WCF_VERSION}">
		WoltLab Community Framework
	</generator>
	<subtitle>{lang}wcf.contest.feed.description{/lang}</subtitle>
	
	{foreach from=$entries item=entry}
		<entry>
			<title>{$entry->subject}</title>
			<id>{@PAGE_URL}/index.php?page=Contest&amp;contestID={@$entry->contestID}</id>
			<updated>{@'c'|gmdate:$entry->time}</updated>
			<author>
				<name>{$entry->username}</name>
			</author>
			<content type="html"><![CDATA[{@$entry->getFormattedMessage()}]]></content>
			<link href="{@PAGE_URL}/index.php?page=Contest&amp;contestID={@$entry->contestID}" />
		</entry>
	{/foreach}
</feed>