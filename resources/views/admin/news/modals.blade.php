<div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" action="{{ route('news.store') }}" method="POST" class="row" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control" name="meta_description" rows="4" required></textarea>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Content</label>
                    <textarea id="editor" name="content"></textarea>
                </div>
                <div class="col-12 mb-3">
                    <label for="formFile" class="form-label">Image</label>
                    <input class="form-control file-upload" name="image" type="file" id="formFile" accept="image/png, image/gif, image/jpeg">
                    <div class="overflow-auto img-container mt-2">
                        <img width="100%" class="img-preview" id="image" style="max-width: 250px; object-fit: cover;" />
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">External URL</label>
                    <input type="text" class="form-control" name="external_url">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date published</label>
                    <input type="date" class="form-control" name="date_published" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>