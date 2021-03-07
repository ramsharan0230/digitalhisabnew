<table class="table table-bordered">
    <thead>
      <tr>
        <th>Heading</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
      <tr class="success">
        <td>Paid To</td>
        <td>{{$detail->paid_to}}</td>
      </tr>
      <tr class="danger">
        <td>Payment Type</td>
        <td>{{$detail->payment_type}}</td>
      </tr>
      @if($detail->payment_type=='Cheque')
      <tr class="info" >
        <td>Bank</td>
        <td class="bank_name">{{$detail->cheque_of_bank}}</td>
      </tr>
      @endif
      <tr class="warning">
        <td>Amount</td>
        <td>{{$detail->amount}}</td>
      </tr>
      <tr class="success">
        <td>Narration</td>
        <td>{{$detail->narration}}</td>
      </tr>
      <tr class="danger">
        <td>Date</td>
        <td>{{$detail->date}}</td>
      </tr>
      @if($detail->payment_type!='Cash')
      @if($detail->paymentgateway_id==null)
      
      @endif
      @endif

      
      <!-- <tr class="success">
        <td>Start Date</td>
        <td>{{$detail->start_date}}</td>
      </tr>
      <tr class="danger">
        <td>End Date</td>
        <td>{{$detail->renew_date}}</td>
      </tr>  
      <tr class="warning">
        <td>Fee</td>
        <td>{{$detail->fee}}</td>
      </tr>  
      <tr class="warning">
        <td>Remark</td>
        <td>{!!$detail->remark!!}</td>
      </tr> 
      <tr class="danger">
        <td>Who Is</td>
        <td>{{$detail->who_is}}</td>
      </tr>   -->
    </tbody>
  </table>