{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contestRatingoptionL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.contest.ratingoption{/lang}</h2>
		<p>{lang}wcf.acp.contest.ratingoption.count{/lang}</p>
	</div>
</div>

{if $deletedOptionID}
	<p class="success">{lang}wcf.acp.contest.ratingoption.delete.success{/lang}</p>	
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=ContestRatingoptionList&classID=$classID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
	
	{if $this->user->getPermission('admin.contest.canAddRatingoption')}
		<div class="largeButtons">
			<ul><li><a href="index.php?form=ContestRatingoptionAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contestRatingoptionAddM.png" alt="" title="{lang}wcf.acp.contest.ratingoption.add{/lang}" /> <span>{lang}wcf.acp.contest.ratingoption.add{/lang}</span></a></li></ul>
		</div>
	{/if}
</div>


<div class="subTabMenu">
	<div class="containerHead">
		<ul>
			<li{if $classID == 0} class="activeSubTabMenu"{/if}><a href="index.php?page=ContestRatingoptionList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.contest.class.item.default{/lang} ({#$defaultRatingoptions})</span></a></li>
			{foreach from=$classes item=contestClass}
				<li{if $classID == $contestClass->classID} class="activeSubTabMenu"{/if}><a href="index.php?page=ContestRatingoptionList&amp;classID={@$contestClass->classID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.contest.class.item.{$contestClass}{/lang} ({#$contestClass->ratingoptions})</span></a></li>
			{/foreach}
		</ul>
	</div>
</div>
{if $ratingoptions|count}
	<div class="border">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnOptionID{if $sortField == 'optionID'} active{/if}" colspan="2"><div><a href="index.php?page=ContestRatingoptionList&amp;classID={@$classID}&amp;pageNo={@$pageNo}&amp;sortField=ContestOptionID&amp;sortOrder={if $sortField == 'optionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.ratingoption.optionID{/lang}{if $sortField == 'optionID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnRatingoptionTitle{if $sortField == 'ratingoptionTitle'} active{/if}"><div><a href="index.php?page=ContestRatingoptionList&amp;classID={@$classID}&amp;pageNo={@$pageNo}&amp;sortField=ContestRatingoptionTitle&amp;sortOrder={if $sortField == 'ratingoptionTitle' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.ratingoption.title{/lang}{if $sortField == 'ratingoptionTitle'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnRatingoptionOrder{if $sortField == 'position'} active{/if}"><div><a href="index.php?page=ContestRatingoptionList&amp;classID={@$classID}&amp;pageNo={@$pageNo}&amp;sortField=position&amp;sortOrder={if $sortField == 'position' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.contest.ratingoption.position{/lang}{if $sortField == 'position'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					
					{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
				</tr>
			</thead>
			<tbody id="ratingoptionList">
			{foreach from=$ratingoptions item=ratingoption}
				<tr class="{cycle values="container-1,container-2"}" id="ratingoptionRow_{@$ratingoption->optionID}">
					<td class="columnIcon">
						{if $this->user->getPermission('admin.contest.canEditRatingoption')}
							<a href="index.php?form=ContestRatingoptionEdit&amp;optionID={@$ratingoption->optionID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.contest.ratingoption.edit{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.contest.ratingoption.edit{/lang}" />
						{/if}
						{if $this->user->getPermission('admin.contest.canDeleteRatingoption')}
							<a onclick="return confirm('{lang}wcf.acp.contest.ratingoption.delete.sure{/lang}')" href="index.php?action=ContestRatingoptionDelete&amp;optionID={@$ratingoption->optionID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.contest.ratingoption.delete{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.contest.ratingoption.delete{/lang}" />
						{/if}
						
						{if $additionalButtons.$ratingoption->optionID|isset}{@$additionalButtons.$ratingoption->optionID}{/if}
					</td>
					<td class="columnOptionID columnID">{@$ratingoption->optionID}</td>
					<td class="columnRatingoptionTitle columnText">
						{if $this->user->getPermission('admin.contest.canEditRatingoption')}
							<a href="index.php?form=ContestRatingoptionEdit&amp;optionID={@$ratingoption->optionID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.contest.ratingoption.item.{@$ratingoption->optionID}{/lang}</a>
						{else}
							{lang}wcf.contest.ratingoption.item.{@$ratingoption->optionID}{/lang}
						{/if}
					</td>
					<td class="columnRatingoptionOrder columnNumbers">{@$ratingoption->position}</td>
					
					{if $additionalColumns.$ratingoption->optionID|isset}{@$additionalColumns.$ratingoption->optionID}{/if}
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<div class="border content"><div class="container-1">{lang}wcf.acp.contest.ratingoption.count{/lang}</div></div>
{/if}

<div class="contentFooter">
	{@$pagesLinks}
	
	{if $this->user->getPermission('admin.contest.canAddRatingoption')}
		<div class="largeButtons">
			<ul><li><a href="index.php?form=ContestRatingoptionAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/contestRatingoptionAddM.png" alt="" title="{lang}wcf.acp.contest.ratingoption.add{/lang}" /> <span>{lang}wcf.acp.contest.ratingoption.add{/lang}</span></a></li></ul>
		</div>
	{/if}
</div>

{include file='footer'}
