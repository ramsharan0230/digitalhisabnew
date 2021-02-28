<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
          <th>S.N.</th>
          <th>Sales To</th>
          <th>Bill No</th>
          <th>Date</th>
          <th>Taxable Amount</th>
          <th>Vat Collected</th>
          <th>Total</th>
          <th>VAT No.</th>
          
   
        </tr>
    </thead>
    <tbody id="sortable">
        @php($i=1)
        @foreach($details as $detail)
        <tr id="{{ $detail->id }}">
          <td>{{$i}}</td>
          <td>{{$detail->sales_to}}</td>
          <td>{{$detail->bill_no}}</td>
          <td>{{$detail->vat_date}}</td>
          <td>{{$detail->taxable_amount}}</td>
          <td>{{$detail->vat_paid}}</td>
          <td>{{$detail->total}}</td>
          <td>{{$detail->vat_pan}}</td>
        </tr>
        @php($i++)
        @endforeach
    </tbody>
    
    <tr>
      <td colspan="4"><h4>Total Sales =<b>123000 </b></h4> </td>
      <td colspan="4"><h4>Total Sales Vat = <b>1200</b></h4></td>
    </tr>
   
  </table>   