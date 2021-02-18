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
    <h3 style="margin-left: 40%">Annual Report</h3>
    <table class="table" style="border">
        <thead>
            <tr>
                <th>SN.</th>
                <th>Sales To</th>
                <th>Date</th>
                <th>Billing Status</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            @forelse ($details as $key=>$detail)
            <tr>
                <td>{{ $key+1 }}.</td>
                <td>{{ $detail->sales_to }}</td>
                <td>{{ $detail->vat_date }}</td>
                <td>{{ $detail->collected==0?"Open":"Close" }}</td>
                <td>Rs. {{ $detail->total }}</td>
                <?php $total +=$detail->total ?>
            </tr>
            @empty
            
            <tr>
                <td colspan="5">No Sales fgdfgdfgFound!!</td>
            </tr>
            @endforelse
            
        </tbody>
        <tfoot>
            <tr>
                <td><b>Total.</b> </td>
                <td colspan="4"><span style="margin-right: 0px; margin-left: 83%">Rs. <b>{{ $total }}</b></span></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>