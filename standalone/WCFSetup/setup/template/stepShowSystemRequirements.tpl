{include file='header'}

<h2>{lang}wcf.global.systemRequirements{/lang}</h2>

<p>{lang}wcf.global.systemRequirements.description{/lang}</p>

<fieldset>
	<legend>{lang}wcf.global.systemRequirements.required{/lang}</legend>
	<div class="inner">
	
		<div>
			<h3>{lang}wcf.global.systemRequirements.php{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.required{/lang} 5.0.0</span>
			<span class="right" style="color: {if !$system.phpVersion.result}red{elseif !$system.phpVersion.result2}orange{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {$system.phpVersion.value}</span>
			{if !$system.phpVersion.result}<div>{lang}wcf.global.systemRequirements.php.description{/lang}</div>
			{elseif !$system.phpVersion.result2}<div>{lang}wcf.global.systemRequirements.php.description{/lang}</div>{/if}
		</div>
		
		<hr />
		
		<div>
			<h3>{lang}wcf.global.systemRequirements.mysql{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.required{/lang} {lang}wcf.global.systemRequirements.active{/lang}</span>
			<span class="right" style="color: {if !$system.mySQLVersion.result}red{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {if !$system.mySQLVersion.result}{lang}wcf.global.systemRequirements.notActive{/lang}{else}{lang}wcf.global.systemRequirements.active{/lang}{/if}</span>
			{if !$system.mySQLVersion.result}<div>{lang}wcf.global.systemRequirements.mysql.description{/lang}</div>{/if}
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>{lang}wcf.global.systemRequirements.recommended{/lang}</legend>
	<div class="inner">
	
		<div>
			<h3>{lang}wcf.global.systemRequirements.uploadMaxFilesize{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.recommended{/lang} > 0</span>
			<span class="right" style="color: {if !$system.uploadMaxFilesize.result}orange{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {$system.uploadMaxFilesize.value}</span>
			{if !$system.uploadMaxFilesize.result}<div>{lang}wcf.global.systemRequirements.uploadMaxFilesize.description{/lang}</div>{/if}
		</div>
		
		<hr />
		
		<div>
			<h3>{lang}wcf.global.systemRequirements.gdLib{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.recommended{/lang} 2.0.0</span>
			<span class="right" style="color: {if !$system.gdLib.result}orange{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {$system.gdLib.value}</span>
			{if !$system.gdLib.result}<div>{lang}wcf.global.systemRequirements.gdLib.description{/lang}</div>{/if}
		</div>
		
		<hr />
		
		<div>
			<h3>{lang}wcf.global.systemRequirements.mbString{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.recommended{/lang} {lang}wcf.global.systemRequirements.active{/lang}</span>
			<span class="right" style="color: {if !$system.mbString.result}orange{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {if !$system.mbString.result}{lang}wcf.global.systemRequirements.notActive{/lang}{else}{lang}wcf.global.systemRequirements.active{/lang}{/if}</span>
			{if !$system.mbString.result}<div>{lang}wcf.global.systemRequirements.mbString.description{/lang}</div>{/if}
		</div>
		
		<hr />
		
		<div>
			<h3>{lang}wcf.global.systemRequirements.safeMode{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.recommended{/lang} {lang}wcf.global.systemRequirements.notActive{/lang}</span>
			<span class="right" style="color: {if !$system.safeMode.result}orange{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {if !$system.safeMode.result}{lang}wcf.global.systemRequirements.active{/lang}{else}{lang}wcf.global.systemRequirements.notActive{/lang}{/if}</span>
			{if !$system.safeMode.result}<div>{lang}wcf.global.systemRequirements.safeMode.description{/lang}</div>{/if}
		</div>
		
		{if !$system.safeMode.result}
		<hr />
		<div>
			<h3>{lang}wcf.global.systemRequirements.ftp{/lang}</h3>
			<span class="left">{lang}wcf.global.systemRequirements.element.recommended{/lang} {lang}wcf.global.systemRequirements.active{/lang}</span>
			<span class="right" style="color: {if !$system.ftp.result}orange{else}green{/if}">{lang}wcf.global.systemRequirements.element.yours{/lang} {if !$system.ftp.result}{lang}wcf.global.systemRequirements.notActive{/lang}{else}{lang}wcf.global.systemRequirements.active{/lang}{/if}</span>
			{if !$system.ftp.result}<div>{lang}wcf.global.systemRequirements.ftp.description{/lang}</div>{/if}
		</div>
		{/if}
		
	</div>
</fieldset>

<form method="post" action="install.php">	
	<div class="nextButton">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.next{/lang}" {if !$system.phpVersion.result || !$system.mySQLVersion.result} disabled="disabled"{/if}/>
		<input type="hidden" name="step" value="{@$nextStep}" />
		<input type="hidden" name="tmpFilePrefix" value="{@$tmpFilePrefix}" />
		<input type="hidden" name="charset" value="{@CHARSET}" />
		<input type="hidden" name="languageCode" value="{@$languageCode}" />
		<input type="hidden" name="dev" value="{@$developerMode}" />
	</div>
</form>

{include file='footer'}