{if $holdings[0]->samples|@count gt 0}

	<div id='preview'>
		<div class='previewIcons'>
			{foreach from=$holdings[0]->samples item=sample name=test}
				{if $smarty.foreach.test.iteration <=3}
						<a href="{$sample.url}" target='_blank'>
							<img src='/interface/themes/dcl/images/previews/{$smarty.foreach.test.iteration}.png' />					
						</a>
				{/if}
			{/foreach}
		</div>
	</div>
{/if}