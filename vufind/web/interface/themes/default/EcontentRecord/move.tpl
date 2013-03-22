<script type="text/javascript" src="{$path}/js/econtent.js" ></script>
<div id="page-content" class="content">
  <div id="sidebar">
    {include file="MyResearch/menu.tpl"}
    
    {include file="Admin/menu.tpl"}
  </div>
  
  <div id="main-content">
    <h1>Move related information from a Brief Econtent Record to another non Brief Econtent Record</h1>
    
    {if $error}
	    <p class='error'>
			  {$error}  	
	    </p>
	{/if}
    <p>You are about to replace the information of:</p>
    <ul>
    	<li>Econtent User History</li>
    	<li>Econtent User Holds</li>
    	<li>Econtent User CheckOuts History</li>
    </ul>
    <p>from this Brief Econtent Record to another Econtent Record</p>
    <p>
    	<form id='moveRelatedInformation' name='moveRelatedInformation' action="{$path}/EcontentRecord/{$id|escape:"url"}/Move" method="post">
    		<p class='resultInformationLabel'>
    			Move from <strong>{$id}</strong> to <input type='text' id='toId' name='toId' />
    		</p>
    		<input type='button' value='Submit' onclick='finalQuestion();'/>
    	</form>
    </p>
  </div>
</div>

<script type="text/javascript">
	{literal}
	function finalQuestion()
	{
		var toId = $("#toId").val();
		if(toId == "")
		{
			alert("Please, insert the Econtent Record Id you want to move to");
			return false;
		}
		var text = 'Are you that you want to move from {/literal}{$id}{literal} to ' + $("#toId").val();
		if(confirm(text))
		{
			document.moveRelatedInformation.submit();
		}
	}
	{/literal}
</script>