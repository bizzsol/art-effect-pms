@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('main-content')
@include('yajra.css')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
          <ul class="breadcrumb">
              <li>
                  <i class="ace-icon fa fa-home home-icon"></i>
                  <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
              </li>
              <li>
                  <a href="#">PMS</a>
              </li>
              <li class="active">{{__($title)}} List</li>
        </ul>
    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    <form action="{{ url('pms/store-manage/delivered-list') }}" method="get" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="from_date">{{ __('From Date') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="date" name="from_date" id="from_date" class="form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ $from_date }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="to_date">{{ __('To Date') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="date" name="to_date" id="to_date" class="form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ $to_date }}">
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <div class="input-group input-group-md pt-4">
                                    <button type="submit" class="btn btn-success btn-block rounded mt-8"> <i class="las la-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>

            <div class="panel panel-info mt-2 p-2">
                @include('yajra.datatable')
            </div>
        </div>
    </div>
</div>
</div>

<!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
<!-- Requisition Details Modal Start -->
<div class="modal" id="requisitionDetailModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Requisition Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" id="tableData">

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- Requisition Details Modal End -->
@endsection
@section('page-script')
@include('yajra.js')
<script>
    function storedeliveredAcknowledge(element) {
        let id = element.attr('data-id');
        $.ajax({
            url: "{{ url('pms/store-manage/store-delivered-requistion-ack') }}",
            type: 'POST',
            dataType: 'json',
            data: {_token: "{{ csrf_token() }}", id:id},
        })
        .done(function(response) {
            if(response.result=='success'){
                $('#statusName'+id).html('Delivered');
                $('#hideBtn'+id).hide();
                notify(response.message,response.result);
            }
        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
    }

    function requisitionview(element) {
        let id = element.attr('data-id');
        $.ajax({
            type: 'post',
            url: "{{url("pms/store-manage/store-delivered-requistion-view")}}",
            data:{_token:'{!! csrf_token() !!}',id:id},
            success:function (data) {
                if(data.result == 'success'){
                    showPreloader('none');
                    $('#requisitionDetailModal').find('.modal-title').html(`Requisition Details`);
                    $('#tableData').html(data.body);
                    $('#requisitionDetailModal').modal('show');

                }else{
                    showPreloader('none');
                }   
            }
        });
    }
</script>
@endsection
