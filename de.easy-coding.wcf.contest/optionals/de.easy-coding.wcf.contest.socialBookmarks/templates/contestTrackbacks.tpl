<!-- Trackbacks -->
{if $trackbacks|isset && $trackbacks|count > 0}
        <a id="trackbacks"></a>
        <div class="contentBox">
                <h3 class="subHeadline">{lang}wcf.contest.trackback.title{/lang} <span>({#$trackbacks|count})</span></h3>

                <ul class="dataList messages">
                        {foreach from=$trackbacks item=trackbackEntry}
                                <li class="{cycle name='className' values='container-1,container-2'}">
                                        <a id="trackback{@$trackbackEntry.trackbackID}"></a>
                                        <div class="containerIcon">
                                                <img src="{@RELATIVE_WCF_DIR}icon/contestTrackbackM.png" title="Trackback" alt="" />
                                        </div>
                                        <div class="containerContent">
                                                <p class="smallFont">{$trackbackEntry.applicationName}</p>
                                                <h4><a href="{$trackbackEntry.url}" title="wcf.contest.trackback.jumpToLinkedArticle">{$trackbackEntry.title}</a></h4>
                                                <p class="light smallFont">{@$trackbackEntry.time|time}</p>
                                                <p>{$trackbackEntry.excerpt}</p>
                                        </div>
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
