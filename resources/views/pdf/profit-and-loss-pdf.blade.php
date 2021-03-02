        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profit and Lost Report</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px
        }
    </style>
</head>
<body>
    <h3 style="margin-left: 40%">Profit and Lost Report</h3>
      <table id="example1" class="table table-bordered table-striped" style="float: center">
        <thead>
            <tr>
              <th>Months</th>
              <th>Invoiced Amount</th>
              <th>Added Receipt</th>
              <th>Total Purchased</th>
              <th>Payment</th>
            
            </tr>
        </thead>
        
        <tbody id="sortable">
           <tr>
             <td>{{ ucfirst($months[$month]) }}</td>
             <td>Rs. {{$invoice}}</td>
             <td>Rs. {{$other_received}}</td>
             <td>Rs. {{$purchase}}</td>
             <td>Rs. {{$payment}}</td>
           </tr>
        </tbody>
      </table>
</body>
</html>