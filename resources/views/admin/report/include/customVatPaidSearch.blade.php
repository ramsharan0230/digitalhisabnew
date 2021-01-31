<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>S.N.</th>
        <th>To</th>
        <th>Bill no</th>

        <th>Date</th>
        <th>Vat Amount</th>
        
      </tr>
  </thead>
  <tbody id="sortable">
  @php($i=1)
  @foreach($purchases as $detail)
    <tr id="{{ $detail->id }}">
        <td>{{$i}}</td>
        <td>{{$detail->purchased_from}}</td>
        <td>{{$detail->bill_no}}</td>
        <td>{{$detail->vat_date}}</td>
        <td>{{$detail->vat_paid}}</td>
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>