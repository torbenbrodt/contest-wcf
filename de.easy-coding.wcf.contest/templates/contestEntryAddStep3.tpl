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

var prices = new Array();
{assign var=i value=0}
{foreach from=$prices item=price}
	prices[{@$i}] = new Object();
	prices[{@$i}]['name'] = '{@$price.name|encodeJS}';
	prices[{@$i}]['type'] = '{@$price.type}';
	prices[{@$i}]['id'] = '{@$price.id}';
	{assign var=i value=$i+1}
{/foreach}

onloadEvents.push(function() {
	// sponsors
	var list1 = new ContestPermissionList('sponsor', sponsors, 'index.php?page=ContestSponsorObjects');
	// prices
	var list2 = new ContestPermissionList('price', prices, function() {
		var url = 'index.php?page=ContestPriceObjects&text='+escape($('priceAddText').getValue());
		$('priceAddText').setValue(''); // reset
		return url;
	});
		
	$('priceAddInput').onfocus = $('priceAddInput').onblur = $('priceAddInput').onkeyup = function() {
		return false;
	};
	
	var suggestion = new ContestSuggestion();
	suggestion.setSource('index.php?page=ContestSponsorSuggest{@SID_ARG_2ND_NOT_ENCODED}');
	suggestion.enableIcon(true);
	suggestion.init('sponsorAddInput');
	
	// add onsubmit event
	onsubmitEvents.push(function(suggestion) {
		return function(form) {
			if (suggestion.selectedIndex != -1) return false;
			if (list1.inputHasFocus || list2.inputHasFocus) return false;
			list1.submit(form);
			list2.submit(form);
		};
	}(suggestion));
});
</script>

<h3 class="subHeadline">{lang}wcf.contest.{@$action}{/lang}: {lang}wcf.contest.prices{/lang}</h3>
<p>{lang}wcf.contest.price.description{/lang}</p>

<fieldset>
	<legend>{lang}wcf.contest.price{/lang}</legend>
		
	<div class="formElement">
		<div class="formFieldLabel" id="priceTitle">
			{lang}wcf.contest.prices{/lang}
		</div>
		<div class="formField"><div id="price" class="accessRights" style="height:80px"></div></div>
	</div>
	
	<div class="formElement">
		<div class="formField">	
			<fieldset>
				<legend>{lang}wcf.contest.price.add{/lang}</legend>
				
				<div class="formElement">
					<div class="formFieldLabel">
						{lang}wcf.contest.price.subject{/lang}
					</div>
					<div class="formField">
						<input id="priceAddInput" type="text" name="" value="" class="inputText" />
					</div>
				</div>
				<div class="formElement">
					<div class="formFieldLabel">
						{lang}wcf.contest.price.message{/lang}
					</div>
					<div class="formField">
						<textarea id="priceAddText" type="text" name="" rows="3" cols="40"></textarea>
					</div>
				</div>
				<div class="formElement">
					<div class="formField">
						<input id="priceAddButton" type="button" value="{lang}wcf.contest.price.add{/lang}" />
					</div>
				</div>	
			</fieldset>
		</div>
	</div>
</fieldset>

{if $additionalFields1|isset}{@$additionalFields1}{/if}
	
<fieldset>
	<legend>{lang}wcf.contest.sponsor{/lang}</legend>
		
	<div class="formElement">
		<div class="formFieldLabel" id="sponsorTitle">
			{lang}Sponsoren{/lang}
		</div>
		<div class="formField"><div id="sponsor" class="accessRights" style="height:80px"></div></div>
	</div>
	<div class="formElement">
		<div class="formField">	
			<input id="sponsorAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
			<input id="sponsorAddButton" type="button" value="{lang}wcf.contest.sponsor.add{/lang}" />
		</div>
		<p class="formFieldDesc">{lang}Benutzer- oder Gruppennamen eingeben.{/lang}</p>
	</div>
	<div class="formElement">
		<div class="formField">
			<label for="sponsortalk_trigger">
				<input type="checkbox" name="sponsortalk_trigger" id="sponsortalk_trigger" value="1" {if $sponsortalk_trigger} checked="checked"{/if} onclick="Effect.toggle('sponsortalk', 'slide');"/>
				{lang}Sponsortalk eröffnen{/lang}
				
				
				<script type="text/javascript">
					//<![CDATA[
					document.observe("dom:loaded", function() {
						if(typeof document.getElementById('sponsortalk_trigger').checked == 'undefined' || !document.getElementById('sponsortalk_trigger').checked) {
							document.getElementById('sponsortalk').style.display = 'none';
						}
					});
					//]]>
				</script>
			</label>
		</div>
		<p class="formFieldDesc">{lang}Um Juroren zu gewinnen können Sie ihnen eine persönliche Nachricht schicken.{/lang}</p>
	</div>
	<div class="formElement" id="sponsortalk">
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.contest.sponsortalk{/lang}</legend>
				<div class="formElement">
					<div class="formFieldLabel">
						{lang}wcf.contest.sponsortalk.message{/lang}
					</div>
					<div class="formField">
						<textarea id="sponsortalkAddText" type="text" name="sponsortalkAddText" rows="5" cols="40"></textarea>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</fieldset>

{if $additionalFields2|isset}{@$additionalFields2}{/if}

<div class="formSubmit">
	<input type="submit" name="back" accesskey="b" value="{lang}wcf.global.button.back{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.back()" />
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.next{/lang}" tabindex="{counter name='tabindex'}" onclick="return steppedTabMenu.next()" />
	{if $action != 'add'}
	<input type="submit" name="send" accesskey="n" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
	{/if}
	{@SID_INPUT_TAG}
	<input type="hidden" name="idHash" value="{$idHash}" />
</div>
