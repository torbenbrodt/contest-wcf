{foreach from=$contestPromotionNotifications key=contestID item=row}
	<div class="info deletable" id="contestPromotion{$contestID}">
		{@$row.message}
		<a rel="nofollow" style="text-decoration:none" href="index.php?action=ContestPromotion&amp;contestID={$contestID}&amp;contestAction={@$row.action}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}"><img src="{icon}checkS.png{/icon}" alt="" /> {lang}wcf.contest.promotion.yes{/lang}</a>,
		<a rel="nofollow" class="deleteButton" style="text-decoration:none" href="index.php?action=ContestPromotion&amp;contestID={$contestID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}"><img src="{icon}deleteS.png{/icon}" alt="" longdesc="" /> {lang}wcf.contest.promotion.no{/lang}</a>
	</div>
{/foreach}
