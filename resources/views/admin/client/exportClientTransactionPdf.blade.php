<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Client Invoice Business Detail</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px
        }
    </style>
</head>
<body>
    <h3 style="align:center">Client Invoice Reports ({{ @$year }}-{{ @$month }})</h3>
    <table id="example1" class="table table-bordered table-striped" style="width: 100%">
        <thead>
            <tr>
              <th>S.N.</th>
              <th>Created Date</th>
              <th>Billing Status</th>
              <th>Amount</th>
            </tr>
        </thead>
        <tbody >
            <?php $total = 0; ?>
        @forelse($invoices as $key=>$detail)
          <tr>
              <td>{{$key+1}}.</td>
              <td>{{$detail->nepali_date}}</td>
              <td>{{$detail->collected==$detail->grand_total?'Closed':'Open'}}</td>
              <td>{{$detail->grand_total}}</td>
              <?php $total += $detail->grand_total ?>
          </tr>
          @empty
          <tr>
              <td colspan="4">No Data Found!</td>
          </tr>
          @endforelse
          <tr>
              <td colspan="3"><strong>Total.</strong> </td>
              <td>Rs. <strong>{{ $total }}</strong></td>
          </tr>
        </tbody>
    </table>
</body>
</html>