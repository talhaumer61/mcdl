<?php
class settings {

// Get All Regions
	public function get_regions($srch = '') {

		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'region_id, region_name, region_ordering, region_codedigit, 
								                    region_codealpha, id_parentregion, region_status'
								, 'where' 		=> array ( 
															   'is_deleted' => 0
														 )
								, 'search_by' 	=> $srch
								, 'order_by' 	=> "region_name ASC"
								, 'return_type' => 'all'
							); 
		$result = $dblms->getRows(REGIONS, $conditions);
		return $result;
	}
// end All Regions

// Get Single Region
	public function get_region($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'region_id, region_name, region_ordering, region_codedigit, 
								                    region_codealpha, id_parentregion, region_status'
								, 'where' 		=> array (
															    'is_deleted' => 0
															  , 'region_id'  => $id
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(REGIONS, $conditions);
		return $result;
	}
// end Single Region

// Get All Currencies
	public function get_currencies($srch = '') {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'currency_id, currency_name,currency_code, currency_ordering, 
								                    currency_symbol, currency_position, currency_fractionalunits, currency_status'
								, 'where' 		=> array (
															   'is_deleted'      => 0
														 )
								, 'search_by' 	=> $srch
								, 'order_by' 	=> "currency_ordering ASC"
								, 'return_type' => 'all'
							);
		$result = $dblms->getRows(CURRENCIES, $conditions);
		return $result;
	}
// end All Currencies

// Get Single currency
	public function get_currency($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'currency_id, currency_name,currency_code, currency_ordering, 
								                    currency_symbol, currency_position, currency_fractionalunits, currency_status'
								, 'where' 		=> array (
															     'currency_id'  => cleanvars($id)
															   , 'is_deleted'       => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(CURRENCIES, $conditions);
		return $result;
	}
// end Single currency

// Get Single Coupon
	public function get_coupon($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> '*'
								, 'where' 		=> array (
															     'cpn_id'       => cleanvars($id)
															   , 'is_deleted'   => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(COUPONS, $conditions);
		return $result;
	}
// end Single Coupon

// Get All Country
	public function get_allcountry($srch = '') {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'country_id, country_name, country_ordering, country_iso2digit, 
								                    country_iso3digit,country_callingcode,country_latitude,country_longitude,
								                    id_timezone,id_currency, id_region, country_status'
								, 'where' 		=> array (
															     'is_deleted'  => 0
														 )
                                , 'search_by' 	=> $srch
                                , 'return_type' => 'all'
							);
		$result = $dblms->getRows(COUNTRIES, $conditions);
		return $result;
	}
// end Single Country

// Get Single Country
	public function get_country($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'country_id, country_name, country_ordering, country_iso2digit, 
								                    country_iso3digit,country_callingcode,country_latitude,country_longitude,
								                    id_timezone,id_currency, id_region, country_status'
								, 'where' 		=> array (
															     'country_id'  => cleanvars($id)
															   , 'is_deleted'  => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(COUNTRIES, $conditions);
		return $result;
	}
// end Single Country

// Get Single City
	public function get_city($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'city_id, id_substate, id_state, city_ordering, id_country, city_name, 
								                    city_codedigit, city_codealpha, city_latitude, city_longitude, city_status'
								, 'where' 		=> array (
															     'city_id'    => cleanvars($id)
															   , 'is_deleted' => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(CITIES, $conditions);
		return $result;
	}
// end Single City

// Get Single Sub State
	public function get_substate($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'substate_id, substate_name, substate_ordering, substate_latitude, 
								                    substate_longitude, id_state, id_country, substate_status'
								, 'where' 		=> array (
															     'substate_id' => cleanvars($id)
															   , 'is_deleted'  => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(SUB_STATES, $conditions);
		return $result;
	}
// end Single sub State

// Get all Sub States
	public function get_substates($srch = '') {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'substate_id, substate_name, substate_ordering, substate_latitude, 
								                    substate_longitude, id_state, id_country, substate_status'
								, 'where' 		=> array (
                                                            'is_deleted'  => 0
														 )
                                , 'search_by' 	=> $srch
								, 'order_by'    => 'substate_name ASC'
								, 'return_type' => 'all'
							);
		$result = $dblms->getRows(SUB_STATES, $conditions);
		return $result;
	}
// end all Sub States

// Get Single State
    public function get_state($id) {

        $dblms = new dblms();
        $conditions = array (
                                      'select' 		=> 'state_id, state_status, state_ordering, state_name, state_codedigit, 
                                                        state_codealpha, state_latitude, state_longitude, id_country'
                                    , 'where' 		=> array (
                                                                  'state_id'    => cleanvars($id)
                                                                , 'is_deleted'  => 0
                                                             )
                                    , 'return_type' => 'single'
                            );
        $result = $dblms->getRows(STATES, $conditions);
        return $result;
    }
// end Single State

// Get all States
	public function get_states($srch = '') {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'state_id, state_status, state_ordering, state_name, state_codedigit, 
								                    state_codealpha, state_latitude, state_longitude, id_country'
								, 'where' 		=> array (
                                                            'is_deleted'  => 0
														 )
                                , 'search_by' 	=> $srch
								, 'order_by'    => 'state_name ASC'
								, 'return_type' => 'all'
							);
		$result = $dblms->getRows(STATES, $conditions);
		return $result;
	}
// end all States

}
// end class 