{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.solutions{/lang} - {$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=rss2" title="{lang}wcf.contest.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=atom" title="{lang}wcf.contest.feed{/lang} (Atom)" />
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
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
							<h4 class="subHeadline">{lang}wcf.contest.solution.{$action}{/lang}</h4>
							<form enctype="multipart/form-data" method="post" action="index.php?form=ContestSolution{$action|ucfirst}&amp;contestID={@$contestID}&amp;action={$action}{if $action == 'edit'}&amp;solutionID={$solutionID}{/if}">
				
								{if $preview|isset}
									<div class="message content">
										<div class="messageInner container-1">
											<div class="messageHeader">
												<h4>{lang}wcf.message.preview{/lang}</h4>
											</div>
											<div class="messageBody">
												<div>{@$preview}</div>
											</div>
										</div>
									</div>
								{/if}
							
								<fieldset>
									<div class="editorFrame formElement editor formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
										<div class="formFieldLabel">
											<label for="text">{lang}wcf.contest.solution{/lang}</label>
										</div>
										<div class="formField">			
											<textarea name="text" id="text" rows="15" cols="40" tabindex="{counter name='tabindex'}">{$text}</textarea>
											{if $errorField == 'text'}
												<p class="innerError">
													{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
													{if $errorType == 'tooLong'}{lang}wcf.message.error.tooLong{/lang}{/if}
													{if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
												</p>
											{/if}
										</div>
									</div>

									{if $additionalInformationFields|isset}{@$additionalInformationFields}{/if}

									{include file='messageFormTabs'}
								</fieldset>
								<fieldset>
									<div class="formElement{if $errorField == 'state'} formError{/if}">
										<div class="formFieldLabel">
											<label>{lang}wcf.contest.state{/lang}</label>
										</div>
										<div class="formField">
											<fieldset>
												<legend>{lang}wcf.contest.state{/lang}</legend>
												<select name="state" id="state">
												{htmloptions options=$states selected=$state}
												</select>
											</fieldset>
										</div>
									</div>
									<div class="formElement{if $errorField == 'owner'} formError{/if}">
										<div class="formFieldLabel">
											<label>{lang}wcf.contest.owner{/lang}</label>
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
										<div class="formFieldDesc">
											{lang}wcf.contest.solution.{$action}.description{/lang}
										</div>
									</div>
								</fieldset>
								
								<div class="formSubmit">
									<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
									<input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" tabindex="{counter name='tabindex'}" />
									<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" tabindex="{counter name='tabindex'}" />
									{@SID_INPUT_TAG}
									{@SECURITY_TOKEN_INPUT_TAG}
									<input type="hidden" name="idHash" value="{$idHash}" />
								</div>
							</form>
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
