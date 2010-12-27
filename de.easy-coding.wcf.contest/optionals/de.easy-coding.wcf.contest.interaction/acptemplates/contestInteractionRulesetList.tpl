{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contestInteractionL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.contest.interaction.list{/lang}</h2>
	</div>
</div>

{if $deletedContestInteractionRulesets}
	<p class="success">{lang}wcf.acp.contest.interaction.delete.success{/lang}</p>	
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=ContestInteractionRulesetList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?form=ContestInteractionRulesetAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.contest.interaction.add{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/contestInteractionL.png" alt="" /> <span>{lang}wcf.acp.contest.interaction.add{/lang}</span></a></li>
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}			
		</ul>
	</div>
</div>

{if $rulesets|count}
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}wcf.acp.contest.interaction.list.data{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnRulesetID{if $sortField == 'rulesetID'} active{/if}" colspan="2"><div><a href="index.php?page=ContestInteractionRulesetList&amp;pageNo={@$pageNo}&amp;sortField=rulesetID&amp;sortOrder={if $sortField == 'rulesetID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.interaction.rulesetID{/lang}{if $sortField == 'rulesetID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnContestInteractionRulesetName{if $sortField == 'rulesetTable'} active{/if}"><div><a href="index.php?page=ContestInteractionRulesetList&amp;pageNo={@$pageNo}&amp;sortField=rulesetTable&amp;sortOrder={if $sortField == 'rulesetTable' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.interaction.rulesetTable{/lang}{if $sortField == 'rulesetTable'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnContestInteractionRulesetName{if $sortField == 'kind'} active{/if}"><div><a href="index.php?page=ContestInteractionRulesetList&amp;pageNo={@$pageNo}&amp;sortField=kind&amp;sortOrder={if $sortField == 'kind' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.interaction.kind{/lang}{if $sortField == 'kind'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnContestInteractionRulesetName{if $sortField == 'rulesetColumn'} active{/if}"><div><a href="index.php?page=ContestInteractionRulesetList&amp;pageNo={@$pageNo}&amp;sortField=rulesetColumn&amp;sortOrder={if $sortField == 'rulesetColumn' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.interaction.rulesetColumn{/lang}{if $sortField == 'rulesetColumn'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnContestInteractionRulesetName{if $sortField == 'rulesetColumnTime'} active{/if}"><div><a href="index.php?page=ContestInteractionRulesetList&amp;pageNo={@$pageNo}&amp;sortField=rulesetColumnTime&amp;sortOrder={if $sortField == 'rulesetColumnTime' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.interaction.rulesetColumnTime{/lang}{if $sortField == 'rulesetColumnTime'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					
					{if $additionalColumns|isset}{@$additionalColumns}{/if}
				</tr>
			</thead>
			<tbody>
			{foreach from=$rulesets item=ruleset}
				<tr class="{cycle values="container-1,container-2"}">
					<td class="columnIcon">
						{if $ruleset.editable}
							<a href="index.php?form=ContestInteractionRulesetEdit&amp;rulesetID={@$ruleset.rulesetID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.contest.interaction.edit{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.contest.interaction.edit{/lang}" />
						{/if}
						{if $ruleset.deletable}
							<a onclick="return confirm('{lang}wcf.acp.contest.interaction.delete.sure{/lang}')" href="index.php?action=ContestInteractionRulesetDelete&amp;rulesetID={@$ruleset.rulesetID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.contest.interaction.delete{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.contest.interaction.delete{/lang}" />
						{/if}
						
						{if $ruleset.additionalButtons|isset}{@$ruleset.additionalButtons}{/if}
					</td>
					<td class="columnRulesetID columnID">{@$ruleset.rulesetID}</td>
					<td class="columnContestInteractionRulesetName columnText">{if $ruleset.editable}<a title="{lang}wcf.acp.contest.interaction.edit{/lang}" href="index.php?form=ContestInteractionRulesetEdit&amp;rulesetID={@$ruleset.rulesetID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$ruleset.rulesetTable}</a>{else}{$ruleset.rulesetTable}{/if}</td>
					<td class="columnMembers">{$ruleset.kind}</td>
					<td class="columnMembers">{$ruleset.rulesetTable}</td>
					<td class="columnMembers">{$ruleset.rulesetColumn}</td>
					<td class="columnMembers">{$ruleset.rulesetColumnTime}</td>
					{if $ruleset.additionalColumns|isset}{@$ruleset.additionalColumns}{/if}
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{/if}

<div class="contentFooter">
	{@$pagesLinks}
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?form=ContestInteractionRulesetAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.contest.interaction.add{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/contestInteractionM.png" alt="" /> <span>{lang}wcf.acp.contest.interaction.add{/lang}</span></a></li>
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
		</ul>
	</div>
</div>

{include file='footer'}
