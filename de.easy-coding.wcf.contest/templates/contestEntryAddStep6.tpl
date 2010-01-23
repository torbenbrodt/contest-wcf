<h3 class="subHeadline">{lang}wcf.user.contest.entry.{@$action}{/lang}: {lang}wcf.user.contest.entry.settings{/lang}</h3>
<p>{lang}wcf.user.contest.entry.contest.description{/lang}</p>
<fieldset>
	<legend>{lang}wcf.user.contest.entry.settings{/lang}</legend>
	
	<div class="formElement{if $errorField == 'state'} formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.user.contest.entry.state{/lang}</label>
		</div>
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.user.contest.entry.state{/lang}</legend>
				{foreach from=$states item=availableState}
					<label><input type="radio" name="state" value="{@$availableState}" {if $state == $availableState}checked="checked" {/if}/> {lang}{$availableState}{/lang}</label>
				{/foreach}
			</fieldset>
		</div>
	</div>
	
	<div class="formElement{if $errorField == 'from'}formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.user.contest.entry.from{/lang}</label>
		</div>
		<div class="formField">
			<input type="text" name="from" id="from" class="inputText" value="{$from}" />
		</div>
		<div class="formFieldDesc">
			{lang}wcf.user.contest.entry.from.description{/lang}
		</div>
	</div>
	
	<div class="formElement{if $errorField == 'to'}formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.user.contest.entry.to{/lang}</label>
		</div>
		<div class="formField">
			<input type="text" name="to" id="to" class="inputText" value="{$to}" />
		</div>
		<div class="formFieldDesc">
			{lang}wcf.user.contest.entry.to.description{/lang}
		</div>
	</div>
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}

<div class="formSubmit">
	<input type="submit" name="next" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
