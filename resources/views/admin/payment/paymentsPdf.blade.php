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
    <h3 style="align:center">Payment Reports ({{ @$year }}-{{ @$month }})</h3>
    <table style="border; width:100%" >
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Date</th>
                <th>Paid To</th>
                <th>Payment Type</th>
                <th>Amount</th>
            </tr>
          </thead>
          <tbody>
              <?php $total = 0 ?>
            @forelse($details as $key =>$detail)
              <tr>
                <td>{{$key +1}}</td>
                <td>{{$detail->date}}</td>
                <td>{{$detail->paid_to}}</td>

                <td>{{$detail->payment_type}}</td>
                <td>{{$detail->amount}}</td>
                  <?php $total += $detail->amount ?>
              </tr>
              @empty
              <tr>
                  <td colspan="5">No item found</td>
              </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan="4">Total Paid</td>
                    <td>Rs. {{ $total }}</td>
                </tr>
            </tfoot>
          </tbody>
      </table>
</body>
</html>