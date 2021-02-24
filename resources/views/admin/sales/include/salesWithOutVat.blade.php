<table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Date</th>
                      <th>Email</th>
                      <th>Total</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @forelse($details as $key =>$detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{$key+1}}</td>
                      <td>{{$detail->client_name}}</td>
                      <td>{{$detail->contact}}</td>
                      <td>{{$detail->nepali_date}}</td>
                      <td>{{$detail->email}}</td>
                      <td>{{$detail->grand_total}}</td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6">No data Found!</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>