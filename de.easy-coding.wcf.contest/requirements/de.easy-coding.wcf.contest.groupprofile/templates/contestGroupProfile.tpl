{include file="documentHeader"}
<head>
        <title>{lang}{$group->groupName}{/lang} - {lang}wcf.user.userGroups.title{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{lang}{PAGE_TITLE}{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}groupL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}{$group->groupName}{/lang}</h2>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="border {if $this|method_exists:'getGroupProfileMenu' && $this->getGroupProfileMenu()->getMenuItems('')|count > 1}tabMenuContent{else}content{/if}">
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
							{cycle values='container-1,container-2' print=false advance=false}
							<div class="userProfileContent">
								<div class="border">
									<div class="containerHead">
										<div class="containerIcon"><img src="{@RELATIVE_WCF_DIR}icon/groupS.png" alt="" /></div>
										<h3 class="containerContent">{lang}wcf.user.profile.information{/lang}</h3>
									</div>
					
									<div class="{cycle}">
										<div class="fieldTitle">{lang}wcf.acp.style.name{/lang}</div>
										<div class="fieldValue">{$group->groupName}</div>
									</div>
				{if $group->groupDescription|isset}
									<div class="{cycle}">
										<div class="fieldTitle">{lang}wcf.acp.group.description{/lang}</div>
										<div class="fieldValue">{$group->groupDescription}</div>
									</div>
				{/if}
									<div class="{cycle}">
										<div class="fieldTitle">{lang}wcf.acp.group.type{/lang}</div>
										<div class="fieldValue">{lang}wcf.user.userGroups.groupType.{$group->groupType}{/lang}</div>
									</div>
					
									{foreach from=$informationFields item=field}
										<div class="{cycle}">
											<div class="fieldTitle">{@$field.title}</div>
											<div class="fieldValue">{@$field.value}</div>
										</div>
									{/foreach}
					
								</div>
							</div>

							{if $additionalContents2|isset}{@$additionalContents2}{/if}
			
							<div class="userProfileContent">
								<div class="border">
									<div class="containerHead">
										<div class="containerIcon"><img src="{@RELATIVE_WCF_DIR}icon/usersS.png" alt="" /></div>
										<h3 class="containerContent">{lang}wcf.user.profile.members{/lang}</h3>
									</div>
									<div class="container-1">
										<ul class="memberList">
										{foreach from=$userlist item=user}
											<li>
												{if $user->isOnline()}
													<img class="memberListStatusIcon" alt="" title="{lang username=$user}wcf.user.online{/lang}" src="{@RELATIVE_WCF_DIR}icon/onlineS.png" />
												{else}
													<img class="memberListStatusIcon" alt="" title="{lang username=$user}wcf.user.offline{/lang}" src="{@RELATIVE_WCF_DIR}icon/offlineS.png" />
												{/if}
												<a class="memberName" href="index.php?page=User&amp;userID={$user->userID}"><span>{$user->username}</span></a>
											</li>
										{/foreach}
										</ul>
									</div>
								</div>
							</div>
							
							<div class="contentFooter"> </div>
						</div>
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
