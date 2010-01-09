<div class="contentBox">
	<h3 class="subHeadline"><a href="index.php?page=Contest&amp;userID={@$user->userID}{@SID_ARG_2ND}">{lang}wcf.user.contest{/lang}</a> <span>({#$user->blogEntries})</span></h3>
	
	<ul class="dataList">
		{foreach from=$entries item=entry}
			<li class="{cycle values='container-1,container-2'}">
				<div class="containerIcon">
					<img src="{icon}contestM.png{/icon}" alt="" />
				</div>
				<div class="containerContent">
					<h4><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
					<p class="firstPost smallFont light">{@$entry->time|time}</p>
				</div>
			</li>
		{/foreach}
	</ul>
	
	<div class="buttonBar">
		<div class="smallButtons">
			<ul>
				<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				<li><a href="index.php?page=Contest&amp;userID={@$user->userID}{@SID_ARG_2ND}" title="{lang}wcf.user.contest.allEntries{/lang}"><img src="{icon}messageS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest.allEntries{/lang}</span></a></li>
			</ul>
		</div>
	</div>
</div>