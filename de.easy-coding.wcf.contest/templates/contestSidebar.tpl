<div class="contentBox">
	<div class="border"> 
		<div class="containerHead">
			<h3>{lang}wcf.contest.sidebar.addcontest.title{/lang}</h3>
		</div>
		<div style="padding:10px">
		{lang}wcf.contest.sidebar.addcontest.description{/lang}
		
		<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
			<ul>
				{if $isRegistered}
				<li><a href="index.php?form=ContestAdd{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.addcontest.submit{/lang}</span></a></li>
				{else}<li><a href="index.php?page=Register{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.addcontest.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>
{*
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
				<li><a href="index.php?form=ContestParticipantAdd{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomeparticipant.submit{/lang}</span></a></li>
				{else}<li><a href="index.php?page=Register{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomeparticipant.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>

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
				<li><a href="index.php?form=ContestSponsorAdd{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomesponsor.submit{/lang}</span></a></li>
				{else}<li><a href="index.php?page=Register{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomesponsor.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>

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
				<li><a href="index.php?form=ContestJuryAdd{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomejury.submit{/lang}</span></a></li>
				{else}<li><a href="index.php?page=Register{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomejury.submit{/lang}</span></a></li>
				{/if}
			</ul>
		</div>
		
		</div>
	</div>
</div>
*}
{if $availableClasses|count > 0}
	<div class="contentBox">
		<div class="border"> 
			<div class="containerHead"> 
				<h3>{lang}wcf.contest.classes{/lang}</h3> 
			</div> 
			 
			<ul class="dataList">
				{foreach from=$availableClasses item=class}
					<li class="{cycle values='container-1,container-2'}">
						<a href="index.php?page=ContestOverview&amp;classID={@$class->classID}{@SID_ARG_2ND}"><span>{lang}{$class}{/lang}</span></a>
					</li>
				{/foreach}
			</ul>
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
						<a href="index.php?page=ContestOverview&amp;juryID={@$jury->juryID}{@SID_ARG_2ND}"><span>{lang}{$jury->getOwner()->getName()}{/lang}</span></a>
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
						<a href="index.php?page=ContestOverview&amp;sponsorID={@$sponsor->sponsorID}{@SID_ARG_2ND}"><span>{lang}{$sponsor->getOwner()->getName()}{/lang}</span></a>
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
						<a href="index.php?page=ContestOverview&amp;priceID={@$price->priceID}{@SID_ARG_2ND}"><span>{lang}{$price}{/lang}</span></a>
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
						<a href="index.php?page=ContestOverview&amp;participantID={@$participant->participantID}{@SID_ARG_2ND}"><span>{lang}{$participant->getOwner()->getName()}{/lang}</span></a>
					</li>
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

{if $lastestEntries|count > 0}
	<div class="contentBox">
		<div class="border">
			<div class="containerHead">
				<h3>{lang}wcf.contest.lastestEntries{/lang}</h3>
			</div>
			
			<ul class="dataList">
				{foreach from=$lastestEntries item=entry}
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

{if $lastestSolutions|count > 0}
	<div class="contentBox">
		<div class="border">
			<div class="containerHead">
				<h3>{lang}wcf.contest.lastestSolutions{/lang}</h3>
			</div>
			
			<ul class="dataList">
				{foreach from=$lastestSolutions item=solution}
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
							<h4><a href="index.php?page=Contest&amp;contestID={@$solution->contestID}&amp;solutionID={@$solution->solutionID}{@SID_ARG_2ND}#solution{@$solution->solutionID}">{$solution->getExcerpt()}</a></h4>
							<p class="light smallFont">{lang}wcf.contest.solution.by{/lang} <a href="{$solution->getOwner()->getLink()}{@SID_ARG_2ND}">{$solution->getOwner()->getName()}</a> ({@$solution->time|shorttime})</p>
						</div>
					</li>
				{/foreach}
			</ul>
		</div>
	</div>
{/if}
