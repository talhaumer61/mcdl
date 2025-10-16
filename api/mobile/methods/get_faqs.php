<?php
if($data_arr['method_name'] == "get_faqs") {
		$allFaq = array();
		$condition = array ( 
								 'select'   	=>	' DISTINCT question, answer'
								,'where' 		=>	array( 
														 	 'faq_status'   		=> 1
															,'is_deleted'    	=> 0
													)
								,'order_by'		=>	' RAND()'
								,'return_type'	=>	'all'
							); 
		$FAQS = $dblms->getRows(FAQS, $condition);
		foreach ($FAQS AS $key => $val) {
			$faq['question']				= html_entity_decode(html_entity_decode($val['question']));
			$faq['answer']					= html_entity_decode(html_entity_decode($val['answer']));

			array_push($allFaq, $faq);
		}

		$rowjson['faqs']					= $allFaq;
	} 