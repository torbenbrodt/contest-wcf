{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contestRatingoption{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.contest.ratingoption.{@$action}{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.contest.ratingoption.{@$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ContestRatingoptionList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.contest.ratingoption{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/contestRatingoptionM.png" alt="" /> <span>{lang}wcf.acp.menu.link.contest.ratingoption{/lang}</span></a></li></ul>
	</div>
</div>

<form method="post" action="index.php?form=ContestRatingoption{@$action|ucfirst}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<div class="formElement{if $errorField == 'class'} formError{/if}" id="classDiv">
					<div class="formFieldLabel">
						<label for="class">{lang}wcf.acp.contest.class{/lang}</label>
					</div>
					<div class="formField">
						<select name="classID" id="class">
						<option value="0">{lang}wcf.contest.class.item.default{/lang}</option>
						{htmloptions options=$classes selected=$classID}
						</select>
						{if $errorField == 'class'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>{lang}wcf.acp.contest.ratingoption.data{/lang}</legend>
				
				{if $action == 'edit'}
					<div class="formElement" id="languageIDDiv">
						<div class="formFieldLabel">
							<label for="languageID">{lang}wcf.user.language{/lang}</label>
						</div>
						<div class="formField">
							<select name="languageID" id="languageID" onchange="location.href='index.php?form=ContestRatingoptionEdit&amp;optionID={@$optionID}&amp;languageID=' + this.value + '&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}'">
								{foreach from=$languages key=key item=language}
									<option value="{@$key}"{if $key == $languageID} selected="selected"{/if}>
										{lang}wcf.global.language.{@$language}{/lang}
									</option>
								{/foreach}						
							</select>
						</div>
					</div>
				{/if}
				
				<div class="formElement{if $errorField == 'topic'} formError{/if}" id="topicDiv">
					<div class="formFieldLabel">
						<label for="topic">{lang}wcf.acp.contest.ratingoption.title{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="topic" name="topic" value="{$topic}" />
						{if $errorField == 'topic'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>
							
				<div class="formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
					<div class="formFieldLabel">
						<label for="text">{lang}wcf.acp.contest.ratingoption.description{/lang}</label>
					</div>
					<div class="formField">
						<textarea name="text" id="text" rows="7" cols="40">{$text}</textarea>
						{if $errorField == 'text'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>
			</fieldset>
						
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>

	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
		{if $optionID|isset}<input type="hidden" name="optionID" value="{@$optionID}" />{/if}
		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer'}
