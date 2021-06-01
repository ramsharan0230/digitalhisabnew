<div class="card">
    <div class="card-header">
      <h3>Client Detail: </h3>
    </div>
    <div class="card-body">
      <span>Name: {{ $client->name }}</span>
      <span> Email: {{ $client->email }} </span><br>
      <span>Phones: </span>
      @foreach (json_decode($client->phone) as $phone)
          <span> {{ $phone }}, </span>
      @endforeach
      <br><span> Contact person: </span>
      @foreach (json_decode($client->contact_person) as $person)
          <span> {{ $person }}, </span>
      @endforeach
    </div>
    <hr>

    <div class="card-header">
      <h3>Client Invoices: </h3>
    </div>
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
              
              <th>S.N.</th>
              <th>Name</th>
              <th>Email</th>
              <th>Collected</th>
              <th>Total</th>
              <th>Invoice Paid</th>
       
            </tr>
        </thead>
        <tbody id="sortable">
        @forelse($invoices as $key=>$detail)
          <tr id="{{ $detail->id }}">
              <td>{{$key + 1}}</td>
              <td>{{$detail->client_name}}</td>
              <td>{{$detail->email}}</td>
              <td>{{$detail->collected_amount}}</td>
              <td>{{$detail->grand_total}}</td>
              <td>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Descriptions</th>
                      <th>Amount</th>
                      <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                  @forelse($detail->invoiceDetail as $key=>$paid)
                  <?php //  var_dump($invoice_detail) ?>
                  <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$paid->fee_description}}</td>
                    <td>{{$paid->fee_amount}}</td>
                    <td>{{$paid->created_at->toDateString()}}</td>
                  </tr>
                  @empty
                  <tr >
                    <td>Not Paid Yet!!</td>
                  </tr>
                  @endforelse
                </tbody>
                </table>
              </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">No invoice found!</td>
          </tr>
          @endforelse
        </tbody>
      </table>
     
    </div>

    <hr>

    <div class="card-header">
      <h3>Client Receiveds: </h3>
    </div>
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
              <th>S.N.</th>
              <th>Date</th>
              <th>Particular</th>
              <th>payment_type  </th>
              <th>amount</th>
            </tr>
        </thead>
        <tbody id="sortable">
        @forelse($invoice_receiveds as $key=>$received)
          <tr id="{{ $detail->id }}">
          <?php //dd($received, $invoice_receiveds) ?>

              <td>{{$key + 1}}</td>
              <td>{{@$received->date}}</td>
              <td>{{@$received->particular}}</td>
              <td>{{@$received->payment_type}}</td>
              <td>{{@$received->amount}}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5">No Received found!</td>
          </tr>
          @endforelse
        </tbody>
      </table>
     
    </div>
  </div>