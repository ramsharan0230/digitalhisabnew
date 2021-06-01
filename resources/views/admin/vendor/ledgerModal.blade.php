<div class="card">
    <div class="card-header">
      <h3>Vendor Detail: </h3>
    </div>
    <div class="card-body">
      @if($vendor)
      <span> Name: {{ $vendor->name }}</span>
      <span> Email: {{ $vendor->email }} </span><br>
      <span> Phones: {{ $vendor->phone }}</span><br>
      <span> Address: {{ $vendor->address }}</span>
      @endif
    </div>
    <hr>

    <div class="card-header">
      <h3>Purchases Details: </h3>
    </div>
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
              
              <th>S.N.</th>
              <th>Particular</th>
              <th>Purchased_from</th>
              <th>Purchased_item</th>
              <th>Bill Number</th>
              <th>Total Paid</th>
       
            </tr>
        </thead>
        <tbody id="sortable">
        @forelse($purchases as $key=>$detail)
          <tr id="{{ $detail->id }}">
              <td>{{ $key + 1}}</td>
              <td>{{ $detail->particular}}</td>
              <td>{{ $detail->purchased_from}}</td>
              <td>{{ $detail->purchased_item}}</td>
              <td>{{ $detail->bill_no}}</td>
              <td>{{ $detail->total_paid}}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6">No Purchase found!</td>
          </tr>
          @endforelse
        </tbody>
      </table>
     
    </div>

    <hr>

    <div class="card-header">
      <h3>vendor Daybooks: </h3>
    </div>
    <div class="card-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>S.N.</th>
                  <th>Date</th>
                  <th>collection_from</th>
                  <th>purchase_from  </th>
                  <th>purchase_item</th>
                </tr>
            </thead>
            <tbody id="sortable">
            @if(count($daybooks) > 0)
            @forelse($daybooks as $key=>$daybook)
              @forelse ($daybook as $item)
                <tr id="{{ $detail->id }}">
                  <td>{{ $key + 1}}</td>
                  <td>{{ $item->date }}</td>
                  <td>{{ $item->collection_from}}</td>
                  <td>{{ $item->purchase_from}}</td>
                  <td>{{ $item->purchase_item}}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5">No Daybook found!</td>
                </tr>
              @endforelse
              
              @empty
              <tr>
                <td colspan="5">No Daybook found!</td>
              </tr>
              @endforelse
              @endif
            </tbody>
          </table>
     
    </div>
  </div>