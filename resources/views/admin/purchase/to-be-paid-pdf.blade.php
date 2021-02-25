<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vat Paid Report</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px
        }
    </style>
</head>
<body>
    <h3 style="margin-left: 40%">Vat To Be Paid Report ({{ $year }}-{{ $month }})</h3>
    <table style="border; width:100%" >
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
              <th>Paid</th>
            </tr>
          </thead>
          <tbody>
              <?php $total = 0 ?>
            @forelse($details as $key =>$detail)
              <tr>
                  <td>{{$key +1}}</td>
                  <td>{{$detail->purchased_from}}</td>
                  <td>{{$detail->purchased_item}}</td>
                  <td>{{$detail->bill_no}}</td>     
                  <td>{{$detail->vat_date}}</td>
                  <td>{{$detail->taxable_amount}}</td>
                  <td>{{$detail->vat_paid}}</td>
                  <td>{{$detail->total}}</td>
                  <td>{{$detail->round_total}}</td>
                  <td>{{ $detail->total_paid }}</td>
                  <?php $total += $detail->total_paid ?>
              </tr>
              @empty
              <tr>
                  <td colspan="10">No item found</td>
              </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan="9">Total Paid</td>
                    <td>Rs. {{ $total }}</td>
                </tr>
            </tfoot>
          </tbody>
      </table>
</body>
</html>