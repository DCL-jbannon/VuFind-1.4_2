{if count($holdings) > 0}
	<table>
	<thead>
		<tr>
			<th>Type</th>
			<th>Source</th>
			<th>Usage</th>
			{if $showEContentNotes}<th>Notes</th>{/if}
			{if $showSize}<th>Size</th>{/if}
			<th>&nbsp;</th>
	</thead>
	<tbody>
	{foreach from=$holdings item=eContentItem key=index}
		{if get_class($eContentItem) == 'OverdriveItem'}
					{* Options for the user to view online or download *}
					{foreach from=$eContentItem->links item=link}
						<tr id="itemRow{$index}">
							<td>{translate text=$link.format}</td>
							<td>OverDrive</td>
							<td>{$eContentItem->getUsageNotes()}</td>
							{if $showSize}
								<td>{$eContentItem->getSize()}</td>
							{/if}
							<td>
								<a href="{if $link.url}{$link.url}{else}#{/if}" {if $link.onclick}onclick="{$link.onclick}"{/if} class="button">{$link.text}</a>
							</td>
						</tr>
					{/foreach}
		{else}
			<tr id="itemRow{$eContentItem->id}">
				<td>{translate text=$eContentItem->item_type}</td>
				<td>{$eContentItem->source}</td>
				
				{if isset($eContentItem->usageNotes)}
					<td>{$eContentItem->usageNotes}</td>
				{else}
					<td>{$eContentItem->getUsageNotes()}</td>
				{/if}
				{if $showEContentNotes}<td>{$eContentItem->notes}</td>{/if}
				{if $showSize}
					{if isset($eContentItem->size)}
						<td>{$eContentItem->size}</td>
					{else}
						<td>{if $eContentItem->getSize() !='Unknown'}{$eContentItem->getSize()|file_size}{else}Unknown{/if}</td>
					{/if}
				{/if}
				<td>
					{* Options for the user to view online or download *}
					{foreach from=$eContentItem->links item=link}
						<a href="{if $link.url}{$link.url}{else}#{/if}" {if $link.onclick}onclick="{$link.onclick}"{/if} class="button">{$link.text}</a>
					{/foreach}
					
					{if $eContentItem->showActionButtons}
						{if $user && $user->hasRole('epubAdmin')}
							<a href="#" onclick="return editItem('{$id}', '{$eContentItem->id}')" class="button">Edit</a>
							<a href="#" onclick="return deleteItem('{$id}', '{$eContentItem->id}')" class="button">Delete</a>
						{/if}
					{/if}
				</td>
			</tr>
		{/if}
	{/foreach}
	</tbody>
	</table>
{else}
	No Copies Found
{/if}

{assign var=firstItem value=$holdings.0}
{if $isOverDrive}
	<a href="#" onclick="return addOverDriveRecordToWishList('{$id}')" class="button">Add&nbsp;to&nbsp;Wish&nbsp;List</a>
{/if}

{if $showAddItemButton}
	{if strcasecmp($source, 'OverDrive') != 0 && $user && $user->hasRole('epubAdmin')}
		<a href="#" onclick="return addItem('{$id}');" class="button" target='_blank' >Add Item</a>
	{/if}
{/if}

{if $isOverDrive}
	<div id='overdriveMediaConsoleInfo'>
		<img src="{$path}/images/overdrive.png" width="125" height="42" alt="Powered by Overdrive" class="alignleft"/>
		<p>This title requires the <a href="http://www.overdrive.com/software/omc/">OverDrive&reg; Media Console&trade;</a> to use the title.  
		If you do not already have the OverDrive Media Console, you may download it <a href="http://www.overdrive.com/software/omc/">here</a>.</p>
		<div class="clearer">&nbsp;</div> 
		<p>Need help transferring a title to your device or want to know whether or not your device is compatible with a particular format?
		Click <a href="http://help.overdrive.com">here</a> for more information. 
		</p>
	</div>
{/if}

