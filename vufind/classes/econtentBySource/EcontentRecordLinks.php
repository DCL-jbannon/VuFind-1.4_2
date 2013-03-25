<?php
require_once dirname(__FILE__).'/BaseEcontentRecordHelpers.php';

class EcontentRecordLinks extends BaseEcontentRecordHelpers
{
	
	public function getLinksItemChekedOut(IUser $user, $returnLink = true)
	{
		$urls = $this->econtentRecordDetails->getAccessUrls($user);
		
		$i = 0;
		if(is_string($urls))
		{
			$links[$i]['url'] = $urls;
			$links[$i]['text'] = "Access eContent";
			$i++;
		}
		elseif(is_array($urls))
		{
			foreach ($urls as $url)
			{
				if(is_array($url))
				{
					$links[$i]['url'] = $url['link'];
					$links[$i]['text'] = $url['label'];
				}
				else
				{
					$links[$i]['url'] = $url;
					$links[$i]['text'] = "Access eContent";
				}
				
				$i++;
			}
		}
		
		if($returnLink)
		{
			$links[$i]['url'] = "#None";
			$links[$i]['text'] = "Return Now";
			$links[$i]['onclick'] = "if (confirm('Are you sure you want to return this title?')){returnEpub('/EcontentRecord/".$this->econtentRecordDetails->getRecordId()."/ReturnTitle')};return false;";
		}
		
		return $links;
	}
	
	public function getCancelHoldsLinks($patronId = NULL)
	{
		$urls = $this->econtentRecordDetails->getCancelHoldUrls($patronId);
		$links[] = array(
				'text' => 'Cancel&nbsp;Hold',
				'onclick' => "if (confirm('Are you sure you want to cancel this title?')){cancelEContentHold('".$urls."')};return false;",
		);
		return $links;
	}
	
	public function getLinksAvailableHolds($patronId = NULL)
	{
		if ($this->econtentRecordDetails->showCancelHoldLinkAvailableHolds())
		{
			$cancelUrls = $this->econtentRecordDetails->getCancelHoldUrls($patronId);
			$links[] = array(
					'text' => 'Cancel&nbsp;Hold',
					'onclick' => "if (confirm('Are you sure you want to cancel this title?')){cancelEContentHold('".$cancelUrls."')};return false;"
			);
		}
		
		$checkOutUrls = $this->econtentRecordDetails->getCheckOutUrls($patronId);
		$links[] = array(
				'text' => 'Check Out',
				'url' => $checkOutUrls
		);
		return $links;
	}
	
}
?>