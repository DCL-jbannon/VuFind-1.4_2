<div id="record{$summId|escape}" class="resultsList">	
	<div class="imageColumn"> 
		{if !isset($user->disableCoverArt) ||$user->disableCoverArt != 1}	
		<div id='descriptionPlaceholder{$summId|escape}' style='display:none'></div>
		<a href="{$path}/EcontentRecord/{$summId|escape:"url"}?searchId={$searchId}&amp;recordIndex={$recordIndex}&amp;page={$page}" id="descriptionTrigger{$summId|escape:"url"}">
		<img src="{$bookCoverUrl}" class="listResultImage" alt="{translate text='Cover Image'}"/>
		</a>
		{/if}
		{* Place hold link *}
		<div class='requestThisLink generic-item-background' id="placeEcontentHold{$summId|escape:"url"}" style="display:none">
			<a class="generic-item-background" href="{$path}/EcontentRecord/{$summId|escape:"url"}/Hold">
				Place Hold
			</a>
		</div>
		
		{* Checkout link *}
		<div class='checkoutLink generic-item-background' id="checkout{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Checkout">
				Checkout
			</a>
		</div>
		
		{* Access online link *}
		<div class='accessOnlineLink generic-item-background' id="accessOnline{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Home?detail=holdingstab#detailsTab">
				Access Online
			</a>
		</div>
		
		{* Add to Wish List *}
		<div class='generic-item-background-wishList' id="addToWishList{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Home?detail=holdingstab#detailsTab" title='If you want the library to buy this eBook, click this button and add it to your Wish List.' alt='If you want the library to buy this eBook, click this button and add it to your Wish List.'>
				Add To<br/>Wish List
			</a>
		</div>
		
		{* Access eVideo *}
		<div class='generic-item-background' id="access-eVideo{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Home?detail=holdingstab#detailsTab" title='Access eVideo' alt='Access eVideo'>
				Access eVideo
			</a>
		</div>
		{* Access eAudio *}
		<div class='generic-item-background' id="access-eAudio{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Home?detail=holdingstab#detailsTab" title='Access eAudio' alt='Access eAudio'>
				Access eAudio
			</a>
		</div>
		{* Access eBook *}
		<div class='generic-item-background' id="access-eBook{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Home?detail=holdingstab#detailsTab" title='Access eBook' alt='Access eBook'>
				Access eBook
			</a>
		</div>
		{* Access eMusic *}
		<div class='generic-item-background' id="access-eMusic{$summId|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$summId|escape:"url"}/Home?detail=holdingstab#detailsTab" title='Access eMusic' alt='Access eMusic'>
				Access eMusic
			</a>
		</div>
	</div>

<div class="resultDetails">
	<div class="resultItemLine1">
	<a href="{$path}/EcontentRecord/{$summId|escape:"url"}?searchId={$searchId}&amp;recordIndex={$recordIndex}&amp;page={$page}" class="title">{if !$summTitle|regex_replace:"/(\/|:)$/":""}{translate text='Title not available'}{else}{$summTitle|regex_replace:"/(\/|:)$/":""|truncate:180:"..."|highlight:$lookfor}{/if}</a>
	{if $summTitleStatement}
		<div class="searchResultSectionInfo">
			{$summTitleStatement|regex_replace:"/(\/|:)$/":""|truncate:180:"..."|highlight:$lookfor}
		</div>
		{/if}
	</div>

	<div class="resultItemLine2">
		{if $summAuthor}
			{translate text='by'}
			{if is_array($summAuthor)}
				{foreach from=$summAuthor item=author}
					<a href="{$path}/Author/Home?author={$author|escape:"url"}">{$author|highlight:$lookfor}</a>
				{/foreach}
			{else}
				<a href="{$path}/Author/Home?author={$summAuthor|escape:"url"}">{$summAuthor|highlight:$lookfor}</a>
			{/if}
		{/if}
 
		{if $summDate}{translate text='Published'} {$summDate.0|escape}{/if}
	</div>
	
	<div class="resultItemLine3">
		{if !empty($summSnippetCaption)}<b>{translate text=$summSnippetCaption}:</b>{/if}
		{if !empty($summSnippet)}<span class="quotestart">&#8220;</span>...{$summSnippet|highlight}...<span class="quoteend">&#8221;</span><br />{/if}
	</div>

	{if is_array($summFormats)}
		{strip}
		{foreach from=$summFormats item=format name=formatLoop}
			{if $smarty.foreach.formatLoop.index != 0}, {/if}
			<span class="iconlabel {$format|lower|regex_replace:"/[^a-z0-9]/":""}">{translate text=$format}</span>
		{/foreach}
		{/strip}
	{else}
		<span class="iconlabel {$summFormats|lower|regex_replace:"/[^a-z0-9]/":""}">{translate text=$summFormats}</span>
	{/if}
	<div id = "holdingsEContentSummary{$summId|escape:"url"}" class="holdingsSummary">
		<div class="statusSummary" id="statusSummary{$summId|escape:"url"}">
			<span class="unknown" style="font-size: 8pt;">{translate text='Loading'}...</span>
		</div>
	</div>
</div>

<div id ="searchStars{$summId|escape}" class="resultActions">
	<div class="rateEContent{$summId|escape} stat">
		<div class="statVal">
			<span class="ui-rater">
				<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:0px"></span></span>
				(<span class="ui-rater-rateCount-{$summId|escape} ui-rater-rateCount">0</span>)
			</span>
		</div>
		<div id="saveLink{$summId|escape}">
			{if $user}
				<div id="lists{$summId|escape}"></div>
				<script type="text/javascript">
					getSaveStatuses('{$summId|escape:"javascript"}');
				</script>
			{/if}
			{if $showFavorites == 1} 
				<a href="{$path}/Resource/Save?id={$summId|escape:"url"}&amp;source=eContent" style="padding-left:8px;" onclick="getSaveToListForm('{$summId|escape}', 'eContent'); return false;">{translate text='Add to'} <span class='myListLabel'>MyLIST</span></a>
			{/if}
		</div>
		{assign var=id value=$summId scope="global"}
		{include file="EcontentRecord/title-review.tpl" id=$summId}
		<div class="addToBookBag">
		  <a id="add_to_cart_econtentRecord{$summId|escape}" class="button add_to_cart" href="#" onclick="toggleInBag('econtentRecord{$summId|escape:"url"}', '{$summTitle|regex_replace:"/(\/|:'\")$/":""|escape:"javascript"}', this); return false;"><span class="add_to_cart">{translate text='Add to cart'}</span><span class="in_cart" style="display:none;">{translate text='Remove from cart'}</span></a>
		</div>
	</div>
	<script type="text/javascript">
		$(
			 function() {literal} { {/literal}
					 $('.rateEContent{$summId|escape}').rater({literal}{ {/literal}module: 'EcontentRecord', recordId: {$summId},	rating:0.0, postHref: '{$path}/EcontentRecord/{$summId|escape}/AJAX?method=RateTitle'{literal} } {/literal});
			 {literal} } {/literal}
		);
	</script>
		
</div>


<script type="text/javascript">
	addRatingId('{$summId|escape:"javascript"}', 'eContent');
	addIdToStatusList('{$summId|escape:"javascript"}', '{$methodToLoadStatusSummaries}');
	$(document).ready(function(){literal} { {/literal}
		resultDescription('{$summId}','{$summId}', 'eContent');
	{literal} }); {/literal}
	
</script>

</div>