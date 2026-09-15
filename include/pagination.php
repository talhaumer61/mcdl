<?php
// if($count>$Limit) {
    echo'
    <div class="d-flex">
        <div class="col">
            <div class="justify-content-start mb-0 mt-3">
                Showing <b>'.((($page - 1) * $Limit) + 1).'</b> to <b>'.$srno.'</b> of <b>'.$count.'</b> entries
            </div>
        </div>
        <div class="col">
            <nav>
                <ul class="pagination justify-content-end mb-0 mt-3">';
                    $current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
                    $pagination = "";
                    if($lastpage >= 1){
                        // PREVIOUS BUTTON
                        if($page > 0){
                            $pagination.= '<li class="page-item '.($page==1 ? 'disabled cursor-not-allowed' : '').'"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page='.$prev.$sqlstring.'"><i class="mdi mdi-chevron-left"></i></a></li>';
                        }

                        // PAGES 
                        if($lastpage < 7 + ($adjacents * 1)){ //not enough pages to bother breaking it up
                            for($counter = 1; $counter <= $lastpage; $counter++){
                                $pagination.= '<li class="page-item '.($counter == $page ? 'active' : '').'"><a class="page-link" href="'.($counter == $page ? '' : $current_page.'.php?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                            }
                        }elseif($lastpage > 5 + ($adjacents * 1)){
                            //enough pages to hide some
                            //close to beginning - only hide later pages
                            if($page < 1 + ($adjacents * 1)){
                                for($counter = 1; $counter < 4 + ($adjacents * 1); $counter++){
                                    $pagination.= '<li class="page-item '.($counter == $page ? 'active' : '').'"><a class="page-link" href="'.($counter == $page ? '' : $current_page.'.php?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                                }
                                $pagination.= '<li class="page-item"><a class="page-link" href="#"> ... </a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                            }elseif($lastpage - ($adjacents * 1) > $page && $page > ($adjacents * 1)){
                                //in middle; hide some front and some back
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="#"> ... </a></li>';
                                for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
                                    $pagination.= '<li class="page-item '.($counter == $page ? 'active' : '').'"><a class="page-link" href="'.($counter == $page ? '' : $current_page.'.php?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                                }
                                $pagination.= '<li class="page-item"><a class="page-link" href="#"> ... </a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                            }else{
                                //close to end; only hide early pages
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                                $pagination.= '<li class="page-item"><a class="page-link" href="#"> ... </a></li>';
                                for($counter = $lastpage - (3 + ($adjacents * 1)); $counter <= $lastpage; $counter++){
                                    $pagination.= '<li class="page-item '.($counter == $page ? 'active' : '').'"><a class="page-link" href="'.($counter == $page ? '' : $current_page.'.php?'.$filters.'&page='.$counter.$sqlstring).'">'.$counter.'</a></li>';
                                }
                            }
                        }

                        // NEXT BUTTON
                        if($page < $counter){
                            $pagination.= '<li class="page-item '.($page==$counter-1 ? 'disabled cursor-not-allowed' : '').'"><a class="page-link" href="'.$current_page.'.php?'.$filters.'&page='.$next.$sqlstring.'"><i class="mdi mdi-chevron-right"></i></a></li>';
                        }else{
                            $pagination.= "";
                        }
                        echo $pagination;
                    }
                    echo'
                </ul>
            </nav>
        </div>
    </div>';
// }
?>