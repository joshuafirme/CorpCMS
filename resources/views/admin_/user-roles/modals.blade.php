<div class="modal fade" id="roleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="#" method="post" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Create Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3 ">
                <div class="col-md-12">
                    <label for="validationCustom01" class="form-label">Role name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="col-md-12 mt-4">
                    <b>Permissions</b>
                </div>

                <div class="col-md-12">
                    <div class="row my-2">
                        <div class="col-8 pt-1">
                            <div class="custom-control pl-0">
                                <label for="customCheck-all">All Permission</label>
                            </div>
                        </div>
                        <div class="col-4 pt-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" id="customCheck-all" value="all">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1">
                                        <strong>News</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="news"
                                        class="ic-parent-permission" id="chkbx-news" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1">
                                        <strong>Sliders</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="sliders"
                                        class="ic-parent-permission" id="chkbx-sliders" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1">
                                        <strong>Gallery</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="gallery"
                                        class="ic-parent-permission" id="chkbx-gallery" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1">
                                        <strong>Messages</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="messages"
                                        class="ic-parent-permission" id="chkbx-messages" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>Pages
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="pages"
                                        id="chkbx-all-pages">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>About us</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-about_us" value="about_us"
                                        class="parent-identy-pages">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Contact us</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-contact_us" value="contact_us"
                                        class="parent-identy-pages">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Privacy policy</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-privacy_policy" value="privacy_policy"
                                        class="parent-identy-pages">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Terms of service</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-terms_of_service" value="terms_of_service"
                                        class="parent-identy-pages">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                   
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1">
                                        <strong>General settings</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="general_settings"
                                        class="ic-parent-permission" id="chkbx-general_settings" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>Administration
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="administration"
                                        id="chkbx-all-administration">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Users</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-users" value="users"
                                        class="parent-identy-administration">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Roles</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-roles" value="roles"
                                        class="parent-identy-administration">
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
