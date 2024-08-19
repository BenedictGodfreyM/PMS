<?php

class Notification extends Controller {

	public function __construct(){
		parent::authenticate();//Check if User is Logged In First ???
	}

	public function index(){

		parent::view('index', 'Notifications', [
			'page_include' => 'notifications'
		], 0);

	}

	public function expired(){

		$ExpiredList = parent::model('NotificationModel')->expiredDrugs();

		parent::view('index', 'Notifications - Expired Drugs', [
			'page_include' => 'notify_expiry',
			'header' => 'Expired Drugs',
			'notification_contents' => $ExpiredList
		], 0);

	}

	public function move_to_trash(){

		if(isset($_POST['drug_id'])){
			//Move the selected batches to trash
			parent::model('StockManagementModel')->moveToTrash($_POST['drug_id']);
		}

		self::expired();

	}

	public function nearExpiry(){

		$NearExpiryList = parent::model('NotificationModel')->drugsNearExpiry();

		parent::view('index', 'Notifications - Drugs near Expiry', [
			'page_include' => 'notify_near_expiry',
			'header' => 'Drugs near Expiry',
			'notification_contents' => $NearExpiryList
		], 0);

	}

	public function outOFstock(){

		//Receive a list of Drugs near Expiry
		$OutOFStockList = parent::model('NotificationModel')->outOFstockDrugs();

		parent::view('index', 'Notifications - Out of Stock Drugs', [
			'page_include' => 'notify_outOfStock',
			'header' => 'Out of Stock Drugs',
			'notification_contents' => $OutOFStockList
		], 0);

	}

	public function nearOutOFstock(){

		//Receive a list of Drugs near Expiry
		$NearShortageList = parent::model('NotificationModel')->drugsNearShortage();

		parent::view('index', 'Notifications - Drugs near Shortage', [
			'page_include' => 'notifications',
			'header' => 'Drugs near Shortage',
			'notification_contents' => $NearShortageList
		], 0);

	}

	public function stock_changes_notifications(){

		if(isset($_POST['count']))
		{
			//Receive the number of stock changes made
			parent::model('NotificationModel')->countStockChanges();
		}

		if(isset($_POST['view']))
		{
			//Receive a brief report on stock changes made
			parent::model('NotificationModel')->retrieveStockChanges();
		}

		if(isset($_POST['trash_id']) || isset($_POST['batch_id']))
		{
			//Mark the selected stock records as read
			if(!empty($_POST['trash_id']) || !empty($_POST['batch_id'])){
				parent::model('NotificationModel')->markStockChangesAsRead();
				parent::back();
			}
		}

	}

}

?>
