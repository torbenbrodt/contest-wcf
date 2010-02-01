<h3 class="subHeadline">{lang}wcf.user.contest.entry.{@$action}{/lang}: {lang}Aufgabe{/lang}</h3>
<p>{lang}Geben Sie hier die Aufgabe vor. Seiten Sie dabei möglichst ausführlich und versuchen Sie Mehrdeutigkeiten zu vermeiden.{/lang}</p>
<fieldset>
	<legend>{lang}wcf.user.contest.entry.message{/lang}</legend>
	
	<div class="editorFrame formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
		<div class="formFieldLabel">
			<label for="text">{lang}wcf.user.contest.entry.message{/lang}</label>
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

{if $additionalFields1|isset}{@$additionalFields1}{/if}

<div class="formSubmit">
	<input type="submit" name="next" accesskey="b" value="{lang}wcf.global.button.back{/lang}" tabindex="{counter name='tabindex'}" {if $action == 'add'}onclick="return steppedTabMenu.back()"{/if} />
	<input type="submit" name="next" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" {if $action == 'add'}onclick="return steppedTabMenu.next()"{/if} />
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>

{if $insertQuotes == 1}
	<script type="text/javascript">
		//<![CDATA[
		document.observe("dom:loaded", function() {
			window.setTimeout(function() {
				multiQuoteManagerObj.insertParentQuotes('contestEntry', {@$userID});
				multiQuoteManagerObj.insertParentQuotes('contestEntrySolution', {@$userID});
			}, 500);
		});
		//]]>
	</script>
{/if}
