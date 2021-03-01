        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Annual Report</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px
        }
    </style>
</head>
<body>
    <h3 style="margin-left: 40%">Sales Report</h3>
    <table class="table" style="border">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Sales To</th>
                <th>Amount</th>
                <th>Due</th>
                <th>Billing Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            @forelse ($details as $key=>$detail)
            <tr>
                <td>{{$key+1}}.</td>
                <td>{{$detail->sales_to}}</td>
                <td>{{ $detail->vat_date }}</td>
                <td>{{$detail->total-$detail->collected}}</td>
                <td>{{($detail->total-$detail->collected)>0?"Open":"Close"}}</td>
                <td>{{ $detail->total }}</td>
                <?php $total +=$detail->total; ?>
            </tr>
            @empty
            
            <tr>
                <td colspan="6">No Sales Found!!</td>
            </tr>
            @endforelse
            
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><b>Total Paid</b> </td>
                <td><span style="margin-right: 0px; ">Rs. <b>{{ $total }}</b></span></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>