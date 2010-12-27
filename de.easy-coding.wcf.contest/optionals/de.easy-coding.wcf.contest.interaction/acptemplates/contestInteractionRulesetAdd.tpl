{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/updateServer{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.contest.interaction.{$action}{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.contest.interaction.{$action}.success{/lang}</p>	
{/if}

{if $packageContestInteractionRuleset|isset && $packageContestInteractionRuleset->errorText}
	<p class="warning">{lang}wcf.acp.contest.interaction.lastErrorText{/lang}<br />{$packageContestInteractionRuleset->errorText}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ContestInteractionRulesetList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.package.server.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/updateServerM.png" alt="" /> <span>{lang}wcf.acp.menu.link.package.server.view{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=ContestInteractionRuleset{@$action|ucfirst}{if $packageContestInteractionRulesetID|isset}&amp;packageContestInteractionRulesetID={@$packageContestInteractionRulesetID}{/if}">
	<div class="border content">
		<div class="container-1">
	
			<fieldset>
				<legend>{lang}wcf.acp.contest.interaction.data{/lang}</legend>
				
				<div class="formElement" id="kindDiv">
					<div class="formFieldLabel">
						<label for="kind">{lang}wcf.acp.contest.interaction.kind{/lang}</label>
					</div>
					<div class="formField">
						<input type="password" class="inputText" name="kind" value="{$kind}" id="kind" />
					</div>
					<div class="formFieldDesc hidden" id="kindHelpMessage">
						<p>{lang}wcf.acp.contest.interaction.kind.description{/lang}</p>
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('kind');
				//]]></script>
				
				<div class="formElement" id="rulesetTableDiv">
					<div class="formFieldLabel">
						<label for="rulesetTable">{lang}wcf.acp.contest.interaction.rulesetTable{/lang}</label>
					</div>
					<div class="formField">
						<input type="password" class="inputText" name="rulesetTable" value="{$rulesetTable}" id="rulesetTable" />
					</div>
					<div class="formFieldDesc hidden" id="rulesetTableHelpMessage">
						<p>{lang}wcf.acp.contest.interaction.rulesetTable.description{/lang}</p>
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('rulesetTable');
				//]]></script>
				
				<div class="formElement" id="rulesetColumnDiv">
					<div class="formFieldLabel">
						<label for="rulesetColumn">{lang}wcf.acp.contest.interaction.rulesetColumn{/lang}</label>
					</div>
					<div class="formField">
						<input type="password" class="inputText" name="rulesetColumn" value="{$rulesetColumn}" id="rulesetColumn" />
					</div>
					<div class="formFieldDesc hidden" id="rulesetColumnHelpMessage">
						<p>{lang}wcf.acp.contest.interaction.rulesetColumn.description{/lang}</p>
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('rulesetColumn');
				//]]></script>
				
				<div class="formElement" id="rulesetColumnTimeDiv">
					<div class="formFieldLabel">
						<label for="rulesetColumnTime">{lang}wcf.acp.contest.interaction.rulesetColumnTime{/lang}</label>
					</div>
					<div class="formField">
						<input type="password" class="inputText" name="rulesetColumnTime" value="{$rulesetColumnTime}" id="rulesetColumnTime" />
					</div>
					<div class="formFieldDesc hidden" id="rulesetColumnTimeHelpMessage">
						<p>{lang}wcf.acp.contest.interaction.rulesetColumnTime.description{/lang}</p>
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('rulesetColumnTime');
				//]]></script>
				
				{if $additionalFields|isset}{@$additionalFields}{/if}
			</fieldset>
		</div>
	</div>
	
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
	</div>
</form>

{include file='footer'}
