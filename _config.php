<?php

MinMaxModifier::set_use_stock_quantities(true); //make use of the stock quantity tables to keep track of them

DataObject::add_extension('Product', 'ProductStockDecorator');
DataObject::add_extension('ProductVariation', 'ProductVariationStockDecorator');
DataObject::add_extension('Order','OrderStockDecorator');

Order::set_modifiers(array(
	'MinMaxModifier'
));

SS_Report::register("SideReport", "StockSideReport");