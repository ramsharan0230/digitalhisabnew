<table id="example1" class="table table-bordered table-striped">
  <thead>
      <tr>
        <th>Months</th>
        <th>Invoiced Amount</th>
        <th>Added Receipt</th>
        <th>Total Purchased</th>
        <th>Payment</th>
        <th>Profit</th>
      
      </tr>
  </thead>
  
  <tbody id="sortable">
  
    
     <tr>
       <td>{{ ucfirst($months[$monthValue]) }}</td>
       <td>{{$invoice}}</td>
       <td>{{$other_received}}</td>
       <td>{{$purchase}}</td>
       <td>{{$payment}}</td>
       <td>
         {{$total}}
       </td>
     </tr>
  </tbody>
</table>