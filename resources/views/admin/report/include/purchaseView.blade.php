<table class="table table-dark">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Date</td>
      <td>{{$purchase->vat_date}}</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Purchased From</td>
      <td>{{$purchase->purchased_from}}</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Purchased Item</td>
      <td>{{$purchase->purchased_item}}</td>
    </tr>
    <tr>
      <th scope="row">4</th>
      <td>Amount </td>
      <td>{{$purchase->total}}</td>
    </tr>
    
    <tr>
      <th scope="row">7</th>
      <td>Total Paid</td>
      <td>{{$purchase->total_amount_of_purchase_amount_paid}}</td>
    </tr>
    
  </tbody>
</table>