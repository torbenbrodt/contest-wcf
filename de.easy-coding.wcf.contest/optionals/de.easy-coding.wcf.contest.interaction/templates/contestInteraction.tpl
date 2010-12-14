<table>
{foreach from=$users item=owner}
	<tr>
		<td>
		{if $owner->getOwner()->getAvatar()}
			{assign var=x value=$owner->getOwner()->getAvatar()->setMaxSize(24, 24)}
			{if $owner->userID}<a href="index.php?page=User&amp;userID={@$owner->userID}{@SID_ARG_2ND}">{/if}{@$owner->getOwner()->getAvatar()}{if $owner->userID}</a>{/if}
		{else}
			{if $owner->userID}<a href="index.php?page=User&amp;userID={@$owner->userID}{@SID_ARG_2ND}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $owner->userID}</a>{/if}
		{/if}
		</td>
		<td style="padding:5px"><a href="{$owner->getOwner()->getLink()}{@SID_ARG_2ND}">{$owner->getOwner()->getName()}</a></td>
		<td>{assign var=tickets value=$owner->c}{lang}wcf.contest.interaction.tickets{/lang}</td>
	</tr>
{/foreach}
</table>
<div id="contestInteractionPagination{$contestID}">
{pages print=true assign=pagesOutput link="index.php?page=ContestInteraction&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
</div>
<br style="clear:both" />
