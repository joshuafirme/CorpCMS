<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <h3>Please login to continue</h3>
                            </div>
                        </div>
                    </div>
    
                    <form action="{{ route('user.doLogin') }}" id="loginForm" autocomplete="off" method="POST">
                        @csrf
                        <div class="row gy-3 gy-md-4 overflow-hidden">
                            <input type="hidden" name="submit_request_page" value="1">
                            <div class="col-12">
                                <label for="email" class="form-label small">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email"
                                    required>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label small">Password <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="password"
                                    value="" required>
                            </div>
                            <div class="col-12">
                                <a href="#!" class="link-primary text-decoration-none">Forgot password</a>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn bsb-btn-xl btn-primary" type="submit">Log in</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12 mt-3">
                            <div class="d-flex gap-1 flex-column flex-md-row justify-content-md-end">
                                <span>Don’t have an account?</span><a href="{{ url('/signup') }}"
                                    class="link-primary text-decoration-none">Sign up</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>