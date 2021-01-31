<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>S.N.</th>
        <th>Amount</th>
        <th>Date</th>
        
        
        
      </tr>
  </thead>
  <tbody id="sortable">
  @php($i=1)
  @foreach($details as $detail)
    <tr id="{{ $detail->id }}">
        <td>{{$i}}</td>
        <td>{{$detail->amount}}</td>
        
        <td>{{$detail->date}}</td>
        
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>