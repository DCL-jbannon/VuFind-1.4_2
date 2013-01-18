<script type="text/javascript" src="{$url}/services/MyResearch/ajax.js"></script>
<script type="text/javascript" src="{$url}/services/EcontentRecord/ajax.js"></script>
{if (isset($title)) }
<script type="text/javascript">
    alert("{$title}");
</script>
{/if}
<div id="page-content" class="content">
  <div id="sidebar">
    {include file="MyResearch/menu.tpl"}
      
    {include file="Admin/menu.tpl"}
  </div>
  
  <div id="main-content">
    {if $user->cat_username}

      {* Display recommendations for the user *}
      {if $showStrands && $user->disableRecommendations == 0}
	      {assign var="scrollerName" value="Recommended"}
				{assign var="wrapperId" value="recommended"}
				{assign var="scrollerVariable" value="recommendedScroller"}
				{assign var="scrollerTitle" value="Recommended for you"}
				{include file=titleScroller.tpl}
			
				<script type="text/javascript">
					var recommendedScroller;
	
					recommendedScroller = new TitleScroller('titleScrollerRecommended', 'Recommended', 'recommended');
					recommendedScroller.loadTitlesFrom('{$url}/Search/AJAX?method=GetListTitles&id=strands:HOME-3&scrollerName=Recommended', false);
				</script>
			{/if}
          
      <div class="myAccountTitle">{translate text='Your Checked Out eContent'}</div>
      {if $userNoticeFile}
        {include file=$userNoticeFile}
      {/if}
      
      {if $transList}
        <div class='sortOptions'>
          {*
          {translate text='Sort by'}
          <select name="sort" id="sort" onchange="changeSort($(this).val());">
          {foreach from=$sortOptions item=sortDesc key=sortVal}
            <option value="{$sortVal}"{if $defaultSortOption == $sortVal} selected="selected"{/if}>{translate text=$sortDesc}</option>
          {/foreach}
          </select> *}
          Hide Covers <input type="checkbox" onclick="$('.imageColumn').toggle();"/>
        </div>
	      
	    {/if}
	    
	    {if count($checkedOut) > 0}
	    	<table class="myAccountTable">
	    	<thead>
	    		<tr><th>Title</th><th>Source</th><th>Out</th><th>Due</th><th>Wait List</th><th>Rating</th><th>Read</th></tr>
	    	</thead><tbody>
		    {foreach from=$checkedOut item=record}
		    	<tr>
	        	<td><a href="{$path}/EcontentRecord/{$record.id}/Home">{$record.title}</a></td>
	        	<td>{$record.source}</td>
	        	<td>{$record.checkoutdate|date_format}</td>
	        	<td>
	        		{$record.duedate|date_format}
	        		{if $record.overdue}
                <span class='overdueLabel'>OVERDUE</span>
              {elseif $record.daysUntilDue == 0}
                <span class='dueSoonLabel'>(Due today)</span>
              {elseif $record.daysUntilDue == 1}
                <span class='dueSoonLabel'>(Due tomorrow)</span>
              {elseif $record.daysUntilDue <= 7}
                <span class='dueSoonLabel'>(Due in {$record.daysUntilDue} days)</span>
              {/if}
	        	</td>
	        	<td>{$record.holdQueueLength}</td>
	        	<td>
	        		<div id ="searchStars{$record.recordId|escape}" class="resultActions">
      				  <div class="rateEContent{$record.id|escape} stat">
      					  <div class="statVal">
      					    <span class="ui-rater">
      					      <span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:0px"></span></span>
      					      (<span class="ui-rater-rateCount-{$record.id|escape} ui-rater-rateCount">0</span>)
      					    </span>
      					  </div>
      				      <div id="saveLink{$record.id|escape}">
      				        {if $showFavorites == 1} 
      				        <a href="{$url}/Record/{$record.id|escape:"url"}/Save" style="padding-left:8px;" onclick="getLightbox('Record', 'Save', '{$record.id|escape}', '', '{translate text='Add to favorites'}', 'Record', 'Save', '{$record.id|escape}'); return false;">{translate text='Add to'} <span class='myListLabel'>MyLIST</span></a>
      				        {/if}
      				        {if $user}
      				        	<div id="lists{$record.id|escape}"></div>
      							<script type="text/javascript">
      							  getSaveStatuses('{$record.id|escape:"javascript"}');
      							</script>
      				        {/if}
      				      </div>
      				    </div>
      				    <script type="text/javascript">
      				      $(
      				         function() {literal} { {/literal}
      				             $('.rateEContent{$record.id|escape}').rater({literal}{ {/literal}module: 'EcontentRecord', recordId: '{$record.id}',  rating:0.0, postHref: '{$url}/Record/{$record.id|escape}/AJAX?method=RateTitle'{literal} } {/literal});
      				         {literal} } {/literal}
      				      );
      				    </script>
      				    
                      {assign var=id value=$record.id}
                      {include file="EcontentRecord/title-review.tpl"}
      				  </div>
                    
        				{if $record.id != -1}
        				<script type="text/javascript">
        				  addRatingId('{$record.id|escape:"javascript"}');
        				</script>
        				{/if}
	        	</td>
	        	<td>
	        		{* Options for the user to view online or download *}
							{foreach from=$record.links item=link}
								<a href="{if $link.url}{$link.url}{else}#{/if}" {if $link.onclick}onclick="{$link.onclick}"{/if} class="button" target='_blank'>{$link.text}</a>
							{/foreach}
	        	</td>
	        </tr>
		    {/foreach}
		    </tbody></table>
	    {else}
	    	<div class='noItems'>You do not have any eContent checked out</div>
	    {/if}
	
	    <h1 class="idclreader-title">iDCL Reader Installation Instructions</h1>    
	    <div class="node">
  			<div class="content">
  			<p>
  				<img alt="iDCL eBook Reader" src="http://douglascountylibraries.org/files/images/idcl114.png" title="iDCL Reader" class="idclreader-leftimg">
  			</p>
			<p>The iDCL Reader is an eReader app that gives you FREE access to ePub and PDF eBooks in and outside the library.</p><p>With this simple-to-use app, reading is now possible wherever, and whenever, you want, without being tethered to a specific product or vendor.  It allows you to read eBooks in ePub and PDF formats, and has support for Adobe DRM, including our <a href="http://douglascountylibraries.org/epublishers">eContent Publishers</a>.  It also allows you to transfer books from different libraries and booksellers between your reading devices and personal computers, has different settings to customize your reading experience, and offers a one-stop Get Books feature to browse and search books from a variety of online sources.</p><p>Enhance your eBook reading experience with the iDCL Reader and share our passion for the freedom and versatility of eReading.</p>
			<div class="ipad ipadFirst">
				<a href="http://douglascountylibraries.org/content/idcl-reader-for-apple">
					<img alt="iDCL Reader for Apple devices" src="http://douglascountylibraries.org/files/images/ipad150.jpg">
				</a>
				<div class="idcltext">
					<a href="http://douglascountylibraries.org/content/idcl-reader-for-apple">How to Install iDCL Reader on an Apple device</a>
				</div>
			</div> 
			<div class="ipad">
				<a href="http://douglascountylibraries.org/content/idcl-reader-for-android">
					<img alt="iDCL Reader for Android devices" src="http://douglascountylibraries.org/files/images/android_tablet150.jpg">
				</a>
				<div class="idcltext">
					<a href="http://douglascountylibraries.org/content/idcl-reader-for-android">How to Install iDCL Reader on an Android device</a>
				</div>
			</div>
			<div class="ipad">
				<a href="#">
					<img alt="iDCL Reader for Android devices" src="/interface/themes/dcl/images/3m.jpg">
				</a>
				<div class="idcltext">
					<a href="http://douglascountylibraries.org/content/3m-cloud-ereader-help">Help downloading to eReaders</a>
				</div>
				<div class="idcltext">
					<a href="http://douglascountylibraries.org/content/3m-cloud-tablet-help">Help downloading to tablets and smartphones</a>
				</div>
			</div>
		</div>
  			<div class="clear-block clear"></div> 
		</div>
	    
  {else}
    You must login to view this information. Click <a href="{$path}/MyResearch/Login">here</a> to login.
  {/if}
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {literal} { {/literal}
		doGetRatings();
	{literal} }); {/literal}
</script>