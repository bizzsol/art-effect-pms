@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')
@include('yajra.css')
@endsection

@section('main-content')
<?php 
use Illuminate\Support\Facades\Request;
?>
<!-- WRAPPER CONTENT ----------------------------------------------------------------------------->
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
              <li class="top-nav-btn">

                @if(request()->has('fixed-assets'))
                    <a href="{{ url('pms/fixed-assets/product/create') }}?fixed-assets" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Fixed Assets"> <i class="las la-plus"></i>Add</a>
                @elseif(request()->has('cwip'))
                    <a href="{{ url('pms/cwip/product/create') }}?cwip" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Fixed Assets"> <i class="las la-plus"></i>Add</a>
                @else
                     <a href="{{ url('pms/product-management/product/create') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Product"> <i class="las la-plus">&nbsp;Add</i></a>
                @endif

                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Purchase-Department'))
                <a href="javascript:void(0)" class="btn btn-sm btn-success text-white" data-toggle="tooltip" title="Upload Product Using Xls Sheet" id="uploadFile"> <i class="las la-cloud-upload-alt">&nbsp;Upload Xls File</i></a>
                @endif
            </li>
        </ul><!-- /.breadcrumb -->

    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info mt-2 p-2">
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
<div class="modal fade bd-example-modal-lg" id="brandUploadModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandAddModalLabel">{{ __('Upload Product') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="brandForm"  enctype="multipart/form-data" action="{{route('pms.product-management.product.import')}}" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-12 pb-4">
                            <a href="{{ url('pms/product-management/product-import-sample') }}" download class="btn btn-link"><i class="las la-download"></i>{{__('Click Here To Download Format File')}}</a>
                        </div>
                        <div class="col-md-8">
                            <p class="mb-1 font-weight-bold"><label for="code">{{ __('Select File for Upload') }}:</label> <code>{{ __('Expected file is .xls , .xslx') }}</code> <span class="text-danger"></span></p>
                            <div class="input-group input-group-md mb-3">
                                <input type="file" name="product_file" id="product_file" class="form-control rounded" required aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-sm btn-success text-white" style="margin-top:32px"><i class="las la-cloud-upload-alt">Upload Xls File</i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script>
    (function ($) {
        "use script";
        $('#uploadFile').on('click', function () {
            $('#brandUploadModal').find('form')[0].reset();
            $('#brandUploadModal').modal('show');
        });
    })(jQuery);


    function toggleProduct(element) {
        var product_id = element.attr('data-product-id');
        var status = element.attr('data-status');
        var button = (status == "pending" ? "Approve" : "Halt");
        var text = (status == "pending" ? "Once you Approve, this product will be available for requisitions" : "Once you Halt, this product will not be available for requisitions")
        swal({
            title: "{{__('Are you sure?')}}",
            text: text,
            icon: "warning",
            dangerMode: false,
            buttons: {
                cancel: true,
                confirm: {
                    text: button,
                    value: true,
                    visible: true,
                    closeModal: true,
                    className: 'bg-success'
                },
            },
        }).then((value) => {
            if(value){
                $.ajax({
                    url: "{{ url('pms/product-management/product') }}/"+product_id+"/toggle",
                    type: 'POST',
                    dataType: 'json',
                    data: {_token: "{{ csrf_token() }}"},
                })
                .done(function(response) {
                    if(response.success){
                        notification('success', response.message);
                        element.parent().html(response.button);
                    }else{
                        notification('error', response.message);
                        return;
                    }
                });
            }
        });
    }

    function unitConversions(element) {
        $.dialog({
            title: 'Unit Conversions',
            content: "url:{{ url('pms/product-management/product') }}/"+element.attr('data-product-id')+'/unit-conversions',
            animation: 'scale',
            columnClass: 'large',
            closeAnimation: 'scale',
            backgroundDismiss: true,
        });
    }

    function updateSuppliers(element) {
        $.dialog({
            title: 'Update Suppliers',
            content: "url:{{ url('pms/product-management/product') }}/"+element.attr('data-product-id')+'/update-suppliers',
            animation: 'scale',
            columnClass: 'large',
            closeAnimation: 'scale',
            backgroundDismiss: true,
        });
    }
</script>
@endsection
