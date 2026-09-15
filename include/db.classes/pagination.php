<?php
class Pagination {

// Paginator function
	public function get_Paginator($pagename, $count, $Limit, $lastpage, $page, $adjacents, $next, $prev, $sqlstring, $lpm1) { 
		if (strpos($pagename, '?') !== false) {
			$operater = '&';
		} else {
			$operater = '?';
		}
		
		$pagination = "";
		//-----------------------------------------
		if($count>$Limit) {
		$pagination .= '
			<div class=" d-flex justify-content-end">
				<ul class="pagination">';
		//--------------------------------------------------
		

		if($lastpage > 1) {	
		//previous button
		if ($page > 1) {
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$prev.$sqlstring.'"">Previous</a></li>';
		}
		//pages	
		if ($lastpage < 7 + ($adjacents * 3)) {	//not enough pages to bother breaking it up
			for ($counter = 1; $counter <= $lastpage; $counter++) {
				if ($counter == $page) {
					$pagination.= '<li class="paginate_button page-item active"><span class="page-link">'.$counter.'</span></li>';
				} else {
					$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
				}
			}
		} else if($lastpage > 5 + ($adjacents * 3))	{ //enough pages to hide some
		//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 3)) {
				for ($counter = 1; $counter < 4 + ($adjacents * 3); $counter++)	{
					if ($counter == $page) {
						$pagination.= '<li class="paginate_button page-item active"><span class="page-link">'.$counter.'</span></li>';
					} else {
						$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
					}
				}
				$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
				$pagination.= '<li class="page-item "><a class="page-link" href="'.$pagename.$operater.'page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
				$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';	
		} else if($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 3)) { //in middle; hide some front and some back
				$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page=1'.$sqlstring.'">1</a></li>';
				$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page=2'.$sqlstring.'">2</a></li>';
				$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page=3'.$sqlstring.'">3</a></li>';
				$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
				if ($counter == $page) {
					$pagination.= '<li class="page-item active"><span class="page-link">'.$counter.'</span></li>';
				} else {
					$pagination.= '<li class="page-item "><a class="page-link" href="'.$pagename.$operater.'page='.$counter.$sqlstring.'">'.$counter.'</a></li>';					
				}
			}
			$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';	
		} else { //close to end; only hide early pages
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page=1'.$sqlstring.'">1</a></li>';
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page=2'.$sqlstring.'">2</a></li>';
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page=3'.$sqlstring.'">3</a></li>';
			$pagination.= '<li class="page-item"><a class="page-link" href="javascript:void(0);">...</a></li>';
			for ($counter = $lastpage - (3 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
				if ($counter == $page) {
					$pagination.= '<li class="paginate_button page-item active"><span class="page-link">'.$counter.'</span></li>';
				} else {
					$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$counter.$sqlstring.'">'.$counter.'</a></li>';					
				}
			}
		}
		}
		//next button
		if ($page < $counter - 1) {
			$pagination.= '<li class="page-item"><a class="page-link" href="'.$pagename.$operater.'page='.$next.$sqlstring.'">Next</a></li>';
		} else {
			$pagination.= "";
		}
			$pagination.= "";
		}

		$pagination .= '
					</ul>
			</div>';
		}
		return $pagination;
	}
// end Paginator function
	

}
// end class 