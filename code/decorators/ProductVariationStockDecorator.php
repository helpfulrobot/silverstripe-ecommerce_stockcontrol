<?php

class ProductVariationStockDecorator extends DataObjectDecorator{
	
	function extraStatics(){
		
		ProductVariation::$summary_fields['Stock'] = 'Stock'; //adds 'stock' to variation table
		
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
		if( Product::$alwaysAllowPurchase )
			return true;
		
		if($this->owner->Stock <= 0){
			 return false;
		}
		return null; //returning null ensures that can checks continue
	}
	
	function decrementStock($qty = 1,$write = true){
		$this->owner->Stock = $this->owner->Stock - $qty;
		if($this->owner->Stock < 0)
			$this->owner->Stock = 0;
			
		//save new stock level
		if($write){
			$this->owner->writeToStage('Stage');
		}
	}
	
	function StockIndicator(){
		return $this->owner->Product()->StockIndicator($this->owner->Stock);
	}
	
}