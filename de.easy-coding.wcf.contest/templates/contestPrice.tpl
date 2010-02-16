{include file="documentHeader"}
<head>
	<title>{$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	
	{if $entry->isOwner()}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ItemListEditor.class.js"></script>
	<script type="text/javascript"> 
	//<![CDATA[
	document.observe("dom:loaded", function() {
		new ItemListEditor('pricePosition');
	});
	//]]>
	</script>
	{/if}
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
						{if $action != 'edit'}<form method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}">{/if}
						<div class="contentBox">
							<h4 class="subHeadline">{lang}wcf.contest.prices{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestPrice&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							{assign var='messageNumber' value=$items-$startIndex+1}
							<ol class="itemList" id="pricePosition" style="list-style-type:none;padding:0px">
							{foreach from=$prices item=priceObj}
								<li id="item_{$priceObj->priceID}" class="deletable">
								{assign var="contestID" value=$priceObj->contestID}
								<div class="message content">
									<div class="messageInner {cycle values='container-1,container-2'}">
										<a id="priceObj{@$priceObj->contestID}"></a>
										{if $action == 'edit' && $priceID == $priceObj->priceID}
											<form method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}&amp;action=edit">
												<div class="formElement{if $errorField == 'subject'} formError{/if}">
													<div class="formFieldLabel">
														<label for="subject">{lang}wcf.contest.price.subject{/lang}</label>
													</div>
													<div class="formField">
														<input type="text" class="inputText" name="subject" id="subject" value="{$subject}" tabindex="{counter name='tabindex'}" />
														{if $errorField == 'subject'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												<div class="formElement{if $errorField == 'message'} formError{/if}">
													<div class="formFieldLabel">
														<label for="message">{lang}wcf.contest.price.message{/lang}</label>
													</div>
													<div class="formField">
														<textarea name="message" id="message" rows="3" cols="40">{$message}</textarea>
														{if $errorField == 'message'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												<div class="formSubmit">
													{@SID_INPUT_TAG}
													<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
													<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
												</div>
											</form>
										{else}
										<div class="messageHeader">
											{if $entry->isOwner()}
											<p class="messageCount">
												<select name="pricePositionPositions[{$contestID}][{$priceObj->priceID}]">
													{section name='positions' loop=$prices|count}
														<option value="{@$positions+1}"{if $positions+1 == $priceObj->position} selected="selected"{/if}>{@$positions+1}</option>
													{/section}
												</select>
											</p>
											{/if}
											<div class="containerIcon">
												{if $priceObj->getOwner()->getAvatar()}
													{assign var=x value=$priceObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
													<a href="{@$priceObj->getOwner()->getLink()}{@SID_ARG_2ND}" title="{lang username=$priceObj->username}wcf.user.viewProfile{/lang}">{@$priceObj->getOwner()->getAvatar()}</a>
												{else}
													<a href="{@$priceObj->getOwner()->getLink()}{@SID_ARG_2ND}" title="{lang username=$priceObj->username}wcf.user.viewProfile{/lang}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
												{/if}
											</div>
											<div class="containerContent">
												<div style="float:right">*{$priceObj->state}*</div>
												<h4 style="margin: 0; padding: 0">{$priceObj->subject}</h4>
												<p class="light smallFont">{lang}wcf.contest.by{/lang} <a href="{$priceObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$priceObj->getOwner()->getName()}</a></p>
											</div>
										</div>
										<div class="messageBody">
											{@$priceObj->getFormattedMessage()}
										</div>
								
										<div class="messageFooter">
											<div class="smallButtons">
												<ul>
													<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
													{if $priceObj->isEditable()}<li><a href="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}&amp;action=edit{@SID_ARG_2ND}#price{@$priceObj->priceID}" title="{lang}wcf.contest.price.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.contest.price.edit{/lang}</span></a></li>{/if}
													{if $priceObj->isDeletable()}<li><a href="index.php?action=ContestPriceDelete&amp;priceID={@$priceObj->priceID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.price.delete.sure{/lang}')" title="{lang}wcf.contest.price.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.contest.price.delete{/lang}</span></a></li>{/if}
												</ul>
											</div>
										</div>
										{/if}
										<hr />
									</div>
								</div>
								{assign var='messageNumber' value=$messageNumber-1}
								</li>
							{/foreach}
							</ol>
							
							{if $entry->isOwner()}		
							<div class="formSubmit">
								{@SID_INPUT_TAG}
								<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
								<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
							</div>
							{/if}
							
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
						{if $action != 'edit'}</form>{/if}
						
						{if $entry->isPriceable() && $action != 'edit'}
							<h4 class="subHeadline">{lang}wcf.contest.prices{/lang}</h4>
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<div class="formElement{if $errorField == 'owner'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.contest.sponsor{/lang}</label>
											</div>
											<div class="formField">
												<fieldset>
													<legend>{lang}wcf.contest.owner{/lang}</legend>
														<label><input type="radio" name="ownerID" value="0" {if 0 == $ownerID}checked="checked" {/if}/> {lang}wcf.contest.owner.self{/lang}</label>
													{foreach from=$availableGroups item=availableGroup}
														<label><input type="radio" name="ownerID" value="{@$availableGroup->groupID}" {if $availableGroup->groupID == $ownerID}checked="checked" {/if}/> {lang}{$availableGroup->groupName}{/lang}</label>
													{/foreach}
												</fieldset>
											</div>
										</div>
										
										<div class="formElement{if $errorField == 'subject' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="subject">{lang}wcf.contest.price.subject{/lang}</label>
											</div>
											<div class="formField">
												<input type="text" name="subject" id="subject" value="{$subject}" class="inputText" />
												{if $errorField == 'subject' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.contest.price.error.tooLong{/lang}{/if}
													</p>
												{/if}
											</div>
										</div>
										
										<div class="formElement{if $errorField == 'message' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="message">{lang}wcf.contest.price.message{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="message" id="message" rows="5" cols="40">{$message}</textarea>
												{if $errorField == 'message' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.contest.price.error.tooLong{/lang}{/if}
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
