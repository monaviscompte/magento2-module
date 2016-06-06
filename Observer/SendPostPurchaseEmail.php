<?php

namespace monaviscompte\Widget\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject as Object;
use monaviscompte\Widget\Helper\Data;

class SendPostPurchaseEmail implements ObserverInterface 
{
  protected $_dataHelper;
  
 /**
  * Constructor
  */ 
  public function __construct(Data $dataHelper) {
		$this->_dataHelper = $dataHelper;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) 
  {
		$event = $observer->getEvent();
    $orderIds = $event->getOrderIds();

    $order_id = $orderIds[0];
    
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$order = $objectManager->get('Magento\Sales\Model\Order')->load($order_id);
		$order_information = $order->loadByIncrementId($order->getIncrementId());
		$order_information_data = $order_information->getData();

    $data = array();
		$data['item_id'] = $this->_dataHelper->getItemId();
		$data['private_key'] = $this->_dataHelper->getPrivateKey();
		$data['order_id'] = $order_id;
		$data['source'] = 'magento';
		$data['recipient'] = $order_information_data['customer_email'];
		
		if ($order_information_data['customer_firstname'] != null) {
			$data['first_name'] = $order_information_data['customer_firstname'];
		}
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://www.monaviscompte.fr/api/post-purchase/create/');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10000);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10000);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_exec($curl);
		curl_close($curl);
  }
}