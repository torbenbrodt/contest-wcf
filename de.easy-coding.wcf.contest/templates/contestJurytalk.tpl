{include file="documentHeader"}
<head>
	<title>{$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	{include file='imageViewer'}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH}; 
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	{include file='multiQuote'}
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
		<li><a href="index.php?page=ContestOverview{@SID_ARG_2ND}"><img src="{icon}contestS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}contestL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.user.contest{/lang}</h2>
		</div>
	</div>

	{if $entry->isCommentable()}{assign var=commentUsername value=$username}{/if}
	
	<div class="tabMenu">
		<ul>
			<li class="activeTabMenu"><a href="index.php?page=ContestEntry&contestID={$contestID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contestM.png" alt="" /> {lang}Übersicht{/lang}</a></li>
			
			<li><a href="index.php?page=ContestJurytalk&contestID={$contestID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contestM.png" alt="" /> {lang}Jurytalk{/lang}</a></li>
			<li><a href="index.php?page=ContestSponsortalk&contestID={$contestID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contestM.png" alt="" /> {lang}Sponsortalk{/lang}</a></li>
			
		</ul>
	</div>
	<div class="subTabMenu">
		<div class="containerHead"></div>
	</div>
	<div class="border tabMenuContent">
		<div class="layout-2 blog">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						{if $comments|count > 0}
							<a id="comments"></a>
							<div class="contentBox">
								<h4 class="subHeadline">{lang}wcf.user.contest.entry.comments{/lang} <span>({#$items})</span></h4>
								
								<div class="contentHeader">
									{pages print=true assign=pagesOutput link="index.php?page=ContestEntry&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								</div>
								
								<ul class="dataList messages">
									{assign var='messageNumber' value=$items-$startIndex+1}
									{foreach from=$comments item=commentObj}
										<li class="{cycle values='container-1,container-2'}">
											<a id="comment{@$commentObj->commentID}"></a>
											<div class="containerIcon">
												{if $commentObj->getUser()->getAvatar()}
													{assign var=x value=$commentObj->getUser()->getAvatar()->setMaxSize(24, 24)}
													{if $commentObj->userID}<a href="index.php?page=User&amp;userID={@$commentObj->userID}{@SID_ARG_2ND}" title="{lang username=$commentObj->username}wcf.user.viewProfile{/lang}">{/if}{@$commentObj->getUser()->getAvatar()}{if $commentObj->userID}</a>{/if}
												{else}
													{if $commentObj->userID}<a href="index.php?page=User&amp;userID={@$commentObj->userID}{@SID_ARG_2ND}" title="{lang username=$commentObj->username}wcf.user.viewProfile{/lang}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $commentObj->userID}</a>{/if}
												{/if}
											</div>
											<div class="containerContent">
												{if $action == 'edit' && $commentID == $commentObj->commentID}
													<form method="post" action="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;commentID={@$commentObj->commentID}&amp;action=edit">
														<div{if $errorField == 'comment'} class="formError"{/if}>
															<textarea name="comment" id="comment" rows="10" cols="40">{$comment}</textarea>
															{if $errorField == 'comment'}
																<p class="innerError">
																	{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																	{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.comment.error.tooLong{/lang}{/if}
																</p>
															{/if}
														</div>
														<div class="formSubmit">
															{@SID_INPUT_TAG}
															<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
															<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
														</div>
													</form>
												{else}
													<div class="buttons">
														{if $commentObj->isEditable()}<a href="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;commentID={@$commentObj->commentID}&amp;action=edit{@SID_ARG_2ND}#comment{@$commentObj->commentID}" title="{lang}wcf.user.contest.entry.comment.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
														{if $commentObj->isDeletable()}<a href="index.php?action=ContestCommentDelete&amp;commentID={@$commentObj->commentID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.user.contest.entry.comment.delete.sure{/lang}')" title="{lang}wcf.user.contest.entry.comment.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
														<a href="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;commentID={@$commentObj->commentID}{@SID_ARG_2ND}#comment{@$commentObj->commentID}" title="{lang}wcf.user.contest.entry.comment.permalink{/lang}">#{#$messageNumber}</a>
													</div>
													<p class="firstPost smallFont light">{lang}wcf.user.contest.entry.comment.by{/lang} {if $commentObj->userID}<a href="index.php?page=User&amp;userID={@$commentObj->userID}{@SID_ARG_2ND}">{$commentObj->username}</a>{else}{$commentObj->username}{/if} ({@$commentObj->time|time})</p>
													<p>{@$commentObj->getFormattedComment()}</p>
													
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
						
						{if $entry->isCommentable() && $action != 'edit'}
							{assign var=username value=$commentUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.user.contest.entry.comment.add{/lang}</legend>
										
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
												<label for="comment">{lang}wcf.user.contest.entry.comment{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="comment" id="comment" rows="10" cols="40">{$comment}</textarea>
												{if $errorField == 'comment' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.comment.error.tooLong{/lang}{/if}
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
