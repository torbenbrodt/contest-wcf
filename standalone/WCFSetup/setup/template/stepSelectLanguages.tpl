{include file='header'}

<h2>{lang}wcf.global.languages{/lang}</h2>

<p>{lang}wcf.global.languages.description{/lang}</p>

{if $errorField}
<p class="error">
	{if $errorType == 'empty'}{lang}wcf.global.languages.error.empty{/lang}{/if}
	{if $errorType == 'notAvailable'}{lang}wcf.global.languages.error.notAvailable{/lang}{/if}
</p>
{/if}

<script type="text/javascript">
	//<![CDATA[
	var languages = new Object();
	var charsets = new Object();
	var charsetHelpList = new Object();
	
	function addLanguage(language, charset) {
		// add language
		if (!languages[language]) {
			languages[language] = new Array();
		}
		languages[language][languages[language].length] = charset;
		
		// add charset
		if (!charsets[charset]) {
			charsets[charset] = new Array();
			charsetHelpList[charset] = 0;
		}
		charsets[charset][charsets[charset].length] = language;
	}
	
	function selectCharset(charset) {
		for (var language in languages) {
			var found = false;
			
			for (var i = 0; i < languages[language].length; i++) {
				if (languages[language][i] == charset) found = true;
			}
			
			var languageCheckBox = document.getElementById('language-'+language);
			var languageLabel = document.getElementById('languageLabel-'+language);
			
			if (languageCheckBox) {
				// language supports this charset
				if (found) {
					languageCheckBox.disabled = false;
					languageLabel.className = '';
				}
				// language does not support this charset
				else {
					languageCheckBox.disabled = true;
					languageCheckBox.checked = false;
					languageLabel.className = 'disabled';
				}
			}
		}
	}
	
	function selectLanguage(language, checked) {
		for (var charset in charsets) {
			var found = false;
			
			for (var i = 0; i < charsets[charset].length; i++) {
				if (charsets[charset][i] == language) found = true;
			}
			
			var charsetCheckBox = document.getElementById('charset-'+charset);
			var charsetLabel = document.getElementById('charsetLabel-'+charset);
			
			// charset does not support this language
			if (!found) {
				if (checked) {
					charsetCheckBox.disabled = true;
					charsetCheckBox.checked = false;
					charsetLabel.className = 'disabled';
					charsetHelpList[charset]++;
				}
				else {
					if (--charsetHelpList[charset] == 0) {
						charsetCheckBox.disabled = false;
						charsetLabel.className = '';
					}
				}
			}
		}
	}
	//]]>
</script>

<form method="post" action="install.php">
	<fieldset>
		<legend>{lang}wcf.global.languages.charset{/lang}</legend>
		
		<div class="inner">
			<p>{lang}wcf.global.languages.charset.description{/lang}</p>
			
			<ul class="charsets">
				<li id="charsetLabel-UTF-8"{if $disableMultiByte} class="disabled"{/if}>
					<label for="charset-UTF-8"><b><input onclick="selectCharset(this.value);" type="radio" id="charset-UTF-8" name="charset" value="UTF-8" {if CHARSET == 'UTF-8'}checked="checked" {/if}{if $disableMultiByte} disabled="disabled"{/if}/> {lang}wcf.global.languages.charset.UTF-8{/lang} [UTF-8]</b></label>
				</li>
				
				{foreach from=$charsets key=charset item=data}
					<li id="charsetLabel-{@$charset}"{if $disableMultiByte && $charset != 'ISO-8859-1'} class="disabled"{/if}>
						<label for="charset-{@$charset}"><b><input onclick="selectCharset(this.value);" type="radio" id="charset-{@$charset}" name="charset" value="{@$charset}" {if CHARSET == $charset}checked="checked" {/if}{if $disableMultiByte && $data.multibyte || $disableMultiByte && $charset != 'ISO-8859-1'} disabled="disabled"{/if}/> {lang}wcf.global.languages.charset.{@$charset}{/lang} [{@$charset}]</b></label>
					</li>
				{/foreach}
			</ul>
			<br style="clear: both" />
		</div>
	</fieldset>
	
	<fieldset>
		<legend>{lang}wcf.global.languages.languages{/lang}</legend>
		
		<div class="inner">
			<ul class="languages">
				{foreach from=$languages key=language item=languageName}
					<li id="languageLabel-{@$language}"{if $language|in_array:$illegalLanguages} class="errorField"{/if}><label for="language-{@$language}"><input onclick="selectLanguage(this.value, this.checked);" type="checkbox" id="language-{@$language}" name="selectedLanguages[]" value="{@$language}" {if $language|in_array:$selectedLanguages}checked="checked" {/if}/> {@$languageName}</label>
						<script type="text/javascript">
							//<![CDATA[
							{if !$disableMultiByte}
								addLanguage('{@$language}', 'UTF-8');
							{/if}
							//]]>
						</script>
					</li>
				{/foreach}
			</ul>
			<br style="clear: both" />
		</div>
	</fieldset>
	
	<div class="nextButton">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.next{/lang}" />
		<input type="hidden" name="send" value="1" />
		<input type="hidden" name="step" value="{@$nextStep}" />
		<input type="hidden" name="tmpFilePrefix" value="{@$tmpFilePrefix}" />
		<input type="hidden" name="languageCode" value="{@$languageCode}" />
		<input type="hidden" name="wcfDir" value="{$wcfDir}" />
		<input type="hidden" name="dev" value="{@$developerMode}" />
	</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	window.onload = function() {
		{foreach from=$charsets key=charset item=data}
			{if $charset == 'ISO-8859-1' || !$disableMultiByte}
				{foreach from=$data.languages item=language}
					addLanguage('{@$language}', '{@$charset}');
				{/foreach}
			{/if}
		{/foreach}
		
		selectCharset('{@CHARSET}');
		{foreach from=$selectedLanguages item=$language}
			selectLanguage('{$language}', true);
		{/foreach}
	}
	//]]>
</script>

{include file='footer'}