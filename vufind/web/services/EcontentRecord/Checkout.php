<?php
require_once 'Action.php';
require_once 'Drivers/EContentDriver.php';
require_once dirname(__FILE__).'/../../../classes/econtentBySource/EcontentDetailsFactory.php';
require_once dirname(__FILE__).'/../../../classes/Utils/BookCoverURL.php';

class Checkout extends Action
{
	
	protected $chooseFormat = false;
	
	public function launch()
	{
		global $interface;
		global $configArray;
		global $user;
	
		$driver = new EContentDriver();
		$id = strip_tags($_REQUEST['id']);
		$interface->assign('id', $id);
		
		$logger = new Logger();
				
		if (isset($_POST['submit']) || $user) 
		{
			if (isset($_REQUEST['username']) && isset($_REQUEST['password']))
			{
				$user = UserAccount::login();
			}

			if (!PEAR::isError($user) && $user)
			{
				
				$econtentRecord = new EContentRecord();
				$econtentRecord->id = $id;
				if($econtentRecord->find(true))
				{
					$formatId = RequestUtils::getGet("formatId");
					if($econtentRecord->isOverDrive() && $formatId == '' )
					{
						$bookCoverUrl = new BookcoverURL();
						
						$econtentDetails = EcontentDetailsFactory::get($econtentRecord);
						$formats = $econtentDetails->getFormatsInfo();
						$interface->assign("action", ($this->chooseFormat ? "CFormat" : "Checkout"));
						$interface->assign("title", $econtentRecord->getTitle());
						$interface->assign("formats", $formats);
						$interface->assign("econtentRecordId", $econtentRecord->id);
						$interface->assign("bookCoverUrl", $bookCoverUrl->getBookCoverUrl('large', $econtentRecord->getISSN(), $econtentRecord->id, true));
						
						//Var for the IDCLREADER TEMPLATE
						$interface->assign('ButtonBack',true);
						$interface->assign('ButtonHome',true);
						$interface->assign('MobileTitle','&nbsp;');
						
						$interface->setTemplate('checkout-overdrive.tpl');
						$interface->display('layout.tpl', 'RecordHold' . $_GET['id']);
						return true;
					}
					else
					{
						if(!$this->chooseFormat)
						{
							$return = $driver->checkoutRecord($econtentRecord, $user, $formatId);
							$interface->assign('result', $return['result']);
							$message = $return['message'];
							$interface->assign('message', $message);
							$showMessage = true;
						}
						else
						{
							$details = EcontentDetailsFactory::get($econtentRecord);
							$details->chooseFormat($user, $formatId);
							$interface->assign('result', true);
							$interface->assign('message', "The item has been checked out.");
							$showMessage = true;
							$return = "";
						}
					}
				}
			} 
			else
			{
				$message = 'Incorrect Patron Information';
				$interface->assign('message', $message);
				$interface->assign('focusElementId', 'username');
				$showMessage = true;
			}
		}
		else
		{
			if (isset($_SERVER['HTTP_REFERER']))
			{
				$referer = $_SERVER['HTTP_REFERER'];
				$_SESSION['checkout_referrer'] = $referer;
			}

			//Showing checkout form.
			if (!PEAR::isError($user) && $user)
			{
				//set focus to the submit button if the user is logged in since the campus will be correct most of the time.
				$interface->assign('focusElementId', 'submit');
			}
			else
			{
				//set focus to the username field by default.
				$interface->assign('focusElementId', 'username');
			}

		}
		
		if (isset($return) && $showMessage)
		{
			$hold_message_data = array(
              'successful' => $return['result'] ? 'all' : 'none',
              'error' => isset($return['error']) ? $return['error'] : null,
              'titles' => array(
			$return,
			),
			);
			
			$_SESSION['checkout_message'] = $hold_message_data;
			if (isset($_SESSION['checkout_referrer']))
			{
				$logger->log('Checkout Referrer is set, redirecting to there.  type = ' . $_REQUEST['type'], PEAR_LOG_INFO);

				header("Location: " . $_SESSION['checkout_referrer']);
				unset($_SESSION['checkout_referrer']);
				if (isset($_SESSION['autologout'])){
					unset($_SESSION['autologout']);
					UserAccount::softLogout();
				}
			}
			else
			{
				$logger->log('No referrer set, but there is a message to show, go to the main eContent page', PEAR_LOG_INFO);
				header("Location: " . $configArray['Site']['url'] . '/MyResearch/EContentCheckedOut');
			}
		}
		else
		{
			//Var for the IDCLREADER TEMPLATE
			$interface->assign('ButtonBack',true);
			$interface->assign('ButtonHome',true);
			$interface->assign('MobileTitle','Login to your account');
			
			
			$logger->log('eContent checkout finished, do not need to show a message', PEAR_LOG_INFO);
			$interface->setPageTitle('Checkout Item');
			$interface->assign('subTemplate', 'checkout.tpl');
			$interface->setTemplate('checkout.tpl');
			$interface->display('layout.tpl', 'RecordHold' . $_GET['id']);
		}
	}
}