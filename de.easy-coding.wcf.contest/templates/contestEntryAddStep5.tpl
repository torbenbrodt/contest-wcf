<script type="text/javascript">
var participants = new Array();
{assign var=i value=0}
{foreach from=$participants item=participant}
	participants[{@$i}] = new Object();
	participants[{@$i}]['name'] = '{@$participant.name|encodeJS}';
	participants[{@$i}]['type'] = '{@$participant.type}';
	participants[{@$i}]['id'] = '{@$participant.id}';
	{assign var=i value=$i+1}
{/foreach}

onloadEvents.push(function() {
	// participants
	var list1 = new ContestPermissionList('participant', participants);
	// add onsubmit event
	document.getElementById('ContestAddForm').onsubmit = function() { 
		if (suggestion.selectedIndex != -1) return false;
		if (list1.inputHasFocus) return false;
		list1.submit(this);
	};
});
</script>

<h3 class="subHeadline">{lang}wcf.user.contest.entry.{@$action}{/lang}: {lang}wcf.user.contest.participants{/lang}</h3>
<p>{lang}wcf.user.contest.entry.participant.description{/lang}</p>
<fieldset>
	<legend>{lang}wcf.user.contest.entry.participant{/lang}</legend>
	
	<div class="formElement">
		<div class="formFieldLabel" id="participantTitle">
			{lang}wcf.user.contest.entry.participant.add{/lang}
		</div>
		<div class="formField"><div id="participant" class="accessRights"></div></div>
	</div>
	<div class="formElement">
		<div class="formField">	
			<input id="participantAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
			<script type="text/javascript">
				//<![CDATA[
				suggestion.setSource('index.php?page=ContestParticipantSuggest{@SID_ARG_2ND_NOT_ENCODED}');
				suggestion.enableIcon(true);
				suggestion.init('participantAddInput');
				//]]>
			</script>
			<input id="participantAddButton" type="button" value="{lang}wcf.user.contest.entry.participant.add{/lang}" />
		</div>
		<p class="formFieldDesc">{lang}Benutzer- oder Gruppennamen eingeben.{/lang}</p>
	</div>
	
	{if $additionalInformationFields|isset}{@$additionalInformationFields}{/if}
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}

<div class="formSubmit">
	<input type="submit" name="next" accesskey="b" value="{lang}wcf.global.button.back{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.back()" />
	<input type="submit" name="next" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>