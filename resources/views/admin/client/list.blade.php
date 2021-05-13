@extends('layouts.admin')
@section('title','Client List')
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content')
<section class="content-header">
  <h1>Client<small>List</small></h1>
    <a href="{{route('client.create')}}" class="btn btn-success">Add Client</a>
    <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="">Client</a></li>
      <li><a href="">List</a></li>
    </ol>
</section>
<div class="content">
    @if(Session::has('message'))
    <div class="alert alert-success alert-dismissible message">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! Session::get('message') !!}
    </div>
    @endif
    <div class="alert alert-success alert-dismissible message1" style="display:none">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="row">
      <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
                <h3 class="box-title">Data Table</h3>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.N.</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Designation</th>
                      <th>Phone</th>
                      <th>Contact</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody id="sortable">
                @foreach($details as $key=>$detail)
                  <tr id="{{ $detail->id }}">
                      <td>{{ $key+1 }}</td>
                      <td>{{$detail->name}}</td>
                      <td>{{$detail->email}}</td>
                      <td>
                        @if(count(json_decode($detail->designation))>0)
                        @forelse (json_decode($detail->designation) as $key=>$item)
                            {{ $item }},
                        @empty
                        @endforelse
                        @endif
                      </td>
                      <td>
                      @if(count(json_decode($detail->phone))>0)
                      @forelse (json_decode($detail->phone) as $key=>$item)
                          {{ $item }},
                      @empty
                      @endforelse
                      @endif
                      </td>

                      <td>
                      @if($detail->contact_person !=null && count(json_decode($detail->contact_person))>0)
                        @forelse (json_decode($detail->contact_person) as $key=>$item)
                            {{ $item }},
                        @empty
                        @endforelse
                        @endif
                      </td>


                      <td>
                        <a class="btn btn-info edit" href="{{route('client.edit',$detail->id)}}" title="Edit"><span class="fa fa-edit"></span></a>
                        <form method= "post" action="{{route('client.destroy',$detail->id)}}" class="delete btn btn-danger">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-delete" style="display:inline"><span class="fa fa-trash"></span></button>
                        </form>
                       <a class="btn btn-info edit" href="{{route('client.show',$detail->id)}}" title="Edit"><span class="fa fa-eye"></span></a>
                        
                      </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>
        </div>
    </div>
</div>
</div>
@include('admin.include.modal')
@endsection
@push('script')
  <!-- DataTables -->
  <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
  <!-- SlimScroll -->
  <script src="{{ asset('backend/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ asset('backend/plugins/fastclick/fastclick.js') }}"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script >

    

  $('#example1').dataTable( {
    "pageLength": 10
  } );
</script>
@endpush
