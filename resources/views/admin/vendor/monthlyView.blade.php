<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>S.N.</th>
        <th>Created Date</th>
        <th>Billing Status</th>
        <th>Amount</th>
        
        <th>Action</th>
      </tr>
  </thead>
  <tbody id="sortable">
  @php($i=1)
  @foreach($purchases as $detail)
    <tr id="{{ $detail->id }}">
        <td>{{$i}}</td>
        <td>{{$detail->vat_date}}</td>
        <td>{{$detail->total_amount_of_purchase_amount_paid==$detail->total?'Closed':'Open'}}</td>
        <td>{{$detail->total}}</td>
        <td>
          <a class="btn btn-info edit" href="{{route('vendorTransactionView',$detail->id)}}" title="Edit"><span class="fa fa-eye"></span></a>
         
          



        </td>
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>