<h3 class="subHeadline">{lang}wcf.contest.{@$action}{/lang}: {lang}wcf.contest.information{/lang}</h3>
<p>{lang}wcf.contest.contest.description{/lang}</p>
<fieldset>
	<legend>{lang}wcf.contest.information{/lang}</legend>
	
	<div class="formElement{if $errorField == 'subject'} formError{/if}">
		<div class="formFieldLabel">
			<label for="subject">{lang}wcf.contest.subject{/lang}</label>
		</div>
		<div class="formField">
			<input type="text" class="inputText" name="subject" id="subject" value="{$subject}" tabindex="{counter name='tabindex'}" />
			{if $errorField == 'subject'}
				<p class="innerError">
					{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
				</p>
			{/if}
		</div>
		<p class="formFieldDesc">{lang}wcf.contest.subject.description{/lang}</p>
	</div>
	
	<div class="formElement{if $errorField == 'classes'} formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.contest.classes{/lang}</label>
		</div>
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.contest.classes{/lang}</legend>
				{foreach from=$availableClasses item=child}
					{assign var="contestClass" value=$child.contestClass}
					<label>{if $child.depth > 1}{@" * "|str_repeat:$child.depth-1}{/if}<input type="checkbox" name="classIDArray[]" value="{@$contestClass->classID}" {if $contestClass->classID|in_array:$classIDArray}checked="checked" {/if}/> {lang}{$contestClass->title}{/lang}</label>
				{/foreach}
			</fieldset>
		</div>
	</div>
	
	{if MODULE_TAGGING}{include file='tagAddBit'}{/if}
</fieldset>


<fieldset>
	<legend>{lang}wcf.contest.owner{/lang}</legend>
	<p>{lang}wcf.contest.owner.description{/lang}</p>
	
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
	</div>
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}

<div class="formSubmit">
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
	{if $action != 'add'}
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
	{/if}
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
