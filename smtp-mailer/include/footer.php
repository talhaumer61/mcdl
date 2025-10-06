<?php
        echo'
        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-dark fw-semibold">Velvet</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
                        <span class="fw-semibold text-primary text-decoration-underline">Spruko</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>
    </div>
    <!-- SCROLL-TO-TOP -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-circle-fill fs-20"></i></span>
    </div>
    <div id="responsive-overlay"></div>
    <!-- POPPER JS -->
    <script src="'.SERVER_URL.'assets/libs/%40popperjs/core/umd/popper.min.js"></script>
    <!-- BOOTSTRAP JS -->
    <script src="'.SERVER_URL.'assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DATE & TIME PICKER JS -->
    <script src="'.SERVER_URL.'assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="'.SERVER_URL.'assets/js/date%26time_pickers.js"></script>
    <!-- NODE WAVES JS -->
    <script src="'.SERVER_URL.'assets/libs/node-waves/waves.min.js"></script>
    <!-- SIMPLEBAR JS -->
    <script src="'.SERVER_URL.'assets/libs/simplebar/simplebar.min.js"></script>
    <script src="'.SERVER_URL.'assets/js/simplebar.js"></script>
    <!-- COLOR PICKER JS -->
    <script src="'.SERVER_URL.'assets/libs/%40simonwep/pickr/pickr.es5.min.js"></script>
    <!-- DEFAULTMENU JS -->
    <script src="'.SERVER_URL.'assets/js/defaultmenu.js"></script>
    <!-- QUILL EDITOR JS -->
    <script src="'.SERVER_URL.'assets/libs/quill/quill.min.js"></script>
    <!-- MAIL JS -->
    <script src="'.SERVER_URL.'assets/js/mail.js"></script>
    <!-- STICKY JS -->
    <script src="'.SERVER_URL.'assets/js/sticky.js"></script>
    <!-- CUSTOM JS -->
    <script src="'.SERVER_URL.'assets/js/custom.js"></script>
    <!-- CUSTOM-SWITCHER JS -->
    <script src="'.SERVER_URL.'assets/js/custom-switcher.js"></script>
    <script>
        $(document).ready(function() {
            $("#checkAll").on("click", function() {
                $(".checkbox").prop("checked", this.checked);
            });
            $(".checkbox").on("click", function() {
                console.log($(".checkbox:checked").length);
                console.log($(".checkbox").length);
                if ($(".checkbox:checked").length === $(".checkbox").length) {
                    $("#checkAll").prop("checked", true);
                } else {
                    $("#checkAll").prop("checked", false);
                }
            });
        });
    </script>
</body>
</html>';