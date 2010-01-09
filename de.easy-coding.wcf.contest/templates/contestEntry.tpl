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
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
		<li><a href="index.php?page=ContestOverview{@SID_ARG_2ND}"><img src="{icon}contestS.png{/icon}" alt="" /> <span>{lang}wcf.user.contest{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}contestL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.user.contest{/lang}</h2>
		</div>
	</div>

	{if $entry->isSolutionable()}{assign var=solutionUsername value=$username}{/if}
	
	<div class="border">
		<div class="layout-2 blog">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
							<h3 class="subHeadline">{lang}wcf.user.contest{/lang}</h3>
							
							<div class="contentHeader"> </div>
							<div class="blogInner">
								<script type="text/javascript">
									//<![CDATA[
									quoteData.set('contestEntry-{@$entry->contestID}', {
										objectID: {@$entry->contestID},
										objectType: 'contestEntry',
										quotes: {@$entry->isQuoted()}
									});
									//]]>
								</script>
								
								<div class="message">
									<div class="messageInner container-1">
										<a id="entry{@$entry->contestID}"></a>
										<div class="messageHeader">
											{if $userID}
											<div class="containerIcon">
												
												{if $user->getAvatar()}
													{assign var=x value=$user->getAvatar()->setMaxSize(24, 24)}
													<a href="index.php?page=User&amp;userID={@$user->userID}{@SID_ARG_2ND}" title="{lang username=$user->username}wcf.user.viewProfile{/lang}">{@$user->getAvatar()}</a>
												{else}
													<a href="index.php?page=User&amp;userID={@$user->userID}{@SID_ARG_2ND}" title="{lang username=$user->username}wcf.user.viewProfile{/lang}"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" /></a>
												{/if}
											</div>
											{/if}
											<div class="containerContent">
												<h4 style="margin: 0; padding: 0"><a href="index.php?page=ContestEntry&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}">{$entry->subject}</a></h4>
												<p class="light smallFont">{@$entry->time|time}</p>
											</div>
										</div>
										<div class="messageBody" id="contestEntryText{@$entry->contestID}">
											{@$entry->getFormattedMessage()}
										</div>
										
										{if $userID}
										{include file='attachmentsShow' messageID=$entry->contestID author=$user}
										{else}
										{include file='attachmentsShow' messageID=$entry->contestID}
										{/if}
										
										{if $tags|count > 0 || $classes|count > 0 || $jurys|count > 0 || $prices|count > 0}
											<div class="editNote smallFont light">
												{if $tags|count > 0}<p>{lang}wcf.user.contest.entry.tags{/lang}: {implode from=$tags item=tag}<a href="index.php?page=ContestOverview&amp;tagID={@$tag->getID()}{@SID_ARG_2ND}">{$tag->getName()}</a>{/implode}</p>{/if}
												{if $classes|count > 0}<p>{lang}wcf.user.contest.entry.classes{/lang}: {implode from=$classes item=class}<a href="index.php?page=ContestOverview&amp;classID={@$class->classID}{@SID_ARG_2ND}">{lang}{$class->title}{/lang}</a>{/implode}</p>{/if}
												{if $jurys|count > 0}<p>{lang}wcf.user.contest.entry.jurys{/lang}: {implode from=$jurys item=jury}<a href="index.php?page=ContestOverview&amp;juryID={@$jury->juryID}{@SID_ARG_2ND}">{lang}{$jury->title}{/lang}</a>{/implode}</p>{/if}

												{if $prices|count > 0}<p>{lang}wcf.user.contest.entry.prices{/lang}: {implode from=$prices item=price}<a href="index.php?page=ContestOverview&amp;priceID={@$price->priceID}{@SID_ARG_2ND}">{lang}{$price->title}{/lang}</a>{/implode}</p>{/if}
												
												{if $entry->location|empty == false}<p>{lang}wcf.user.contest.entry.prices{/lang}: {$entry->location}</p>{/if}
											</div>
										{/if}
										
										<div class="messageFooter">
											<div class="smallButtons">
												<ul id="contestEntryButtons{@$entry->contestID}">
													<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
													{if $entry->isEditable()}<li><a href="index.php?form=ContestEntryEdit&amp;contestID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.user.contest.entry.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}wcf.global.button.edit{/lang}</span></a></li>{/if}
													{if $entry->isDeletable()}<li><a href="index.php?action=ContestEntryDelete&amp;contestID={@$entry->contestID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.user.contest.entry.delete.sure{/lang}')" title="{lang}wcf.user.contest.entry.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}wcf.global.button.delete{/lang}</span></a></li>{/if}
													{if MODULE_USER_INFRACTION == 1 && $this->user->getPermission('admin.user.infraction.canWarnUser')}
														<li><a href="index.php?form=UserWarn&amp;userID={@$entry->userID}&amp;objectType=contestEntry&amp;objectID={@$entry->contestID}{@SID_ARG_2ND}" title="{lang}wcf.user.infraction.button.warn{/lang}"><img src="{icon}infractionWarningS.png{/icon}" alt="" /> <span>{lang}wcf.user.infraction.button.warn{/lang}</span></a></li>
													{/if}
													{if $additionalSmallButtons|isset}{@$additionalSmallButtons}{/if}
												</ul>
											</div>
										</div>
										<hr />
									</div>
								</div>
							</div>
						</div>
						
						{if $solutions|count > 0}
							<a id="solutions"></a>
							<div class="contentBox">
								<h4 class="subHeadline">{lang}wcf.user.contest.entry.solutions{/lang} <span>({#$items})</span></h4>
								
								<div class="contentHeader">
									{pages print=true assign=pagesOutput link="index.php?page=ContestEntry&contestID=$contestID&pageNo=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								</div>
								
								<ul class="dataList messages">
									{assign var='messageNumber' value=$items-$startIndex+1}
									{foreach from=$solutions item=solutionObj}
										<li class="{cycle values='container-1,container-2'}">
											<a id="solution{@$solutionObj->solutionID}"></a>
											<div class="containerIcon">
												{if $solutionObj->getUser()->getAvatar()}
													{assign var=x value=$solutionObj->getUser()->getAvatar()->setMaxSize(24, 24)}
													{if $solutionObj->userID}<a href="index.php?page=User&amp;userID={@$solutionObj->userID}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}">{/if}{@$solutionObj->getUser()->getAvatar()}{if $solutionObj->userID}</a>{/if}
												{else}
													{if $solutionObj->userID}<a href="index.php?page=User&amp;userID={@$solutionObj->userID}{@SID_ARG_2ND}" title="{lang username=$solutionObj->username}wcf.user.viewProfile{/lang}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $solutionObj->userID}</a>{/if}
												{/if}
											</div>
											<div class="containerContent">
												{if $action == 'edit' && $solutionID == $solutionObj->solutionID}
													<form method="post" action="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;action=edit">
														<div{if $errorField == 'solution'} class="formError"{/if}>
															<textarea name="solution" id="solution" rows="10" cols="40">{$solution}</textarea>
															{if $errorField == 'solution'}
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
														{if $solutionObj->isEditable()}<a href="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}&amp;action=edit{@SID_ARG_2ND}#solution{@$solutionObj->solutionID}" title="{lang}wcf.user.contest.entry.solution.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
														{if $solutionObj->isDeletable()}<a href="index.php?action=ContestSolutionDelete&amp;solutionID={@$solutionObj->solutionID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.user.contest.entry.solution.delete.sure{/lang}')" title="{lang}wcf.user.contest.entry.solution.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
														<a href="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;solutionID={@$solutionObj->solutionID}{@SID_ARG_2ND}#solution{@$solutionObj->solutionID}" title="{lang}wcf.user.contest.entry.solution.permalink{/lang}">#{#$messageNumber}</a>
													</div>
													<p class="firstPost smallFont light">{lang}wcf.user.contest.entry.solution.by{/lang} {if $solutionObj->userID}<a href="index.php?page=User&amp;userID={@$solutionObj->userID}{@SID_ARG_2ND}">{$solutionObj->username}</a>{else}{$solutionObj->username}{/if} ({@$solutionObj->time|time})</p>
													<p>{@$solutionObj->getFormattedSolution()}</p>
													
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
						{/if}
						
						{if $entry->isSolutionable() && $action != 'edit'}
							{assign var=username value=$solutionUsername}
							<div class="contentBox">
								<form method="post" action="index.php?page=ContestEntry&amp;contestID={@$contestID}&amp;action=add">
									<fieldset>
										<legend>{lang}wcf.user.contest.entry.solution.add{/lang}</legend>
										
										{if !$this->user->userID}
											<div class="formElement{if $errorField == 'username'} formError{/if}">
												<div class="formFieldLabel">
													<label for="username">{lang}wcf.user.username{/lang}</label>
												</div>
												<div class="formField">
													<input type="text" class="inputText" name="username" id="username" value="{$username}" />
													{if $errorField == 'username'}
														<p class="innerError">
															{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
															{if $errorType == 'notValid'}{lang}wcf.user.error.username.notValid{/lang}{/if}
															{if $errorType == 'notAvailable'}{lang}wcf.user.error.username.notUnique{/lang}{/if}
														</p>
													{/if}
												</div>
											</div>
										{/if}
										
										<div class="formElement{if $errorField == 'solution' && $action == 'add'} formError{/if}">
											<div class="formFieldLabel">
												<label for="solution">{lang}wcf.user.contest.entry.solution{/lang}</label>
											</div>
											<div class="formField">
												<textarea name="solution" id="solution" rows="10" cols="40">{$solution}</textarea>
												{if $errorField == 'solution' && $action == 'add'}
													<p class="innerError">
														{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
														{if $errorType == 'tooLong'}{lang}wcf.user.contest.entry.solution.error.tooLong{/lang}{/if}
													</p>
												{/if}
											</div>
										</div>
										
										{include file='captcha' enableFieldset=false}
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