<?php 
	require_once dirname(__FILE__).'/functions.php';
?>
<HTML>
	<HEAD>
		<meta http-equiv="refresh" content="30">
		<TITLE>VuFind Monitor Servers & Services</TITLE>
		<style type="text/css">
			body
			{
				background-color:gray; width: 1400px;
				margin: 0 auto;
			}
			p
			{
				color:blue;
			}
			div.main_page
			{
				float:left;
				margin: 5px
			}
			div.main_page img
			{
				width: 690px;
			}
			h1
			{
				text-align: center;
			}
		</style>
	</HEAD>
	<BODY>
	<H1>VuFind DCL Servers/Services Monitoring</H1>
<?php 
	foreach ($validMethods as $method):
?>
		<div class='main_page'>
			<a href = '/monitor/viewDetail.php?method=<?php echo $method; ?>'>
				<IMG src="http://<?php echo $_SERVER['SERVER_NAME'];?>/monitor/generateGraphByMethod.php?method=<?php echo $method;?>&t=<?php echo mktime();?>" alt="<?php echo $trans[$method];?> Response Time">
			</a>
		</div>
<?php 
	endforeach;
?>
		
	</BODY>
</HTML>