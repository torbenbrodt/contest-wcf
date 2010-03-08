<script type="text/javascript">
var jurys = new Array();
{assign var=i value=0}
{foreach from=$jurys item=jury}
	jurys[{@$i}] = new Object();
	jurys[{@$i}]['name'] = '{@$jury.name|encodeJS}';
	jurys[{@$i}]['type'] = '{@$jury.type}';
	jurys[{@$i}]['id'] = '{@$jury.id}';
	{assign var=i value=$i+1}
{/foreach}

onloadEvents.push(function() {
	// jurys
	var list1 = new ContestPermissionList('jury', jurys, 'index.php?page=ContestJuryObjects');
	
	// add onsubmit event
	onsubmitEvents.push(function(form) {
		if (suggestion.selectedIndex != -1) return false;
		if (list1.inputHasFocus) return false;
		list1.submit(form);
	});
});
</script>

<h3 class="subHeadline">{lang}wcf.contest.{@$action}{/lang}: {lang}wcf.contest.jurys{/lang}</h3>
<p>{lang}wcf.contest.jury.owner.description{/lang}</p>
<fieldset>
	<legend>{lang}wcf.contest.jury{/lang}</legend>
	
	<div class="formElement">
		<div class="formFieldLabel" id="juryTitle">
			{lang}wcf.contest.jury.add{/lang}
		</div>
		<div class="formField"><div id="jury" class="accessRights" style="height:80px"></div></div>
	</div>
	<div class="formElement">
		<div class="formField">	
			<input id="juryAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
			<script type="text/javascript">
				//<![CDATA[
				suggestion.setSource('index.php?page=ContestJurySuggest{@SID_ARG_2ND_NOT_ENCODED}');
				suggestion.enableIcon(true);
				suggestion.init('juryAddInput');
				//]]>
			</script>
			<input id="juryAddButton" type="button" value="{lang}wcf.contest.jury.add{/lang}" />
		</div>
		<p class="formFieldDesc">{lang}Benutzer- oder Gruppennamen eingeben.{/lang}</p>
	</div>
	
	<div class="formElement">
		<div class="formField">
			<label for="jurytalk_trigger">
				<input type="checkbox" name="jurytalk_trigger" id="jurytalk_trigger" value="1" {if $jurytalk_trigger} checked="checked"{/if} onclick="Effect.toggle('jurytalk', 'slide');"/>
				{lang}Jurytalk eröffnen{/lang}
				
				
				<script type="text/javascript">
					//<![CDATA[
					document.observe("dom:loaded", function() {
						if(typeof document.getElementById('jurytalk_trigger').checked == 'undefined' || !document.getElementById('jurytalk_trigger').checked) {
							document.getElementById('jurytalk').style.display = 'none';
						}
					});
					//]]>
				</script>
			</label>
		</div>
		<p class="formFieldDesc">{lang}wcf.contest.jurytalk.owner.description{/lang}</p>
	</div>
	<div class="formElement" id="jurytalk">
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.contest.jurytalk{/lang}</legend>
				<div class="formElement">
					<div class="formFieldLabel">
						{lang}wcf.contest.jurytalk.message{/lang}
					</div>
					<div class="formField">
						<textarea id="jurytalkAddText" type="text" name="jurytalkAddText" rows="5" cols="40"></textarea>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	
	{if $additionalInformationFields|isset}{@$additionalInformationFields}{/if}
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}

<div class="formSubmit">
	<input type="submit" name="back" accesskey="b" value="{lang}wcf.global.button.back{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.back()" />
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
	{if $action != 'add'}
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
	{/if}
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
