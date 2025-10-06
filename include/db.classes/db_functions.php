<?php
class main {

// get Item Price
	function get_itemprice($id) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'item_pricesale, item_pricedealership, item_pricepurchase'
								, 'where' 		=> array ( 
															  'item_id' => cleanvars($id)
														 )
								, 'limit' 		=> 1
								, 'return_type' => 'single' 
							); 
		$result = $dblms->getRows(ITEMS, $conditions);
		return $result;
	}
// end get Item Price
	
// get Item avaiable Stock
	function get_itemstock($id) { 
		$dblms = new dblms();
		$conditions 	= array ( 
								'select' 		=> "SUM(CASE WHEN ".STOCK.".stock_type = '1' then ".STOCK.".qty end) as TotalInQTY, 
													SUM(CASE WHEN ".STOCK.".stock_type = '2' then ".STOCK.".qty end) as TotalOutQTY",
								'where' 		=> array ( 
															''.STOCK.'.id_item' 	=> cleanvars($id) 
														 ),
								'return_type' 	=> 'single' 
							); 
    	$result 	= $dblms->getRows(STOCK, $conditions);
		
		return $result;
	}
// end get Item avaiable Stock
	
// get all Item
	function get_items($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'item_id, item_name, item_code'
								, 'where' 		=> array ( 
															  'item_status' => cleanvars($status)
														 )
								, 'order_by' 	=> 'item_code ASC, item_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(ITEMS, $conditions);
		return $result;
	}
// end get all Items
	
// get all Item Categories
	function get_itemcategories($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'cat_id, cat_name'
								, 'where' 		=> array ( 
															  'cat_status' => cleanvars($status)
														 )
								, 'order_by' 	=> 'cat_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(ITEMS_CAT, $conditions);
		return $result;
	}
// end get all categories
	
// get Customer Type
	function get_customertype($id) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'id_type'
								, 'where' 		=> array ( 
															  'customer_id' => cleanvars($id)
														 )
								, 'limit' 		=> 1
								, 'return_type' => 'single' 
							); 
		$result = $dblms->getRows(CUSTOMERS, $conditions);
		return $result;
	}
// end get all Customers

// get all Customers
	function get_customers($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'customer_id, customer_name, customer_code, id_brand'
								, 'where' 		=> array ( 
															  'customer_status' => cleanvars($status)
														 )
								, 'order_by' 	=> 'customer_code ASC, customer_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(CUSTOMERS, $conditions);
		return $result;
	}
// end get all Customers

// get all Vendors
	function get_vendors($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'vendor_id, vendor_name, vendor_cellno'
								, 'where' 		=> array ( 
															  'vendor_status' => cleanvars($status)
														 )
								, 'order_by' 	=> 'vendor_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(VENDORS, $conditions);
		return $result;
	}
// end get all Vendors
	
// get all Cities
	function get_cities($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'city_id, city_name, city_code'
								, 'where' 		=> array ( 
															  'city_status'  => cleanvars($status) 
														 )
								, 'order_by' 	=> 'city_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(CITIES, $conditions);
		return $result;
	}
// end get all Cities

// get all Companies
	function get_companies($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'company_id, company_name, company_code'
								, 'where' 		=> array ( 
															  'company_status'  => cleanvars($status) 
														 )
								, 'order_by' 	=> 'company_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(COMPANY, $conditions);
		return $result;
	}
// end get all Companies

// get all Ades
	function get_ades($status = 1) { 
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'ade_id, ade_name, ade_cellno'
								, 'where' 		=> array ( 
															  ''
														 )
								, 'order_by' 	=> 'ade_name ASC'
								, 'return_type' => 'all' 
							); 
		$result = $dblms->getRows(ADES, $conditions);
		return $result;
	}
// end get all Ades
	
}
// end class 