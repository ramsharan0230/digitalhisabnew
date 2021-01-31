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
      <td>{{$sale->vat_date}}</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Sales To</td>
      <td>{{$sale->sales_to}}</td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Taxable </td>
      <td>{{$sale->total}}</td>
    </tr>
    <tr>
      <th scope="row">4</th>
      <td>Taxable Amount </td>
      <td>{{$sale->taxable_amount}}</td>
    </tr>
    <tr>
      <th scope="row">5</th>
      <td>Vat Paid </td>
      <td>{{$sale->vat_paid}}</td>
    </tr>
    <tr>
      <th scope="row">6</th>
      <td>Grand Total </td>
      <td>{{$sale->round_total}}</td>
    </tr>
    <tr>
      <th scope="row">7</th>
      <td>Total Paid</td>
      <td>{{$sale->invoice->collected_amount}}</td>
    </tr>
    
  </tbody>
</table>