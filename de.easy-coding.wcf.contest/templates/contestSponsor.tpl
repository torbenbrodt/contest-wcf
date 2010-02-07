{include file="documentHeader"}
<head>
	<title>{$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/StringUtil.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestPermissionList.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
	<script type="text/javascript">
	var sponsors = new Array();

	onloadEvents.push(function() {
		// sponsors
		var list1 = new ContestPermissionList('sponsor', sponsors, 'index.php?page=ContestSponsorObjects');
	
		// add onsubmit event
		document.getElementById('SponsorInviteForm').onsubmit = function() {
			if (suggestion.selectedIndex != -1) return false;
			if (list1.inputHasFocus) return false;
			list1.submit(this);
		};
	});
	</script>
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
		<div class="layout-2 blog">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						
						<div class="contentBox">
							<h4 class="subHeadline">{lang}wcf.contest.sponsors{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestSponsor&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$sponsors item=sponsorObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="sponsor{@$sponsorObj->sponsorID}"></a>
										<div class="containerIcon">
											{if $sponsorObj->getOwner()->getAvatar()}
												{assign var=x value=$sponsorObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
												{if $sponsorObj->userID}<a href="index.php?page=User&amp;userID={@$sponsorObj->userID}{@SID_ARG_2ND}">{/if}{@$sponsorObj->getOwner()->getAvatar()}{if $sponsorObj->userID}</a>{/if}
											{else}
												{if $sponsorObj->userID}<a href="index.php?page=User&amp;userID={@$sponsorObj->userID}{@SID_ARG_2ND}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $sponsorObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $sponsorID == $sponsorObj->sponsorID}
												
												<p>{@$sponsorObj}</p>
												<form method="post" action="index.php?page=ContestSponsor&amp;contestID={@$contestID}&amp;sponsorID={@$sponsorObj->sponsorID}&amp;action=edit">
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
													{if $sponsorObj->isEditable()}<a href="index.php?page=ContestSponsor&amp;contestID={@$contestID}&amp;sponsorID={@$sponsorObj->sponsorID}&amp;action=edit{@SID_ARG_2ND}#sponsor{@$sponsorObj->sponsorID}" title="{lang}wcf.contest.sponsor.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $sponsorObj->isDeletable()}<a href="index.php?action=ContestSponsorDelete&amp;sponsorID={@$sponsorObj->sponsorID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.sponsor.delete.sure{/lang}')" title="{lang}wcf.contest.sponsor.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestSponsor&amp;contestID={@$contestID}&amp;sponsorID={@$sponsorObj->sponsorID}{@SID_ARG_2ND}#sponsor{@$sponsorObj->sponsorID}" title="{lang}wcf.contest.sponsor.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p><a href="{$sponsorObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$sponsorObj->getOwner()->getName()}</a> <span>*{$sponsorObj->state}*</span></p>
												
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
						</div>
						
						{if $entry->isOwner() && $action != 'edit'}
							<h4 class="subHeadline">{lang}wcf.contest.sponsors{/lang}</h4>
							<div class="contentBox">
								<form method="post" id="SponsorInviteForm" action="index.php?page=ContestSponsor&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.sponsor{/lang}</legend>
										<p>{lang}wcf.contest.sponsor.owner.description{/lang}</p>
	
										<div class="formElement">
											<div class="formFieldLabel" id="sponsorTitle">
												{lang}wcf.contest.sponsor.add{/lang}
											</div>
											<div class="formField"><div id="sponsor" class="accessRights" style="height:80px"></div></div>
										</div>
										<div class="formElement">
											<div class="formField">	
												<input id="sponsorAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
												<script type="text/javascript">
													//<![CDATA[
													suggestion.setSource('index.php?page=ContestSponsorSuggest{@SID_ARG_2ND_NOT_ENCODED}');
													suggestion.enableIcon(true);
													suggestion.init('sponsorAddInput');
													//]]>
												</script>
												<input id="sponsorAddButton" type="button" value="{lang}wcf.contest.sponsor.add{/lang}" />
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
						
						{if $entry->isSponsorable() && $action != 'edit'}
							<h4 class="subHeadline">{lang}wcf.contest.sponsors{/lang}</h4>
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestSponsor&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.sponsor{/lang}</legend>
										<p>{lang}wcf.contest.sponsor.description{/lang}</p>
	
										<div class="formElement{if $errorField == 'sponsor'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.contest.sponsor{/lang}</label>
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
