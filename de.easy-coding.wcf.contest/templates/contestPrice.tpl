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
							<h4 class="subHeadline">{lang}wcf.user.contest.entry.prices{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestPrice&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$prices item=priceObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="price{@$priceObj->priceID}"></a>
										<div class="containerIcon">
											{if $priceObj->getUser()->getAvatar()}
												{assign var=x value=$priceObj->getUser()->getAvatar()->setMaxSize(24, 24)}
												{if $priceObj->userID}<a href="index.php?page=User&amp;userID={@$priceObj->userID}{@SID_ARG_2ND}" title="{lang username=$priceObj->username}wcf.user.viewProfile{/lang}">{/if}{@$priceObj->getUser()->getAvatar()}{if $priceObj->userID}</a>{/if}
											{else}
												{if $priceObj->userID}<a href="index.php?page=User&amp;userID={@$priceObj->userID}{@SID_ARG_2ND}" title="{lang username=$priceObj->username}wcf.user.viewProfile{/lang}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $priceObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $priceID == $priceObj->priceID}
												<form method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}&amp;action=edit">
													<p>{@$priceObj}</p>
													<div{if $errorField == 'state'} class="formError"{/if}>
														<select name="state" id="state">
														{htmloptions options=$states selected=$state}
														</select>
														{if $errorField == 'state'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
													<div{if $errorField == 'message'} class="formError"{/if}>
														<textarea name="message" id="message" rows="5" cols="40">{$price}</textarea>
														{if $errorField == 'message'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.price.error.tooLong{/lang}{/if}
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
													{if $priceObj->isEditable()}<a href="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}&amp;action=edit{@SID_ARG_2ND}#price{@$priceObj->priceID}" title="{lang}wcf.user.contest.entry.price.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $priceObj->isDeletable()}<a href="index.php?action=ContestPriceDelete&amp;priceID={@$priceObj->priceID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.user.contest.entry.price.delete.sure{/lang}')" title="{lang}wcf.user.contest.entry.price.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}{@SID_ARG_2ND}#price{@$priceObj->priceID}" title="{lang}wcf.user.contest.entry.price.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p>{@$priceObj->getFormattedMessage()}</p>
												
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
						
						{if $entry->isPriceable()}{assign var=priceUsername value=$username}{/if}
						{if $entry->isPriceable() && $action != 'edit'}
							{assign var=username value=$priceUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<div class="formElement{if $errorField == 'owner'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.user.contest.entry.sponsor{/lang}</label>
											</div>
											<div class="formField">
												<fieldset>
													<legend>{lang}wcf.user.contest.entry.owner{/lang}</legend>
														<label><input type="radio" name="ownerID" value="0" {if 0 == $ownerID}checked="checked" {/if}/> {lang}wcf.user.contest.entry.owner.self{/lang}</label>
													{foreach from=$availableGroups item=availableGroup}
														<label><input type="radio" name="ownerID" value="{@$availableGroup->groupID}" {if $availableGroup->groupID == $ownerID}checked="checked" {/if}/> {lang}{$availableGroup->groupName}{/lang}</label>
													{/foreach}
												</fieldset>
											</div>
										</div>
										
										<div class="formElement{if $errorField == 'subject' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="subject">{lang}wcf.user.contest.entry.price.subject{/lang}</label>
											</div>
											<div class="formField">
												<input type="text" name="subject" id="subject" value="{$subject}" class="inputText" />
												{if $errorField == 'subject' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.price.error.tooLong{/lang}{/if}
													</p>
												{/if}
											</div>
										</div>
										
										<div class="formElement{if $errorField == 'message' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="message">{lang}wcf.user.contest.entry.price.message{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="message" id="message" rows="5" cols="40">{$message}</textarea>
												{if $errorField == 'message' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.price.error.tooLong{/lang}{/if}
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
