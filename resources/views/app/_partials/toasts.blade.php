<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
        data-bs-delay="8000">
        <div class="toast-body">

            <div v-if="success_msg">
                <i class="fa-solid fa-circle-check text-success"></i> @{{ success_msg }}
            </div>
            <div v-else-if="err_msg">
                <i class="fa-solid fa-triangle-exclamation text-danger"></i> @{{ err_msg }}
            </div>


            <button type="button" class="btn-close float-end" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>