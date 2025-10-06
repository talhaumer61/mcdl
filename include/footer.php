<?php
echo'
                <footer class="footer pt-1 pb-2">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div>';
                                    foreach (getSocialMediaLinks() as $key => $value) {
                                        echo '
                                        <a href="'.$value['url'].'" target="_blank" class="mx-2" title="'.$value['name'].'">
                                            <i class="'.$value['icon'].' fs-24" style="color: '.$value['color'].';"></i>
                                        </a>';
                                    }
                                    echo '
                                </div>
                                '.COPY_RIGHTS_ORG.' <br>
                                Powered by: <a href="'.COPY_RIGHTS_URL.'" target="_blank">'.COPY_RIGHTS.'</a> <small>v1.0</small>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top"><i class="ri-arrow-up-line"></i></button>

        <!-- INCLUDES MODAL FUNCTIONS -->
        <script type="text/javascript">
            function showAjaxModalZoom(url) {
                $.ajax( {
                    url: url,
                    success: function ( response ) {
                        jQuery( \'#show_modal\' ).html( response );
                        $("#show_modal").modal("show");
                    }
                } );
            }
            function showAjaxModalView(url) {
                $.ajax( {
                    url: url,
                    success: function ( response ) {
                        jQuery(\'#offcanvasRight\').html( response );
                    }
                } );
            }
        </script>
        <!-- AJAX MODAL AND CANVAS -->
        <div class="modal fade" id="show_modal"></div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel"></div>
        <script type="text/javascript">
            function confirm_modal( delete_url ) {
                swal( {
                    title: "Are you sure?",
                    text: "Are you sure that you want to delete this information?",
                    type: "warning",
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    closeOnConfirm: false,
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    cancelButtonColor: "#f5f7fa",
                    confirmButtonColor: "#ed5e5e"
                }, function () {
                    $.ajax( {
                        url: delete_url,
                        type: "POST"
                    } )
                    .done( function ( data ) {
                        swal( {
                            title: "Deleted",
                            text: "Information has been successfully deleted",
                            type: "success"
                        }, function () {
                            location.reload();
                        } );
                    } )
                    .error( function ( data ) {
                        swal( "Oops", "We couldn\'t\ connect to the server!", "error" );
                    } );
                } );
            }
            function getCity(val) {
                $.ajax({
                    type: "POST",
                    url: "include/ajax/get_city.php",
                    data: "id_substate=" + val,
                    success: function(data) {
                        $("#id_city").html(data);
                    }
                });
            }
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    Toastify({
                        text: "Text ( " + text + " ) Copied",
                        gravity: "top",
                        position: "right",
                        className: "bg-success",
                        duration: 2000,
                        close: true,
                    }).showToast();
                }).catch(err => {
                    console.error("Failed to copy text: ", err);
                    Toastify({
                        text: "Failed to copy text!",
                        gravity: "top",
                        position: "right",
                        className: "bg-danger",
                        duration: 2000,
                        close: true,
                    }).showToast();
                });
            }
            function validateForm(event) {
                const form = event.target.closest(\'form\');
                const inputs = form.querySelectorAll(\'input[required], textarea[required], select[required]\');
                inputs.forEach(input => {
                    if (input.value.trim() === \'\') {
                        if (input.tagName === \'INPUT\') {
                            const label = input.parentNode.querySelector(\'label\');
                            label.classList.add(\'red-blink\');
                        } else if (input.tagName === \'SELECT\') {
                            const label = input.parentNode.parentNode.parentNode.querySelector(\'label\');
                            label.classList.add(\'red-blink\');
                        }
                    } else {
                        if (input.tagName === \'INPUT\') {
                            const label = input.parentNode.querySelector(\'label\');
                            label.classList.remove(\'red-blink\');
                        } else if (input.tagName === \'SELECT\') {
                            const label = input.parentNode.parentNode.parentNode.querySelector(\'label\');
                            label.classList.remove(\'red-blink\');
                        }
                    }
                });
            }            
            const submitButtons = document.querySelectorAll(\'input[type="submit"], button[type="submit"]\');
            submitButtons.forEach(button => {
                button.addEventListener(\'click\', validateForm);
            });
            const inputs = document.querySelectorAll(\'input\');
            inputs.forEach(input => {
                input.addEventListener(\'input\', validateForm);
            });

            const select = document.querySelectorAll(\'select\');
            select.forEach(input => {
                input.addEventListener(\'change\', validateForm);
            });

            // AUTOFOCUS ON SEARCH INPUT
            document.addEventListener("DOMContentLoaded", function() {
                var searchInput = document.getElementById("searchInput");
                searchInput.focus();
                if (searchInput.value) {
                    // Move the cursor to the end of the searchInput field value
                    searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                }
            });
        </script>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>

        <!-- prismjs plugin -->
        <script src="assets/libs/prismjs/prism.js"></script>
        <script src="assets/libs/list.js/list.min.js"></script>
        <script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>

        <!-- listjs init -->
        <script src="assets/js/pages/listjs.init.js"></script>
    
        <!-- apexcharts -->
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
    
        <!-- Vector map-->
        <script src="assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
        <script src="assets/libs/jsvectormap/maps/world-merc.js"></script>
    
        <!--Swiper slider js-->
        <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    
        <!-- Dashboard init -->
        <script src="assets/js/pages/dashboard-ecommerce.init.js"></script>
    
        <!-- App js -->
        <script src="assets/js/app.js"></script>
    
        <!-- gridjs js -->
        <script src="assets/libs/gridjs/gridjs.umd.js"></script>

        <!-- gridjs init -->
        <script src="assets/js/pages/gridjs.init.js"></script>
        
        <!-- notifications init -->
        <script src="assets/js/pages/notifications.init.js"></script>

        <script src="assets/js/pages/profile-setting.init.js"></script>
    </body>
</html>';
?>
<!-- LINK EXPORT TO EXCEL -->
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>
	// PRINT REPORT
	function print_report(printResult) {
        document.getElementById('header').style.display = 'block';
        var printContents = document.getElementById(printResult).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
		var css = '',
		// var css = '@page { size: portrait; }',
		head = document.head || document.getElementsByTagName('head')[0],
		style = document.createElement('style');
		style.type = 'text/css';
		style.media = 'print';
		if (style.styleSheet){
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);
        window.print();
        document.body.innerHTML = originalContents;
		document.getElementById('header').style.display = 'none';
    }

	// EXPORT TO EXCEL
	function html_table_to_excel(type){
		var data = document.getElementById('printResult');
		var file = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
		XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
		XLSX.writeFile(file, '<?=moduleName()?>_report.' + type);
	}

	const export_button = document.getElementById('export_button');
	export_button.addEventListener('click', () =>  {
		html_table_to_excel('xlsx');
	});
</script>

<!-- AJAX TO LOAD UNREAD MESSAGES -->
<script>
    const userType = "<?php echo $_SESSION['userlogininfo']['LOGINAFOR']; ?>";
    const emplyID = "<?php echo $_SESSION['userlogininfo']['EMPLYID']; ?>";
    function loadUnreadCourseMessages() {
        $.ajax({
            url: 'include/ajax/get_unread_courses.php',
            method: 'GET',
            data: { user_type: userType, emplyID: emplyID  },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if(userType == 1){
                    var container = $('#unread-course-messages');
                }else{
                    var container = $('#teacher-unread-course-messages');
                }
                container.empty(); // Clear previous content
                let totalUnread = 0;

                if (response==null || response==false || response.length === 0) {
                    container.append(`
                        <div class="p-4">
                            <div class="w-25 w-sm-50 pt-3 mx-auto">
                                <img src="assets/images/svg/bell.svg" class="img-fluid" alt="user-pic">
                            </div>
                            <div class="text-center pb-5 mt-2">
                                <h6 class="fs-18 fw-semibold lh-base">Hey! You have no Messages </h6>
                            </div>
                        </div>
                    `);
                }
                else {
                    response.forEach(item => {
                        totalUnread += parseInt(item.unread_count, 10);

                        container.append(`
                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-xs me-3 position-relative">
                                        <span class="avatar-title bg-soft-danger text-danger rounded-circle fs-16">
                                            <i class="bx bx-message-square-dots"></i>
                                            <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
                                                ${item.unread_count}
                                                <span class="visually-hidden">unread messages</span>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <a href="courses.php?edit&id_type=2&id=${item.id_curs}&tab=manage_course&view=question_answers" class="stretched-link">
                                            <h6 class="mt-0 mb-1 fs-13 lh-base">
                                                <b>${item.curs_name}</b>
                                            </h6>
                                            <p class="mb-0 fs-11 text-muted">
                                                You have <span class="text-success fw-semibold">${item.unread_count}</span> unread message(s)
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }
                userType ==1?$('#total-unread-badge').text(`${totalUnread} New`):$('#teacher-total-unread-badge').text(`${totalUnread} New`);
                userType ==1?$('#not-btn').text(`${totalUnread}`):$('#teacher-not-btn').text(`${totalUnread}`);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching unread courses:', error);
            }
        });
    }

    $(document).ready(function () {
        loadUnreadCourseMessages();
        setInterval(loadUnreadCourseMessages, 10000); // Reload every 10 seconds
    });
</script>
