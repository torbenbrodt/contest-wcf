<div class="message content">
	<div class="messageInner container-{cycle name='results' values='1,2'}">
		<div class="messageHeader">
			<div class="containerIcon">
				<a href="index.php?page=ContestEntry&amp;contestID={@$item.message->contestID}&amp;highlight={$query|urlencode}{@SID_ARG_2ND}"><img src="{icon}contestM.png{/icon}" alt="" /></a>
			</div>
			<div class="containerContent">
				<p class="light smallFont">{@$item.message->time|time}</p>
				<p class="light smallFont">{lang}wcf.user.contest.entry.by{/lang} <a href="index.php?page=User&amp;userID={@$item.message->userID}{@SID_ARG_2ND}">{$item.message->username}</a></p>
			</div>
		</div>
		
		<h3><a href="index.php?page=ContestEntry&amp;contestID={@$item.message->contestID}&amp;highlight={$query|urlencode}{@SID_ARG_2ND}">{$item.message->subject}</a></h3>
		
		<div class="messageBody">
			{@$item.message->getFormattedMessage()}
		</div>
		
		<div class="messageFooter">
			<div class="smallButtons">
				<ul>
					<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				</ul>
			</div>
		</div>
		<hr />
	</div>
</div>
