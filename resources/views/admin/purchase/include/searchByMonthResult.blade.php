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
                  <th>Action</th>
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
                </tr>
                @php($i++)
                @endforeach
              </tbody>
              <?php

              $sales = \App\Models\Sales::orderBy('created_at','desc')->get();
              $total_sales=$sales->sum('total');
              $salesVat=$sales->sum('vat_paid');
              $purchase_vat = $details->sum('vat_paid');
              $difference = $salesVat-$purchase_vat;

              ?>
              <tr>
                <!-- <td colspan="4"><h4>Total Sales =<b>({{$total_sales}}) </b></h4></td> -->
                <!-- <td colspan="5"><h4>Total Sales Vat = <b>{{$salesVat}}</b></h4></td> -->
              </tr>
              <tr>
                <td colspan="4"><h4>Total Purchase = <b>({{$details->sum('total')}})</b></h4> </td>
                <td colspan="5"><h4>Total Purchase Vat = <b>{{$purchase_vat}}</b></h4></td>
              </tr>
              <tr>
                <!-- <td colspan="9"><h4>Conclusion(sales vat - purchase vat)= <b>{{$sales_vat-$purchase_vat}}</b></h4></td> -->
              </tr>
            </table>  