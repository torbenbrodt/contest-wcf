{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.{@$action}{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
		<li><a href="index.php?page=ContestOverview{@SID_ARG_2ND}"><img src="{icon}contestS.png{/icon}" alt="" /> <span>{lang}wcf.contest{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}contestL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.contest.{@$action}{/lang}</h2>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
				
	<div class="largeButtons">
		<ul>
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
		</ul>
	</div>
	
	<form method="post" action="index.php?form=ContestJury{@$action|ucfirst}{if $action == 'add'}{elseif $action == 'edit'}&amp;entryID={@$entryID}{/if}">
		<div class="border content">
			<div class="container-1 blog">
				<h3 class="subHeadline">{lang}wcf.contest.{@$action}{/lang}</h3>
				
				{if $additionalLargeButtons|isset && $additionalLargeButtons|count}
				<div class="contentHeader">
					<div class="largeButtons">
						<ul>
							{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
						</ul>
					</div>
				</div>
				{/if}
				
				<fieldset>
					<legend>{lang}wcf.contest.information{/lang}</legend>
					
					<div class="formElement{if $errorField == 'subject'} formError{/if}">
						<div class="formFieldLabel">
							<label for="subject">{lang}wcf.contest.subject{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="subject" id="subject" value="{$subject}" tabindex="{counter name='tabindex'}" />
							{if $errorField == 'subject'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
					</div>
					
					{if $additionalInformationFields|isset}{@$additionalInformationFields}{/if}
				</fieldset>

				<fieldset>
					<legend>{lang}wcf.contest.owner{/lang}</legend>
					<p>{lang}wcf.contest.owner.description{/lang}</p>
	
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
					</div>
				</fieldset>
				
				{if $additionalFields1|isset}{@$additionalFields1}{/if}
			</div>
		</div>
		
		<div class="formSubmit">
			<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" tabindex="{counter name='tabindex'}" />
			<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" tabindex="{counter name='tabindex'}" />
			{@SID_INPUT_TAG}
			<input type="hidden" name="idHash" value="{$idHash}" />
		</div>
	</form>
</div>

{include file='footer' sandbox=false}
</body>
</html>
