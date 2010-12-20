{assign var='i' value=1}
<ul class="dataList" style="width: 275px; float:left">
{foreach from=$users item=owner}
	<li class="{cycle values='container-1,container-2'}">
		<div class="containerIcon">
			{if $owner->getOwner()->getAvatar()}
				{assign var=x value=$owner->getOwner()->getAvatar()->setMaxSize(24, 24)}
				{if $owner->userID}<a href="index.php?page=User&amp;userID={@$owner->userID}{@SID_ARG_2ND}">{/if}{@$owner->getOwner()->getAvatar()}{if $owner->userID}</a>{/if}
			{else}
				{if $owner->userID}<a href="index.php?page=User&amp;userID={@$owner->userID}{@SID_ARG_2ND}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $owner->userID}</a>{/if}
			{/if}
		</div>
		<div class="containerContent">
			<a href="{$owner->getOwner()->getLink()}{@SID_ARG_2ND}">{$owner->getOwner()->getName()}</a>
			<div class="buttons">
			{assign var=tickets value=$owner->c}{lang}wcf.contest.interaction.tickets{/lang}
			</div>
		</div>
	</li>
	{if $i == 8}
		</ul>
		<ul class="dataList" style="width: 275px; float:left; margin-left: 20px">
	{/if}
	{assign var='i' value=$i+1}
{/foreach}
</ul>
<div id="contestInteractionPagination{$contestID}">
{pages print=true assign=pagesOutput link="index.php?page=ContestInteraction&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
</div>
