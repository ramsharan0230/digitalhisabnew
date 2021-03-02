<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
          <th>S.N.</th>
          <th>Date</th>
          <th>Sales To</th>
          <th>Amount</th>
          <th>Billing Status</th>
          <th>Detail</th>
        </tr>
    </thead>
    <tbody id="sortable">
        @php($i=1)
        @foreach($details as $detail)
        <tr id="{{ $detail->id }}">
          <td>{{$i}}</td>
          <td>{{$detail->vat_date}}</td>
          <td>{{$detail->sales_to}}</td>
          <td>{{$detail->total}}</td>
          <td>{{$detail->invoice->collected_amount==$detail->invoice->grand_total?'Close':'Open'}}</td>
          
          <td><a class="btn btn-info edit salesView" href="{{route('reportInvoiceView',$detail->invoice->id)}}" title="Sales View" data-id="{{$detail->id}}" target="_blank"><span class="fa fa-eye"></span></a></td>
        </tr>
        @php($i++)
        @endforeach
    </tbody>
    
  </table>   