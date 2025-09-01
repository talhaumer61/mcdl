<?php
if($data_arr['method_name'] == "get_faqs") {
		$allFaq = array();
		$condition = array ( 
								 'select'   	=>	' DISTINCT q.question, q.answer'
								,'where' 		=>	array( 
														 	 'q.status'   		=> 1
															,'q.is_deleted'    	=> 0
													)
								,'order_by'		=>	' RAND() LIMIT 5'
								,'return_type'	=>	'all'
							); 
		$COURSES_FAQS = $dblms->getRows(COURSES_FAQS.' AS q', $condition);
		foreach ($COURSES_FAQS AS $key => $val) {
			$faq['question']				= html_entity_decode(html_entity_decode($val['question']));
			$faq['answer']					= html_entity_decode(html_entity_decode($val['answer']));

			array_push($allFaq, $faq);
		}

		$rowjson['faqs']					= $allFaq;
	} 