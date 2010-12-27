{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.promotion{/lang} - {$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
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
							
						</div>
						
						<div class="formElement" id="languageIDDiv">
							<div class="formFieldLabel">
								<label for="languageID">{lang}wcf.user.language{/lang}</label>
							</div>
							<div class="formField">
								<select name="languageID" id="languageID">
									{foreach from=$languages key=key item=language}
										<option value="{@$key}"{if $key == $languageID} selected="selected"{/if}>
											{lang}wcf.global.language.{@$language}{/lang}
										</option>
									{/foreach}						
								</select>
							</div>
						</div>
						
						<div class="formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
							<div class="formFieldLabel">
								<label for="text">{lang}wcf.contest.promotion.action{/lang}</label>
							</div>
							<div class="formField">
								<textarea name="text" id="text" rows="7" cols="40">{$text}</textarea>
								{if $errorField == 'text'}
									<p class="innerError">
										{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									</p>
								{/if}
							</div>
						</div>
						
						<div class="formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
							<div class="formFieldLabel">
								<label for="text">{lang}wcf.contest.promotion.message{/lang}</label>
							</div>
							<div class="formField">
								<textarea name="text" id="text" rows="7" cols="40">{$text}</textarea>
								{if $errorField == 'text'}
									<p class="innerError">
										{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									</p>
								{/if}
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
