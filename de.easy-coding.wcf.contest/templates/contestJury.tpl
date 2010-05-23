{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.jurys{/lang} - {$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/StringUtil.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestPermissionList.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestSuggestion.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestListRender.class.js"></script>
	<script type="text/javascript">
	var jurys = new Array();

	onloadEvents.push(function() {
		// jurys
		var list1 = new ContestPermissionList('jury', jurys, 'index.php?page=ContestJuryObjects');
		
		if(document.getElementById('juryAddInput')) {
			var suggestion = new ContestSuggestion();
			suggestion.setSource('index.php?page=ContestJurySuggest{@SID_ARG_2ND_NOT_ENCODED}');
			suggestion.enableIcon(true);
			suggestion.init('juryAddInput');

			// add onsubmit event
			document.getElementById('JuryInviteForm').onsubmit = function(suggestion) {
				return function() {
					if (suggestion.selectedIndex != -1) return false;
					if (list1.inputHasFocus) return false;
					list1.submit(this);
				};
			}(suggestion);
		}
		
		// contest list render
		var x = new ContestListRender($('dataListView'));
		x.change('thumbnailView');
	});
	</script>
	<link rel="alternate" type="application/rss+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=rss2" title="{lang}wcf.contest.feed{/lang} (RSS2)" />
	<link rel="alternate" type="application/atom+xml" href="index.php?page=ContestFeed&amp;contestID={$entry->contestID}&amp;format=atom" title="{lang}wcf.contest.feed{/lang} (Atom)" />
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.contest.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="types[]" value="contestEntry" />
{/capture}
{* --- end --- *}
{include file='header' sandbox=false}

<div id="main">
	{include file="contestEntryHeader"}
	
	<div class="border tabMenuContent">
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
							{if $userMessages|isset}{@$userMessages}{/if}
							{if $jurys|count > 0}
							<h4 class="subHeadline">{lang}wcf.contest.jurys{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestJury&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages" id="dataListView">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$jurys item=juryObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="jury{@$juryObj->juryID}"></a>
										<div class="containerIcon">
											{if $juryObj->getOwner()->getAvatar()}
												{assign var=x value=$juryObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
												{if $juryObj->userID}<a href="index.php?page=User&amp;userID={@$juryObj->userID}{@SID_ARG_2ND}">{/if}{@$juryObj->getOwner()->getAvatar()}{if $juryObj->userID}</a>{/if}
											{else}
												{if $juryObj->userID}<a href="index.php?page=User&amp;userID={@$juryObj->userID}{@SID_ARG_2ND}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $juryObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $juryID == $juryObj->juryID}
												
												<p>{@$juryObj}</p>
												<form method="post" action="index.php?page=ContestJury&amp;contestID={@$contestID}&amp;juryID={@$juryObj->juryID}&amp;action=edit">
													<div{if $errorField == 'state'} class="formError"{/if}>
														<select name="state" id="state">
														{htmloptions options=$states selected=$state}
														</select>
														{if $errorField == 'state'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															</p>
														{/if}
													</div>
													<div class="formSubmit">
														{@SID_INPUT_TAG}
														<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
														<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
													</div>
												</form>
											{else}
												<div class="buttons">
													{if $juryObj->isEditable()}<a href="index.php?page=ContestJury&amp;contestID={@$contestID}&amp;juryID={@$juryObj->juryID}&amp;action=edit{@SID_ARG_2ND}#jury{@$juryObj->juryID}" title="{lang}wcf.contest.jury.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $juryObj->isDeletable()}<a href="index.php?action=ContestJuryDelete&amp;juryID={@$juryObj->juryID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.jury.delete.sure{/lang}')" title="{lang}wcf.contest.jury.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestJury&amp;contestID={@$contestID}&amp;juryID={@$juryObj->juryID}{@SID_ARG_2ND}#jury{@$juryObj->juryID}" title="{lang}wcf.contest.jury.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p><a href="{$juryObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$juryObj->getOwner()->getName()}</a> <div style="float:right">{@$juryObj->getState()->renderButton()}</div></p>
												
											{/if}
										</div>
									</li>
									{assign var='messageNumber' value=$messageNumber-1}
								{/foreach}
							</ul>
							
							<div class="contentFooter">
								{@$pagesOutput}
							</div>
							
							<div class="buttonBar">
								<div class="smallButtons">
									<ul>
										<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
									</ul>
								</div>
							</div>
							{/if}
						</div>
						
						{if $isJury == false}
						<fieldset>
							<legend>{lang}wcf.contest.sidebar.becomejury.title{/lang}</legend>
							{lang}wcf.contest.sidebar.becomejury.description{/lang}
	
							<div class="largeButtons" style="width:175px;margin-top:10px; margin-left:10px">
								<ul>
									{if $isRegistered}
									<li><a href="index.php?page=ContestJury&amp;contestID={$contestID}{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomejury.submit{/lang}</span></a></li>
									{else}<li><a href="index.php?page=Register{@SID_ARG_2ND}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}wcf.contest.sidebar.becomejury.submit{/lang}</span></a></li>
									{/if}
								</ul>
							</div>
						</fieldset>
						{/if}
						
						{if $entry->isOwner() && $action != 'edit'}
							<h4 class="subHeadline">{lang}wcf.contest.jury.invite{/lang}</h4>
							<div class="contentBox">
								<form method="post" id="JuryInviteForm" action="index.php?page=ContestJury&amp;contestID={@$contestID}&amp;action=add">
								<input type="hidden" name="ContestJuryInviteForm" value="1" />
									<fieldset>
										<legend>{lang}wcf.contest.jury{/lang}</legend>
										<p>{lang}wcf.contest.jury.owner.description{/lang}</p>
	
										<div class="formElement">
											<div class="formFieldLabel" id="juryTitle">
												{lang}wcf.contest.jury.add{/lang}
											</div>
											<div class="formField"><div id="jury" class="accessRights" style="height:80px"></div></div>
										</div>
										<div class="formElement">
											<div class="formField">	
												<input id="juryAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
												<input id="juryAddButton" type="button" value="{lang}wcf.contest.jury.add{/lang}" />
											</div>
											<p class="formFieldDesc">{lang}Benutzer- oder Gruppennamen eingeben.{/lang}</p>
										</div>
									</fieldset>
									
									<div class="formSubmit">
										{@SID_INPUT_TAG}
										<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
										<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
									</div>
								</form>
							</div>
						{/if}
						
						{if $entry->isJuryable() && $action != 'edit' && $entry->isJury() == false}
							<h4 class="subHeadline">{lang}wcf.contest.jury.add{/lang}</h4>
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestJury&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.jury{/lang}</legend>
										<p>{lang}wcf.contest.jury.description{/lang}</p>
	
										<div class="formElement{if $errorField == 'jury'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.contest.jury{/lang}</label>
											</div>
											<div class="formField">
												<fieldset>
													<legend>{lang}wcf.contest.owner{/lang}</legend>
														<label><input type="radio" name="ownerID" value="0" {if 0 == $ownerID}checked="checked" {/if}/> {lang}wcf.contest.owner.self{/lang}</label>
													{foreach from=$availableGroups item=availableGroup}
														<label><input type="radio" name="ownerID" value="{@$availableGroup->groupID}" {if $availableGroup->groupID == $ownerID}checked="checked" {/if}/> {lang}{$availableGroup->groupName}{/lang}</label>
													{/foreach}
												</fieldset>
											</div>
										</div>
									</fieldset>
									
									<div class="formSubmit">
										{@SID_INPUT_TAG}
										<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
										<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
									</div>
								</form>
							</div>
						{/if}
						{if $additionalContent1|isset}{@$additionalContent1}{/if}
						<div class="contentFooter"> </div>
					</div>
				</div>
			
				<div class="container-3 column second contestSidebar">
					<div class="columnInner">
						{include file='contestSidebar'}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


{include file='footer' sandbox=false}
</body>
</html>
