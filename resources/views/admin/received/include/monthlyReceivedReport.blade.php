<table id="example1" class="table table-bordered table-striped">
<thead>
    <tr>
      <th>S.N.</th>
      <th>Particular</th>
      <th>Payment Type</th>
      <th>Date</th>
      <th>Amount</th>
      
    </tr>
</thead>
<tbody id="sortable">
@php($i=1)
@foreach($details as $detail)
  <tr id="{{ $detail->id }}">
      <td>{{$i}}</td>
      <td>{{$detail->particular}}</td>
      <td>{{$detail->payment_type}}</td>
      <td>{{$detail->date}}</td>
      <td>{{$detail->amount}}</td>
  </tr>
  @php($i++)
  @endforeach
</tbody>
</table>