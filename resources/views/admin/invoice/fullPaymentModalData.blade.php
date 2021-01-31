<form method="post" action="{{route('saveFullAmount')}}">
	{{csrf_field()}}
    <div class="form-group">
  		<input type="hidden" name="invoice_id" value="{{$invoice->id}}">
      	<label>Total Amount</label>
      	<input type="text" name="full_amount" class="form-control full_amount" value="{{$invoice->grand_total}}" readonly="">
    </div>	
  	<div class="form-group">
		
    	<label>Amount To Be Paid</label>
    	<input type="text" name="full_amount" class="form-control remaining_amount" value="{{$invoice->grand_total}}" readonly="">
  	</div>
    <div class="form-group">
        <label>Payment Type</label>
        <select name="payment_type" class="form-control payment_type">
            <option value="Cash">Cash</option>
            <option value="Cheque">Cheque</option>
            <option value="Diposited">Diposited</option>
            <option value="Transfered">Transfered</option>
        </select>
    </div>
    <div class="type hidden">
      <div class="form-group">
        <label>Transfer Type</label>
        <select class="form-control transfer_type" name="transfer_type">
          <option>Select Transfer type</option>
          <option value="bank">Bank</option>
          <option value="wallet">Digital Wallets</option>
        </select>
      </div>
      <div class="form-group wallet hidden">
        <label>Select Digital Wallet</label>
        <select name="digital_wallet" class="form-control">
          @foreach($digital_wallet as $wallet)
          <option value="{{$wallet->id}}">{{$wallet->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group transfer_bank hidden">
        <label>Bank</label>
        <select name="transfer_bank" class="form-control">
          @foreach($dashboard_bank as $bank)
          <option value="{{$bank->id}}">{{$bank->name}}</option>
          @endforeach
        </select>
      </div>
    </div>
    
    <div class="form-group cheque_of_bank hidden">
      <label>Cheque Of Bank</label>
      <select name="cheque_of_bank" class="form-control">
        @foreach($dashboard_bank as $bank)
        <option value="{{$bank->name}}">{{$bank->name}}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group bank hidden">
      <label>Bank</label>
      <select name="bank" class="form-control">
        @foreach($dashboard_bank as $bank)
        <option value="{{$bank->id}}">{{$bank->name}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>Remarks</label>
      <input type="text" name="particular" class="form-control">
    </div>
    
    <div class="form-group">
      <label>Date</label>
      <input type="text" id="nepaliDate1" class="bod-picker form-control" name="date" autocomplete="off" value="{{old('date')}}">
    </div>
    <!-- <div class="form-group">
      <label>Narration</label>
      <input type="text" name="narration" value="" class="form-control">
    </div> -->
    <div class="row">
     <div class="col-md-6">
       <div class="form-group deposited_at">
         <div>Diposited At</div>
         <select name="deposited_at_bank" class="form-control">
           @foreach($dashboard_bank->where('our_bank',1) as $bank)
           <option value="{{$bank->id}}">{{$bank->name}}</option>
           @endforeach
         </select>
       </div>
     </div> 
     <div class="col-md-6">
       <div class="form-group officeDeposit">
        <label for="vatShow"><input type="checkbox" id="vatShow" name="deposited_at_office" value="1"> Keep At Office</label>
      </div>
     </div> 
    </div>
    <div class="form-group">
      <label for="tdsShow"><input type="checkbox" id="tdsShow" name="tdsShow" value="1" > Tds</label>
    </div>
    <div class="form-group hidden tds_amount">
      <label>Tds Amount</label>
      <input type="text" name="tds" class="form-control">
    </div>
  	<!-- <div class="form-group">
    	<label>Amount</label>
    	<input type="text" name="amount" class="form-control amount">
  	</div> -->
  	<div class="form-group">
  		<input type="submit" name="submit" class="btn btn-success submitButton" value="submit" >
  	</div>
</form>
<script type="text/javascript">
  $(".bod-picker").nepaliDatePicker({
      dateFormat: "%y-%m-%d",
      closeOnDateSelect: true,
      // minDate: formatedNepaliDate
    });
    $(document).ready(function(){
      $('.payment_type').on('change',function(){
        value = $(this).val();
        //for deposited
        if(value=='Diposited'){
          $('.bank').removeClass('hidden');
          $('.cheque_of_bank').addClass('hidden');

          $('.deposited_at').addClass('hidden');
          $('.type').addClass('hidden');
          $('.officeDeposit').addClass('hidden');
        }
        //for cheque
        if(value=='Cheque'){
          $('.bank').addClass('hidden');
          $('.cheque_of_bank').removeClass('hidden');
          $('.deposited_at').removeClass('hidden');
          $('.type').addClass('hidden');
          $('.officeDeposit').addClass('hidden');
        }
        if(value=='Cash'){
          $('.deposited_at').removeClass('hidden');
          $('.cheque_of_bank').addClass('hidden');
          $('.bank').addClass('hidden');
          $('.type').addClass('hidden');
          $('.officeDeposit').removeClass('hidden');
        }
        if(value=='Transfered'){
          $('.deposited_at').addClass('hidden');
          $('.cheque_of_bank').addClass('hidden');  
          $('.bank').addClass('hidden');
          $('.type').removeClass('hidden');
          $('.officeDeposit').addClass('hidden');
        }
      });
      $(document).ready(function(){
        $('.transfer_type').on('change',function(){
          value = $(this).val();
          if(value=='bank'){
            $('.wallet').addClass('hidden');
            $('.transfer_bank').removeClass('hidden');
          }
          if(value=='wallet'){
            $('.wallet').removeClass('hidden');
            $('.transfer_bank').addClass('hidden');
          }
        });
      });
      $('input[name="tdsShow"]').click(function(){
        var tdsShowChecked = $('input[name="tdsShow"]:checked').val();
        if(tdsShowChecked==1){
          $('.tds_amount').removeClass('hidden');
        }else{
          $('.tds_amount').addClass('hidden');
        }
        
      });
      $('input[name="tdsShow"]').click(function(){
        var tdsShowChecked = $('input[name="tdsShow"]:checked').val();
        if(tdsShowChecked==1){
          $('.tds_amount').removeClass('hidden');
        }else{
          $('.tds_amount').addClass('hidden');
        }
        
      });
      $('input[name="deposited_at_office"]').click(function(){
        var depositedAtOfficeChecked = $('input[name="deposited_at_office"]:checked').val();
        if(depositedAtOfficeChecked==1){
          $('.deposited_at').addClass('hidden');
        }else{
          $('.deposited_at').removeClass('hidden');
        }
        
      });
    });


</script>