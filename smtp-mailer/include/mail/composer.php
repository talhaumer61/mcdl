<?php
echo'
<div class="mail-navigation d-none d-md-block card custom-card">
    <div class="d-grid align-items-top p-3 border-bottom">
        <button class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal"
        data-bs-target="#mail-Compose">
            <i class="ri-add-circle-line fs-16 align-middle me-1"></i>Compose Mail
        </button>
        <div class="modal modal-lg fade" id="mail-Compose" tabindex="-1" aria-labelledby="mail-ComposeLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="mail-ComposeLabel">Compose Mail</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="row">
                            <div class="col-xl-6 mb-2">
                                <label for="fromMail" class="form-label">From<sup><i class="ri-star-s-fill text-success fs-8"></i></sup></label>
                                <input type="email" class="form-control" id="fromMail" value="jsontaylor2345@gmail.com">
                            </div>
                            <div class="col-xl-6 mb-2">
                                <label for="toMail" class="form-label">To<sup><i class="ri-star-s-fill text-success fs-8"></i></sup></label>
                                <select class="form-control" name="toMail" id="toMail" multiple>
                                    <option value="Choice 1" selected>jay@gmail.com</option>
                                    <option value="Choice 2">kia@gmail.com</option>
                                    <option value="Choice 3">don@gmail.com</option>
                                    <option value="Choice 4">kimo@gmail.com</option>
                                </select>
                            </div>
                            <div class="col-xl-6 mb-2">
                                <label for="mailCC" class="form-label text-dark fw-semibold">Cc</label>
                                <input type="email" class="form-control" id="mailCC">
                            </div>
                            <div class="col-xl-6 mb-2">
                                <label for="mailBcc" class="form-label text-dark fw-semibold">Bcc</label>
                                <input type="email" class="form-control" id="mailBcc">
                            </div>
                            <div class="col-xl-12 mb-2">
                                <label for="Subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="Subject" placeholder="Subject">
                            </div>
                            <div class="col-xl-12">
                                <label class="col-form-label">Content :</label>
                                <div class="mail-compose">
                                    <div id="mail-compose-editor"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <ul class="list-unstyled mail-main-nav" id="mail-main-nav">
            <li class="px-0 pt-0 mail-nav-heading">
                <span class="fs-11 text-muted op-7 fw-semibold">MAILS</span>
            </li>
            <li class="active">
                <a href="javascript:void(0);">
                    <div class="d-flex align-items-center">
                        <span class="me-2 lh-1">
                            <i class="ri-inbox-archive-line align-middle fs-14"></i>
                        </span>
                        <span class="flex-fill text-nowrap">
                            All Mails
                        </span>
                        <span class="badge bg-info-transparent rounded-1">789</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <div class="d-flex align-items-center">
                        <span class="me-2 lh-1">
                            <i class="ri-inbox-archive-line align-middle fs-14"></i>
                        </span>
                        <span class="flex-fill text-nowrap">
                            Inbox
                        </span>
                        <span class="badge bg-success-transparent rounded-1">5</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <div class="d-flex align-items-center">
                        <span class="me-2 lh-1">
                            <i class="ri-bookmark-line align-middle fs-14"></i>
                        </span>
                        <span class="flex-fill text-nowrap">
                            In Active
                        </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <div class="d-flex align-items-center">
                        <span class="me-2 lh-1">
                            <i class="ri-delete-bin-line align-middle fs-14"></i>
                        </span>
                        <span class="flex-fill text-nowrap">
                            Trash
                        </span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>';