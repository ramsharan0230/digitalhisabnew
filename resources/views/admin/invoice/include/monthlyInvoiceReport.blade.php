<table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Collected</th>
                      <th>Total</th>
                      <th>Action</th>
               
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">

                     
                      <td>{{$i}}</td>
                      <td>{{$detail->client_name}}</td>
                      <td>{{$detail->email}}</td>
                      <td>{{$detail->collected_amount}}</td>
                      <td>{{$detail->grand_total}}</td>
                      <td>
                        <a class="btn btn-info edit" href="{{route('invoice.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
                       <a class="btn btn-warning edit" href="{{route('previewInvoice',$detail->id)}}" target="_blank" title="Edit"><span class="fa fa-eye"></span></a>
                       @if($detail->collected==0)
                       <a class="btn btn-info send" title="Send" data-id="{{$detail->id}}">{{$detail->collected_type!=null?'ReInvoice':'Send Email'}}</a>
                       @endif
                        <form method= "post" action="{{route('invoice.destroy',$detail->id)}}" class="delete btn btn-danger">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
                        </form>
                        
                        @if($detail->vat_id==null && $detail->vat!=0)
                        <a href="{{route('invoice.show',$detail->id)}}" class="btn btn btn-warning">Billed</a>
                        @endif
                        <!-- for sales with vat -->
                        @if($detail->vat_id!=null && $detail->vat!=0 && $detail->collected==0)
                        
                          @if($detail->collected!=$detail->grand_total)
                          <div class="form-group" id="select{{$detail->id}}">
                            <select class="form-control collectionWithVat">
                              <option disabled="true" selected="true">Collection</option>
                              <option value="partial" data-id="{{$detail->id}}">Partial</option>
                              @if($detail->collected_type!='partial')
                              <option value="full" data-id="{{$detail->id}}">Full</option>
                              @endif
                            </select>
                          </div>
                          @endif
                        @endif
                        <!-- for sales without vat -->
                        @if($detail->vat==0 && $detail->sales_without_vat_collected==0 && $detail->collected==0)
                          @if($detail->collected!=$detail->grand_total)
                          <div class="form-group" id="select{{$detail->id}}">
                            <select class="form-control collectionWithVat">
                              <option disabled="true" selected="true">Collection</option>
                              <option value="partial" data-id="{{$detail->id}}">Partial</option>
                              @if($detail->collected_type!='partial')
                              <option value="full" data-id="{{$detail->id}}">Full</option>
                              @endif
                            </select>
                          </div>
                          @endif
                        @endif
                        



                      </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>