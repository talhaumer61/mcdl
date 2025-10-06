<?php
class finance {

// get single Challan Detail
	public function get_singlechallan($id) {
	
		$dblms = new dblms();
		$conditions = array ( 
								  'select' 		=> 'ap.*, c.id as challan_id, c.status, c.installment_no, c.challan_type, c.challan_no, 
								                    c.reference_no, c.payable_duedate, c.principal_outstanding, c.date_paid, c.date_due, 
								                    c.bank_name, c.transcation_id, c.payment_type, c.remarks, c.id_applicant, c.id_product, 
								                    p.product_name, a.applicant_name'
                                , 'join' 	    => 'INNER JOIN '.APPLICANTS.' a ON a.applicant_id = c.id_applicant
                                                    INNER JOIN '.APPLICANTS_PRODUCTS.' ap ON ap.id_product = c.id_applicantproduct  AND ap.id_applicant = c.id_applicant
                                                    INNER JOIN '.PRODUCTS.' p ON p.product_id = c.id_applicantproduct '
                                , 'where' 	    => array(
                                                              'c.is_deleted' 	=> 0
                                                            , 'a.is_deleted' 	=> 0
                                                            , 'ap.is_deleted' 	=> 0
                                                            , 'c.id'         => cleanvars($id)
                                                        )
                                , 'return_type' => 'single'
                        );
		$result = $dblms->getRows(CHALLANS.' c', $conditions);
		return $result;
	}
// end single Challan Detail

// get single Challan Particulars
	public function get_challanparticulars($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'p.particular_id, p.particular_name, cd.amount'
                                , 'join' 	    => 'LEFT JOIN '.CHALLAN_DETAILS.' cd ON p.particular_id = cd.id_particular AND cd.id_challan = '.cleanvars($id).''
                                , 'where' 	    => array(
                                                              'p.particular_status' => 1
                                                            , 'p.is_deleted' 	    => 0
                                                        )
                                , 'order_by'    =>  'p.particular_id ASC'
                                , 'return_type' => 'all'
                          );
		$result = $dblms->getRows(CHALLAN_PARTICULARS.' p', $conditions);
		return $result;
	}
// end single Challan Particulars

// get Challan Particulars
	public function get_particulars($status = 1) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'p.particular_id, p.particular_name'
                                , 'where' 	    => array(
                                                              'p.particular_status' => $status
                                                            , 'p.is_deleted' 	    => 0
                                                        )
                                , 'order_by'    =>  'p.particular_id ASC'
                                , 'return_type' => 'all'
                          );
		$result = $dblms->getRows(CHALLAN_PARTICULARS.' p', $conditions);
		return $result;
	}
// end Challan Particulars

// get Single Challan Particular
	public function get_particular($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'particular_id, particular_status, particular_name, particular_detail'
                                , 'where' 	    => array(
                                                              'particular_id' => $id
                                                            , 'is_deleted' 	  => 0
                                                        )
                                , 'return_type' => 'single'
                          );
		$result = $dblms->getRows(CHALLAN_PARTICULARS, $conditions);
		return $result;
	}
// end Single Challan Particular


// get Financing Modes
	public function get_financingmodes($status = 1) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'mode_id, mode_name'
								, 'where' 		=> array (
															    'mode_status' => cleanvars($status)
															  , 'id_deleted'  => 0
														 )
								, 'order_by' 	=> 'mode_name ASC'
								, 'return_type' => 'all'
							);
		$result = $dblms->getRows(FINANCING_MODES, $conditions);
		return $result;
	}
// end get Financing Modes

// get single Financing Mode
	public function get_financingmode($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'mode_id, mode_status, mode_name, mode_details'
								, 'where' 		=> array (
															    'mode_id' => cleanvars($id)
															  , 'id_deleted'  => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(FINANCING_MODES, $conditions);
		return $result;
	}
// end get single Financing Mode
	
// get All Applicants
	public function get_applicantsfinanced($status = 1) {

		$dblms = new dblms();
		$conditions = array (
                                  'select'      => "".APPLICANTS.".applicant_id, ".APPLICANTS.".applicant_refno, ".APPLICANTS.".applicant_name"
                                , 'join' 		=> "INNER JOIN ".APPLICANTS_PRODUCTS." ON ".APPLICANTS_PRODUCTS.".id_applicant =  ".APPLICANTS.".applicant_id 
                                                    INNER JOIN ".PRODUCTS." ON ".PRODUCTS.".product_id =  ".APPLICANTS_PRODUCTS.".id_product
                                                    INNER JOIN ".APPLICANTS_STAGES." ON ".APPLICANTS_STAGES.".id_applicant =  ".APPLICANTS.".applicant_id "
                                , 'where'       => array(
                                                              ''.APPLICANTS.'.is_deleted'       	=> 0
                                                            , ''.APPLICANTS.'.applicant_status' 	=> $status
                                                            , ''.APPLICANTS_STAGES.'.stage' 		=> 8
                                                        )
                                , 'order_by'    => ''.APPLICANTS.'.applicant_id ASC'
                                , 'return_type' => 'all'
                            );
		$result = $dblms->getRows(APPLICANTS, $conditions);
		return $result;
	}
// end get All Applicants


// get single Applicant Product
	public function get_applicantproduct($aid, $pid) {

		$dblms = new dblms();
		$conditions = array (
                                      'select'      => 'ap.*, a.applicant_refno, ch.installment_no, ch.principal_outstanding'
                                    , 'join'        => 'INNER JOIN '.PRODUCTS.' p ON p.product_id = ap.id_product
                                                        INNER JOIN '.APPLICANTS.' a ON a.applicant_id = ap.id_applicant
                                                        LEFT JOIN '.CHALLANS.' ch ON ch.id_applicant = ap.id_applicant AND ch.challan_type = "3" AND ch.is_deleted = "0"'
                                    , 'where'       =>  array(
                                                                      'ap.id_applicant'  => cleanvars($aid)
                                                                    , 'ap.id_product'    => cleanvars($pid)
                                                                    , 'ap.status'        => 1
                                                                    , 'ap.is_deleted'    => 0
                                                            )
                                    , 'order_by'    => 'ch.installment_no DESC'
                                    , 'return_type' => 'single'
                            );
		$result = $dblms->getRows(APPLICANTS_PRODUCTS.' ap', $conditions);
		return $result;
	}
// end get single Applicant Product

}
// end class 