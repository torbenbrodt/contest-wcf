<fieldset>
	<legend>{lang}wcf.contest.interaction.settings{/lang}</legend>
	
	<div class="formElement{if $errorField == 'interaction'} formError{/if}">
		<div class="formFieldLabel">
			<label>{lang}wcf.contest.interaction.settings{/lang}</label>
		</div>
		<div class="formField">
			<fieldset>
				<legend>{lang}wcf.contest.interaction{/lang}</legend>
				<label>
					<input type="checkbox" name="enableInteraction" value="1" {if $enableInteraction}checked="checked" {/if}/>
					{lang}wcf.contest.enableInteraction{/lang}
				</label>
				{$interactionRulesetList|print_r}
			</fieldset>
		</div>
	</div>
</fieldset>
