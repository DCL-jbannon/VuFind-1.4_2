<?php
require_once 'Action.php';
require_once dirname(__FILE__).'/../../../classes/econtentBySource/EcontentDetailsFactory.php';

class RemoveFromWishList extends Action {

	private $user;

	function __construct()
	{
		$this->user = UserAccount::isLoggedIn();
	}

	function launch()
	{
		global $interface;
		global $configArray;

		$id = strip_tags($_GET['id']);
		$interface->assign('id', $id);

		// Check if user is logged in
		if (!$this->user) {
			$interface->assign('recordId', $id);
			$interface->assign('followupModule', 'EContentRecord');
			$interface->assign('followupAction', 'AddToWishList');
			if (isset($_GET['lightbox'])) {
				$interface->assign('title', $_GET['message']);
				$interface->assign('message', 'You must be logged in first');
				return $interface->fetch('AJAX/login.tpl');
			} else {
				$interface->assign('followup', true);
				$interface->setPageTitle('You must be logged in first');
				$interface->assign('subTemplate', '../MyResearch/login.tpl');
				$interface->setTemplate('view-alt.tpl');
				$interface->display('layout.tpl', 'AddToWishList' . $id);
			}
			exit();
		}

		//Add to the wishlist
		require_once 'sys/eContent/EContentWishList.php';
		$wishlistEntry = new EContentWishList();
		$wishlistEntry->userId = $this->user->id;
		$wishlistEntry->recordId = $id;
		$wishlistEntry->status = 'active';
		if ($wishlistEntry->find(true)){
			$wishlistEntry->status = 'deleted';
			$wishlistEntry->update();
		}
		
		$details = EcontentDetailsFactory::getById($id);
		$details->removeWishList($this->user);
		
		header('Location: ' . $configArray['Site']['path'] . '/MyResearch/MyEContentWishList');
	}
}