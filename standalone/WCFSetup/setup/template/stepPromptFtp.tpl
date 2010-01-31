{include file='header'}

<h2>{lang}wcf.global.ftp{/lang}</h2>
	
<p>{lang}wcf.global.ftp.description{/lang}</p>

{if $error}
<p class="error">{lang}wcf.global.ftp.error{/lang}</p>
{/if}

<form method="post" action="install.php">
	<fieldset>
		<legend>{lang}wcf.global.ftp.accessData{/lang}</legend>
		
		<div class="inner">
			<div>
				<label for="ftpHost">{lang}wcf.global.ftp.host{/lang}</label>
				<input type="text" class="inputText" id="ftpHost" name="ftpHost" value="{$ftpHost}" style="width: 100%;" />
			</div>
			
			<div>
				<label for="ftpUser">{lang}wcf.global.ftp.user{/lang}</label>
				<input type="text" class="inputText" id="ftpUser" name="ftpUser" value="{$ftpUser}" style="width: 100%;" />
			</div>
			
			<div>
				<label for="ftpPassword">{lang}wcf.global.ftp.password{/lang}</label>
				<input type="password" class="inputText" id="ftpPassword" name="ftpPassword" value="{$ftpPassword}" style="width: 100%;" />
			</div>
			
		</div>
	</fieldset>
	
	<div class="nextButton">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.next{/lang}" />
		<input type="hidden" name="step" value="{@$nextStep}" />
		<input type="hidden" name="tmpFilePrefix" value="{@$tmpFilePrefix}" />
		<input type="hidden" name="charset" value="{@CHARSET}" />
		<input type="hidden" name="languageCode" value="{@$languageCode}" />
		<input type="hidden" name="wcfDir" value="{$wcfDir}" />
		<input type="hidden" name="dev" value="{@$developerMode}" />
	</div>
</form>

{include file='footer'}