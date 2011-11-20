{if $additionalBoxes1|isset}{@$additionalBoxes1}{/if}
{if $canAddContest}
<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}wcf.contest.sidebar.addcontest.title{/lang}</h3>
		</div>
		<div style="padding:10px">
			{lang}wcf.contest.sidebar.addcontest.description{/lang}
		
			<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
				<ul>
					<li style="float:none"><a href="index.php?form=ContestAdd{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.addcontest.submit{/lang}</span></a></li>
				</ul>
			</div>
		
		</div>
	</div>
</div>
{/if}
{if $additionalBoxes2|isset}{@$additionalBoxes2}{/if}

{if $todos|isset && $todos|count > 0}
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.todo{/lang}</h3> 
			</div> 
			 
			<ul class="dataList">
				{foreach from=$todos item=todo}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							{if $todo->getOwner()->getAvatar()}
								{assign var=x value=$todo->getOwner()->getAvatar()->setMaxSize(24, 24)}
								{@$todo->getOwner()->getAvatar()}
							{else}
								<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
							{/if}
						</div>
						<div class="containerContent">
							<h4>{$todo->getMessage()}</h4>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $additionalBoxes3|isset}{@$additionalBoxes3}{/if}

{if $advertiseParticipant}
<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}wcf.contest.sidebar.becomeparticipant.title{/lang}</h3>
		</div>
		<div style="padding:10px">
		{lang}wcf.contest.sidebar.becomeparticipant.description{/lang}
		
		<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
			<ul>
				{if $isRegistered}
				<li style="float:none"><a href="index.php?page=ContestParticipant&amp;contestID={$contestID}&amp;doParticipate{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomeparticipant.submit{/lang}</span></a></li>
				{else}<li style="float:none"><a href="index.php?form=UserLogin{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomeparticipant.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>
{/if}

{if $advertiseSponsor}
<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}wcf.contest.sidebar.becomesponsor.title{/lang}</h3>
		</div>
		<div style="padding:10px">
		{lang}wcf.contest.sidebar.becomesponsor.description{/lang}
		
		<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
			<ul>
				{if $isRegistered}
				<li style="float:none"><a href="index.php?page=ContestSponsor&amp;contestID={$contestID}{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomesponsor.submit{/lang}</span></a></li>
				{else}<li style="float:none"><a href="index.php?form=UserLogin{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomesponsor.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>
{/if}

{if $advertiseJury}
<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}wcf.contest.sidebar.becomejury.title{/lang}</h3>
		</div>
		<div style="padding:10px">
		{lang}wcf.contest.sidebar.becomejury.description{/lang}
		
		<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
			<ul>
				{if $isRegistered}
				<li style="float:none"><a href="index.php?page=ContestJury&amp;contestID={$contestID}{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomejury.submit{/lang}</span></a></li>
				{else}<li style="float:none"><a href="index.php?form=UserLogin{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomejury.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>
{/if}

{if $availableJurys|count > 0}
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.jurys{/lang}</h3> 
			</div> 
			 
			<ul class="dataList">
				{foreach from=$availableJurys item=jury}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<a href="{$jury->getOwner()->getLink()}{@SID_ARG_2ND}">
								{if $jury->getOwner()->getAvatar()}
									{assign var=x value=$jury->getOwner()->getAvatar()->setMaxSize(24, 24)}
									{@$jury->getOwner()->getAvatar()}
								{else}
									<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
								{/if}
							</a>
						</div>
						<div class="containerContent">
							<h4><a href="{$jury->getOwner()->getLink()}{@SID_ARG_2ND}">{$jury->getOwner()->getName()}</a></h4>
							<p class="light smallFont">({@$jury->time|shorttime})</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $availableSponsors|count > 0}
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.sponsors{/lang}</h3> 
			</div> 
			 
			<ul class="dataList">
				{foreach from=$availableSponsors item=sponsor}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<a href="{$sponsor->getOwner()->getLink()}{@SID_ARG_2ND}">
								{if $sponsor->getOwner()->getAvatar()}
									{assign var=x value=$sponsor->getOwner()->getAvatar()->setMaxSize(24, 24)}
									{@$sponsor->getOwner()->getAvatar()}
								{else}
									<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
								{/if}
							</a>
						</div>
						<div class="containerContent">
							<h4><a href="{$sponsor->getOwner()->getLink()}{@SID_ARG_2ND}">{$sponsor->getOwner()->getName()}</a></h4>
							<p class="light smallFont">({@$sponsor->time|shorttime})</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $availableParticipants|count > 0}
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.participants{/lang}</h3> 
			</div> 
			 
			<ul class="dataList">
				{foreach from=$availableParticipants item=participant}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<a href="{$participant->getOwner()->getLink()}{@SID_ARG_2ND}">
								{if $participant->getOwner()->getAvatar()}
									{assign var=x value=$participant->getOwner()->getAvatar()->setMaxSize(24, 24)}
									{@$participant->getOwner()->getAvatar()}
								{else}
									<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
								{/if}
							</a>
						</div>
						<div class="containerContent">
							<h4><a href="{$participant->getOwner()->getLink()}{@SID_ARG_2ND}">{$participant->getOwner()->getName()}</a></h4>
							<p class="light smallFont">({@$participant->time|shorttime})</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $availablePrices|count > 0}
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.prices{/lang}</h3> 
			</div> 
			 
			<ul class="dataList">
				{foreach from=$availablePrices item=price}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<a href="{$price->getOwner()->getLink()}{@SID_ARG_2ND}">
								<img src="{@RELATIVE_WCF_DIR}icon/contestPriceM.png" alt="" />
							</a>
						</div>
						<div class="containerContent">
							<h4><a href="index.php?page=ContestPrice&amp;contestID={@$price->contestID}#priceObj{@$price->priceID}{@SID_ARG_2ND}"><span>{lang}{$price}{/lang}</span></a></h4>
							<p class="light smallFont">{lang}wcf.contest.price.by{/lang} <a href="{$price->getOwner()->getLink()}{@SID_ARG_2ND}">{$price->getOwner()->getName()}</a> ({@$price->time|shorttime})</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $availableClasses|count > 0}
	<style type="text/css">
	.contestClassTree ol li {
		list-style-type:none;
	}
	.contestClassTree ol {
		padding:0px 4px;
	}
	</style>
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.classes{/lang}</h3> 
			</div>
			<ul class="dataList contestClassTree">
				{foreach from=$availableClasses item=child}
					{assign var="contestClass" value=$child.contestClass}

					<li {if $child.depth == 1}class="{cycle values='container-1,container-2'}"{/if}>
						{if $child.depth == 1}
						<div class="containerIcon">
							<a href="index.php?page=ContestOverview&amp;classID={@$contestClass->classID}{@SID_ARG_2ND}">
								<img src="{icon}contestM.png{/icon}" alt="" />
							</a>
						</div>
						{/if}
						<div class="containerContent">
							<h4><a href="index.php?page=ContestOverview&amp;classID={@$contestClass->classID}{@SID_ARG_2ND}"><span>{lang}wcf.contest.class.item.{$contestClass->classID}{/lang}</span></a> ({$contestClass->contests|intval})</h4>
						</div>
						
					{if $child.hasChildren}<ol>{else}</li>{/if}
					{if $child.openParents > 0}{@"</ol></li>"|str_repeat:$child.openParents}{/if}
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $availableTags|count > 0}
	<div class="contentBox">
		<div class="border">
			<div class="containerHead">
				<h3>{lang}wcf.tagging.tags.used{/lang}</h3>
			</div>
			<div class="container-1">
				{include file="tagCloud" tags=$availableTags}
			</div>
		</div>
	</div>
{/if}

{if $latestEntries|count > 0}
	<div class="contentBox">
		<div class="border">
			<div class="containerHead">
				<h3>{lang}wcf.contest.latestEntries{/lang}</h3>
			</div>
			
			<ul class="dataList">
				{foreach from=$latestEntries item=entry}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<a href="index.php?page=Contest&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}"><img src="{icon}contestM.png{/icon}" alt="" /></a>
						</div>
						<div class="containerContent">
							<h4><a href="index.php?page=Contest&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
							<p class="light smallFont">{@$entry->time|time}</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $latestSolutions|count > 0}
	<div class="contentBox">
		<div class="border">
			<div class="containerHead">
				<h3>{lang}wcf.contest.latestSolutions{/lang}</h3>
			</div>
			
			<ul class="dataList">
				{foreach from=$latestSolutions item=solution}
					<li class="{cycle values='container-1,container-2'}">
						<div class="containerIcon">
							<a href="index.php?page=Contest&amp;contestID={@$solution->contestID}&amp;solutionID={@$solution->solutionID}{@SID_ARG_2ND}#solution{@$solution->solutionID}">
								{if $solution->getOwner()->getAvatar()}
									{assign var=x value=$solution->getOwner()->getAvatar()->setMaxSize(24, 24)}
									{@$solution->getOwner()->getAvatar()}
								{else}
									<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />
								{/if}
							</a>
						</div>
						<div class="containerContent">
							<h4><a href="index.php?page=ContestSolutionEntry&amp;contestID={@$solution->contestID}&amp;solutionID={@$solution->solutionID}{@SID_ARG_2ND}#solution{@$solution->solutionID}">{$solution->getExcerpt()}</a></h4>
							<p class="light smallFont">{lang}wcf.contest.solution.by{/lang} <a href="{$solution->getOwner()->getLink()}{@SID_ARG_2ND}">{$solution->getOwner()->getName()}</a> ({@$solution->time|shorttime})</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}
