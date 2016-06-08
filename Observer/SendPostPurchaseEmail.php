<?php

namespace monaviscompte\Widget\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\DataObject as Object;
use monaviscompte\Widget\Helper\Data;

class SendPostPurchaseEmail implements ObserverInterface {

  protected $_dataHelper;
  
 /**
  * Constructor
  */ 
  public function __construct(Data $dataHelper) {
	$this->_dataHelper = $dataHelper;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
	$event = $observer->getEvent();
    $orderIds = $event->getOrderIds();

    $order_id = $orderIds[0];
    
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$order = $objectManager->get('Magento\Sales\Model\Order')->load($order_id);
	$order_information = $order->loadByIncrementId($order->getIncrementId());
	$order_information_data = $order_information->getData();

    $data = array();
	$data['private_key'] = $this->_dataHelper->getPrivateKey();
	$data['order_id'] = $order_id;
	$data['source'] = 'magento';
	$data['recipient'] = $order_information_data['customer_email'];
	
	if ($order_information_data['customer_firstname'] != null) {
		$data['first_name'] = $order_information_data['customer_firstname'];
	}
	
	$serializedProducts = array();

	$i = 0;
	foreach ($order->getAllVisibleItems() as $item) {
		$_product_id = $item->getProductId();
		$_product = $objectManager->get('Magento\Catalog\Model\Product')->load($_product_id);
		$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
		$_image_url = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $_product->getImage();
		$serializedProducts['products'][$i] = array(
			"id" => strval($_product_id),
			"name" => $_product->getName(),
			"summary" => $_product->getShortDescription(),
			"picture" => $_image_url
		);
		$i++;
	}
	
	$data['cart'] = json_encode($serializedProducts);
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api.monaviscompte.fr/post-purchase/create/');
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