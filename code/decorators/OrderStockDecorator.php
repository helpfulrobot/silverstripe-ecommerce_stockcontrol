<?php

class OrderStockDecorator extends DataObjectDecorator{
	
	
	/**
	 * This will update the stock levels when customer chooses to "place order and make payment" on the checkout page.
	 */
	function onSave(){
		
		if($items = $this->owner->Items()){
			foreach($items as $item){
				if($item instanceof ProductVariation_OrderItem)
					$item->ProductVariation()->decrementStock($item->Quantity);
				else
					$item->Product()->decrementStock($item->Quantity);				
			}
		}
	}
	
}

?>