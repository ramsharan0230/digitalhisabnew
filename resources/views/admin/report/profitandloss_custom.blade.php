<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
          <th>Months</th>
          <th>Invoiced Amount</th>
          <th>Added Receipt</th>
          <th>Total Purchased</th>
          <th>Payment</th>
          <th>Total</th>
        
        </tr>
    </thead>
    
    <tbody id="sortable">
      @php($total = 0)
      @foreach($vatInvoices as $key=>$invoice)
      
      <tr>
        <td>{{$months[$key]}}</td>
        <td>{{$invoice}}</td>
        <td>{{$other_receiveds[$key]}}</td>
        <td>{{$purchases[$key]}}</td>
        <td>{{$payments[$key]}}</td>
        <td>
          <?php
          $ans = ($invoice+$other_receiveds[$key])-($purchases[$key]+$payments[$key]);
          $total+=$ans;
          ?>
          <span style="color:{{ $ans<0?"red":"" }}">{{$ans}}</span>
        </td>
        
      </tr>
        
      @endforeach
    
    </tbody>
  </table>

  <div class="table-total-wrapper">
      <p>Total :</p>
      <span>{{$total}}</span>
  </div>