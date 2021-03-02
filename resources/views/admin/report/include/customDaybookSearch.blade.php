<table id="example2" class="table table-bordered table-striped">
  <thead>
      <th></th>
      <th colspan="2">Collectionsss</th> 

      <th colspan="3">Purchase</th>
    
      <th colspan="3">Paid</th>
  </thead>
  <tbody id="sortable">

    <tr id="" style="font-weight: 700; color: #666;">
        <td>Datessss</td>
        <td>From</td> 
        <td>Amount</td>
        <td>From</td> 
        <td>Item</td> 
        <td>Amount</td> 
        <td>To</td> 
        <td>For</td>  
        <td>Amount</td> 
    </tr>   
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
</table>