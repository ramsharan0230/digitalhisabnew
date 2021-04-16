<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
       "http://www.w3.org/TR/html4/strict.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cost Estimation</title>
</head>

<body>
<div class="container" style="max-width: 700px; margin:0 auto; background: #f8f8ff; border:1px solid #e7e7ff; padding: 0px 15px 15px 15px; font-size: 16px; font-family: arial;" >
    <section>
        <table style="width: 100%; border-bottom: 5px solid #5757e7;">
            <tr>
                <td>
                    <img src="{{asset('images/'.$dashboard_setting->invoice_logo !=null? $dashboard_setting->invoice_logo:$dashboard_setting->logo)}}" alt="" style="width: 140px; height: 70px; text-align: left;">
                </td>
                <td style="text-align: right; font-size: 20px; text-transform: uppercase;">
                    <h2 style="font-weight: 900; color:#5757e7;">Cost Estimation</h2>
                </td>
            </tr>
        </table>
    </section>
    <!-- customer detail table -->
    <section>
        
        <table style="width: 100%; font-size: 15px; margin-top: 20px;">
            <tr>
                <th style="text-align: left; font-weight: 500;"><span style="font-weight: 700;">Name : </span> {{$client_name}}</th>
                <th style="text-align: right; font-weight: 500;"><span style="font-weight: 700;">Date : </span> {{ Carbon\Carbon::parse($date)->format('F j, Y') }}</th>
            </tr>
            <tr>
                <th style="text-align: left;font-weight: 500; "> <span style="font-weight: 700;">Address : </span> {{$client_address}}</th>
                <th style="text-align: right;font-weight: 500; "> <span style="font-weight: 700;">Estimate Number : </span> {{$number}}</th>
            </tr>
            <tr>
                <th style="text-align: left;font-weight: 500; "> <span style="font-weight: 700;">Contact : </span> {{$contact}}</th>
            </tr>
        </table>
    </section>
    <!-- bill table -->
     
    <section>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; " border="1px">
            <thead style="background: #343483; color:white;">
                <tr>
                    <th style="width: 100px; padding: 5px;">SN</th>
                    <th style="width: 360px; padding: 5px;">Particular</th>
                    <th style="padding: 5px;">Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                @php($i=1)
                @foreach($description as $desc)
                <tr>
                    <td style="padding: 5px; text-align: center; font-weight: 600; border-bottom: 0; border-top: 0;">{{$i}}</td>
                    <td style="padding: 5px;border-bottom: 0; border-top: 0;">{{$desc->fee_description}}</td>
                    <td style="padding: 5px; text-align: right;border-bottom: 0;border-top: 0;">{{$desc->fee_amount}}/-</td>
                </tr>
                @php($i++)
                @endforeach
                <tr>
                    <td style="height: 150px; border-top: 0"></td>
                    <td style="height: 150px; border-top: 0;"></td>
                    <td style="height: 150px; border-top: 0;"></td>
                </tr>
            </tbody>   
        </table>
    </section>
    <section>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; margin-top: 5px;" border="1" >
            <thead>
                <tr>
                    <th style="width: 55.5%;font-weight: 500; font-size: 14px; text-align: left; padding: 5px;"><span style="font-weight: 700;">In Word : </span> {{$words}}</th>

                    <th style="width: 25%; background: #a6e5ff;">
                        <table style="text-align: left; width: 100%;">
                            <tr>
                                <td style="width: 100%;">Total(Rs.) :</td>
                            </tr>
                                                     
                            @if($vat!=0)
                            <tr>
                                <td style="width: 100%;">Vat 13%  :</td>
                            </tr>
                            @endif
                            @if($grand_total)
                            <tr>
                                <td style="width: 100%;">Grand Total(Rs.) :</td>
                            </tr>
                            @endif
                            @if(count($invoicePayments)>0)
                                @foreach($invoicePayments as $payment)
                                <!-- <tr>
                                    <td style="width: 100%;font-size: 14px;font-weight: 300;font-style:italic;">Paid at {{$payment->paid_date}} :</td>
                                </tr> -->
                                @endforeach
                                

                                <!-- <tr>
                                    <td style="width: 100%;font-size: 14px;font-weight: 300;font-style:italic;">Due Amount :</td>
                                </tr> -->
                            @endif 
                        </table>
                    </th>
                    <th style="width: 25%; text-align: right; background: #a6e5ff;">
                        <table style="width: 100%; text-align: right;">
                            
                            <tr>
                                <td>{{$total}}/-</td> 
                            </tr>
                            @if($vat!=0)
                            <tr>
                                <td>{{$vat}}/-</td> 
                            </tr>
                            @endif
                            @if($grand_total)
                            <tr>
                                <td>{{$grand_total}}/-</td>
                            </tr>
                            @endif
                            @if(count($invoicePayments)>0)
                            @foreach($invoicePayments as $payment)
                            <!-- <tr>
                                <td>{{$payment->paid_amount}}/-</td> 
                            </tr> -->
                            @endforeach
                            <?php
                            dd($collected_amount, $grand_total);
                                $remaining_amount=$grand_total-$collected_amount;
                            ?>
                            <!-- <tr>
                                <td>{{$remaining_amount}}/-</td> 
                            </tr> -->
                            @endif
                        </table>
                    </th>
                </tr>
            </thead>
        </table>
    </section>

    <section style="margin:20px 0">
        <h2 style="font-size:17px;font-weight:600;margin:10px 0;text-align:center;display:block;">Statement</h2>
        <table style="width:100%; border:1px solid #000;">
            <thead style="background:#343483;">
                <tr style="background:#343483;">
                    <th style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">SN.</th>
                    <th style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">Remark</th>
                    <th style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">P. Type</th>
                    <th style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">Date</th>
                    <th style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">Paid</th>
           
                </tr>
            </thead>
            <tbody>
                <?php $total_payments = 0 ?>
                @forelse($invoicePayments as $key =>$payment)
                <?php $total_payments +=$payment->paid_amount ?>
                <tr style="border:1px;border-color: #ccc;">
                    <td style="padding:7px;font-size:14px;text-align:center;">{{ $key+1 }}.</td>
                    <td style="padding:7px;font-size:14px;text-align:center;">{{ $payment->remarks }}</td>
                    <td style="padding:7px;font-size:14px;text-align:center;">{{ $payment->payment_type }}</td>
                    <td style="padding:7px;font-size:14px;text-align:center;">{{ $payment->paid_date }}</td>
                    <td style="padding:7px;font-size:14px;text-align:center;">Rs. {{ $payment->paid_amount }}</td>
                </tr>
                @empty
                <tr style="border:1px;border-color: #ccc;">
                    <td colspan="6" style="padding:7px;font-size:14px;text-align:center;">No Payment Found!</td>
                </tr>
                @endforelse
               
            </tbody>
            <tfoot>
                <th>
                    <tr>
                        <td colspan="4">
                            <span class="float-right">Total</span>
                        </td>
                        <td>Rs. {{ $total_payments }}</td>
                    </tr>
                </th>
            </tfoot>
        </table>
        <div style="display:flex;justify-content:space-between;padding:10px 0;">
            <div style="display:flex;">
                <p style="font-size:14px;margin:0; width:90px;">Status :</p>
                <span style="font-size:14px;">Open</span>
            </div>
            <div style="display:flex;">
                <p style="font-size:14px;margin:0;width:120px;"><b>Due Amount :</b></p>
                <span style="font-size:14px;"><b>Rs. {{$grand_total-$collected_amount}}</b></span>
            </div>
        </div>
    </section>
 
    <footer>
        <table style="text-align: right; width: 100%;">
            <tr>
                <td >
                    <span style=" color: red; float: left;"><strong>Note :</strong> This is not tax invoice</span>
                </td>
                <td>
                    <h2>{{$dashboard_setting->organization_name}}</h2>
                    <p> <a href="" target="_blank" style="text-decoration: none; color: darkblue;">{{$dashboard_setting->website}}</a></p>
                    <p> {{$dashboard_setting->address}}</p>
                    <p>  {{$dashboard_setting->email}}</p>
                    <p> Phone: {{$dashboard_setting->phone_number}}</p>
                </td>
            </tr>
        </table>
    </footer>
</div>
</body>
</html>
