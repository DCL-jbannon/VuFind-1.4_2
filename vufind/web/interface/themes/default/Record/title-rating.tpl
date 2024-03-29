<div id ="searchStars{$shortId|escape}{$starPostFixId}" class="{$ratingClass}">
  <div id="rate{$shortId|escape}{$starPostFixId}" class="rate{$shortId|escape} stat">
    <div class="statVal">
      <span class="ui-rater">
        <span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:0px"></span></span><br/>
        <span class="ui-rater-rating-{$shortId|escape} ui-rater-rating">0</span>&#160;(<span class="ui-rater-rateCount-{$shortId|escape} ui-rater-rateCount">0</span>)
      </span>
    </div>
    {if $showFavorites == 1} 
    <div id="saveLink{$recordId|escape}">
      <a href="{$path}/Resource/Save?id={$recordId|escape:"url"}&amp;source=VuFind" style="padding-left:8px;" onclick="getSaveToListForm('{$recordId|escape}', 'VuFind'); return false;">{translate text='Add to favorites'}</a>
      {if $user}
      <script type="text/javascript">
        getSaveStatuses('{$recordId|escape:"javascript"}');
      </script>
      {/if}
    </div>
    {/if}
  </div>
  <script type="text/javascript">
    $(
       function() {literal} { {/literal}
           addRatingId('{$recordId|escape}');
           $('#rate{$shortId|escape}{$starPostFixId}').rater({literal}{ {/literal}module: 'Record', recordId: {$shortId},  rating:0.0, postHref: '{$path}/Record/{$recordId|escape}/AJAX?method=RateTitle'{literal} } {/literal});
       {literal} } {/literal}
    );
  </script>    
</div>