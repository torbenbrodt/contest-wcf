{include file="documentHeader"}
<head>
	<title>{$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/StringUtil.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestPermissionList.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
	<script type="text/javascript">
	var participants = new Array();

	onloadEvents.push(function() {
		// participants
		var list1 = new ContestPermissionList('participant', participants, 'index.php?page=ContestParticipantObjects');
	
		// add onsubmit event
		document.getElementById('ParticipantInviteForm').onsubmit = function() {
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
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						
						<div class="contentBox">
							<h4 class="subHeadline">{lang}wcf.contest.participants{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestParticipant&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$participants item=participantObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="participant{@$participantObj->participantID}"></a>
										<div class="containerIcon">
											{if $participantObj->getOwner()->getAvatar()}
												{assign var=x value=$participantObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
												{if $participantObj->userID}<a href="index.php?page=User&amp;userID={@$participantObj->userID}{@SID_ARG_2ND}">{/if}{@$participantObj->getOwner()->getAvatar()}{if $participantObj->userID}</a>{/if}
											{else}
												{if $participantObj->userID}<a href="index.php?page=User&amp;userID={@$participantObj->userID}{@SID_ARG_2ND}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $participantObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $participantID == $participantObj->participantID}
												
												<p>{@$participantObj}</p>
												<form method="post" action="index.php?page=ContestParticipant&amp;contestID={@$contestID}&amp;participantID={@$participantObj->participantID}&amp;action=edit">
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
													{if $participantObj->isEditable()}<a href="index.php?page=ContestParticipant&amp;contestID={@$contestID}&amp;participantID={@$participantObj->participantID}&amp;action=edit{@SID_ARG_2ND}#participant{@$participantObj->participantID}" title="{lang}wcf.contest.participant.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $participantObj->isDeletable()}<a href="index.php?action=ContestParticipantDelete&amp;participantID={@$participantObj->participantID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.participant.delete.sure{/lang}')" title="{lang}wcf.contest.participant.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestParticipant&amp;contestID={@$contestID}&amp;participantID={@$participantObj->participantID}{@SID_ARG_2ND}#participant{@$participantObj->participantID}" title="{lang}wcf.contest.participant.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p><a href="{$participantObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$participantObj->getOwner()->getName()}</a> <span>*{$participantObj->state}*</span></p>
												
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
							<h4 class="subHeadline">{lang}wcf.contest.participants{/lang}</h4>
							<div class="contentBox">
								<form method="post" id="ParticipantInviteForm" action="index.php?page=ContestParticipant&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.participant{/lang}</legend>
										<p>{lang}wcf.contest.participant.owner.description{/lang}</p>
	
										<div class="formElement">
											<div class="formFieldLabel" id="participantTitle">
												{lang}wcf.contest.participant.add{/lang}
											</div>
											<div class="formField"><div id="participant" class="accessRights" style="height:80px"></div></div>
										</div>
										<div class="formElement">
											<div class="formField">	
												<input id="participantAddInput" type="text" name="" value="" class="inputText accessRightsInput" />
												<script type="text/javascript">
													//<![CDATA[
													suggestion.setSource('index.php?page=ContestParticipantSuggest{@SID_ARG_2ND_NOT_ENCODED}');
													suggestion.enableIcon(true);
													suggestion.init('participantAddInput');
													//]]>
												</script>
												<input id="participantAddButton" type="button" value="{lang}wcf.contest.participant.add{/lang}" />
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
						
						{if $entry->isParticipantable() && $action != 'edit'}
							<h4 class="subHeadline">{lang}wcf.contest.participants{/lang}</h4>
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestParticipant&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.contest.participant{/lang}</legend>
										<p>{lang}wcf.contest.participant.description{/lang}</p>
	
										<div class="formElement{if $errorField == 'participant'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.contest.participant{/lang}</label>
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
