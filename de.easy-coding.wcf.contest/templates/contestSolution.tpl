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
							<h4 class="subHeadline">{lang}wcf.contest.solutions{/lang} <span>({#$items})</span></h4>
							
							<div class="contentHeader">
								{pages print=true assign=pagesOutput link="index.php?page=ContestSolution&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
							</div>
							
							<div class="blogInner">
							{assign var='messageNumber' value=$items-$startIndex+1}
							{foreach from=$solutions item=solutionObj}
								{assign var="contestID" value=$solutionObj->contestID}
								<div class="message">
									<div class="messageInner {cycle values='container-1,container-2'}">
										<a id="solutionObj{@$solutionObj->contestID}"></a>
										<div class="messageHeader">
											<p class="messageCount">
												<a href="index.php?page=ContestSolution&amp;contestID={@$solutionObj->contestID}{@SID_ARG_2ND}" title="{lang}wcf.contest.permalink{/lang}" class="messageNumber">{#$messageNumber}</a>
											</p>
											<div class="containerIcon">
												{if $solutionObj->getOwner()->getAvatar()}
													{assign var=x value=$solutionObj->getOwner()->getAvatar()->setMaxSize(24, 24)}
													<a href="{@$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}">{@$solutionObj->getOwner()->getAvatar()}</a>
												{else}
													<a href="{@$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
												{/if}
											</div>
											<div class="containerContent">
												<div style="float:right">*{$solutionObj->state}*</div>
												<p class="light smallFont">{lang}wcf.contest.by{/lang} <a href="{$solutionObj->getOwner()->getLink()}{@SID_ARG_2ND}">{$solutionObj->getOwner()->getName()}</a> ({@$solutionObj->time|time})</p>
											</div>
										</div>
										<div class="messageBody">
											{@$solutionObj->getExcerpt()}
										</div>
								
										<div class="messageFooter">
											<div class="smallButtons">
												<ul>
													<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
													{if $solutionObj->isEditable()}<li><a href="index.php?page=ContestSolution&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;action=edit{@SID_ARG_2ND}#solution{@$solutionObj->solutionID}" title="{lang}wcf.contest.solution.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.edit{/lang}</span></a></li>{/if}
													{if $solutionObj->isDeletable()}<li><a href="index.php?action=ContestSolutionDelete&amp;solutionID={@$solutionObj->solutionID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.contest.solution.delete.sure{/lang}')" title="{lang}wcf.contest.solution.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.contest.solution.delete{/lang}</span></a></li>{/if}
												</ul>
											</div>
										</div>
										<hr />
									</div>
								</div>
								{assign var='messageNumber' value=$messageNumber-1}
							{/foreach}
						</div>
							
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
						
						{if $entry->isSolutionable() && $action != 'edit'}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestSolution&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<div class="formElement{if $errorField == 'owner'} formError{/if}">
											<div class="formFieldLabel">
												<label>{lang}wcf.contest.owner{/lang}</label>
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
											<div class="formFieldDesc">
												{lang}wcf.contest.solution.description{/lang}
											</div>
										</div>
										
										<div class="formElement{if $errorField == 'message' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="message">{lang}wcf.contest.solution{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="message" id="message" rows="10" cols="40">{$message}</textarea>
												{if $errorField == 'message' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.contest.solution.error.tooLong{/lang}{/if}
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
