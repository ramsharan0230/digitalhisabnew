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
    <h3 style="margin-left: 40%">Particular Purchased Report</h3>
    <table class="table" style="border">
        <thead>
            <tr>
                <th>SN.</th>
                <th>Particular</th>
                <th>Purchased From</th>
                <th>Purchased Item</th>
                <th>Bill Number</th>
                <th>Taxable Amount</th>
                <th>Vat Paid</th>
                <th>Date</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            @forelse ($details as $key=>$detail)
            <tr>
                <?php //dd($detail) ?>
                <td>{{ $key+1 }}.</td>
                <td>{{ $detail->particular }}</td>
                <td>{{ $detail->purchased_from }}</td>
                <td>{{ $detail->purchased_item }}</td>
                <td>{{ $detail->bill_no }}</td>
                <td>{{ $detail->taxable_amount }}</td>
                <td>{{ $detail->vat_paid }}</td>
                <td>{{ $detail->vat_date }}</td>
                <td>{{ $detail->total }}</td>
                <?php $total +=$detail->total ?>
            </tr>
            @empty
            
            <tr>
                <td colspan="9">No Sales Found!!</td>
            </tr>
            @endforelse
            
        </tbody>
        <tfoot>
            <tr>
                <td><b>Total.</b> </td>
                <td colspan="8"><span style="margin-right: 5px; margin-left: 85%">Rs. <b>{{ $total }}</b></span></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>