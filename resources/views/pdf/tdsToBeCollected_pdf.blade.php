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
    <h3 style="margin-left: 40%">TDs to be Collected Report</h3>
    <table class="table" style="float:center; margin-left: 30%" >
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Amount</th>
                <th>From</th>
                <th>Bill No</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            @forelse ($details as $key=>$detail)
            <tr id="{{ $detail->id }}">
                <td>{{$key + 1}}</td>
                <td>{{$detail->amount}}</td>
                <td>{{$detail->company_name}}</td>
                <td>{{$detail->bill_no}}</td>
                <td>{{$detail->date}}</td>
            </tr>
            @empty
            
            <tr>
                <td colspan="5">No Sales Found!!</td>
            </tr>
            @endforelse
            
        </tbody>
        <tfoot>
            <tr>
                <td ><b>Total.</b> </td>
                <td colspan="4"><span style="margin-right: 0px; margin-left: 83%">Rs. <b>{{$details->sum('amount')}}</b></span></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>