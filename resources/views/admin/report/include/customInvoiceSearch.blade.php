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
         
         <a class="btn btn-warning edit" href="{{route('reportInvoiceView',$detail->id)}}" target="_blank" title="Edit"><span class="fa fa-eye"></span></a>
         <a class="btn btn-info print" href="#" title="Edit" data-id="{{$detail->id}}"><span class="fa fa-edit"></span>Print</a>
         



        </td>
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>