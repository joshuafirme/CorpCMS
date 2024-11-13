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
                                        <strong>Dashboard</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="dashboard"
                                        class="ic-parent-permission" id="chkbx-dashboard" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr> <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-1">
                                        <strong>Service requests</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="service_requests"
                                        class="ic-parent-permission" id="chkbx-service_requests" ref="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>User Management
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="user_management"
                                        id="chkbx-all-user_management">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Customers</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-customers" value="customers"
                                        class="parent-identy-user_management">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Service providers</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-service_providers" value="service_providers"
                                        class="parent-identy-user_management">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label><strong>Service Categories
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="ic-parent-permission" value="service_categories"
                                        id="chkbx-all-service_categories">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Categories</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-service_categories" value="service_categories"
                                        class="parent-identy-service_categories">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label>Subcategories</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" id="chkbx-service_subcategories" value="service_subcategories"
                                        class="parent-identy-service_categories">
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
                                        <strong>Operational Areas</strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="operational_areas"
                                        class="ic-parent-permission" id="chkbx-operational_areas" ref="1">
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
