<b>To be collected = <?php echo $invoice;?></b>
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
  @if(isset($value))
    <tr>
      <td colspan="4">total amount</td>
      <td>{{$total_taxable_amount}}</td>
      <td>{{$sales_vat}}</td>
      <td>{{$total}}</td>
      <td></td>
    </tr>
      <tr>
        <td colspan="4">
          <h4>Total Sales =<b>({{$total_sales}}) </b></h4>
          
        </td>
        <td colspan="4">
          <h4>Total Sales Vat = <b>{{$details->sum('vat_paid')}}</b></h4>
        </td>
      </tr>
    <tr>
      <td colspan="4">
        <h4>Total Purchase = <b>({{$purchase->sum('total')}})</b></h4> 
      </td>
      <td colspan="4">
        <h4>Total Purchase Vat = <b>{{$purchase->sum('vat_paid')}}</b></h4>
      </td>
    </tr>
    <tr>
      <td colspan="8">
      <h4>Conclusion(sales vat - purchase vat)= <b>{{$difference}}</b></h4>
      </td>
      
    </tr>
      @else
      <?php
      
        $salesVat=$details->sum('vat_paid');
        $purchase_vat=$purchaseVat->sum('vat_paid');
        $difference=$salesVat-$purchase_vat;
        $salesTotal=$details->sum('total');
        $purchase_total=$purchaseVat->sum('total');

    ?>
    
    <td colspan="4"><h4>Total Sales =<b>({{$total_sales}})</b></h4> </td>
    <td colspan="4"><h4>Total Sales Vat = <b>{{$salesVat}}</b></h4></td>
  </tr>
  <tr>
    <td colspan="4"><h4>Total Purchase  = <b>({{$purchase_total}})</b></h4> </td>      
    <td colspan="4"><h4>Total Purchase Vat = <b>{{$purchase_vat}}</b></h4></td>
  </tr>
    <td colspan="8"><h4>Conclusion(sales vat - purchase vat)= <b>{{$difference}}</b></h4></td>
</tr>

    @endif
</table>