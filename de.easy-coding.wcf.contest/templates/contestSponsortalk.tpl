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
{assign var='searchFieldTitle' value='{lang}wcf.contest.search.query{/lang}'}
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
							<h4 class="subHeadline">{lang}wcf.contest.sponsortalks{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestSponsortalk&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$sponsortalks item=sponsortalkObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="sponsortalk{@$sponsortalkObj->sponsortalkID}"></a>
										<div class="containerIcon">
											{if $sponsortalkObj->getUser()->getAvatar()}
												{assign var=x value=$sponsortalkObj->getUser()->getAvatar()->setMaxSize(24, 24)}
												{if $sponsortalkObj->userID}<a href="index.php?page=User&amp;userID={@$sponsortalkObj->userID}{@SID_ARG_2ND}">{/if}{@$sponsortalkObj->getUser()->getAvatar()}{if $sponsortalkObj->userID}</a>{/if}
											{else}
												{if $sponsortalkObj->userID}<a href="index.php?page=User&amp;userID={@$sponsortalkObj->userID}{@SID_ARG_2ND}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $sponsortalkObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $sponsortalkID == $sponsortalkObj->sponsortalkID}
												<form method="post" action="index.php?page=ContestSponsortalk&amp;contestID={@$contestID}&amp;sponsortalkID={@$sponsortalkObj->sponsortalkID}&amp;action=edit">
													<div{if $errorField == 'message'} class="formError"{/if}>
														<textarea name="message" id="message" rows="10" cols="40">{$message}</textarea>
														{if $errorField == 'message'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																{if $errorType == 'tooLong'}{lang}wcf.contest.sponsortalk.error.tooLong{/lang}{/if}
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
													{if $sponsortalkObj->isEditable()}<a href="index.php?page=ContestSponsortalk&amp;contestID={@$contestID}&amp;sponsortalkID={@$sponsortalkObj->sponsortalkID}&amp;action=edit{@SID_ARG_2ND}#sponsortalk{@$sponsortalkObj->sponsortalkID}" title="{lang}wcf.contest.sponsortalk.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $sponsortalkObj->isDeletable()}<a href="index.php?action=ContestSponsortalkDelete&amp;sponsortalkID={@$sponsortalkObj->sponsortalkID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.sponsortalk.delete.sure{/lang}')" title="{lang}wcf.contest.sponsortalk.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestSponsortalk&amp;contestID={@$contestID}&amp;sponsortalkID={@$sponsortalkObj->sponsortalkID}{@SID_ARG_2ND}#sponsortalk{@$sponsortalkObj->sponsortalkID}" title="{lang}wcf.contest.sponsortalk.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p class="firstPost smallFont light">{lang}wcf.contest.sponsortalk.by{/lang} {if $sponsortalkObj->userID}<a href="index.php?page=User&amp;userID={@$sponsortalkObj->userID}{@SID_ARG_2ND}">{$sponsortalkObj->username}</a>{else}{$sponsortalkObj->username}{/if} ({@$sponsortalkObj->time|time})</p>
												<p>{@$sponsortalkObj->getFormattedMessage()}</p>
												
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
						
						{if $entry->isSponsortalkable() && $action != 'edit'}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestSponsortalk&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.sponsortalk.add{/lang}</legend>
										<p>{lang}wcf.contest.sponsortalk.description{/lang}</p>

										<div class="formElement{if $errorField == 'message' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="message">{lang}wcf.contest.sponsortalk{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="message" id="message" rows="10" cols="40">{$message}</textarea>
												{if $errorField == 'message' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.contest.sponsortalk.error.tooLong{/lang}{/if}
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
