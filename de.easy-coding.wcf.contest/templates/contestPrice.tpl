{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.prices{/lang} - {$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

	{if $entry->isOwner() && $prices|count > 1 && $action != 'edit'}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ItemListEditor.class.js"></script>
	<script type="text/javascript"> 
	//<![CDATA[
	document.observe("dom:loaded", function() {
		new ItemListEditor('pricePosition');
	});
	//]]>
	</script>
	{/if}
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
						{if $isWinner && !$didPick}
							{if $solution}
								<p class="success">{lang}wcf.contest.price.congratulations.now{/lang}</p>
							{else}
								<p class="success">{lang}wcf.contest.price.congratulations.future{/lang}</p>
							{/if}
						{/if}
					
						{if $action != 'edit'}<form method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}">
						<input type="hidden" name="ContestPricePositionForm" value="1" />{/if}
						<div class="contentBox">

							{if $userMessages|isset}{@$userMessages}{/if}
							{if $prices|count > 0}
							<h4 class="subHeadline">{lang}wcf.contest.prices{/lang} <span>({#$items})</span></h4>

							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestPrice&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>

							{assign var='messageNumber' value=$startIndex}
							<ol class="itemList" id="pricePosition" style="list-style-type:none;padding:0px">
							{foreach from=$prices item=priceObj}
								<li id="item_{$priceObj->priceID}" class="deletable">
								{assign var="contestID" value=$priceObj->contestID}
								<div class="message">
								<div class="columnContainer" style="padding:0px">
									<div class="container-3 column content">
										<div style="width:150px; padding:12px">
											<div class="messageHeader" style="float:left;width:100%;">
												<span style="font-size:32px">
													{$messageNumber}.
												</span>
											</div>
											<br style="clear:both"/>
											<div class="smallButtons">
												{assign var="tmp" value=$priceObj->priceID}
												{if $priceObj->isPickable() || $additionalSmallButtons.$tmp|isset}
													<ul>
														{if $priceObj->isPickable()}<li>{if $solution}<a href="index.php?action=ContestPricePick&amp;priceID={$priceObj->priceID}&amp;solutionID={$solution->solutionID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" title="{lang}wcf.contest.price.pick{/lang}"><img src="{icon}contestPriceS.png{/icon}" alt="" /> <span>{lang}wcf.contest.price.pick{/lang}</span></a>{else}error occured{/if}</li>{/if}
														{if $additionalSmallButtons.$tmp|isset}{@$additionalSmallButtons.$tmp}{/if}
													</ul>
												{/if}
											</div>

										</div>
									</div>
									<div class="container-1 column content" style="width:100%;">
									<div class="messageInner {cycle values='container-1,container-2'}">
										<a name="priceObj{@$priceObj->priceID}"></a>
										{if $action == 'edit' && $priceID == $priceObj->priceID}
											<form enctype="multipart/form-data" method="post" action="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}&amp;action=edit">
												<div class="formElement{if $errorField == 'state'} formError{/if}">
													<div class="formFieldLabel">
														<label for="state">{lang}wcf.contest.state{/lang}</label>
													</div>
													<div class="formField">
														<select name="state" id="state">
														{htmloptions options=$states selected=$state}
														</select>
														{if $errorField == 'state'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												<div class="formElement{if $errorField == 'subject'} formError{/if}">
													<div class="formFieldLabel">
														<label for="subject">{lang}wcf.contest.price.subject{/lang}</label>
													</div>
													<div class="formField">
														<input type="text" class="inputText" name="subject" id="subject" value="{$subject}" tabindex="{counter name='tabindex'}" style="width:100%" />
														{if $errorField == 'subject'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												<div class="formElement{if $errorField == 'text'} formError{/if}">
													<div class="formFieldLabel">
														<label for="text">{lang}wcf.contest.price.message{/lang}</label>
													</div>
													<div class="formField">
														<textarea name="text" id="text" rows="4" cols="40">{$text}</textarea>
														{if $errorField == 'text'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												<div class="formElement{if $errorField == 'secretMessage'} formError{/if}">
													<div class="formFieldLabel">
														<label for="secretMessage">{lang}wcf.contest.price.secretMessage{/lang}</label>
													</div>
													<div class="formField">
														<textarea name="secretMessage" id="secretMessage" rows="4" cols="40">{$secretMessage}</textarea>
														{if $errorField == 'secretMessage'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												{if MODULE_ATTACHMENT && $showAttachments}{include file="attachmentsEdit"}
												<script type="text/javascript"> 
												//<![CDATA[
												// hide wysiwyg button + ordered list
												(function() {
													var base = document.getElementById('attachmentsEdit');
													var x = base.getElementsByTagName('select');
													for(var i=0; i<x.length; i++) {
														x[i].style.display = 'none';
													}
													var x = base.getElementsByTagName('div');
													for(var i=0; i<x.length; i++) {
														if(x[i].className == 'buttons') {
															var y = x[i].getElementsByTagName('a');
															for(var j=0; j<x.length; j++) {
																y[j].style.display = 'none';
															}
														}
													}
												})();
												//]]>
												</script>{/if}
												<div class="formSubmit">
													{@SID_INPUT_TAG}
													{@SECURITY_TOKEN_INPUT_TAG}
													<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
													<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
												</div>
											</form>
										{else}
										<div class="messageHeader"{if $priceObj->isWinner() && $priceObj->secretMessage} style="border-style:dashed"{/if}>
											{if $entry->isOwner()}
											<p class="messageCount">
												<select style="display:none" name="pricePositionPositions[{$contestID}][{$priceObj->priceID}]">
													{section name='positions' loop=$prices|count}
														<option value="{@$positions+1}"{if $positions+1 == $priceObj->position} selected="selected"{/if}>{@$positions+1}</option>
													{/section}
												</select>
											</p>
											{/if}
											<div class="containerIcon">
												{if $priceObj->hasWinner()}
													{if $priceObj->getWinner()->getAvatar()}
													{assign var=x value=$priceObj->getWinner()->getAvatar()->setMaxSize(24, 24)}
													<a href="{@$priceObj->getWinner()->getLink()}{@SID_ARG_2ND}">{@$priceObj->getWinner()->getAvatar()}</a>
													{else}
													<a href="{@$priceObj->getWinner()->getLink()}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>{/if}
												{else}
													<img src="{@RELATIVE_WCF_DIR}images/contestAnonymous.png" alt="" style="width: 24px; height: 24px" />
												{/if}
											</div>
											<div class="containerContent">
												<div style="float:right">{@$priceObj->getState()->renderButton()}</div>
												<h4 style="margin: 0; padding: 0">{$priceObj->subject}</h4>
												<p class="light smallFont">{lang}wcf.contest.by{/lang} <a href="{$priceObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$priceObj->getOwner()->getName()}</a>{if $priceObj->hasWinner()}, {lang}wcf.contest.winner.by{/lang} <a href="{$priceObj->getWinner()->getLink()}{@SID_ARG_2ND}">{$priceObj->getWinner()->getName()}</a>{/if}</p>
											</div>
										</div>
										{if $priceObj->isWinner() && $priceObj->secretMessage}
										<div class="messageHeader">
											<div class="containerIcon">
												<img src="{icon}helpM.png{/icon}" alt="" />
											</div>
											<div class="containerContent">
												<p class="light smallFont">{$priceObj->secretMessage}</p>
											</div>
										</div>
										{/if}
										<div class="messageBody">
											<div style="float:right; padding:0px 0px 10px 10px;">
												{if $priceObj->attachmentID}
												<img src="index.php?page=Attachment&attachmentID={$priceObj->attachmentID}" alt="" />
												{else}
												{assign var=x value=$priceObj->getOwner()->getAvatar()->setMaxSize(96, 96)}
												{@$priceObj->getOwner()->getAvatar()}
												{/if}
											</div>
											{@$priceObj->getFormattedMessage()}
											{assign var="tmp" value=$priceObj->priceID}
											{if $additionalMessageContents.$tmp|isset}{@$additionalMessageContents.$tmp}{/if}
										</div>

										<div class="messageFooter">
											<div class="smallButtons">
												<ul>
													<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
													{if $priceObj->isEditable()}<li><a href="index.php?page=ContestPrice&amp;contestID={@$contestID}&amp;priceID={@$priceObj->priceID}&amp;action=edit{@SID_ARG_2ND}#priceObj{@$priceObj->priceID}" title="{lang}wcf.contest.price.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.contest.price.edit{/lang}</span></a></li>{/if}
													{if $priceObj->isDeletable()}<li><a href="index.php?action=ContestPriceDelete&amp;priceID={@$priceObj->priceID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.price.delete.sure{/lang}')" title="{lang}wcf.contest.price.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.contest.price.delete{/lang}</span></a></li>{/if}
												</ul>
											</div>
										</div>
										{/if}
										<hr />
										</div>
									</div>
									</div>
								</div>
								{assign var='messageNumber' value=$messageNumber+1}
								</li>
							{/foreach}
							</ol>

							{if $entry->isOwner() && $prices|count > 1 && $action != 'edit'}
							<div class="formSubmit">
								{@SID_INPUT_TAG}
								{@SECURITY_TOKEN_INPUT_TAG}
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
							{/if}
						</div>
						{if $action != 'edit'}</form>{/if}

						{if $isSponsor == false}
						{if $additionalContentBecomeSponsor|isset}{@$additionalContentBecomeSponsor}{/if}
						<fieldset>
							<legend>{lang}wcf.contest.sidebar.becomesponsor.title{/lang}</legend>
							{lang}wcf.contest.sidebar.becomesponsor.description{/lang}

							<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
								<ul>
									{if $isRegistered}
									<li><a href="index.php?page=ContestSponsor&amp;contestID={$contestID}{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomesponsor.submit{/lang}</span></a></li>
									{else}<li><a href="index.php?form=UserLogin{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomesponsor.submit{/lang}</span></a></li>
									{/if}
								</ul>
							</div>
						</fieldset>
						{/if}

						{if $entry->isPriceable() && $action != 'edit'}
							<h4 class="subHeadline">{lang}wcf.contest.price.add{/lang}</h4>
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
													{if $ownerGroups|count > 0}<b>{lang}wcf.user.userGroups.title{/lang}</b>
														{foreach from=$ownerGroups item=ownerGroup key=idx}
															<label><input type="radio" name="ownerID" value="{$idx}" {if $idx == $ownerID}checked="checked" {/if}/> {lang}{$ownerGroup->groupName}{/lang}</label>
														{/foreach}
													{/if}
													{if $ownerSponsors|count > 0}<b>{lang}wcf.contest.sponsors{/lang}</b>
														{foreach from=$ownerSponsors item=ownerSponsor key=idx}
															<label><input type="radio" name="ownerID" value="{$idx}" {if $idx == $ownerID}checked="checked" {/if}/> {lang}{$ownerSponsor->getOwner()->getName()}{/lang}</label>
														{/foreach}
													{/if}
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

										<div class="formElement{if $errorField == 'text' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="text">{lang}wcf.contest.price.message{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="text" id="text" rows="5" cols="40" tabindex="{counter name='tabindex'}">{$text}</textarea>
												{if $errorField == 'text'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.message.error.tooLong{/lang}{/if}
													</p>
												{/if}
											</div>
										</div>
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
