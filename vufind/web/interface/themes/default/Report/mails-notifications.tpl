<script type="text/javascript">
{literal}
$(function() {
		$( "#dateFilterStart" ).datepicker(
		{	
			maxDate: "+0D",
			numberOfMonths: 2,
			onClose: function( selectedDate )
					 {
						$( "#dateFilterEnd" ).datepicker( "option", "minDate", selectedDate );
					 }
		});
	});
$(function() {
	$( "#dateFilterEnd" ).datepicker({numberOfMonths: 2, maxDate: "+0D"});
});
{/literal}
</script>
<div id="page-content" class="content">
	<div id="sidebar">
		{include file="MyResearch/menu.tpl"}
		{include file="Admin/menu.tpl"}
	</div>
	
	<div id="main-content">
		{if $user}
			<div class="myAccountTitle">
				<h1>Reports - Mail Notifications</h1>
			</div>		
			<div id="filterContainers">
				<form method="POST" action="" id="reportMailNotifications" class="search">
					<div id="filterLeftColumna">
						{if $msgError neq ''}
							<span style='color:#FF0000;font-weight:bold;'>{$msgError}</span><br/><br/>
						{/if}
						Please, select a range of dates to see the Mail Notifications Statistics<br/><br/>
						<div id="startDate">
							Start Date: 
							<input id="dateFilterStart" name="dateFilterStart" value="{$startDate}" />
						</div>
						<div id="endDate">
							End Date:&nbsp; 
							<input id="dateFilterEnd" name="dateFilterEnd" value="{$endDate}" />
						</div>
						
						<input type="submit" id="filterSubmit" value="Go">
					</div>
				</form>
			</div>
			<div id="reportSorting">
				<br/>
				Total Mail Sent: {$totalMailSent}<br/>
				Total Clicks: {$totalClicks}<br/>
				Total Opens: {$totalOpen}<br/>
				Total Reviews: {$totalReview}<br/>
				Total Rates: {$totalRate}<br/>
			</div>
			<div id="reportTable">
				<h1>Econtent List</h1>
				<table class='myAccountTable'>
					<thead>
						<tr>
							<th>Title</th>
							<th># Opens</th>
							<th># Clicks</th>
							<th># Rates</th>
							<th># Reviews</th>
						</tr>
					</thead>
					<tbody>
					{foreach from=$tableEcontent item=econtent}
						<tr>
							<td>
								<a href='/EcontentRecord/{$econtent.entity->id}'>{$econtent.entity->getTitle()}</a>
							</td>
							<td>{$econtent.open}</td>
							<td>{$econtent.click}</td>
							<td>{$econtent.rate}</td>
							<td>{$econtent.review}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
			</div>
					
		{/if}
	</div>
</div>