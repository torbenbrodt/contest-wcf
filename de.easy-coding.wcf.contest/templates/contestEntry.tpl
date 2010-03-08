{include file="documentHeader"}
<head>
	<title>{$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	{include file='imageViewer'}
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
							<div class="contentHeader">
								<div class="largeButtons">
									{if $entry->isSolutionable() || $additionalLargeButtons|isset}
										<ul>
											{if $entry->isSolutionable()}<li><a href="index.php?form=ContestSolutionAdd&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.solution.add{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.add{/lang}</span></a></li>{/if}
											{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
										</ul>
									{/if}
								</div>
							</div>
							<div class="message content">
								<div class="messageInner container-1">
									<a id="entry{@$entry->contestID}"></a>
									<div class="messageHeader"{if $entry->state == scheduled && ($entry->fromTime > 0 || $entry->untilTime > 0)} style="border-style:dashed"{/if}>
										<div class="containerIcon">
											
											{if $entry->getOwner()->getAvatar()}
												{assign var=x value=$entry->getOwner()->getAvatar()->setMaxSize(24, 24)}
												<a href="{$entry->getOwner()->getLink()}{@SID_ARG_2ND}">{@$entry->getOwner()->getAvatar()}</a>
											{else}
												<a href="{$entry->getOwner()->getLink()}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
											{/if}
										</div>
										<div class="containerContent">
											<p style="float:right">*{$entry->state}*</p>
											<h4 style="margin: 0; padding: 0"><a href="index.php?page=Contest&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
											<p class="light smallFont">{@$entry->time|time}</p>
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
									
									<div{if $pageNo > 1} style="display:none"{/if}>
										<div class="messageBody" id="contestEntryText{@$entry->contestID}">
											{@$entry->getFormattedMessage()}
										</div>
										
										{include file='attachmentsShow' messageID=$entry->contestID author=$entry->getOwner()}
									
										{if $tags|count > 0 || $classes|count > 0}
											<div class="editNote smallFont light">
												{if $tags|count > 0}<p>{lang}wcf.contest.tags{/lang}: {implode from=$tags item=tag}<a href="index.php?page=ContestOverview&amp;tagID={@$tag->getID()}{@SID_ARG_2ND}">{$tag->getName()}</a>{/implode}</p>{/if}
												{if $classes|count > 0}<p>{lang}wcf.contest.classes{/lang}: {implode from=$classes item=class}<a href="index.php?page=ContestOverview&amp;classID={@$class->classID}{@SID_ARG_2ND}">{lang}{$class->title}{/lang}</a>{/implode}</p>{/if}
											</div>
										{/if}
									</div>
									
									<div class="messageFooter">
										<div class="smallButtons">
											<ul id="contestEntryButtons{@$entry->contestID}">
												<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
												{if $entry->isEditable()}<li><a href="index.php?form=ContestEdit&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.global.button.edit{/lang}</span></a></li>{/if}
												{if $entry->isDeletable()}<li><a href="index.php?action=ContestDelete&amp;contestID={@$entry->contestID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.delete.sure{/lang}')" title="{lang}wcf.contest.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.global.button.delete{/lang}</span></a></li>{/if}
												{if $additionalSmallButtons|isset}{@$additionalSmallButtons}{/if}
											</ul>
										</div>
									</div>
									<hr />
								</div>
							</div>
							<div class="largeButtons">
								{if $entry->isSolutionable() || $additionalLargeButtons|isset}
									<ul>
										{if $entry->isSolutionable()}<li><a href="index.php?form=ContestSolutionAdd&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.solution.add{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.add{/lang}</span></a></li>{/if}
										{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
									</ul>
								{/if}
							</div>
						</div>
					
						{if $events|count > 0}
							<a id="events"></a>
							<div class="contentBox">
								<div style="float:right"><a href="index.php?page=ContestFeed&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}"><img src="{icon}contestRssM.png{/icon}" alt="" /></a></div>
								<h4 class="subHeadline">{lang}wcf.contest.events{/lang} <span>({#$items})</span></h4>
								
								<div class="contentHeader">
									{pages print=true assign=pagesOutput link="index.php?page=Contest&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								</div>
								
								<ul class="dataList messages">
									{assign var='messageNumber' value=$items-$startIndex+1}
									{foreach from=$events item=eventObj}
										<li class="{cycle values='container-1,container-2'}">
											<a id="event{@$eventObj->id}"></a>
											<div class="containerIcon">
												{if $eventObj->getOwner()->getAvatar()}
													{assign var=x value=$eventObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
													<a href="{$eventObj->getOwner()->getLink()}{@SID_ARG_2ND}">{@$eventObj->getOwner()->getAvatar()}</a>
												{else if $eventObj->getOwner()->getLink() != ''}
													<a href="{$eventObj->getOwner()->getLink()}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
												{else}
													<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
												{/if}
											</div>
											<div class="containerContent">
												<div class="buttons">
													{if $eventObj->isEditable()}<a href="index.php?page=Contest&amp;contestID={@$contestID}&amp;id={@$eventObj->id}&amp;action=edit{@SID_ARG_2ND}#event{@$eventObj->id}" title="{lang}wcf.contest.event.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $eventObj->isDeletable()}<a href="index.php?action=ContestEventDelete&amp;id={@$eventObj->id}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.event.delete.sure{/lang}')" title="{lang}wcf.contest.event.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=Contest&amp;contestID={@$contestID}&amp;id={@$eventObj->id}{@SID_ARG_2ND}#event{@$eventObj->id}" title="{lang}wcf.contest.event.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p class="firstPost smallFont light">{lang}wcf.contest.event.by{/lang} 
												{if $eventObj->getOwner()->getLink() != ''}
												<a href="{$eventObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$eventObj->getOwner()->getName()}</a>
												{else}
												{$eventObj->getOwner()->getName()}
												{/if}
												({@$eventObj->time|time})</p>
												<p>{@$eventObj->getFormattedMessage()}</p>
											</div>
										</li>
										{assign var='messageNumber' value=$messageNumber-1}
									{/foreach}
								</ul>
								
								<div class="contentFooter">
									{@$pagesOutput}
								</div>
								
								<div class="buttonBar">
									<div class="smallButtons">
										<ul>
											<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
										</ul>
									</div>
								</div>
							</div>
						{/if}
						
						{if $entry->isCommentable()}{assign var=commentUsername value=$username}{/if}
						{if $entry->isCommentable() && $action != 'edit'}
							{assign var=username value=$commentUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=Contest&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.comment.add{/lang}</legend>
										
										{if !$this->user->userID}
											<div class="formElement{if $errorField == 'username'} formError{/if}">
												<div class="formFieldLabel">
													<label for="username">{lang}wcf.user.username{/lang}</label>
												</div>
												<div class="formField">
													<input type="text" class="inputText" name="username" id="username" value="{$username}" />
													{if $errorField == 'username'}
														<p class="innerError">
															{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															{if $errorType == 'notValid'}{lang}wcf.user.error.username.notValid{/lang}{/if}
															{if $errorType == 'notAvailable'}{lang}wcf.user.error.username.notUnique{/lang}{/if}
														</p>
													{/if}
												</div>
											</div>
										{/if}
										
										<div class="formElement{if $errorField == 'comment' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="comment">{lang}wcf.contest.comment{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="comment" id="comment" rows="10" cols="40">{$comment}</textarea>
												{if $errorField == 'comment' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.contest.comment.error.tooLong{/lang}{/if}
													</p>
												{/if}
											</div>
										</div>
										
										{include file='captcha' enableFieldset=false}
									</fieldset>
									
									<div class="formSubmit">
										{@SID_INPUT_TAG}
										<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
										<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
									</div>
								</form>
							</div>
						{/if}
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
