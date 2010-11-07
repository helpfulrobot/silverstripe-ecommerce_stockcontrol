<?php

class ProductVariationStockDecorator extends DataObjectDecorator{
	
	function extraStatics(){
		
		ProductVariation::$summary_fields['Stock'] = 'Stock';
		
		return array(
			'db' => array(
				'Stock' => 'Int'
			)
		);
	}
	
	function updateCMSFields(&$fields){
		$fields->push(new NumericField('Stock','Stock'));
	}
	
	/**
	 * Only allow purchase if stock levels allow
	 */
	function canPurchase(){
		//	TODO: customise this to a certian stock level, on, or off
		if($this->owner->Stock <= 0){
			 return false;
		}
		return null; //returning null ensures that can checks continue
	}
	
	
}