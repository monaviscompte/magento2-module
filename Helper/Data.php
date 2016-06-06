<?php

namespace monaviscompte\Widget\Helper;
 
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	const MAC_ITEM_ID = 'mac_block_config/mac_custom_block/mac_item_id';
	const MAC_ACCESS_KEY = 'mac_block_config/mac_custom_block/mac_access_key';
	const MAC_PRIVATE_KEY = 'mac_block_config/mac_custom_block/mac_private_key';
 
	public function __construct(\Magento\Framework\App\Helper\Context $context) {
  	parent::__construct($context);
	}
 
	public function getItemId()
	{
		return $this->scopeConfig->getValue(self::MAC_ITEM_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getAccessKey()
	{
		return $this->scopeConfig->getValue(self::MAC_ACCESS_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
    
	public function getPrivateKey()
	{
		return $this->scopeConfig->getValue(self::MAC_PRIVATE_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}
}