{include file="documentHeader"}
<head>
	<title>{$solutionObj->subject} - {lang}wcf.contest.solutions{/lang} - {$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	{include file='imageViewer'}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=rss2" title="{lang}wcf.contest.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=atom" title="{lang}wcf.contest.feed{/lang} (Atom)" />
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
							<h4 class="subHeadline">{lang}wcf.contest.solutions{/lang} &raquo; {$solutionObj->subject}</h4>
							
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
							<div class="message content">
								<div class="messageInner container-1">
									<a id="entry{@$solutionObj->solutionID}"></a>
									<div class="messageHeader">
										<div class="containerIcon">
											
											{if $solutionObj->getOwner()->getAvatar()}
												{assign var=x value=$solutionObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
												<a href="{$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}">{@$solutionObj->getOwner()->getAvatar()}</a>
											{else}
												<a href="{$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
											{/if}
										</div>
										<div class="containerContent">
											<div style="float:right">{@$solutionObj->getState()->renderButton()}</div>
											<h4 style="margin: 0; padding: 0"><a href="index.php?page=ContestSolutionEntry&amp;solutionID={@$solutionObj->solutionID}{@SID_ARG_2ND}">{$solutionObj->subject}</a></h4>
											<p class="light smallFont">{lang}wcf.contest.solution.by{/lang} <a href="{$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$solutionObj->getOwner()->getName()}</a> {@$solutionObj->time|time}</p>
										</div>
									</div>
									
									<div class="messageHeader">
										<div class="containerContent" style="padding:10px 0px">
											<div style="float:right;">
												<span style="font-size:22px">
													{$solutionObj->jurycount|intval}
												</span>
												<span style="font-size:14px">
													({$solutionObj->count|intval})
												</span>
											</div>
											<div style="float:left;">
												{lang}wcf.contest.rating.avg.jury{/lang}: {@$solutionObj->getJuryRatingOutput()}<br/>
												{lang}wcf.contest.rating.avg.total{/lang}: {@$solutionObj->getRatingOutput()}
											</div>
											<br style="clear:both"/>
										</div>
									</div>
									
									<div class="messageBody" id="contestEntryText{@$solutionObj->solutionID}">
										{@$solutionObj->getFormattedMessage()}
									</div>

									{include file='attachmentsShow' messageID=$solutionObj->solutionID author=$solutionObj->getOwner()}
									
									<div class="messageFooter">
										<div class="smallButtons">
											<ul id="contestEntryButtons{@$solutionObj->solutionID}">
												<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
												{if $solutionObj->isEditable()}<li><a href="index.php?form=ContestSolutionEdit&amp;contestID={@$solutionObj->contestID}&amp;solutionID={@$solutionObj->solutionID}{@SID_ARG_2ND}" title="{lang}wcf.contest.solution.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.global.button.edit{/lang}</span></a></li>{/if}
												{if $solutionObj->isDeletable()}<li><a href="index.php?action=ContestSolutionDelete&amp;contestID={@$solutionObj->contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.delete.sure{/lang}')" title="{lang}wcf.contest.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.global.button.delete{/lang}</span></a></li>{/if}
												{if $additionalSmallButtons|isset}{@$additionalSmallButtons}{/if}
											</ul>
										</div>
									</div>
									<hr />
								</div>
							</div>
						</div>			
						
						<form method="post" id="SolutionEntryRatingForm" action="index.php?page=ContestSolutionEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionID}">
							<input type="hidden" name="ContestSolutionRatingUpdateForm" value="1" />
							<a id="ratings"></a>
							<div class="contentBox">
								<h4 class="subHeadline">{lang}wcf.contest.ratings{/lang} <span>({$solutionObj->ratings})</span></h4>
								<ul class="dataList messages">
									{foreach from=$ratings item=ratingObj}
										<li class="{cycle values='container-1,container-2'}">
											<a id="rating{@$ratingObj->ratingID}"></a>
											<div class="formElement{if $errorField == 'username'} formError{/if}">
												<div class="formFieldLabel">
													{lang}wcf.contest.rating.avg.jury{/lang}: {@$ratingObj->getJuryRatingOutput()}<br/>
													{lang}wcf.contest.rating.avg.total{/lang}: {@$ratingObj->getRatingOutput()}
												</div>
												<div class="formField">
													<b>{lang}{@$ratingObj->title}{/lang}</b>
													<div style="float:right">
														<span style="font-size:22px">
															{$ratingObj->jurycount|intval}
														</span>
														<span style="font-size:14px">
															({$ratingObj->count|intval})
														</span>
													</div>
												</div>
												<div class="formFieldDesc">
													{if $solutionObj->isRateable()}
														make your own rating:
														{@$ratingObj->getMyRatingOutput()}
													{/if}
												</div>
											</div>
										</li>
									{/foreach}
									{if $solutionObj->isRateable()}
									<li class="container-3" id="SolutionEntryRatingFormSubmit">
										<div class="formElement">
											<div class="formField">
												{@SID_INPUT_TAG}
												{@SECURITY_TOKEN_INPUT_TAG}
												<input type="submit" value="{lang}wcf.contest.rating.submit{/lang}" />
											</div>
											<div class="formFieldDesc">
												{lang}wcf.contest.rating.submit.description{/lang}
											</div>
										</div>
									</li>
									{/if}
								</ul>
							
								<div class="buttonBar">
									<div class="smallButtons">
										<ul>
											<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
										</ul>
									</div>
								</div>
							</div>
						</form>
					
						
						{assign var='messageNumber' value=0}
						{if $comments|count > 0}
							<a id="comments"></a>
							<div class="contentBox">
								<h4 class="subHeadline">{lang}wcf.contest.comments{/lang} <span>({#$items})</span></h4>
								
								<div class="contentHeader">
									{pages print=true assign=pagesOutput link="index.php?page=ContestSolutionEntry&contestID=$contestID&solutionID=$solutionID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								</div>
								
								<ul class="dataList messages">
									{assign var='messageNumber' value=$items-$startIndex+1}
									{foreach from=$comments item=commentObj}
										<li class="{cycle values='container-1,container-2'}">
											<a id="comment{@$commentObj->commentID}"></a>
											<div class="containerIcon">
												{if $commentObj->getOwner()->getAvatar()}
													{assign var=x value=$commentObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
													<a href="{$commentObj->getOwner()->getLink()}{@SID_ARG_2ND}">{@$commentObj->getOwner()->getAvatar()}</a>
												{else if $commentObj->getOwner()->getLink() != ''}
													<a href="{$commentObj->getOwner()->getLink()}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
												{else}
													<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
												{/if}
											</div>
											<div class="containerContent">
												{if $action == 'edit' && $commentID == $commentObj->commentID}
												<form method="post" action="index.php?page=ContestSolutionEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionID}&amp;commentID={@$commentObj->commentID}&amp;action=edit">
													<div{if $errorField == 'comment'} class="formError"{/if}>
														<textarea name="comment" id="comment" rows="10" cols="40">{$comment}</textarea>
														{if $errorField == 'comment'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
													<div class="formSubmit">
														{@SID_INPUT_TAG}
														{@SECURITY_TOKEN_INPUT_TAG}
														<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
														<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
													</div>
												</form>
												{else}
												<div class="buttons">
													{if $commentObj->isEditable()}<a href="index.php?page=ContestSolutionEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionID}&amp;commentID={@$commentObj->commentID}&amp;action=edit{@SID_ARG_2ND}#comment{@$commentObj->commentID}" title="{lang}wcf.contest.comment.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $commentObj->isDeletable()}<a href="index.php?action=ContestEventDelete&amp;commentID={@$commentObj->commentID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.comment.delete.sure{/lang}')" title="{lang}wcf.contest.comment.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestSolutionEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionID}&amp;commentID={@$commentObj->commentID}{@SID_ARG_2ND}#comment{@$commentObj->commentID}" title="{lang}wcf.contest.comment.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p class="firstPost smallFont light">{lang}wcf.contest.comment.by{/lang} 
												{if $commentObj->getOwner()->getLink() != ''}
												<a href="{$commentObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$commentObj->getOwner()->getName()}</a>{else}{$commentObj->getOwner()->getName()}{/if}
												({@$commentObj->time|time})</p>
												<p>{@$commentObj->getFormattedMessage()}</p>
												{/if}
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
						
						{if $solutionObj->isCommentable()}{assign var=commentUsername value=$username}{/if}
						{if $solutionObj->isCommentable() && $action != 'edit'}
							{assign var=username value=$commentUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestSolutionEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionID}">
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
										{@SECURITY_TOKEN_INPUT_TAG}
										<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
										<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
									</div>
								</form>
							</div>
						{/if}
						
						{if $additionalContent1|isset}{@$additionalContent1}{/if}
							
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
