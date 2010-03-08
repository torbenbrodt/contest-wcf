{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.solutions{/lang} - {$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=rss2" title="{lang}wcf.contest.entry.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=atom" title="{lang}wcf.contest.entry.feed{/lang} (Atom)" />
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
	{include file="contestEntryHeader"}
	
	<div class="border tabMenuContent">
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
							{if $userMessages|isset}{@$userMessages}{/if}
							<h4 class="subHeadline">{lang}wcf.contest.solutions{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestSolution&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								<div class="largeButtons">
									{if $entry->isSolutionable() || $additionalLargeButtons|isset}
										<ul>
											{if $entry->isSolutionable()}<li><a href="index.php?form=ContestSolutionAdd&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.solution.add{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.add{/lang}</span></a></li>{/if}
											{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
										</ul>
									{/if}
								</div>
							</div>
							
							{assign var='messageNumber' value=$startIndex}
							{foreach from=$solutions item=solutionObj}
								{assign var="contestID" value=$solutionObj->contestID}
								<div class="message">
								<div class="columnContainer" style="padding:0px">
									<div class="container-3 column content">
										<div style="width:110px; padding:12px">
											<div class="messageHeader" style="float:left;width:100%;">
												<span style="font-size:32px">
													{$messageNumber}.
												</span>
											</div>
											<br style="clear:both"/>
											<div style="padding:5px">
												{lang}wcf.contest.rating.avg.jury{/lang}: {@$solutionObj->getJuryRatingOutput()}<br/>
												{lang}wcf.contest.rating.avg.total{/lang}: {@$solutionObj->getRatingOutput()}
											</div>
										</div>
									</div>
									<div class="container-1 column content" style="width:100%;">
										<div class="messageInner {cycle values='container-1,container-2'}">
										<a id="solutionObj{@$solutionObj->contestID}"></a>
										<div class="messageHeader">
											<p class="messageCount">
												<a href="index.php?page=ContestSolution&amp;contestID={@$solutionObj->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.permalink{/lang}" class="messageNumber">{#$messageNumber}</a>
											</p>
											<div class="containerIcon">
												{if $solutionObj->getOwner()->getAvatar()}
													{assign var=x value=$solutionObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
													<a href="{@$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}">{@$solutionObj->getOwner()->getAvatar()}</a>
												{else}
													<a href="{@$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
												{/if}
											</div>
											<div class="containerContent">
												<div style="float:right">*{$solutionObj->state}*</div>
												<h4 style="margin: 0; padding: 0"><a href="index.php?page=ContestSolutionEntry&amp;contestID={@$entry->contestID}&amp;solutionID={@$solutionObj->solutionID}{@SID_ARG_2ND}">{$solutionObj->subject}</a></h4>
												<p class="light smallFont">{lang}wcf.contest.by{/lang} <a href="{$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$solutionObj->getOwner()->getName()}</a> ({@$solutionObj->time|time})</p>
											</div>
										</div>
										<div class="messageBody">
											{@$solutionObj->getExcerpt()}
										</div>
								
										<div class="messageFooter">
											<div class="smallButtons">
												<ul>
													{if $solutionObj->isEditable()}<li><a href="index.php?form=ContestSolutionEdit&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;action=edit{@SID_ARG_2ND}#solution{@$solutionObj->solutionID}" title="{lang}wcf.contest.solution.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.edit{/lang}</span></a></li>{/if}
													{if $solutionObj->isDeletable()}<li><a href="index.php?action=ContestSolutionDelete&amp;solutionID={@$solutionObj->solutionID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.solution.delete.sure{/lang}')" title="{lang}wcf.contest.solution.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.delete{/lang}</span></a></li>{/if}
													<li><a href="index.php?page=ContestSolutionEntry&amp;contestID={@$entry->contestID}&amp;solutionID={@$solutionObj->solutionID}{@SID_ARG_2ND}#comments" title="{lang}wcf.contest.solution.numberOfComments{/lang}"><img src="{icon}contestSolutionCommentS.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.numberOfComments{/lang}</span></a></li>
												</ul>
											</div>
										</div>
										<hr />
										</div>
									</div>
								</div>
								</div>
								{assign var='messageNumber' value=$messageNumber+1}
							{/foreach}
							
							<div class="contentFooter">
								{@$pagesOutput}
								{if $messageNumber > 3}
								<div class="largeButtons">
									{if $entry->isSolutionable() || $additionalLargeButtons|isset}
										<ul>
											{if $entry->isSolutionable()}<li><a href="index.php?form=ContestSolutionAdd&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.solution.add{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.add{/lang}</span></a></li>{/if}
											{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
										</ul>
									{/if}
								</div>
								{/if}
							</div>
							
							<div class="buttonBar">
								<div class="smallButtons">
									<ul>
										<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
									</ul>
								</div>
							</div>
						</div>
						{if $additionalContent1|isset}{@$additionalContent1}{/if}
						<div class="contentFooter"> </div>
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
