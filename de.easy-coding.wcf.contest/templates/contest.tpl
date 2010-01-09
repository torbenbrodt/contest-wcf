{include file="documentHeader"}
<head>
	<title>{lang}wcf.user.profile.title{/lang} - {lang}wcf.user.profile.members{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	{include file='imageViewer'}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH}; 
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestFeed&amp;userID={@$userID}&amp;format=rss2" title="{lang}wcf.user.contest.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestFeed&amp;userID={@$userID}&amp;format=atom" title="{lang}wcf.user.contest.feed{/lang} (Atom)" />
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.user.contest.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="types[]" value="contestEntry" />
	<input type="hidden" name="userID" value="{@$user->userID}" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	{include file="userProfileHeader"}
	
	<div class="border">
		<div class="layout-2 blog">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
							<h3 class="subHeadline">{if $tagID}{lang}wcf.user.contest.entries.tagged{/lang}{elseif $classID}{lang}wcf.user.contest.class.entries{/lang}{else}{lang}wcf.user.contest{/lang}{/if} <span>({#$items})</span></h3>
							
							{if !$entries|count}<p>{lang}wcf.user.contest.noEntries{/lang}</p>{/if}
								
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=Contest&userID=$userID&tagID=$tagID&classID=$classID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								
								{if ($userID == $this->user->userID && $this->user->getPermission('user.contest.canUseContest')) || $additionalLargeButtons|isset}
									<div class="largeButtons">
										<ul>
											{if $userID == $this->user->userID && $this->user->getPermission('user.contest.canUseContest')}<li><a href="index.php?form=ContestEntryAdd&amp;userID={@$userID}{@SID_ARG_2ND}" title="{lang}wcf.user.contest.entry.add{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.button.entry.add{/lang}</span></a></li>{/if}
											{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
										</ul>
									</div>
								{/if}
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
													{if $user->getAvatar()}
														{assign var=x value=$user->getAvatar()->setMaxSize(24, 24)}
														<a href="index.php?page=User&amp;userID={@$user->userID}{@SID_ARG_2ND}" title="{lang username=$user->username}wcf.user.viewProfile{/lang}">{@$user->getAvatar()}</a>
													{else}
														<a href="index.php?page=User&amp;userID={@$user->userID}{@SID_ARG_2ND}" title="{lang username=$user->username}wcf.user.viewProfile{/lang}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
													{/if}
												</div>
												<div class="containerContent">
													<h4 style="margin: 0; padding: 0"><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
													<p class="light smallFont">{@$entry->time|time}</p>
												</div>
											</div>
											<div class="messageBody">
												{@$entry->getExcerpt()}
											</div>
											
											{if $tags[$contestID]|isset || $classes[$contestID]|isset}
												<div class="editNote smallFont light">
													{if $tags[$contestID]|isset}<p>{lang}wcf.user.contest.entry.tags{/lang}: {implode from=$tags[$contestID] item=entryTag}<a href="index.php?page=ContestOverview&amp;tagID={@$entryTag->getID()}{@SID_ARG_2ND}">{$entryTag->getName()}</a>{/implode}</p>{/if}
													{if $classes[$contestID]|isset}<p>{lang}wcf.user.contest.entry.classes{/lang}: {implode from=$classes[$contestID] item=entryClass}<a href="index.php?page=ContestOverview&amp;classID={@$entryClass->classID}{@SID_ARG_2ND}">{lang}{$entryClass->title}{/lang}</a>{/implode}</p>{/if}
													{if $jurys[$contestID]|isset}<p>{lang}wcf.user.contest.entry.jurys{/lang}: {implode from=$jurys[$contestID] item=entryClass}<a href="index.php?page=ContestOverview&amp;juryID={@$entryClass->juryID}{@SID_ARG_2ND}">{lang}{$entryClass->title}{/lang}</a>{/implode}</p>{/if}
													{if $prices[$contestID]|isset}<p>{lang}wcf.user.contest.entry.prices{/lang}: {implode from=$prices[$contestID] item=entryClass}<a href="index.php?page=ContestOverview&amp;priceID={@$entryClass->priceID}{@SID_ARG_2ND}">{lang}{$entryClass->title}{/lang}</a>{/implode}</p>{/if}
												</div>
											{/if}
											
											<div class="messageFooter">
												<div class="smallButtons">
													<ul>
														<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
														<li><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#solutions" title="{lang}wcf.user.contest.entry.numberOfSolutions{/lang}"><img src="{icon}messageS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.entry.numberOfSolutions{/lang}</span></a></li>
														{if $entry->hasMoreText}<li><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.user.contest.entry.more{/lang}"><img src="{icon}blogReadMoreS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.entry.more{/lang}</span></a></li>{/if}
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
							
							{if $entries|count > 0}
								<div class="contentFooter">
									{@$pagesOutput}
									
									{if ($userID == $this->user->userID && $this->user->getPermission('user.contest.canUseContest')) || $additionalLargeButtons|isset}
										<div class="largeButtons">
											<ul>
												{if $userID == $this->user->userID && $this->user->getPermission('user.contest.canUseContest')}<li><a href="index.php?form=ContestEntryAdd&amp;userID={@$userID}{@SID_ARG_2ND}" title="{lang}wcf.user.contest.entry.add{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.button.entry.add{/lang}</span></a></li>{/if}
												{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
											</ul>
										</div>
									{/if}
								</div>
							{/if}
						</div>
					</div>
				</div>
			
				<div class="container-3 column second contestSidebar">
					<div class="columnInner">
						{include file='contestSidebar'}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{include file='footer' sandbox=false}
</body>
</html>
