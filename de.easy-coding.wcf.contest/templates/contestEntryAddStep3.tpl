<script type="text/javascript">
var sponsors = new Array();
{assign var=i value=0}
{foreach from=$sponsors item=sponsor}
	sponsors[{@$i}] = new Object();
	sponsors[{@$i}]['name'] = '{@$sponsor.name|encodeJS}';
	sponsors[{@$i}]['type'] = '{@$sponsor.type}';
	sponsors[{@$i}]['id'] = '{@$sponsor.id}';
	{assign var=i value=$i+1}
{/foreach}

onloadEvents.push(function() {
	// sponsors
	var list1 = new ContestPermissionList('sponsor', sponsors);
	
	// add onsubmit event
	onsubmitEvents.push(function(form) {
		if (suggestion.selectedIndex != -1) return false;
		if (list1.inputHasFocus) return false;
		list1.submit(form);
	});
});
</script>

<h3 class="subHeadline">{lang}wcf.user.contest.entry.{@$action}{/lang}: {lang}wcf.user.contest.prices{/lang}</h3>
<p>{lang}wcf.user.contest.entry.price.description{/lang}</p>

<fieldset>
	<legend>{lang}wcf.user.contest.entry.price{/lang}</legend>
		
	<div class="formElement">
		<div class="formFieldLabel" id="priceTitle">
			{lang}wcf.user.contest.prices{/lang}
		</div>
		<div class="formField"><div id="price" class="accessRights" style="height:80px"></div></div>
	</div>
	
	<div class="formElement">
		<div class="formField">	
			<fieldset>
				<legend>{lang}wcf.user.contest.entry.price.add{/lang}</legend>
				
				<div class="formElement">
					<div class="formFieldLabel">
						{lang}wcf.user.contest.entry.price.subject{/lang}
					</div>
					<div class="formField">
						<input id="priceAddInput" type="text" name="" value="" class="inputText" />
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						{lang}wcf.user.contest.entry.price.message{/lang}
					</div>
					<div class="formField">
						<textarea id="priceAddText" type="text" name="" rows="3" cols="40"></textarea>
					</div>
				</div>
				<div class="formElement">
					<div class="formField">
						<input id="priceAddButton" type="button" value="{lang}wcf.user.contest.entry.price.add{/lang}" />
					</div>
				</div>	
			</fieldset>
		</div>
	</div>
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}
	
<fieldset>
	<legend>{lang}wcf.user.contest.entry.sponsor{/lang}</legend>
		
	<div class="formElement">
		<div class="formFieldLabel" id="sponsorTitle">
			{lang}Sponsoren{/lang}
		</div>
		<div class="formField"><div id="sponsor" class="accessRights" style="height:80px"></div></div>
	</div>
	<div class="formElement">
		<div class="formField">	
			<input id="sponsorAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
			<script type="text/javascript">
				//<![CDATA[
				suggestion.setSource('index.php?page=ContestSponsorSuggest{@SID_ARG_2ND_NOT_ENCODED}');
				suggestion.enableIcon(true);
				suggestion.init('sponsorAddInput');
				//]]>
			</script>
			<input id="sponsorAddButton" type="button" value="{lang}wcf.user.contest.entry.sponsor.add{/lang}" />
		</div>
		<p class="formFieldDesc">{lang}Benutzer- oder Gruppennamen eingeben.{/lang}</p>
	</div>
</fieldset>

{if $additionalFields2|isset}{@$additionalFields2}{/if}

<div class="formSubmit">
	<input type="submit" name="next" accesskey="b" value="{lang}wcf.global.button.back{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.back()" />
	<input type="submit" name="next" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
