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
              <th>Bill no</th>
              <th>Date</th>
              <th>Vat Amount</th>
            </tr>
        </thead>
        <tbody id="sortable">
        
        @forelse($details as $key =>$detail)
          <tr id="{{ $detail->id }}">
              <td>{{$key+1}}</td>
              <td>{{$detail->purchased_from}}</td>
              <td>{{$detail->bill_no}}</td>
              <td>{{$detail->vat_date}}</td>
              <td>{{$detail->vat_paid}}</td>
          </tr>
          @empty
          <tr>
              <td colspan="5">No data found!</td>
          </tr>
          @endforelse
          <tfoot>
            <tr>
                <td colspan="4">Total. </td>
                <td >Rs. {{ $detail->vat_paid }}</td>
            </tr>
          </tfoot>
        </tbody>
      </table>
</body>
</html>