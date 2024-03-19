@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('page-css')
@include('yajra.css')
@endsection

@section('main-content')

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
                    <a href="{{ url('pms/fixed-assets/category/create') }}?fixed-assets" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Category"> <i class="las la-plus"></i>Add</i></a>
                @elseif($is_cwip == 1)
                    <a href="{{ url('pms/cwip/category/create') }}?cwip" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Category"> <i class="las la-plus"></i>Add</i></a>
                @else
                    <a href="{{ route('pms.product-management.category.create') }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Category"> <i class="las la-plus"></i>Add</i></a>
                @endif
                
                <a href="javascript:void(0)" class="btn btn-sm btn-info text-white" data-toggle="tooltip" title="Upload Category by xlsx file" id="uploadFile"> <i class="las la-cloud-upload-alt"></i>Upload Category</a>
            </li>
        </ul>

    </div>
    
    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    @include('yajra.datatable')
                    {{-- <table class="table table-bordered datatable-exportable" cellspacing="0" width="100%" id="dataTable" data-table-name="{{ $title }}">

                        <thead>
                            <tr class="text-center">
                                <th width="5%">{{__('SL')}}</th>
                                <th width="10%">{{__('Code')}}</th>
                                <th width="20%">{{__('Name')}}</th>
                                <th width="30%">{{__('Departments')}}</th>
                                <th width="10%">{{__('Type')}}</th>
                                <th width="15%" class="text-center">{{__('Option')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $key => $category)
                            <tr id="set{{ $category->id }}">
                                <td class="text-center">{{__($key+1)}}</td>
                                <td class="text-center">{{ $category->code }}</td>
                                <td>{{ $category->name }}</a></td>
                                
                                <td>
                                    @foreach($category->departmentsList as $values)
                                        <a href="javascript:void(0)"><span class="m-1 badge badge-primary">{{ $values->department->hr_department_name }}</span></a>
                                    @endforeach
                                </td>
                                <td class="text-center">{{ ucwords($category->type) }}</td>
                                
                                <td class="text-center">
                                    @php
                                        $url = route('pms.product-management.category.edit', $category->id);
                                        if(request()->has('fixed-assets')){
                                            $url = url('pms/fixed-assets/category/edit/'.$category->id.'?fixed-assets');
                                        }elseif(request()->has('cwip')){
                                            $url = url('pms/cwip/category/'.$category->id.'/edit?cwip');
                                        }
                                    @endphp
                                    <a href="{{ $url }}" class="btn btn-info m-1 btn-xs"><i class="las la-edit"></i></a>

                                    <a href="javascript:void(0)" data-role="delete" data-src="{{ route('pms.product-management.category.destroy', $category->id) }}" class="btn btn-danger m-1 btn-xs deleteBtn"><i class="las la-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>
</div>
</div>


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
        //                     if(data){
        //                         showAlert('error', data);
        //                         return;
        //                     }
        //                     swal({
        //                         icon: 'success',
        //                         text: 'Data deleted successfully',
        //                         button: false
        //                     });
        //                     setTimeout(()=>{
        //                         swal.close();
        //                     }, 1500);
        //                 },
        //             });
        //             $(this).parent().parent().remove();
        //         }
        //     });
        // })
    })(jQuery);

</script>
@endsection
