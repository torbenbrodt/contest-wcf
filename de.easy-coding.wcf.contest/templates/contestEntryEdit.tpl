{include file="documentHeader"}
<head>
	<title>{lang}wcf.contest.{@$action}{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabMenu.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestTabMenu.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/StringUtil.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestPermissionList.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ContestSuggestion.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		onsubmitEvents = [];
		var steppedTabMenu = new ContestTabMenu();
		onloadEvents.push(function() {
			if(steppedTabMenu.isActive() == false) {
				steppedTabMenu.showSubTabMenu('step1');
			}
			
			document.getElementById('ContestAddForm').onsubmit = function() { 
				for(var i=0; i<onsubmitEvents.length; i++) {
					onsubmitEvents[i](this);
				}
			};
		});
		//]]>
	</script>
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
	
	<form method="post" id="ContestAddForm" enctype="multipart/form-data" action="index.php?form=Contest{@$action|ucfirst}{if $action == 'add'}{elseif $action == 'edit'}&amp;contestID={@$contestID}{/if}">
	
		<div class="tabMenu">
			<ul>
				<li id="step1">
					<a onclick="steppedTabMenu.showSubTabMenu('step1');">
					<img src="{@RELATIVE_WCF_DIR}icon/contestS.png" alt="" />
					<span>{lang}wcf.contest.information{/lang}</span></a>
				</li>
				<li id="step2">
					<a onclick="steppedTabMenu.showSubTabMenu('step2');">
					<img src="{@RELATIVE_WCF_DIR}icon/contestS.png" alt="" />
					<span>{lang}wcf.contest.message{/lang}</span></a>
				</li>
				<li id="step3">
					<a onclick="steppedTabMenu.showSubTabMenu('step3');">
					<img src="{@RELATIVE_WCF_DIR}icon/contestS.png" alt="" />
					<span>{lang}wcf.contest.settings{/lang}</span></a>
				</li>
			</ul>
		</div>
		<div class="subTabMenu">
			<div class="containerHead"><div> </div></div>
		</div>
		<div class="border tabMenuContent hidden" id="step1-content">
			<div class="container-1">
				{include file="contestEntryAddStep1"}
			</div>
		</div>
		<div class="border tabMenuContent hidden" id="step2-content">
			<div class="container-1">
				{include file="contestEntryAddStep2"}
			</div>
		</div>
		<div class="border tabMenuContent hidden" id="step3-content">
			<div class="container-1">
				{include file="contestEntryAddStep6"}
			</div>
		</div>
	</form>
</div>

{include file='footer' sandbox=false}
</body>
</html>
