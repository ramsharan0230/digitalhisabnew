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
                    <img src="http://webhousenepal.dhisab.com/public/images/logo.png" alt="Webhouse logo" style="width: 140px; height: 100px; text-align: left;">
                </td>
                <td style="text-align: right; font-size: 20px; text-transform: uppercase;">
                    <h2 style="font-weight: 900; text-align:right; color:#5757e7;">>Cost Estimation</h2>
                </td>
            </tr>
        </table>
    </section>
    <!-- customer detail table -->
    <section>
        
        <table style="width: 100%; font-size: 15px; margin-top: 20px;">
            <tr>
                <th style="text-align: left; font-weight: 500; padding:5px;"><span style="font-weight: 700;">Name : </span> {{$client_name}}</th>
                <th style="text-align: right; font-weight: 500; padding:5px; "><span style="font-weight: 700;">Date : </span> {{ Carbon\Carbon::parse($date)->format('F j, Y') }}</th>
            </tr>
            <tr>
                <th style="text-align: left;font-weight: 500; padding:5px;"> <span style="font-weight: 700;">Address : </span> {{$client_address}}</th>
                <th style="text-align: right;font-weight: 500; padding:5px;"> <span style="font-weight: 700;">Estimate Number : </span> {{$number}}</th>
            </tr>
            <tr>
                <th style="text-align: left;font-weight: 500;padding:5px; "> <span style="font-weight: 700;">Contact : </span> {{$contact}}</th>
            </tr>
        </table>
    </section>

    <!-- bill table -->
     
    <section>
        <table border= "1" style="width: 100%; border-collapse: collapse; border: 1px solid #000; ">
            <thead style="background: #343483; color:white;">
                <tr>
                    <th style="width: 40px; padding:5px 5px;">SN</th>
                    <th  style="width: 360px; padding:5px 5px;">Particular</th>
                    <th style="width:120PX; padding:0 15px;">Amount (Rs.)</th>
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



</section>
    <section style="margin:20px 0">
        <h2 style="font-size:17px;font-weight:600;margin:10px 0;text-align:center;display:block;">Statement</h2>
        <table style="width:100%; border: 1px solid #000;">
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
                    <td style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">{{ $key+1 }}.</td>
                    <td style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">{{ $payment->remarks }}</td>
                    <td style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">{{ $payment->payment_type }}</td>
                    <td style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">{{ $payment->paid_date }}</td>
                    <td style="background-color:#343483;padding:7px;color:#fff;font-size:14px;font-weight:600;">Rs. {{ $payment->paid_amount }}</td>
                </tr>
                @empty
                <tr style=" border: 1px solid #000;">
                    <td colspan="5" style="padding:15px;font-size:14px;text-align:center;">No Payment Found!</td>
                </tr>
                @endforelse
               
            </tbody>
            <tfoot>
                <th>
                    <tr style="border-top:1px solid #000; ">
                        <td colspan="3">
                            <p style="padding:5px;font-size:15px;">Total</p>
                        </td>
                        <td colspan="2"> <p style="padding:5px; font-size:15px;">Rs. {{ $total_payments }}</p></td>
                    </tr>
                </th>
            </tfoot>
        </table>
        <table  width="700">
            <tbody>
                <tr>
                    <td style="width:100px;">
                    <p style="font-size:14px;margin:0; ">Status :</p>
                    </td>
                    <td style="width:200px;">
                    <p style="font-size:14px">Open</p>
                    </td>
                    <td style="width:500px;">
                    <p style="font-size: 14px; margin: 0px;text-align: right;"><strong>Due Amount :</strong></p>
                    </td>
                    <td colspan="1" style="width:100px;  text-align:right;">
                    <p>Rs. {{$grand_total-$collected_amount}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        
    </section>
 
    <footer>
        <table style="width: 100%; border-top: 1px solid #cecece;">
            <tr>
                <td >
                    <span style="color:red; display:table; text-align:right; vertical-align:top"><strong>Note :</strong> This is not tax invoice</span>
                </td>
                <td>
                    <h2>{{$dashboard_setting->organization_name}}</h2>
                    <p  style="text-align: right;line-height:1"> <a href="" target="_blank" style="text-decoration: none; color: darkblue;">{{$dashboard_setting->website}}</a></p>
                    <p  style="text-align: right;line-height:1"> {{$dashboard_setting->address}}</p>
                    <p  style="text-align: right;line-height:1"> Phone: {{$dashboard_setting->phone_number}}</p>
                    <p style="line-height:1; text-align:right"><strong>Our mailing address is:</strong></p>
                    <div style="text-align: right;">{{$dashboard_setting->email}}</div>
                </td>
            </tr>
        </table>
    </footer>
</div>


</div>
</body>
</html>
