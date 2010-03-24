{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.overview{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestOverviewFeed&amp;format=rss2" title="{lang}wcf.contest.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestOverviewFeed&amp;format=atom" title="{lang}wcf.contest.feed{/lang} (Atom)" />
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.contest.search.query{/lang}'}
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
			<h2>{lang}wcf.contest.overview{/lang}</h2>
			<p>{lang}wcf.contest.overview.description{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="border">
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
						
						
					<h3 class="subHeadline">{if $tagID}{lang}wcf.contest.entries.tagged{/lang}{else}{lang}wcf.contest.entries.allEntries{/lang}{/if} <span>({#$items})</span></h3>
					
					<div class="contentHeader">
						{pages print=true assign=pagesOutput link="index.php?page=ContestOverview&tagID=$tagID&juryID=$juryID&classID=$classID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
					</div>
					
					{assign var='messageNumber' value=$items-$startIndex+1}
					{foreach from=$entries item=entry}
						{assign var="contestID" value=$entry->contestID}
						<div class="message content">
							<div class="messageInner {cycle values='container-1,container-2'}">
								<a id="entry{@$entry->contestID}"></a>
								<div class="messageHeader"{if $entry->state == scheduled && ($entry->fromTime > 0 || $entry->untilTime > 0)} style="border-style:dashed"{/if}>
									<p class="messageCount">
										<a href="index.php?page=Contest&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.permalink{/lang}" class="messageNumber">{#$messageNumber}</a>
									</p>
									<div class="containerIcon">
										{if $entry->getOwner()->getAvatar()}
											{assign var=x value=$entry->getOwner()->getAvatar()->setMaxSize(24, 24)}
											<a href="{$entry->getOwner()->getLink()}{@SID_ARG_2ND}">{@$entry->getOwner()->getAvatar()}</a>
										{else}
											<a href="{$entry->getOwner()->getLink()}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
										{/if}
									</div>
									<div class="containerContent">
										<div style="float:right">{@$entry->getState()->renderButton()}</div>
										<h4 style="margin: 0; padding: 0"><a href="index.php?page=Contest&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
										<p class="light smallFont">{lang}wcf.contest.by{/lang} <a href="{$entry->getOwner()->getLink()}{@SID_ARG_2ND}">{$entry->getOwner()->getName()}</a> ({@$entry->time|time})</p>
									</div>
								</div>
								{if $entry->state == scheduled && ($entry->fromTime > 0 || $entry->untilTime > 0)}
								<div class="messageHeader">
									<div class="containerIcon">
										<img src="{icon}contestScheduledM.png{/icon}" alt="" />
									</div>
									<div class="containerContent">
										<p class="light smallFont">{lang}wcf.contest.fromTime{/lang}: {@$entry->fromTime|time}</p>
										<p class="light smallFont">{lang}wcf.contest.untilTime{/lang}: {@$entry->untilTime|time}</p>
									</div>
								</div>
								{/if}
								<div class="messageBody">
									{@$entry->getExcerpt()}
								</div>
								
								{if $tags[$contestID]|isset || $classes[$contestID]|isset}
									<div class="editNote smallFont light">
										{if $tags[$contestID]|isset}<p>{lang}wcf.contest.tags{/lang}: {implode from=$tags[$contestID] item=entryTag}<a href="index.php?page=ContestOverview&amp;tagID={@$entryTag->getID()}{@SID_ARG_2ND}">{$entryTag->getName()}</a>{/implode}</p>{/if}
										{if $classes[$contestID]|isset}<p>{lang}wcf.contest.classes{/lang}: {implode from=$classes[$contestID] item=entryClass}<a href="index.php?page=ContestOverview&amp;classID={@$entryClass->classID}{@SID_ARG_2ND}">{lang}{$entryClass->title}{/lang}</a>{/implode}</p>{/if}
									</div>
								{/if}
								
								<div class="messageFooter">
									<div class="smallButtons">
										<ul>
											<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
											<li><a href="index.php?page=ContestSolution&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#solutions" title="{lang}wcf.contest.numberOfSolutions{/lang}"><img src="{icon}contestSolutionS.png{/icon}" alt="" /> <span>{lang}wcf.contest.numberOfSolutions{/lang}</span></a></li>
											<li><a href="index.php?page=ContestParticipant&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#solutions" title="{lang}wcf.contest.numberOfParticipants{/lang}"><img src="{icon}contestParticipantS.png{/icon}" alt="" /> <span>{lang}wcf.contest.numberOfParticipants{/lang}</span></a></li>
											<li><a href="index.php?page=ContestJury&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#jurys" title="{lang}wcf.contest.numberOfJurys{/lang}"><img src="{icon}contestJuryS.png{/icon}" alt="" /> <span>{lang}wcf.contest.numberOfJurys{/lang}</span></a></li>
											<li><a href="index.php?page=ContestPrice&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#prices" title="{lang}wcf.contest.numberOfPrices{/lang}"><img src="{icon}contestPriceS.png{/icon}" alt="" /> <span>{lang}wcf.contest.numberOfPrices{/lang}</span></a></li>
											{if $additionalSmallButtons[$entry->contestID]|isset}{@$additionalSmallButtons[$entry->contestID]}{/if}
										</ul>
									</div>
								</div>
								<hr />
							</div>
						</div>
						{assign var='messageNumber' value=$messageNumber-1}
					{/foreach}
					
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
