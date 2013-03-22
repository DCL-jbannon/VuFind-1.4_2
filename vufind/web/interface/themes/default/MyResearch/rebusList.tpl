<div id="page-content" class="content">
	<div id="sidebar">
		{include file="MyResearch/menu.tpl"}
		{include file="Admin/menu.tpl"}
	</div>
	<div id="main-content">
		<h1>Rebus:List</h1>
		<iframe src='{$rebusListUrl}/?username={$user->getBarcode()}&password={$user->password}&siteId={$siteId}' width='945px' height='1000px'></iframe>
	</div>
</div>