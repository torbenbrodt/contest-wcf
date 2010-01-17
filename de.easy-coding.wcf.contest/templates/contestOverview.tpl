{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.contest.overview{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	{include file='imageViewer'}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH}; 
		//]]>
	</script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestFeed&amp;format=rss2" title="{lang}wcf.user.contest.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestFeed&amp;format=atom" title="{lang}wcf.user.contest.feed{/lang} (Atom)" />
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.user.contest.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="types[]" value="contestEntry" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}contestL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.user.contest.overview{/lang}</h2>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="border">
		<div class="layout-2 blog">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
						
						
					<h3 class="subHeadline">{if $tagID}{lang}wcf.user.contest.entries.tagged{/lang}{else}{lang}wcf.user.contest.entries.allEntries{/lang}{/if} <span>({#$items})</span></h3>
					
					<div class="contentHeader">
						{pages print=true assign=pagesOutput link="index.php?page=ContestOverview&tagID=$tagID&juryID=$juryID&classID=$classID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
					</div>
					
					<div class="blogInner">
						{assign var='messageNumber' value=$items-$startIndex+1}
						{foreach from=$entries item=entry}
							{assign var="contestID" value=$entry->contestID}
							<div class="message">
								<div class="messageInner {cycle values='container-1,container-2'}">
									<a id="entry{@$entry->contestID}"></a>
									<div class="messageHeader">
										<p class="messageCount">
											<a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.user.contest.entry.permalink{/lang}" class="messageNumber">{#$messageNumber}</a>
										</p>
										<div class="containerIcon">
											{if $entry->getUser()->getAvatar()}
												{assign var=x value=$entry->getUser()->getAvatar()->setMaxSize(24, 24)}
												<a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}" title="{lang username=$entry->username}wcf.user.viewProfile{/lang}">{@$entry->getUser()->getAvatar()}</a>
											{else}
												<a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}" title="{lang username=$entry->username}wcf.user.viewProfile{/lang}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
											{/if}
										</div>
										<div class="containerContent">
											<h4 style="margin: 0; padding: 0"><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
											<p class="light smallFont">{lang}wcf.user.contest.entry.by{/lang} <a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}">{$entry->username}</a> ({@$entry->time|time})</p>
										</div>
									</div>
									<div class="messageBody">
										{@$entry->getExcerpt()}
									</div>
									
									{if $tags[$contestID]|isset || $classes[$contestID]|isset}
										<div class="editNote smallFont light">
											{if $tags[$contestID]|isset}<p>{lang}wcf.user.contest.entry.tags{/lang}: {implode from=$tags[$contestID] item=entryTag}<a href="index.php?page=ContestOverview&amp;tagID={@$entryTag->getID()}{@SID_ARG_2ND}">{$entryTag->getName()}</a>{/implode}</p>{/if}
											{if $classes[$contestID]|isset}<p>{lang}wcf.user.contest.entry.classes{/lang}: {implode from=$classes[$contestID] item=entryClass}<a href="index.php?page=ContestOverview&amp;classID={@$entryClass->classID}{@SID_ARG_2ND}">{lang}{$entryClass->title}{/lang}</a>{/implode}</p>{/if}
										</div>
									{/if}
									
									<div class="messageFooter">
										<div class="smallButtons">
											<ul>
												<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
												<li><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#solutions" title="{lang}wcf.user.contest.entry.numberOfSolutions{/lang}"><img src="{icon}messageS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.entry.numberOfSolutions{/lang}</span></a></li>
												<li><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#jurys" title="{lang}wcf.user.contest.entry.numberOfJurys{/lang}"><img src="{icon}messageS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.entry.numberOfJurys{/lang}</span></a></li>
												<li><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#prices" title="{lang}wcf.user.contest.entry.numberOfPrices{/lang}"><img src="{icon}messageS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.entry.numberOfPrices{/lang}</span></a></li>
												{if $additionalSmallButtons[$entry->contestID]|isset}{@$additionalSmallButtons[$entry->contestID]}{/if}
											</ul>
										</div>
									</div>
									<hr />
								</div>
							</div>
							{assign var='messageNumber' value=$messageNumber-1}
						{/foreach}
					</div>
					
					<div class="contentFooter">
						{@$pagesOutput}
					</div>
				</div>
				
						</div><!-- columnInner -->
					</div><!-- contentBox -->
			
				<div class="container-3 column second contestSidebar">
					<div class="columnInner">
						{include file='contestSidebar'}
					</div>
				</div>
			</div>
		</div>
	</div>
	
	{if $availableTags|count > 0}
		<div class="border infoBox">
		
			<div class="{cycle values='container-1,container-2'}">
				<div class="containerIcon">
					<img src="{icon}tagM.png{/icon}" alt="" />
				</div>
				<div class="containerContent">
					<h3><span>{lang}wcf.tagging.filter{/lang}</span></h3>
					<ul class="tagCloud">
						{foreach from=$availableTags item=tag}
							<li><a href="index.php?page=ContestOverview&amp;tagID={@$tag->getID()}{@SID_ARG_2ND}" style="font-size: {@$tag->getSize()}%">{$tag->getName()}</a></li>
						{/foreach}
					</ul>						
				</div>
			</div>
		</div>
	{/if}
</div>

{include file='footer' sandbox=false}
</body>
</html>
