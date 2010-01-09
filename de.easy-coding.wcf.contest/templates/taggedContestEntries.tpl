<div class="contentBox">
	<h3 class="subHeadline">{lang}wcf.tagging.taggable.de.easy-coding.wcf.contest.entry{/lang} <span>({#$items})</span></h3>

	<ul class="dataList">
		{foreach from=$taggedObjects item=entry}
			<li class="{cycle values='container-1,container-2'}">
				<div class="containerIcon">
					<img src="{icon}contestM.png{/icon}" alt="" style="width: 24px;" />
				</div>
				
				<div class="containerContent">
					<h4><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}#profileContent">{$entry->subject}</a></h4>
					<p class="firstPost smallFont light">{lang}wcf.user.contest.entry.by{/lang} <a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}">{$entry->username}</a> ({@$entry->time|time})</p>
				</div>
			</li>
		{/foreach}
	</ul>
</div>

<div class="buttonBar">
	<div class="smallButtons">
		<ul>
			<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
		</ul>
	</div>
</div>