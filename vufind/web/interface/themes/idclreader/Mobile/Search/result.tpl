<div class="ui-grid-a">
	<div class="ui-block-a">
		<img src='{$record.bookCoverUrl}' />
	</div>
	<div class="ui-block-b">
		<a href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}?searchId={$searchId}&amp;recordIndex={$recordIndex}&amp;page={$page}" class="title">
			{if !$record.docSolr->getTitle()|regex_replace:"/(\/|:)$/":""}
				{translate text='Title not available'}
			{else}
				{$record.docSolr->getTitle()|regex_replace:"/(\/|:)$/":""|truncate:150:"..."|highlight:$lookfor}
			{/if}
		</a>
		<div class="resultItemLine3">
			{if $record.docSolr->getDescription() neq 'false'}
				{translate text=$record.docSolr->getDescription()|truncate:200:"...":true}
			{else}
				No description available
			{/if}
		</div>
		<br/>
		{if $record.docSolr->getAuthor()  neq ''}
			{translate text='Author:'|highlight:"Author:"}
			{$record.docSolr->getAuthor()}
		{/if}
		<br/>
		{if $record.docSolr->getPublishDate() neq ''}
			{translate text='Published'|highlight:"Published"}: {$record.docSolr->getPublishDate()|escape}
		{/if}
		<br/>
		{* Place hold link *}
		<div class='requestThisLink' id="placeEcontentHold{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Hold" data-role="button" data-mini="true" data-theme='b' data-icon="arrow-r" data-inline="true" data-iconpos="right">
				Place Hold
			</a>
		</div>
		{* Checkout link *}
		<div class='checkoutLink' id="checkout{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Checkout" data-role="button" data-mini="true" data-theme='b' data-icon="arrow-r" data-inline="true" data-iconpos="right">
				Checkout
			</a>
		</div>
		{* Access online link *}
		<div class='accessOnlineLink' id="accessOnline{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Home" data-role="button" data-mini="true" data-theme='b' data-icon="arrow-r" data-inline="true" data-iconpos="right">
				Access Online
			</a>
		</div>
		{* Add to Wish List *}
		<div class='addToWishListLink' id="addToWishList{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none" >
			<a href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/AddToWishList" data-role="button" data-mini="true" data-theme='b' data-icon="arrow-r" data-inline="true" data-iconpos="right">
				Add to Wish List
			</a>
		</div>
		{* Access eVideo *}
		<div class='generic-item-background' id="access-eVideo{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Home" title='Access eVideo' alt='Access eVideo'>
				Access eVideo
			</a>
		</div>
		{* Access eAudio *}
		<div class='generic-item-background' id="access-eAudio{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Home" title='Access eAudio' alt='Access eAudio'>
				Access eAudio
			</a>
		</div>
		{* Access eBook *}
		<div class='generic-item-background' id="access-eBook{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Home" title='Access eBook' alt='Access eBook'>
				Access eBook
			</a>
		</div>
		{* Access eMusic *}
		<div class='generic-item-background' id="access-eMusic{$record.docSolr->getMysqlId()|escape:"url"}" style="display:none">
			<a class='generic-item-text' href="{$path}/EcontentRecord/{$record.docSolr->getMysqlId()|escape:"url"}/Home" title='Access eMusic' alt='Access eMusic'>
				Access eMusic
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	addIdToStatusList('{$record.docSolr->getMysqlId()|escape:"javascript"}', {if strcasecmp($source, 'OverDrive') == 0}'OverDrive'{else}'eContent'{/if});
	$(document).ready(function(){literal} { {/literal}
	resultDescription('{$record.docSolr->getMysqlId()}','{$record.docSolr->getMysqlId()}', 'eContent');
	{literal} }); {/literal}
</script>