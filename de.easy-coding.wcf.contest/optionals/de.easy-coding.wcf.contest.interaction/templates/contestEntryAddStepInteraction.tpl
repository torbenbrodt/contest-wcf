<fieldset>
	<legend>{lang}wcf.contest.interaction.settings{/lang}</legend>
	
	<div class="formElement">
		<div class="formFieldLabel">
			<label>{lang}wcf.contest.interaction.settings{/lang}</label>
		</div>
		<div class="formField">
			<label>
				<div id="enableInteractionDiv">
					<input type="checkbox" name="enableInteraction" value="1" {if $enableInteraction}checked="checked" {/if}/>
					{lang}wcf.contest.enableInteraction{/lang}
				</div>
			</label>

			<fieldset>
				<legend>{lang}wcf.contest.interaction.rulesets{/lang}</legend>
				<ul>
				{foreach from=$interactionRulesetList item=ruleset}
					<li><b>{$ruleset->rulesetTable}</b><br />
					{@$ruleset->fromTime|time}<br />
					- {@$ruleset->untilTime|time}
					</li>
				{/foreach}
				</ul>
			</fieldset>
			<label>
				<div id="interactionLastUpdateDiv">
					<input type="text" class="inputText" name="interactionLastUpdate" value="{$interactionLastUpdate}" />
					{lang}wcf.contest.interactionLastUpdate{/lang}
				</div>
			</label>
		</div>
	</div>
</fieldset>
