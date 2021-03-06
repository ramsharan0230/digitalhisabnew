<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
       "http://www.w3.org/TR/html4/strict.dtd">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<div class="container" style="max-width: 600px; margin:0 auto; background: #f1edca; padding: 0px 15px 15px 15px; font-size: 16px; font-family: arial;" >
    <section>
        <table style="width: 100%; border-bottom: 5px solid #5757e7;">
            <tr>
                <td>
                    <img src="{{asset('images/'.$dashboard_setting->invoice_logo)}}" alt="" style="width: 140px; height: 70px; text-align: left;">
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
                <th style="text-align: left; font-weight: 500;"><span style="font-weight: 700;">Name : </span> {{$name}}</th>
                <th style="text-align: right; font-weight: 500;"><span style="font-weight: 700;">Date : </span> {{ Carbon\Carbon::parse($date)->format('F j, Y') }}</th>
            </tr>
            <tr>
                <th style="text-align: left;font-weight: 500; "> <span style="font-weight: 700;">Address : </span> {{$address}}</th>
                <th style="text-align: right; font-weight: 500;"><span style="font-weight: 700;">Invoice No.: </span> {{$number}}</th>
            </tr>
            <tr>
                <th style="text-align: left;font-weight: 500; "> <span style="font-weight: 700;">Contat : </span> {{$phone}}</th>
                <th style="text-align: right; font-weight: 500;"><span style="font-weight: 700;">Amount: </span> Rs. {{$grand_total}}</th>
            </tr>
        </table>
    </section>
    <!-- bill table -->
     
    <section>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; " border="1">
            <thead style="background: #343483; color:white;">
                <tr>
                    <th style="width: 100px; padding: 5px;">SN</th>
                    <th style="width: 360px; padding: 5px;">Title</th>
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
                    <td style="height: 200px; border-top: 0"></td>
                    <td style="height: 200px; border-top: 0;"></td>
                    <td style="height: 200px; border-top: 0;"></td>
                </tr>
            </tbody>   
        </table>
    </section>
    <section>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid black; margin-top: 5px;" border="1" >
            <thead>
                <tr>
                    <th style="width: 55.5%;font-weight: 500; font-size: 14px; text-align: left; padding: 5px;"><span style="font-weight: 700;">In Word : </span> {{$words}}</th>

                    <th style="width: 25%; background: skyblue;">
                        <table style="text-align: left; width: 100%;">
                            <tr>
                                <td style="width: 100%;">Total(Rs.) :</td>
                            </tr>
                            @if($vat!=0)
                            <tr>
                                <td style="width: 100%;">Vat 13%  :</td>
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 100%;">Grand Total(Rs.) :</td>
                            </tr>
                        </table>
                    </th>
                    <th style="width: 25%; text-align: right; background: skyblue;">
                        <table style="width: 100%; text-align: right;">
                            <tr>
                                <td>{{$total}}/-</td> 
                            </tr>
                            @if($vat!=0)
                            <tr>
                                <td>{{$vat}}/-</td> 
                            </tr>
                            @endif
                            <tr>
                                <td>{{$grand_total}}/-</td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </thead>
        </table>
    </section>
 
    <footer>
        <table style="text-align: right; width: 100%;">
            <tr>
                <td style="padding-top: 10px; font-size: 15px; margin-bottom: 0;">
                    <h2>{{$organization_name}}</h2>
                </td>
            </tr>
            <tr>
                <td style="">
                    <a href="" target="_blank" style="text-decoration: none; color: darkblue;">{{$website}}</a>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">
                    {{$office_address}}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">
                    {{$office_email}}
                </td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">
                    {{$office_phone}}
                </td>
            </tr>
        </table>
    </footer>
</div>
</body>
</html>
