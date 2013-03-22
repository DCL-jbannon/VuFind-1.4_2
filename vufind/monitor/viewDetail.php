<?php 
	require_once dirname(__FILE__).'/functions.php';
	$agos[] = array("1", "Last Hour");
	$agos[] = array("3", "Last 3 Hours");
	$agos[] = array("6", "Last 6 Hours");
	$agos[] = array("12", "Last 12 Hours");
	$agos[] = array("24", "Last 24 Hours");
	$agos[] = array("48", "Last 48 Hours");
	$agos[] = array("168", "Last Week"); //1 week
	$agos[] = array("599", "Last Month"); //almost 1 moth
	$agos[] = array("4320", "Last 6 Months"); //6 month
	$agos[] = array("8640", "Last Year"); //1 year
?>
<HTML>
	<HEAD>
		<meta http-equiv="refresh" content="600">
		<TITLE>VuFind Monitor Servers & Services - Detail Page for <?php echo $_GET['method'];?></TITLE>
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
			div.main_page div.title
			{
				color: #FFF;
				text-align: center;
				text-transform: uppercase;
				font-size: 2em;
			}
			a.back
			{
				color:#FFF;
				font-size: 1.3em;
				text-decoration: none;
			}
			
			a:link {background-color:transparent;}
			a:visited {background-color:transparent;}
			a:hover {background-color:transparent;}
			a:active {background-color:transparent;} 
		</style>
	</HEAD>
	<BODY>
	<H1><?php echo $_GET['method'];?> - VuFind DCL Servers/Services Monitoring</H1>
	<a class='back' href='/monitor'><< Back</a>
	<br/>
<?php 
	$method = $_GET['method'];
	foreach ($agos as $ago):
?>
		<div class='main_page'>
			<div class='title'>
				<?php echo $ago[1]; ?>
			</div>
			<IMG src="http://<?php echo $_SERVER['SERVER_NAME'];?>/monitor/generateGraphByMethod.php?method=<?php echo $method;?>&ago=<?php echo $ago[0]; ?>&t=<?php echo mktime();?>" alt="<?php echo $trans[$method];?> Response Time">
		</div>
<?php 
	endforeach;
?>
		
	</BODY>
</HTML>