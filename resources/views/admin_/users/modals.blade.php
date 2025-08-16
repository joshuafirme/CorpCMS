<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-4">
      <div class="modal-header">
        <h5 class="modal-title" id="viewOrderLabel">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="viewOrderForm">
          <input type="hidden" id="orderId" value="">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="email" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Book</label>
            <input type="text" class="form-control" id="book" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea class="form-control" id="address" rows="2" readonly></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Note</label>
            <textarea class="form-control" id="note" rows="2" readonly></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Proof of Payment</label><br>
            <img id="proofImage" class="img-thumbnail" style="max-height: 200px; display: none;" />
            <p id="noImageText" class="text-muted">No image uploaded</p>
          </div>
          <button type="button" class="btn btn-success" id="approveBtn">Approve</button>

        </form>
      </div>
    </div>
  </div>
</div>
