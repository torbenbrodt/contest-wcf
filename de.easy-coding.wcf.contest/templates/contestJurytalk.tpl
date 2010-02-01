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
	{include file="contestEntryHeader"}
	
	<div class="border tabMenuContent">
		<div class="layout-2 blog">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						
						<div class="contentBox">
							<h4 class="subHeadline">{lang}wcf.user.contest.entry.jurytalks{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestJurytalk&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$jurytalks item=jurytalkObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="jurytalk{@$jurytalkObj->jurytalkID}"></a>
										<div class="containerIcon">
											{if $jurytalkObj->getUser()->getAvatar()}
												{assign var=x value=$jurytalkObj->getUser()->getAvatar()->setMaxSize(24, 24)}
												{if $jurytalkObj->userID}<a href="index.php?page=User&amp;userID={@$jurytalkObj->userID}{@SID_ARG_2ND}" title="{lang username=$jurytalkObj->username}wcf.user.viewProfile{/lang}">{/if}{@$jurytalkObj->getUser()->getAvatar()}{if $jurytalkObj->userID}</a>{/if}
											{else}
												{if $jurytalkObj->userID}<a href="index.php?page=User&amp;userID={@$jurytalkObj->userID}{@SID_ARG_2ND}" title="{lang username=$jurytalkObj->username}wcf.user.viewProfile{/lang}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $jurytalkObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $jurytalkID == $jurytalkObj->jurytalkID}
												<form method="post" action="index.php?page=ContestJurytalk&amp;contestID={@$contestID}&amp;jurytalkID={@$jurytalkObj->jurytalkID}&amp;action=edit">
													<div{if $errorField == 'message'} class="formError"{/if}>
														<textarea name="message" id="message" rows="10" cols="40">{$message}</textarea>
														{if $errorField == 'message'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.jurytalk.error.tooLong{/lang}{/if}
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
													{if $jurytalkObj->isEditable()}<a href="index.php?page=ContestJurytalk&amp;contestID={@$contestID}&amp;jurytalkID={@$jurytalkObj->jurytalkID}&amp;action=edit{@SID_ARG_2ND}#jurytalk{@$jurytalkObj->jurytalkID}" title="{lang}wcf.user.contest.entry.jurytalk.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $jurytalkObj->isDeletable()}<a href="index.php?action=ContestJurytalkDelete&amp;jurytalkID={@$jurytalkObj->jurytalkID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.user.contest.entry.jurytalk.delete.sure{/lang}')" title="{lang}wcf.user.contest.entry.jurytalk.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestJurytalk&amp;contestID={@$contestID}&amp;jurytalkID={@$jurytalkObj->jurytalkID}{@SID_ARG_2ND}#jurytalk{@$jurytalkObj->jurytalkID}" title="{lang}wcf.user.contest.entry.jurytalk.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p class="firstPost smallFont light">{lang}wcf.user.contest.entry.jurytalk.by{/lang} {if $jurytalkObj->userID}<a href="index.php?page=User&amp;userID={@$jurytalkObj->userID}{@SID_ARG_2ND}">{$jurytalkObj->username}</a>{else}{$jurytalkObj->username}{/if} ({@$jurytalkObj->time|time})</p>
												<p>{@$jurytalkObj->getFormattedMessage()}</p>
												
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
						
						{if $entry->isJurytalkable()}{assign var=jurytalkUsername value=$username}{/if}
						{if $entry->isJurytalkable() && $action != 'edit'}
							{assign var=username value=$jurytalkUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestJurytalk&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.user.contest.entry.jurytalk.add{/lang}</legend>
										<div class="formElement{if $errorField == 'message' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="message">{lang}wcf.user.contest.entry.jurytalk{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="message" id="message" rows="10" cols="40">{$message}</textarea>
												{if $errorField == 'message' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.jurytalk.error.tooLong{/lang}{/if}
													</p>
												{/if}
											</div>
										</div>
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
