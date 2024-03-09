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
                     {{-- <table  id="dataTable" class="table table-striped table-bordered table-head" border="1">

                        <thead>
                            <tr class="text-center">
                                <th width="3.5%">{{__('SL')}}</th>
                                <th width="10%">{{__('Main Category')}}</th>
                                <th width="10%">{{__('Sub Category Code')}}</th>
                                <th width="20%">{{__('Sub Category Name')}}</th>
                                <th width="35%">{{__('Attributes')}}</th>
                                 <th>{{__('Departments')}}</th> 
                                <th width="11.5%" class="text-center">{{__('Option')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sl = 0; @endphp
                            @foreach($categories as $key => $category)
                            @if($category->subCategory->count() > 0)
                            @foreach($category->subCategory as $s_key => $subCategory)
                            @php 
                                $sl++;
                                $categoryAttributes = \App\Models\PmsModels\CategoryAttribute::where('category_id', $subCategory->id)
                                ->orderBy('serial', 'asc')
                                ->get();
                            @endphp
                            <tr id="set{{ $subCategory->id }}">
                                <td class="text-center">{{__($sl)}}</td>
                                @if($s_key == 0)
                                        <td width="10%" rowspan="{{ $category->subCategory->count() }}">{{ $category->name }}</td>
                                @endif
                                <td>{{ $subCategory->code }}</td>
                                <td>{{ $subCategory->name }}</td>
                                <td class="text-center">{{ $subCategory->is_fixed_asset == 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    @foreach($subCategory->departmentsList as $values)
                                        <a href="javascript:void(0)"><span class="m-1 badge badge-primary">{{ $values->department->hr_department_name }}</span></a>
                                    @endforeach
                                </td> 
                                <td>
                                    @if(isset($categoryAttributes[0]))
                                    <ul>
                                        @foreach($categoryAttributes as $key => $categoryAttribute)
                                        <li><strong>{{ $categoryAttribute->attribute->name }}:</strong> {{ \App\Models\PmsModels\AttributeOption::where('attribute_id', $categoryAttribute->attribute_id)->whereIn('id', (!empty($categoryAttribute->options) ? json_decode($categoryAttribute->options, true) : []))->pluck('name')->implode(', ') }}</li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" data-role="get" data-src="{{ url('pms/product-management/sub-category/'.$subCategory->id.'/create-attributes') }}" class="btn btn-success m-1 btn-xs attributeBtn"><i class="las la-sitemap"></i></a>
                                    @php
                                        $url = route('pms.product-management.sub-category.edit', $subCategory->id);
                                        if(request()->has('fixed-assets')){
                                            $url = url('pms/fixed-assets/sub-category/edit/'.$subCategory->id.'?fixed-assets');
                                        }elseif(request()->has('cwip')){
                                            $url = url('pms/cwip/sub-category/'.$subCategory->id.'/edit?cwip');
                                        }
                                    @endphp
                                    <a href="{{ $url }}" class="btn btn-info m-1 btn-xs"><i class="las la-edit"></i></a>
                                    <a href="javascript:void(0)" data-role="delete" data-src="{{ route('pms.product-management.sub-category.destroy', $subCategory->id) }}" class="btn btn-danger m-1 btn-xs deleteBtn"><i class="las la-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            @php $sl++; @endphp
                            <tr id="set{{ $category->id }}">
                                <td>{{__($sl)}}</td>
                                <td colspan="5">{{ $category->name }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table> --}}

                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
<!-- Modal ------------------------------------------------------------------------->
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


<!-- END Modal ------------------------------------------------------------------------->

<!--Upload Category Modal Start-->
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

        // const tableContainer = document.getElementById('dataTable').querySelector('tbody');
        // const showEmptyTable = () => {
        //     if(tableContainer.querySelectorAll('tr').length === 0){
        //         const row = document.createElement('tr');
        //         row.id = 'emptyRow';
        //         let colEmpty = document.createElement('td');
        //         colEmpty.innerHTML = 'No data is available';
        //         colEmpty.className = 'text-center';
        //         colEmpty.colSpan = 6;
        //         row.appendChild(colEmpty);
        //         tableContainer.appendChild(row);
        //     } else {
        //         if(tableContainer.querySelector('#emptyRow')){
        //             tableContainer.querySelector('#emptyRow').remove();
        //         }
        //     }
        // };
        // showEmptyTable();

        const showAlert = (status, error) => {
            swal({
                icon: status,
                text: error,
                dangerMode: true,
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value) form.reset();
            });
        };

        

        // $('.attributeBtn').on('click', function () {
        //     $.ajax({
        //         type: 'get',
        //         url: $(this).attr('data-src'),
        //         data: {},
        //     })
        //     .done(function(response) {
        //         $('#attributeModal').find('.modal-body').html(response);
        //         $('#attributeModal').modal('show')
        //     });
        // })

        // $('.deleteBtn').on('click', function () {
        //     swal({
        //         title: "{{__('Are you sure?')}}",
        //         text: "{{__('Once you delete, You can not recover this data and related files.')}}",
        //         icon: "warning",
        //         dangerMode: true,
        //         buttons: {
        //             cancel: true,
        //             confirm: {
        //                 text: "Delete",
        //                 value: true,
        //                 visible: true,
        //                 closeModal: true
        //             },
        //         },
        //     }).then((value) => {
        //         if(value){
        //             $.ajax({
        //                 type: 'DELETE',
        //                 url: $(this).attr('data-src'),
        //                 success:function (data) {
        //                     if(data.success){
        //                     location.reload();
        //                     }else{
        //                         showAlert('error', data.message);
        //                         return;
        //                     }
        //                 },
        //             });
        //             showEmptyTable();
        //         }
        //     });
        // })
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
