<meta http-equiv="content-type" content="text/html; charset={@CHARSET}" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-style-type" content="text/css" />
<meta name="description" content="{META_DESCRIPTION}" />
<meta name="keywords" content="{META_KEYWORDS}" />
{if !$allowSpidersToIndexThisPage|isset}<meta name="robots" content="noindex,nofollow" />{/if}

<!-- contest styles -->
<link rel="stylesheet" type="text/css" media="screen" href="{@RELATIVE_CONTEST_DIR}style/contest.css" />

{if $specialStyles|isset}
	<!-- special styles -->
	{@$specialStyles}
{/if}

<!-- dynamic styles -->
<link rel="stylesheet" type="text/css" media="screen" href="{@RELATIVE_WCF_DIR}style/style-{@$this->getStyle()->styleID}.css" />

<!-- print styles -->
<link rel="stylesheet" type="text/css" media="print" href="{@RELATIVE_WCF_DIR}style/extra/print.css" />


<script type="text/javascript">
	//<![CDATA[
	var SID_ARG_2ND	= '{@SID_ARG_2ND_NOT_ENCODED}';
	var RELATIVE_WCF_DIR = '{@RELATIVE_WCF_DIR}';
	//]]>
</script>

<!-- hack styles -->
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" media="screen" href="{@RELATIVE_WCF_DIR}style/extra/ie6-fix.css" />
	<style type="text/css">		
		{if !$this->getStyle()->getVariable('page.width')}
			#page { /* note: non-standard style-declaration */
				_width: expression(((document.body.clientWidth/screen.width)) < 0.7 ? "{$this->getStyle()->getVariable('page.width.min')}":"{$this->getStyle()->getVariable('page.width.max')}" );
			}
		{/if}
	</style>
<![endif]-->

<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" media="screen" href="{@RELATIVE_WCF_DIR}style/extra/ie7-fix.css" />
<![endif]-->

{if $this->getStyle()->getVariable('global.favicon')}<link rel="shortcut icon" href="{@RELATIVE_WCF_DIR}icon/favicon/favicon{$this->getStyle()->getVariable('global.favicon')|ucfirst}.ico" type="image/x-icon" />{/if}

<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/default.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/PopupMenuList.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/AjaxRequest.class.js"></script>

{if $executeCronjobs}
	<script type="text/javascript">
		//<![CDATA[
		var ajaxRequest = new AjaxRequest();
		ajaxRequest.openGet('index.php?action=CronjobsExec'+SID_ARG_2ND);
		//]]>
	</script>
{/if}
