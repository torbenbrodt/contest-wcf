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
							<h3 class="subHeadline">{lang}wcf.user.profile.information{/lang}</h3>
							<ul class="dataList">
								<li class="{cycle} formElement">
									<p class="formFieldLabel">{lang}wcf.acp.style.name{/lang}</p>
									<p class="formField">{$group->groupName}</p>
								</li>
								{if $group->groupDescription|isset}
								<li class="{cycle} formElement">
									<p class="formFieldLabel">{lang}wcf.acp.group.description{/lang}</p>
									<p class="formField">{$group->groupDescription}</p>
								</li>
								{/if}
								<li class="{cycle} formElement">
									<p class="formFieldLabel">{lang}wcf.acp.group.type{/lang}</p>
									<p class="formField">{lang}wcf.user.userGroups.groupType.{$group->groupType}{/lang}</p>
								</li>
				
								{foreach from=$informationFields item=field}
									<li class="{cycle} formElement">
										<p class="formFieldLabel">{@$field.title}</p>
										<p class="formField">{@$field.value}</p>
									</li>
								{/foreach}
							</ul>

							{if $additionalContents2|isset}{@$additionalContents2}{/if}
			
							{if $userlist|count > 0}
								<div class="contentBox">
									<h3 class="subHeadline">{lang}wcf.user.profile.members{/lang}</h3>
								
									<ul class="dataList thumbnailView floatContainer container-1">
										{foreach name='userlist' from=$userlist item=friend}
											<li class="floatedElement smallFont{if $tpl.foreach.userlist.iteration == 5} last{/if}">
												<a href="index.php?page=User&amp;userID={@$friend->userID}" title="{lang username=$friend->username}wcf.user.viewProfile{/lang}">
													{if $friend->getAvatar()}
														{assign var=x value=$friend->getAvatar()->setMaxSize(48, 48)}
														<span class="thumbnail" style="width: {@$friend->getAvatar()->getWidth()}px;">{@$friend->getAvatar()}</span>
													{else}
														<span class="thumbnail" style="width: 48px;"><img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 48px; height: 48px" /></span>
													{/if}
													<span class="avatarCaption">{$friend->username}</span>
												</a>
											</li>
										{/foreach}
									</ul>
									<div class="buttonBar">
										<div class="smallButtons">
											<ul>
												<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
											</ul>
										</div>
									</div>
								</div>
							{/if}
							
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
