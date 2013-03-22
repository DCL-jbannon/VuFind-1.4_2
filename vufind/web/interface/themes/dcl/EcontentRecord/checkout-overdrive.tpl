<div id='page-content'>
	<h2>Check Out OverDrive Title.</h2>
	<div id="image-column" style='float:left;'>		 
	
		<div id="recordcover">  
			<div class="recordcoverWrapper">
				<a href="/EcontentRecord/{$econtentRecordId}">              
					<img src="{$bookCoverUrl}" class="recordcover" alt="Book Cover">
				</a>
			</div>
		</div>
	</div>
	<div class='format-overDrive'>
		<div class="myAccountTitle"> Choose the format for '{$title}':</div>
		<ul>
			{foreach from=$formats item=format}
				{if $format.id neq -1}
					<li style='height:35px'>
						<a class='button' href='/EcontentRecord/{$econtentRecordId}/{$action}?formatId={$format.id}'>
							{if $action eq "Checkout"}
								Check out as {$format.name}
							{else}
								Choose this format ({$format.name}) to access to the eContent
							{/if}
						</a>
					</li>
				{/if}
			{/foreach}		
		</ul>
	</div>
	<div style='clear:both;'></div>
	
</div>