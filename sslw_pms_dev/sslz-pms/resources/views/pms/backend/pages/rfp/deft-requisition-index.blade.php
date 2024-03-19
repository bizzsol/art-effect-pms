@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .modal-backdrop {
            position: relative !important;
        }

        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
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
                    <li class="active">{{__($title)}}</li>
                    <li class="top-nav-btn">
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <div id="accordion">
                            <div class="card">
                                <div class="card-header bg-primary p-0" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#filter"
                                                aria-expanded="true" aria-controls="filter">
                                            <h5 class="text-white"><strong><i
                                                            class="las la-chevron-circle-right la-spin"></i>&nbsp;Filters</strong>
                                            </h5>
                                        </button>
                                    </h5>
                                </div>

                                <div id="filter" class="collapse {{ !request()->has('from') ? 'show' : '' }}"
                                     aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                        <form action="{{ url('pms/rfp/requisitions') }}" method="get"
                                              accept-charset="utf-8">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <label for="department_id"><strong>Department</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="department_id" id="department_id"
                                                                class="form-control rounded">
                                                            <option value="{{ null }}">{{ __('Select One') }}</option>
                                                            @if(isset($departments[0]))
                                                                @foreach($departments as $values)
                                                                    <option value="{{ $values->hr_department_id }}" {{ request()->has('department_id')?(request()->get('department_id')==$values->hr_department_id?'selected':''):'' }}>
                                                                        {{ $values->hr_department_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="category_id"><strong>Product
                                                                Category</strong></label>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="category_id" id="category_id"
                                                                    class="form-control">
                                                                <option value="{{null}}">--Select One--</option>
                                                                @if(isset($categories[0]))

                                                                    @foreach($categories as $category)
                                                                        <option value="{{ $category->id }}" {{ request()->has('category_id') && request()->get('category_id') == $category->id ? 'selected' : '' }}>{{
																$category->name.'('.$category->code.')'}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="requisition_by"><strong>Requisition
                                                                By</strong></label>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="requisition_by" id="requisition_by"
                                                                    class="form-control">
                                                                <option value="{{null}}">--Select One--</option>
                                                                @if(isset($userList[0]))

                                                                    @foreach($userList as $values)
                                                                        <option value="{{ $values->id }}" {{ request()->has('requisition_by') && request()->get('requisition_by') == $values->id ? 'selected' : '' }}>{{
																$values->name}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="from"><strong>Start Date</strong></label>
                                                        <input type="date" name="from" id="from" value="{{ $from }}"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="to"><strong>End Date</strong></label>
                                                        <input type="date" name="to" id="to" value="{{ $to }}"
                                                               class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-1 pt-1 pl-0 mt-4">
                                                    <button class="btn btn-sm btn-block btn-success report-button"
                                                            type="submit"><i class="la la-search"></i>&nbsp;Search
                                                    </button>
                                                </div>
                                                <div class="col-md-1 pt-1 pl-0 mt-4">
                                                    <a class="btn btn-sm btn-block btn-danger"
                                                       href="{{ url('pms/rfp/requisitions') }}"><i
                                                                class="la la-times"></i>&nbsp;Clear</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            @include('yajra.datatable')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="requisitionDetailModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Requisition Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="tableData">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('page-script')
    @include('yajra.js')
    <script>
        function convertToRfp(element) {
            let requisition_id = element.attr('data-id');

            if (requisition_id != '') {
                swal({
                    title: "{{__('Are you sure?')}}",
                    text: "",
                    icon: "warning",
                    dangerMode: false,
                    buttons: {
                        cancel: true,
                        confirm: {
                            text: 'Prepare To RFP',
                            value: true,
                            visible: true,
                            closeModal: true,
                            className: 'bg-success'
                        },
                    },
                }).then((value) => {
                    if (value) {

                        $.ajax({
                            type: 'POST',
                            url: element.attr('data-src'),
                            dataType: "json",
                            data: {_token: '{!! csrf_token() !!}', requisition_id: requisition_id},
                            success: function (data) {
                                if (data.result === 'success') {
                                    //$('#row'+requisition_id).hide();
                                    element.parent().parent().parent().parent().parent().remove();
                                    notify(data.message, 'success');
                                } else {
                                    notify(data.message, data.result);
                                }
                            }
                        });
                        return false;
                    }
                });
            } else {
                notify('Please Select Requisition!!', 'error');
            }
        }

        function openModal(requisitionId) {
            $('#tableData').load('{{URL::to("pms/rfp/store-inventory-compare")}}/' + requisitionId);

            $('#requisitionDetailModal .modal-title').html('Requisition Details');
            $('#requisitionDetailModal').modal('show');
        }
    </script>
@endsection