<?php

namespace monaviscompte\Widget\Block;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use monaviscompte\Widget\Helper\Data;

class Widget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface {
 
 protected $_dataHelper;
 protected $_registry;

 /**
  * Constructor
  */ 
  public function __construct(Context $context, Data $dataHelper, Registry $registry, array $data = []) {
	parent::__construct($context, $data);
	$this->_dataHelper = $dataHelper;
	$this->_registry = $registry;
  }
 
 /**
  * Produce the HTML code of a widget
  *
  * @return string
  */
  protected function _toHtml() 
  {
  	$itemId = $this->_dataHelper->getItemId();
  	$accessKey = $this->_dataHelper->getAccessKey();
  	
  	$_product = $this->_registry->registry('product');
  	
	if ($_product != null && $accessKey != null) {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
		$_image_url = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $_product->getImage();
	
		return '<div id="widget-' . $_product->getId() . '"><script type="text/javascript" src="https://www.monaviscompte.fr/widget?internal_id=' . $_product->getId() . '&public_key=' . $accessKey . '&div=widget-' . $_product->getId() . '&title=' . $_product->getName() . '&summary=' . strip_tags($_product->getShortDescription()) . '&picture=' . $_image_url . '"></script></div>';
	}
	
  	if ($itemId != null && $accessKey != null) {
  		return '<div id="widget-' . $itemId .'"><script type="text/javascript" src="https://www.monaviscompte.fr/widget?id=' . $itemId . '&public_key=' . $accessKey . '&div=widget-' . $itemId . '"></script></div>';
  	}
  }
}