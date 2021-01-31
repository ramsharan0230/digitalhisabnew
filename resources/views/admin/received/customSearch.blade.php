<table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>From</th>
                      <th>Particular</th>
                      <th>Payment Type</th>
                      <th>Date</th>
                      <th>Amount</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @php($i=1)
                @foreach($details as $detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$i}}</td>
                      <td>{{$detail->received_from}}</td>
                      <td>{{$detail->particular}}</td>
                      <td>{{$detail->payment_type}}</td>
                      <td>{{$detail->date}}</td>
                      <td>{{$detail->amount}}</td>
                      <td>
                        <a class="btn btn-warning view " title="Edit"
                            data-id="{{$detail->id}}"
                            >
                            <span class="fa fa-eye"></span>
                        </a>
                      </td>
                  </tr>
                  @php($i++)
                  @endforeach
                </tbody>
              </table>