<?php
class blogs {


// Get Single blog
	public function get_blogs($id) {

		$dblms = new dblms();
		$conditions = array (
								  'select' 		=> 'blog_id, blog_status, blog_name, blog_tags, blog_photo, blog_date, blog_description'
								, 'where' 		=> array (
															     'blog_id'      => cleanvars($id)
															   , 'is_deleted'   => 0
														 )
								, 'return_type' => 'single'
							);
		$result = $dblms->getRows(BLOGS, $conditions);
		return $result;
	}
// end Single blog

}
// end class 