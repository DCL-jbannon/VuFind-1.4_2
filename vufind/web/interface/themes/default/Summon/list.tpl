<script type="text/javascript" src="{$path}/services/Summon/ajax.js"></script>

{* Main Listing *}
<div id="bd">
  <div id="yui-main" class="content">
    <div class="yui-b first">
    <b class="btop"><b></b></b>
      {* Recommendations *}
      {if $topRecommendations}
        {foreach from=$topRecommendations item="recommendations"}
          {include file=$recommendations}
        {/foreach}
      {/if}

      {* Listing Options *}
      <div class="yui-ge resulthead">
        <div class="yui-u first">
        {if $recordCount}
          {translate text="Showing"}
          <b>{$recordStart}</b> - <b>{$recordEnd}</b>
          {translate text='of'} <b>{$recordCount}</b>
          {translate text='for search'} <b>'{$lookfor|escape}'</b>,
        {/if}
        {translate text='query time'}: {$qtime}s
        {if $spellingSuggestions}
          <br /><br /><div class="correction"><strong>{translate text='spell_suggest'}</strong>:<br/>
          {foreach from=$spellingSuggestions item=details key=term name=termLoop}
            {$term|escape} &raquo; {foreach from=$details.suggestions item=data key=word name=suggestLoop}<a href="{$data.replace_url|escape}">{$word|escape}</a>{if $data.expand_url} <a href="{$data.expand_url|escape}"><img src="{$path}/images/silk/expand.png" alt="{translate text='spell_expand_alt'}"/></a> {/if}{if !$smarty.foreach.suggestLoop.last}, {/if}{/foreach}{if !$smarty.foreach.termLoop.last}<br/>{/if}
          {/foreach}
          </div>
        {/if}
        </div>

        <div class="yui-u toggle">
          {translate text='Sort'}
          <select name="sort" onchange="document.location.href = this.options[this.selectedIndex].value;">
          {foreach from=$sortList item=sortData key=sortLabel}
            <option value="{$sortData.sortUrl|escape}"{if $sortData.selected} selected{/if}>{translate text=$sortData.desc}</option>
          {/foreach}
          </select>
        </div>

      </div>
      {* End Listing Options *}

      {if $subpage}
        {include file=$subpage}
      {else}
        {$pageContent}
      {/if}

      <div class="pagination">{$pageLinks.all}</div>
      
      <div class="searchtools">
        <strong>{translate text='Search Tools'}:</strong>
        {* TODO: Implement RSS <a href="{$url}/Search/{$action}?lookfor={$lookfor|escape}&type={$type}&view=rss" class="feed">{translate text='Get RSS Feed'}</a> *}
        <a href="{$url}/Search/Email" class="mail" onclick="getLightbox('Search', 'Email', null, null, '{translate text="Email this"}'); return false;">{translate text='Email this Search'}</a>
        {if $savedSearch}<a href="{$url}/MyResearch/SaveSearch?delete={$searchId}" class="delete">{translate text='save_search_remove'}</a>{else}<a href="{$url}/MyResearch/SaveSearch?save={$searchId}" class="add">{translate text='save_search'}</a>{/if}
      </div>
      <b class="bbot"><b></b></b>
    </div>
    {* End Main Listing *}
    
  </div>

  {* Narrow Search Options *}
  <div class="yui-b">
    {if $sideRecommendations}
      {foreach from=$sideRecommendations item="recommendations"}
        {include file=$recommendations}
      {/foreach}
    {/if}
  </div>
  {* End Narrow Search Options *}

</div>
