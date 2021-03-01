<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>S.N.</th>
        <th>Sales To</th>
        <th>Contact</th>
        <th>Date</th>
        <th>Email</th>
        <th>Total</th>
      </tr>
  </thead>
  <tbody id="sortable">
  @forelse($details as $key =>$detail)
    <tr>
      <?php //dd($detail) ?>
        <td>{{$key+1}}</td>
        <td>{{$detail->client_name}}</td>
        <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail->date )->format('d-m-Y')}}</td>
        <td>{{$detail->contact}}</td>
        <td>{{$detail->collected_amount}}</td>
        <td>{{$detail->grand_total-$detail->collected_amount}}</td>
    </tr>
    @empty
    <tr>
      <td colspan="6">No data Found!</td>
    </tr>
    @endforelse
  </tbody>
</table>