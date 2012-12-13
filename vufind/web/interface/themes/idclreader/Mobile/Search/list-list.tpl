<script type="text/javascript">
$(document).ready(function() {literal} { {/literal}
  doGetStatusSummaries();
  //doGetRatings();
  {if $user}
  	doGetSaveStatuses();
  {/if}
{literal} }); {/literal}
</script>

<ul id='resultSearch' data-role="listview" >
	{if $prevStart !== false}
		<li data-iconpos='left' data-icon='arrow-l'>
			<a href='/Mobile/SearchStore?lookfor={$query}&basicType=Keyword&start={$prevStart}'>
				&laquo; Prev
			</a>
		</li>
	{/if}
	{foreach from=$recordSet item=record name="recordLoop"}
		<li>{include file=Mobile/Search/result.tpl}</li>
	{/foreach}
	{if $nextStart !== false}
		<li>
			<a href='/Mobile/SearchStore?lookfor={$query}&basicType=Keyword&start={$nextStart}'>
				Next &raquo;
			</a>
		</li>
	{/if}
</ul>