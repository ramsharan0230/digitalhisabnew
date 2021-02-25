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
    <h3 style="margin-left: 40%">Vat Paid Report ({{ $year }}-{{ $month }})</h3>
    <table style="border; width:100%" >
        <thead>
            <tr>
              <th>S.N.</th>
              <th>To</th>
              <th>Vat Pan</th>
              <th>Date</th>
              <th>Vat Amount</th>
            </tr>
        </thead>
        <tbody id="sortable">
        <?php $total = 0 ?>
        @forelse($details as $key =>$detail)
          <tr>
              <?php //dd($detail) ?>
              <td>{{$key+1}}</td>
              <td>{{$detail->sales_to}}</td>
              <td>{{$detail->vat_pan}}</td>
              <td>{{$detail->vat_date}}</td>
              <td>Rs. {{$detail->vat_paid}}</td>
              <?php $total +=$detail->vat_paid; ?>
          </tr>
          @empty
          <tr>
              <td colspan="5">No data found!</td>
          </tr>
          @endforelse
          <tfoot>
            <tr>
                <td colspan="4">Total. </td>
                <td >Rs. {{ $total }}</td>
            </tr>
          </tfoot>
        </tbody>
      </table>
</body>
</html>