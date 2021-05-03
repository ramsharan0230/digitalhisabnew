 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Client</h4>
        </div>
        <div class="modal-body">
          <form action="{{route('saveClient')}}" method="post">
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

            {{-- new  --}}
            <div class="input-group control-group after-add-more">
              <div class="row">
                <div class="col-sm-5 col-md-5 col-lg-5">
                  <input type="text" name="contact_person[]" class="form-control" placeholder="Enter Contact person">
                </div>
                <div class="col-sm-5 col-md-5 col-lg-5">
                  <input type="text" name="designation[]" class="form-control" placeholder="Enter Desigation">
                </div>
                <div class="col-sm-2 col-md-2 col-lg-2">
                  <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i> Add</button>
                </div>
              </div>
              
            </div>
    
            <!-- Copy Fields -->
            <div class="copy hide">
              <div class="control-group input-group" style="margin-top:10px">
                <div class="row">
                  
                  <div class="col-sm-5 col-md-5 col-lg-5">
                    <input type="text" name="contact_person[]" class="form-control" placeholder="Enter Contact person">
                  </div>

                  <div class="col-sm-5 col-md-5 col-lg-5">
                    <input type="text" name="designation[]" class="form-control" placeholder="Enter Desigation">
                  </div>

                  <div class="col-sm-2 col-md-2 col-lg-2">
                    <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button>
                  </div>
                </div>

              </div>
            </div>
            {{-- new end --}}

            <div class="form-group">
              <input type="submit" name="submit" value="submit" class="btn btn-success mt-3">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>