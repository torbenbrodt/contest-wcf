{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ItemListEditor.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	function init() {
		{if $contestClasses|count > 0 && $this->user->getPermission('admin.contest.canEditClass')}
			new ItemListEditor('classList', { itemTitleEdit: true, itemTitleEditURL: 'index.php?action=ContestClassRename&classID=', tree: true, treeTag: 'ol' });
		{/if}
	}
	
	// when the dom is fully loaded, execute these scripts
	document.observe("dom:loaded", init);
	
	//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contestClassL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.contest.class{/lang}</h2>
	</div>
</div>

{if $deletedClassID}
	<p class="success">{lang}wcf.acp.contest.class.delete.success{/lang}</p>
{/if}

{if $successfullSorting}
	<p class="success">{lang}wcf.acp.contest.class.sort.success{/lang}</p>
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
			{if $this->user->getPermission('admin.contest.canAddClass')}<li><a href="index.php?form=ContestClassAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.contest.class.add{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/contestClassAddM.png" alt="" /> <span>{lang}wcf.acp.contest.class.add{/lang}</span></a></li>{/if}
		</ul>
	</div>
</div>

{if $contestClasses|count > 0}
	<form method="post" action="index.php?action=ContestClassSort">
		<div class="border content">
			<div class="container-1">
				<ol class="itemList" id="classList">
					{foreach from=$contestClasses item=child}
						{* define *}
						{assign var="contestClass" value=$child.contestClass}
						
						<li id="item_{@$contestClass->classID}">
							
							<div class="buttons">
								{if $this->user->getPermission('admin.contest.canEditClass')}
									<a href="index.php?form=ContestClassEdit&amp;classID={@$contestClass->classID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.contest.class.edit{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.contest.class.editDisabled{/lang}" />
								{/if}
								
								{if $this->user->getPermission('admin.contest.canDeleteClass')}
									<a onclick="return confirm('{lang}wcf.acp.contest.class.delete.sure{/lang}')" href="index.php?action=ContestClassDelete&amp;classID={@$contestClass->classID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.contest.class.delete{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.contest.class.deleteDisabled{/lang}" />
								{/if}
								
								{if $child.additionalButtons|isset}{@$child.additionalButtons}{/if}
							</div>
							
							<h3 class="itemListTitle">
								
								{if $this->user->getPermission('admin.contest.canEditClass')}
									<select name="classListPositions[{@$contestClass->classID}][{@$contestClass->parentContestClassID}]">
										{section name='positions' loop=$child.maxPosition}
											<option value="{@$positions+1}"{if $positions+1 == $child.position} selected="selected"{/if}>{@$positions+1}</option>
										{/section}
									</select>
								{/if}
								
								ID-{@$contestClass->classID}
								{if $this->user->getPermission('admin.contest.canEditClass')}
									<a href="index.php?form=ContestClassEdit&amp;classID={@$contestClass->classID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" class="title">{lang}wcf.contest.class.item.{$contestClass->classID}{/lang}</a>
								{else}
									{lang}wcf.contest.class.item.{$contestClass->classID}{/lang}
								{/if}
							</h3>
							
						{if $child.hasChildren}<ol id="parentItem_{@$contestClass->classID}">{else}<ol id="parentItem_{@$contestClass->classID}"></ol></li>{/if}
						{if $child.openParents > 0}{@"</ol></li>"|str_repeat:$child.openParents}{/if}
					{/foreach}
				</ol>
			</div>
		</div>
		
		{if $this->user->getPermission('admin.contest.canEditClass')}
			<div class="formSubmit">
				<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
				<input type="reset" id="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
				<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
				{@SID_INPUT_TAG}
			</div>
		{/if}
	</form>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.contest_class.list.count.noEntries{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}
