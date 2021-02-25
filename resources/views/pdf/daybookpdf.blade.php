        
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
    <h3 style="margin-left: 40%">Daybook Report</h3>
    <table class="table" style="border">
        <thead>
            <tr>
                <td>Date</td>
                <td>From</td> 
                <td>Amount</td>
                <td>From</td> 
                <td>Item</td> 
                <td>Amount</td> 
                <td>To</td> 
                <td>For</td>  
                <td>Amount</td> 
            </tr>
        </thead>
        <tbody>
            @foreach($daybook as $data)
            <tr id="">
                <td>{{$data->date}}</td>
                <td>{{$data->collection_from?$data->collection_from:'------'}}</td> 
                <td>{{$data->collection_amount?$data->collection_amount:'------'}}</td>
                <td>{{$data->purchase_from?$data->purchase_from:'------'}}</td> 
                <td>{{$data->purchase_item?$data->purchase_item:'------'}}</td> 
                <td>{{$data->purchase_amount?$data->purchase_amount:'------'}}</td> 
                <td>{{$data->payment_to?$data->payment_to:'------'}}</td> 
                <td>{{$data->payment_for?$data->payment_for:'------'}}</td>  
                <td>{{$data->payment_amount?$data->payment_amount:'------'}}</td> 
            </tr>   
            @endforeach
            <tr>
                <th>Total</th> 
                <th colspan="2">Nrs. {{$daybook->sum('collection_amount')}}</th> 
                <th colspan="3">Nrs. {{$daybook->sum('purchase_amount')}}</th>
                <th colspan="3">Nrs. {{$daybook->sum('payment_amount')}}</th>  
            </tr>
        </tbody>
        <tfoot>
            <tr>
                
            </tr>
        </tfoot>
    </table>
</body>
</html>