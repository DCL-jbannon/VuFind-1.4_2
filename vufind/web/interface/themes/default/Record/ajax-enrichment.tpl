<SimilarAuthors><![CDATA[{if $enrichment.novelist.similarAuthorCount != 0}
  <h4 id="similarAuthorTitle" >Similar Authors</h4>
  {foreach from=$enrichment.novelist.authors item=similarAuthor}
    <div class="sidebarLabel">
      <a href={$path}/Author/Home?author={$similarAuthor|escape:"url"}&lookfor=>{$similarAuthor}</a>
    </div>
  {/foreach}
{/if}]]></SimilarAuthors>
<SeriesInfo><![CDATA[{$seriesInfo}]]></SeriesInfo>
<SeriesDefaultIndex>{$enrichment.novelist.seriesDefaultIndex}</SeriesDefaultIndex>
<SimilarTitles><![CDATA[{if $showSimilarTitles}
<h4>{translate text="Similar Titles"}</h4>
  {foreach from=$enrichment.novelist.similarTitles item=similar}
  {if $similar.recordId != -1}
  <div class="sidebarLabel">
    <a href="{$path}/Record/{$similar.recordId|escape:"url"}">{$similar.title|regex_replace:"/(\/|:)$/":""|escape}</a>
  </div>  
  <div class="sidebarValue">
    {if $similar.author}{translate text='By'}: {$similar.author|escape}{/if}
    {if $similar.publishDate} {translate text='Published'}: ({$similar.publishDate.0|escape}){/if}
  </div>
  {/if}
  {/foreach}
{/if}]]></SimilarTitles>
<ShowGoDeeperData>{$showGoDeeper}</ShowGoDeeperData>
<GoodReadReviewsUrl><![CDATA[{$goodReadReviewUrl}]]></GoodReadReviewsUrl>