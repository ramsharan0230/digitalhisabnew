<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>S.N.</th>
        <th>Date</th>
        <th>Received</th>
        <th>Payment </th>
        <th>Balance</th>
      </tr>
  </thead>
  <tbody id="sortable">
  @php($i=1)
  @foreach($balances as $detail)
    <tr id="{{ $detail->id }}">
        <td>{{$i}}</td>
        <td>{{$detail->date}}</td>
        <td>{{$detail->received}}</td>

        <td>{{$detail->payment}}</td>
        <td>{{$detail->balance}}</td>
        
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>