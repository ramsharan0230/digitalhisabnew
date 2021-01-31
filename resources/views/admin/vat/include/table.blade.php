<table id="example1" class="table table-bordered table-striped">
	<thead>
			<tr>
        <th>S.N.</th>
        <th>Purchased From</th>
        <th>Bill No</th>
        <th>Date</th>
        <th>Taxable Amount</th>
        <th>Vat Paid</th>
        <th>Total</th>
        <th>Round Total(VAT)</th>
        @if(!isset($value))
        <th>Action</th>
        @endif
 
			</tr>
	</thead>
	<tbody id="sortable">
	@php($i=1)
	@foreach($details as $detail)
    
		<tr id="{{ $detail->id }}">
				<td>{{$i}}</td>
				<td>{{$detail->purchased_from}}</td>
        <td>{{$detail->bill_no}}</td>
        <td>{{$detail->vat_date}}</td>
        <td>{{$detail->taxable_amount}}</td>
			  <td>{{$detail->vat_paid}}</td>
        <td>{{$detail->total}}</td>
        <td>{{$detail->round_total}}</td>

        @if(!isset($value))
				<td class="whole-btn-wrapper">
          @if($detail->collected_type==null)
  				<a class="btn btn-info edit" href="{{route('vat.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
  				<form method= "post" action="{{route('vat.destroy',$detail->id)}}" class="delete btn btn-danger">
  				{{csrf_field()}}
  				<input type="hidden" name="_method" value="DELETE">
  				<button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
  				</form>
          @endif
          @if($detail->collected!=1)
          <div class="form-group" id="select{{$detail->id}}">
            <select class="form-control purchasePayment">
              <option disabled="true" selected="true">Make Payment</option>
              <option value="partial" data-id="{{$detail->id}}">Partial</option>
              @if($detail->collected_type!='partial')
              <option value="full" data-id="{{$detail->id}}">Full</option>
              @endif
            </select>
          </div>
          @endif
          <a href="{{route('purchasePaymentList',$detail->id)}}" class="btn btn-success">Payments</a>

          
				</td>
        @endif
		</tr>
		@php($i++)
		@endforeach
    
	</tbody>
  @if(isset($value))
    <tr>
      <td colspan="4">total amount</td>
      <td>{{$total_taxable_amount}}</td>
      <td>{{$purchase_vat}}</td>
      <td>{{$total}}</td>
      <td>{{$total_round_total}}</td>
    </tr>
    <tr>
      <td colspan="4">Total Sales= <b>({{$total_sales}})</b></td>
      <td colspan="4">Total Sales Vat = <b>{{$sales->sum('vat_paid')}}<b></td>
    </tr>
    <tr>
      <td colspan="4">Total Purchase= <b>({{$details->sum('total')}})</b> </td>
      <td colspan="4">Total Purchase Vat = <b>{{$details->sum('vat_paid')}}</b></td>
    </tr>
    <tr>
      <td colspan="8">Conclusion(sales vat - purchase vat)= <b>{{$difference}}</b></td>
    </tr>
  @else
    <tr>
    <?php
      
        $purchaseVat=$details->sum('vat_paid');
        $sales_vat=$salesVat->sum('vat_paid');
        $total_purchase=$details->sum('total');
        $difference=$sales_vat-$purchaseVat;

    ?>
    
    <td colspan="4">Total Sales= <b>({{$total_sales}})</b></td>
    <td colspan="4">Total Sales Vat = <b>{{$sales_vat}}</b></td>
    <tr>
      <td colspan="4">Total Purchase= <b>({{$total_purchase}})</b> </td>
      <td colspan="4">Total Purchase Vat = <b>{{$purchaseVat}}</b></td>
    </tr>
    <tr>
      <td colspan="8">Conclusion(sales vat - purchase vat)= <b>{{$difference}}</b></td>
    </tr>

  @endif
</table>
@include('admin.purchasePayment.include.modal')
@push('script')
<script type="text/javascript">
  $(document).ready(function(){
    $(document).on('change','.purchasePayment',function(e){
      e.preventDefault();
      
      
      if($(this).val()=='partial'){
        
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('partialPurchasePayment')}}",
          data:{id:id},
          success:function(data){
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
                });
              $('.purchasePayment').prop('selectedIndex',0);
            }
            
          }
        });
      }else{
        id=$(this).find(':selected').data('id');
        $.ajax({
          method:"post",
          url:"{{route('fullPurchasePayment')}}",
          data:{id:id},
          success:function(data){
            console.log(data.html);
            if(data.message=='success'){
              $('#myModal .modal-body').html(data.html);
              $('#myModal').modal('show').on('hide', function() {
                $('#new_passenger').modal('hide')
              });
              $('.collectionWithVat').prop('selectedIndex',0);
              //$('#select'+id).addClass('hidden');
            }
          }
        });
      }


      $(document).on('keyup','.amount',function(){
        remaining_amount=$('.remaining_amount').val();
        if(parseInt(remaining_amount)>=parseInt($(this).val())){
          $('.submitButton').removeClass('hidden');
        }else{
          $('.submitButton').addClass('hidden');
        }
      });
    });
  });
</script>
@endpush