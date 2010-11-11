<?php

/**
 * ProductStockDecorator
 * Extension of Product for storing current stock level.
 */

class ProductStockDecorator extends DataObjectDecorator{
	
	public static $alwaysAllowPurchase = false;
	
	public static $stockLevelIndicators = array(
		0 => "none",
		10 => "limited",
		1000 => "many"	
	);
	
	function extraStatics(){
		return array(
			'db' => array(
				'Stock' => 'Int'
			)
		);
	}

	/*
	 * Allow setting stock level in CMS
	 */
	function updateCMSFields(&$fields){
		
		if(!$this->owner->Variations()->exists()){ 
			$fields->addFieldToTab('Root.Content.Main',new NumericField('Stock','Stock'),'Content');
		}
	}
	
	/**
	 * Only allow purchase if stock levels allow
	 */
	function canPurchase(){
		if( self::$alwaysAllowPurchase )
			return true;
			
		if($this->VariationStock()){
			return true;
		}elseif($this->owner->Stock <= 0){
			return false;
		}
		return null; //returning null ensures that can checks continue
	}
	
	function decrementStock($qty = 1,$write = true){
		$this->owner->Stock = $this->owner->Stock - $qty;
		if($this->owner->Stock < 0 && !self::$alwaysAllowPurchase )
			$this->owner->Stock = 0;
			
		//save & publish new stock level
		if($write){
			$this->owner->writeToStage('Stage');
			$this->owner->publish('Stage','Live');
		}
	}
	
	/**
	 * Get the stock level for this product's variations;
	 */
	 //TODO: could replace this with a SUM query
	function VariationStock(){
		$stock = 0;
		if($vars = $this->owner->Variations()){
			foreach($vars as $var){
				$stock += $var->Stock;
			}
		}
		return $stock;
	}

	function StockIndicator($level = null){
		$level = is_numeric($level) ? $level : $this->owner->Stock;
		$last = null;
		foreach(self::$stockLevelIndicators as $key => $value)
		{
			$last = $value;
			if($level <= $key)
				return $value;
		} 
		return $last;	
	}
}