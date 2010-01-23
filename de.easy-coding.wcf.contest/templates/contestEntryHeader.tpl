<ul class="breadCrumbs">
	<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	<li><a href="index.php?page=ContestOverview{@SID_ARG_2ND}"><img src="{icon}contestS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest{/lang}</span></a> &raquo;</li>
</ul>

<div class="mainHeadline">
	<img src="{icon}contestL.png{/icon}" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.user.contest{/lang}</h2>
	</div>
</div>

{if $contestmenu->getMenuItems('')|count > 1}
	<div id="profileContent" class="tabMenu">
		<ul>
			{foreach from=$contestmenu->getMenuItems('') item=item}
				<li{if $item.menuItem|in_array:$contestmenu->getActiveMenuItems()} class="activeTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
			{/foreach}
		</ul>
	</div>

	<div class="subTabMenu">
		<div class="containerHead">
			{assign var=activeMenuItem value=$contestmenu->getActiveMenuItem()}
			{if $activeMenuItem && $contestmenu->getMenuItems($activeMenuItem)|count}
				<ul>
					{foreach from=$contestmenu->getMenuItems($activeMenuItem) item=item}
						<li{if $item.menuItem|in_array:$contestmenu->getActiveMenuItems()} class="activeSubTabMenu"{/if}><a href="{$item.menuItemLink}">{if $item.menuItemIcon}<img src="{$item.menuItemIcon}" alt="" /> {/if}<span>{lang}{@$item.menuItem}{/lang}</span></a></li>
					{/foreach}
				</ul>
			{else}
				<div> </div>
			{/if}
		</div>
	</div>
{/if}
