<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Report</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px
        }
    </style>
</head>
<body>
    <h3 style="align:center">Sales Report ({{ @$start_date }}-{{ @$end_date }})</h3>
    <table style="border; width:100%" >
        <thead>
            <tr>
              <th>S.N.</th>
              <th>Date</th>
              <th>Sales To</th>
              <th>Bill No</th>
              <th>Billing Status</th>
              <th>Vat Paid</th>
              <th>Amount</th>

            </tr>
          </thead>
          <tbody>
              <?php $total = 0 ?>
            @forelse($details as $key =>$detail)
              <tr>
                  <?php //dd($detail) ?>
                  <td>{{$key +1}}</td>
                  <td>{{$detail->vat_date}}</td>
                  <td>{{$detail->sales_to}}</td>
                  <td>{{$detail->bill_no}}</td>   
                  <td>{{($detail->round_total-$detail->total_paid)>0?"Open":"Close"}}</td>
                  <td>{{$detail->vat_paid}}</td>
                  <td>{{$detail->round_total}}</td>
                  <?php $total += $detail->round_total ?>
              </tr>
              @empty
              <tr>
                  <td colspan="7">No item found</td>
              </tr>
            @endforelse
            <tfoot>
                <tr>
                    <td colspan="6"><strong>Total.</strong></td>
                    <td>Rs. <strong>{{ $total }}</strong></td>
                </tr>
            </tfoot>
          </tbody>
      </table>
</body>
</html>