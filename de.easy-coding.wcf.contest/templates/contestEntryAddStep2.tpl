<h3 class="subHeadline">{lang}wcf.contest.{@$action}{/lang}: {lang}wcf.contest.message{/lang}</h3>
<p>{lang}wcf.contest.create.description{/lang}</p>
				
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
	<legend>{lang}wcf.contest.message{/lang}</legend>
	
	<div class="editorFrame formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
		<div class="formFieldLabel">
			<label for="text">{lang}wcf.contest.message{/lang}</label>
		</div>
		
		<div class="formField">				
			<textarea name="text" id="text" rows="15" cols="40" tabindex="{counter name='tabindex'}">{$text}</textarea>
			{if $errorField == 'text'}
				<script type="text/javascript">
				//<![CDATA[
				onloadEvents.push(function() {
					steppedTabMenu.showSubTabMenu('step2');
				});
				//]]>
				</script>
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
	<input type="submit" name="back" accesskey="b" value="{lang}wcf.global.button.back{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.back()" />
{* all steps during registration?
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
*}
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
	{@SID_INPUT_TAG}
	{@SECURITY_TOKEN_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
