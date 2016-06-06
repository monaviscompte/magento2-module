<?php

namespace monaviscompte\Widget\Block;

use Magento\Framework\View\Element\Template\Context;
use monaviscompte\Widget\Helper\Data;

class Widget extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface {
 
 protected $_dataHelper;

 /**
  * Constructor
  */ 
  public function __construct(Context $context, Data $dataHelper, array $data = []) 
  {
		parent::__construct($context, $data);
		$this->_dataHelper = $dataHelper;
  }
 
 /**
  * Produces the HTML code of a widget
  *
  * @return string
  */
  protected function _toHtml() 
  {
  	$itemId = $this->_dataHelper->getItemId();
  	$accessKey = $this->_dataHelper->getAccessKey();
  	
  	if ($itemId != null && $accessKey != null ) {	
  		return '<div id="widget-' . $itemId .'"><script type="text/javascript" src="https://www.monaviscompte.fr/widget?id=' . $itemId . '&public_key=' . $accessKey . '&div=widget-' . $itemId . '"></script></div>';
  	}
  }
}