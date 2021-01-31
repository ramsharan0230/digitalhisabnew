<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>S.N.</th>
      <th>Date</th>
      
      <th>Purchased From</th>
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
      <td>{{$detail->purchased_from}}</td>
      <td>{{$detail->total}}</td>     
      
      
      <td>{{$detail->round_total}}</td>
      
      <td class="whole-btn-wrapper">
        
        <a class="btn btn-info edit salesView" href="{{route('reportPurchaseView',$detail->id)}}" title="Sales View" data-id="{{$detail->id}}" target="_blank"><span class="fa fa-eye"></span></a>
        
      </td>
    </tr>
    @php($i++)
    @endforeach
  </tbody>
 
</table>	