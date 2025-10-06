<?php
echo'
<div class="float-end mt-2 p-2">
    <span><a href="javascript:void(0);" class="text-muted d-inline-flex">'.((($page - 1) * $Limit) + 1).'-'.$srno.' of '.$count.'</a></span>
    <nav aria-label="Page navigation" class="pagination-style-4 float-end">
        <ul class="pagination mb-0 gap-2">';
            $current_page = CONTROLER;
            $pagination = "";
            if($lastpage >= 1){
                // PREVIOUS BUTTON
                if($page > 0){
                    $pagination.= '<li class="page-item bg-light rounded-5 '.($page==1 ? 'disabled cursor-not-allowed' : '').'"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page='.$prev.$sqlstring.'">Prev</a></li>';
                }

                // PAGES 
                if($lastpage < 7 + ($adjacents * 1)){ //not enough pages to bother breaking it up
                    for($counter = 1; $counter <= $lastpage; $counter++){
                        $pagination.= '<li class="page-item bg-light rounded-5 '.($counter == $page ? 'active' : '').'"><a class="page-link bg-light rounded-5" href="'.($counter == $page ? '' : $current_page.'?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                    }
                }elseif($lastpage > 5 + ($adjacents * 1)){
                    //enough pages to hide some
                    //close to beginning - only hide later pages
                    if($page < 1 + ($adjacents * 1)){
                        for($counter = 1; $counter < 4 + ($adjacents * 1); $counter++){
                            $pagination.= '<li class="page-item bg-light rounded-5 '.($counter == $page ? 'active' : '').'"><a class="page-link bg-light rounded-5" href="'.($counter == $page ? '' : $current_page.'?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                        }
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="#"> <i class="bi bi-three-dots"></i> </a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                    }elseif($lastpage - ($adjacents * 1) > $page && $page > ($adjacents * 1)){
                        //in middle; hide some front and some back
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="#"> <i class="bi bi-three-dots"></i> </a></li>';
                        for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
                            $pagination.= '<li class="page-item bg-light rounded-5 '.($counter == $page ? 'active' : '').'"><a class="page-link bg-light rounded-5" href="'.($counter == $page ? '' : $current_page.'?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                        }
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="#"> <i class="bi bi-three-dots"></i> </a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                    }else{
                        //close to end; only hide early pages
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="'.$current_page.'?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                        $pagination.= '<li class="page-item bg-light rounded-5"><a class="page-link bg-light rounded-5" href="#"> <i class="bi bi-three-dots"></i> </a></li>';
                        for($counter = $lastpage - (3 + ($adjacents * 1)); $counter <= $lastpage; $counter++){
                            $pagination.= '<li class="page-item bg-light rounded-5 '.($counter == $page ? 'active' : '').'"><a class="page-link bg-light rounded-5" href="'.($counter == $page ? '' : $current_page.'?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                        }
                    }
                }

                // NEXT BUTTON
                if($page < $counter){
                    $pagination.= '<li class="page-item rounded-5 '.($page==$counter-1 ? 'disabled cursor-not-allowed' : '').'"><a class="page-link rounded-5" href="'.$current_page.'?'.$filters.'&page='.$next.$sqlstring.'">next</a></li>';
                }else{
                    $pagination.= "";
                }
                echo $pagination;
            }
            echo'
        </ul>
    </nav>
</div>';
?>