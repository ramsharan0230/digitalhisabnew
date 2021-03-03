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
  @foreach($invoices as $detail)
    <tr id="{{ $detail->id }}">
        <td>{{$i}}</td>
        <td>{{$detail->nepali_date}}</td>
        <td>{{$detail->collected==$detail->grand_total?'Closed':'Open'}}</td>
        <td>{{$detail->grand_total}}</td>
        <td>
          <a class="btn btn-info edit" href="" title="Edit"><span class="fa fa-eye"></span></a>
         
        </td>
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>