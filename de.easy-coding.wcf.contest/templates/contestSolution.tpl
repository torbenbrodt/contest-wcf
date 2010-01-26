{include file="documentHeader"}
<head>
	<title>{$entry->subject} - {lang}wcf.header.menu.user.contest{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	{include file='imageViewer'}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH}; 
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	{include file='multiQuote'}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}wcf.user.contest.search.query{/lang}'}
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
							<h4 class="subHeadline">{lang}wcf.user.contest.entry.solutions{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestSolution&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<ul class="dataList messages">
								{assign var='messageNumber' value=$items-$startIndex+1}
								{foreach from=$solutions item=solutionObj}
									<li class="{cycle values='container-1,container-2'}">
										<a id="solution{@$solutionObj->solutionID}"></a>
										<div class="containerIcon">
											{if $solutionObj->getOwner()->getAvatar()}
												{assign var=x value=$solutionObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
												{if $solutionObj->userID}<a href="index.php?page=User&amp;userID={@$solutionObj->userID}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}">{/if}{@$solutionObj->getOwner()->getAvatar()}{if $solutionObj->userID}</a>{/if}
											{else}
												{if $solutionObj->userID}<a href="index.php?page=User&amp;userID={@$solutionObj->userID}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $solutionObj->userID}</a>{/if}
											{/if}
										</div>
										<div class="containerContent">
											{if $action == 'edit' && $solutionID == $solutionObj->solutionID}
												<form method="post" action="index.php?page=ContestSolution&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;action=edit">
													<div{if $errorField == 'message'} class="formError"{/if}>
														<textarea name="message" id="message" rows="10" cols="40">{$solution}</textarea>
														{if $errorField == 'message'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.solution.error.tooLong{/lang}{/if}
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
													{if $solutionObj->isEditable()}<a href="index.php?page=ContestSolution&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;action=edit{@SID_ARG_2ND}#solution{@$solutionObj->solutionID}" title="{lang}wcf.user.contest.entry.solution.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
													{if $solutionObj->isDeletable()}<a href="index.php?action=ContestSolutionDelete&amp;solutionID={@$solutionObj->solutionID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.user.contest.entry.solution.delete.sure{/lang}')" title="{lang}wcf.user.contest.entry.solution.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
													<a href="index.php?page=ContestSolution&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}{@SID_ARG_2ND}#solution{@$solutionObj->solutionID}" title="{lang}wcf.user.contest.entry.solution.permalink{/lang}">#{#$messageNumber}</a>
												</div>
												<p class="firstPost smallFont light">{lang}wcf.user.contest.entry.solution.by{/lang} <a href="{@$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$solutionObj->getOwner()->getName()}</a> ({@$solutionObj->time|time})</p>
												<p>{@$solutionObj->getFormattedMessage()}</p>
												
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
						
						{if $entry->isSolutionable()}{assign var=solutionUsername value=$username}{/if}
						{if $entry->isSolutionable() && $action != 'edit'}
							{assign var=username value=$solutionUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestSolution&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<div class="formElement{if $errorField == 'owner'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.user.contest.entry.owner{/lang}</label>
											</div>
											<div class="formField">
												<fieldset>
													<legend>{lang}wcf.user.contest.entry.owner{/lang}</legend>
														<label><input type="radio" name="ownerID" value="0" {if 0 == $ownerID}checked="checked" {/if}/> {lang}wcf.user.contest.entry.owner.self{/lang}</label>
													{foreach from=$availableGroups item=availableGroup}
														<label><input type="radio" name="ownerID" value="{@$availableGroup->groupID}" {if $availableGroup->groupID == $ownerID}checked="checked" {/if}/> {lang}{$availableGroup->groupName}{/lang}</label>
													{/foreach}
												</fieldset>
											</div>
											<div class="formFieldDesc">
												{lang}wcf.user.contest.entry.owner.description{/lang}
											</div>
										</div>
										
										<div class="formElement{if $errorField == 'message' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="message">{lang}wcf.user.contest.entry.solution{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="message" id="message" rows="10" cols="40">{$message}</textarea>
												{if $errorField == 'message' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.solution.error.tooLong{/lang}{/if}
													</p>
												{/if}
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
