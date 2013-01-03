<script type="text/javascript">
$(document).ready(function() {literal} { {/literal}
  doGetStatusSummaries();
  doGetRatings();
  {if $user}
  doGetSaveStatuses();
  {/if}
{literal} }); {/literal}
</script>

<form id="addForm" action="{$url}/MyResearch/HoldMultiple">
	<div id="addFormContents">
		{foreach from=$recordSet item=record name="recordLoop"}
		  <div class="result {if ($smarty.foreach.recordLoop.iteration % 2) == 0}alt{/if} record{$smarty.foreach.recordLoop.iteration}">
		    {* This is raw HTML -- do not escape it: *}
		    {$record}
		  </div>
		{/foreach}
    
		<input type="hidden" name="type" value="hold" />
		
		{if !$enableBookCart}
		<input type="submit" name="placeHolds" value="Request Selected Items" class="requestSelectedItems"/>
		{/if}
	</div>
</form>

{* Add tracking to strands based on the user search string.  Only track searches that have results. *}
{literal}
<script type="text/javascript">

//This code can actually be used anytime to achieve an "Ajax" submission whenever called
if (typeof StrandsTrack=="undefined"){StrandsTrack=[];}

StrandsTrack.push({
   event:"searched",
   searchstring: "{/literal}{$lookfor|escape:"url"}{literal}"
});

</script>
{/literal}