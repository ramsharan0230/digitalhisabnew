<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>S.N.</th>
        <th>Date</th>
        <th>Paid To</th>
        <th>Payment Type</th>
        <th>Amount</th>
        <th>Bank</th>
        <th>Action</th>
      </tr>
  </thead>
  <tbody id="sortable">
  @php($i=1)
  @foreach($details as $detail)
    <tr id="{{ $detail->id }}">
        <td>{{$i}}</td>
        <td>{{$detail->date}}</td>
        <td>{{$detail->paid_to}}</td>

        <td>{{$detail->payment_type}}</td>
        <td>{{$detail->amount}}</td>
        <td>{{$detail->bank}}</td>
        <td>
          
          @if($detail->date==$todaysNepaliDate)
          <a class="btn btn-info edit" href="{{route('payment.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
          <form method= "post" action="{{route('payment.destroy',$detail->id)}}" class="delete btn btn-danger">
          {{csrf_field()}}
          <input type="hidden" name="_method" value="DELETE">
          <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
          </form>
          @endif
        </td>
    </tr>
    @php($i++)
    @endforeach
  </tbody>
</table>