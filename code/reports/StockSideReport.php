<?php
class StockSideReport extends SS_Report {
	function title() {
		return _t('SideReport.NOSTOCK',"Products out of stock");
	}
	function group() {
		return _t('SideReport.ECOMMERCEGROUP', "ECommerce");
	}
	function sort() {
		return 0;
	}
	function sourceRecords($params = null) {
		return DataObject::get("Product", "\"Product\".\"Stock\" IS NULL OR \"Product\".\"Stock\" <= 0", "\"SiteTree\".\"Title\" ASC");
	}
	function columns() {
		return array(
			"Title" => array(
				"title" => "Title",
				"link" => true,
			)
		);
	}
}
?>
