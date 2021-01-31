 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Vendor</h4>
        </div>
        <div class="modal-body">
          <form action="{{route('saveVendor')}}" method="post">
            {{csrf_field()}}
            <div class="form-group">
              <label>Name</label>
              <input type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="text" name="email" class="form-control">
            </div>
            <div class="form-group">
              <label>Phone</label>
              <input type="text" name="phone" class="form-control">
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="text" name="address" class="form-control">
            </div>
            <div class="form-group">
              <label>Vat No.</label>
              <input type="text" name="vat_no" class="form-control">
            </div>
            <div class="form-group">
              <label>Contact person</label>
              <input type="text" name="contact_person" class="form-control">
            </div>
            <div class="form-group">
              <label>Designation</label>
              <input type="text" name="designation" class="form-control">
            </div>
            <div class="form-group">
              <input type="submit" name="submit" value="submit" class="btn btn-success">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>