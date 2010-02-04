<div id="page">
	<a id="top"></a>
	<div id="userPanel" class="userPanel">
		<p id="userNote"> 
			{if $this->user->userID != 0}{lang}contest.header.userNote.user{/lang}{else}{lang}contest.header.userNote.guest{/lang}{/if}
		</p>
		<div id="userMenu">
			<ul>
				{if $this->user->userID != 0}
					<li><a href="index.php?action=UserLogout&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/logoutS.png" alt="" /> <span>{lang}contest.header.userMenu.logout{/lang}</span></a></li>
					<li><a href="index.php?form=UserProfileEdit{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/profileS.png" alt="" /> <span>{lang}contest.header.userMenu.profile{/lang}</span></a></li>
					
					{if $additionalUserMenuItems|isset}{@$additionalUserMenuItems}{/if}
					
					{if $this->user->getPermission('admin.general.canUseAcp')}
						<li><a href="acp/index.php?packageID={@PACKAGE_ID}"><img src="{@RELATIVE_WCF_DIR}icon/acpS.png" alt="" /> <span>{lang}contest.header.userMenu.acp{/lang}</span></a></li>
					{/if}

				{else}
					<li><a href="index.php?form=UserLogin{@SID_ARG_2ND}" id="loginButton"><img src="{@RELATIVE_WCF_DIR}icon/loginS.png" alt="" /> <span>{lang}contest.header.userMenu.login{/lang}</span></a>
					
					{if !LOGIN_USE_CAPTCHA}
						<div class="hidden" id="loginBox">
							<form method="post" action="index.php?form=UserLogin" class="container-1">
								<div>	
									<div>
										<input tabindex="1" type="text" class="inputText" id="loginUsername" name="loginUsername" value="{lang}wcf.user.username{/lang}" />
										<input tabindex="2" type="password" class="inputText" name="loginPassword" value="" />
										{if $this->session->requestMethod == 'GET'}<input type="hidden" name="url" value="{$this->session->requestURI}" />{/if}
										{@SID_INPUT_TAG}
										<input tabindex="4" type="image" class="inputImage" src="{@RELATIVE_WCF_DIR}icon/submitS.png" />
									</div>
									<label><input tabindex="3" type="checkbox" id="useCookies" name="useCookies" value="1" /> {lang}contest.header.login.useCookies{/lang}</label>
								</div>
							</form>
						</div>
						
						<script type="text/javascript">
							//<![CDATA[
							var loginFormVisible = false;
							function showLoginForm() {
								var loginBox = document.getElementById("loginBox");
								
								if (loginBox) {
									if (!loginFormVisible) {
										loginBox.className = "border loginPopup";
										loginFormVisible = true;
									}
									else {
										loginBox.className = "hidden";
										loginFormVisible = false;
									}
								}
								
								return false;
							}
							
							document.getElementById('loginButton').onclick = function() { return showLoginForm(); };
							document.getElementById('loginButton').ondblclick = function() { document.location.href = fixURL('index.php?form=UserLogin{@SID_ARG_2ND_NOT_ENCODED}'); };
							document.getElementById('loginUsername').onfocus = function() { if (this.value == '{lang}wcf.user.username{/lang}') this.value=''; };
							document.getElementById('loginUsername').onblur = function() { if (this.value == '') this.value = '{lang}wcf.user.username{/lang}'; };
							//]]>
						</script>
					{/if}
					
					</li>
					{if !REGISTER_DISABLED}<li><a href="index.php?page=Register{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/registerS.png" alt="" /> <span>{lang}contest.header.userMenu.register{/lang}</span></a></li>{/if}
					
					{if $this->language->countLanguages() > 1}
						<li><a id="changeLanguage" class="hidden"><img src="{@RELATIVE_WCF_DIR}icon/language{@$this->language->getLanguageCode()|ucfirst}S.png" alt="" /> <span>{lang}contest.header.userMenu.changeLanguage{/lang}</span></a>
							<div class="hidden" id="changeLanguageMenu">
								<ul>
									{foreach from=$this->language->getLanguageCodes() item=guestLanguageCode key=guestLanguageID}
										<li{if $guestLanguageID == $this->language->getLanguageID()} class="active"{/if}><a href="index.php?l={$guestLanguageID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/language{@$guestLanguageCode|ucfirst}S.png" alt="" /> <span>{lang}wcf.global.language.{@$guestLanguageCode}{/lang}</span></a></li>
									{/foreach}
								</ul>
							</div>
							<script type="text/javascript">
								//<![CDATA[
								onloadEvents.push(function() { document.getElementById('changeLanguage').className=''; });
								popupMenuList.register('changeLanguage');
								//]]>
							</script>
							<noscript>
								<form method="get" action="index.php">
									<div>
										<label><img src="{@RELATIVE_WCF_DIR}icon/language{@$this->language->getLanguageCode()|ucfirst}S.png" alt="" />
											<select name="l" onchange="this.form.submit()">
												{htmloptions options=$this->language->getLanguages() selected=$this->language->getLanguageID()}
											</select>
										</label>
										{@SID_INPUT_TAG}
										<input type="image" class="inputImage" src="{@RELATIVE_WCF_DIR}icon/submitS.png" />
									</div>
								</form>
							</noscript>
						</li>
					{/if}
				{/if}
			</ul>
		</div>
	</div>
	
	{* --- quick search controls ---
	 * $searchScript=search script; default=index.php?form=search
	 * $searchFieldName=name of the search input field; default=q
	 * $searchFieldValue=default value of the search input field; default=content of $query
	 * $searchFieldTitle=title of search input field; default=language variable contest.header.search.query
	 * $searchFieldOptions=special search options for popup menu; default=empty
	 * $searchExtendedLink=link to extended search form; default=index.php?form=search{@SID_ARG_2ND}
	 * $searchHiddenFields=optional hidden fields; default=empty
	 * $searchShowExtendedLink=set to false to disable extended search link; default=true
	 *}
	
	{if !$searchScript|isset}{assign var='searchScript' value='index.php?form=WikiSearch'}{/if}
	{if !$searchFieldName|isset}{assign var='searchFieldName' value='q'}{/if}
	{if !$searchFieldValue|isset && $query|isset}{assign var='searchFieldValue' value=$query}{/if}
	{if !$searchFieldTitle|isset}{assign var='searchFieldTitle' value='{lang}contest.header.search.query{/lang}'}{/if}
	{if !$searchFieldOptions|isset}
		{capture assign=searchFieldOptions}
			{*<li><a href="index.php?form=WikiSearch&amp;action=24h{@SID_ARG_2ND}">{lang}contest.search.threadsOfTheLast24Hours{/lang}</a></li>*}
		{/capture}
	{/if}
	{if !$searchExtendedLink|isset}{assign var='searchExtendedLink' value='index.php?form=WikiSearch'|concat:SID_ARG_2ND}{/if}
	{if !$searchShowExtendedLink|isset}{assign var='searchShowExtendedLink' value=true}{/if}
		
	<div id="header" class="border">
	<div id="search">
			<form method="post" action="{@$searchScript}">
		
				<div class="searchContainer">
					<input type="text" tabindex="5" id="searchInput" class="inputText" name="{@$searchFieldName}" value="{if !$searchFieldValue|empty}{$searchFieldValue}{else}{@$searchFieldTitle}{/if}" />
					<input type="image" tabindex="6" id="searchSubmit" class="searchSubmit inputImage" src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="{lang}wcf.global.button.submit{/lang}" />
					{@SID_INPUT_TAG}
					{if $searchHiddenFields|isset}{@$searchHiddenFields}{else}<input type="hidden" name="types[]" value="article" />{/if}
					
					<script type="text/javascript">
						//<![CDATA[
						document.getElementById('searchInput').setAttribute('autocomplete', 'off');
						document.getElementById('searchInput').onfocus = function() { if (this.value == '{@$searchFieldTitle}') this.value=''; };
						document.getElementById('searchInput').onblur = function() { if (this.value == '') this.value = '{@$searchFieldTitle}'; };
						document.getElementById('searchSubmit').ondblclick = function() { window.location = 'index.php?form=WikiSearch{@SID_ARG_2ND_NOT_ENCODED}'; };
						{if $searchFieldOptions || $searchShowExtendedLink}
							popupMenuList.register("searchInput");
							document.getElementById('searchInput').className += " searchOptions";
						{/if}
						//]]>
					</script>
					{if $searchFieldOptions || $searchShowExtendedLink}
						<div class="searchInputMenu">
							<div class="hidden" id="searchInputMenu">
								<div class="pageMenu smallFont">
									<ul>
										{@$searchFieldOptions}
										{if $searchShowExtendedLink}<li><a href="{@$searchExtendedLink}">{lang}contest.header.search.extended{/lang}</a></li>{/if}
									</ul>
								</div>
							</div>
						</div>
					{/if}
					
					{if $searchShowExtendedLink}
						<noscript>
							<p><a href="{@$searchExtendedLink}">{lang}wbb.header.search.extended{/lang}</a></p>
						</noscript>
					{/if}
				</div>
			</form>
		</div>
		<div id="logo">
			<h1 class="pageTitle"><a href="index.php?page=Index{@SID_ARG_2ND}">{PAGE_TITLE}</a></h1>
			{if $this->getStyle()->getVariable('page.logo.image')}
				<a href="index.php?page=Index{@SID_ARG_2ND}" class="pageLogo">
					<img src="images/wiki-header-logo.png" title="{PAGE_TITLE}" alt="" />
				</a>
			{/if}
		</div>
		
		{include file=headerMenu}
	</div>
	
	{if $additionalInfoBoxes|isset}{@$additionalInfoBoxes}{/if}
