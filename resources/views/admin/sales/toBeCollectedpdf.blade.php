        
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
    <h3 style="margin-left: 40%">Invoice To be Collected Report</h3>
    <table class="table" style="border">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Clent</th>
                <th>Date</th>
                <th>Need To Be Collected</th>
                <th>Total</th>
                <th>Paid</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            @forelse ($details as $key=>$detail)
            <tr>
                <td>{{$key+1}}.</td>
                <td>{{$detail->client_name}}</td>
                <td>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $detail->date )->format('d-m-Y')}}</td>
                <td>{{$detail->grand_total-$detail->collected_amount}}</td>
                <td>{{$detail->grand_total}}</td>
                <td>{{$detail->collected_amount}}</td>
                <?php $total +=$detail->collected_amount; ?>
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