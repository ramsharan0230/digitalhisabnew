<table id="example1" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>S.N.</th>
        <th>Purchased From</th>
        <th>Purchased Item</th>
        <th>Bill No</th>
        <th>Date</th>
        <th>Taxable Amount</th>
        <th>Vat Paid</th>
        <th>Total</th>
        <th>Round Total(VAT)</th>
        <th>Total Paid</th>
      </tr>
    </thead>
    <tbody>
      @forelse($details as $key =>$detail)
        <tr>
            <td>{{$key++}}</td>
            <td>{{$detail->purchased_from}}</td>
            <td>{{$detail->purchased_item}}</td>
            <td>{{$detail->bill_no}}</td>     
            <td>{{$detail->vat_date}}</td>
            <td>{{$detail->taxable_amount}}</td>
            <td>{{$detail->vat_paid}}</td>
            <td>{{$detail->total}}</td>
            <td>{{$detail->round_total}}</td>
            <td>{{ $detail->total_paid }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="10">No item found</td>
        </tr>
      @endforelse
    </tbody>
  </table>  