@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')
@include('yajra.css')
@endsection

@section('main-content')
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

                @if($is_fixed_asset == 1)
                    <a href="{{ url('pms/fixed-assets/sub-category/create') }}?fixed-assets" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Category"> <i class="las la-plus"></i>Add</i></a>
                @elseif($is_cwip == 1)
                    <a href="{{ url('pms/cwip/sub-category/create') }}?cwip" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Category"> <i class="las la-plus"></i>Add</i></a>
                @else
                    <a href="{{ route('pms.product-management.sub-category.create') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Category"> <i class="las la-plus"></i>Add</i></a>
                @endif


                <a href="javascript:void(0)" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Upload Category by xlsx file" id="uploadFile"> <i class="las la-cloud-upload-alt"></i>Upload Category</a>
            </li>
        </ul><!-- /.breadcrumb -->

    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="attributeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryAddModalLabel">Update Sub Category Attributes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="categoryUploadModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="brandAddModalLabel">{{ __('Upload Category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form id="brandForm"  enctype="multipart/form-data" action="{{route('pms.product-management.category.import')}}" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-row">
                        <div class="col-md-12 pb-4">
                            <a href="{{URL::to('upload/excel/categories-sample.xlsx')}}" download class="btn btn-link"><i class="las la-download"></i>{{__('Click Here To Download Format File')}}</a>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 font-weight-bold"><label for="code">{{ __('Select File for Upload') }}:</label> <code>{{ __('Expected file size is .xls , .xslx') }}</code> <span class="text-danger"></span></p>
                            <div class="input-group input-group-md mb-3">
                                <input type="file" name="category_file" class="form-control" required id="excelFile" placeholder="Browse Excel file"
                                       accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>
                        </div>
                        <div class="col-3">

                            <button type="submit" class="btn btn-sm btn-success text-white" style="margin-top:32px"><i class="las la-cloud-upload-alt"></i>Upload Xls File</i></button>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded pull-left" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!--Upload Category Modal End-->
@endsection

@section('page-script')
@include('yajra.js')
<script>
    (function ($) {
        "use script";
        $('#uploadFile').on('click', function () {
            $('#categoryUploadModal').modal('show');
        });
    })(jQuery);

    function attributeBtn(e){
        $.ajax({
            type: 'get',
            url: e.attr('data-src'),
            data: {},
        })
        .done(function(response) {
            $('#attributeModal').find('.modal-body').html(response);
            $('#attributeModal').modal('show')
        });
    }
    
    function updateAttributes(){
        var attributes = $("#attributes :selected").map(function(i, el) {
            return $(el).val();
        }).get();

        $('.attributes').hide();
        $.each(attributes, function(index, attribute) {
            $('.attribute-'+attribute).show();
            $('.attribute-serial-'+attribute).show();
        });
    }
</script>
@endsection
