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
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#filter" aria-expanded="true" aria-controls="filter">
                                        <h5 class="text-white"><strong><i class="las la-chevron-circle-right la-spin"></i>&nbsp;Filters</strong>
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
    <div class="modal-dialog modal-lg" style="max-width: 80%;">
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
        $.dialog({
            title: 'CS Submission Form',
            content: "url:{{ url('pms/rfp/requisitions') }}?send-to-rfp&requisition_id=" + element.attr('data-id'),
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
        });
    }

    function assignPerson(element) {
        $.dialog({
            title: 'Assign Person For This Requisition',
            content: "url:{{ url('pms/rfp/requisitions') }}?assign-person&requisition_id=" + element.attr('data-id'),
            animation: 'scale',
            columnClass: 'col-md-12',
            closeAnimation: 'scale',
        });
    }

    function trackingRequistionStatus(element) {
        let id = $(element).data('id');

        $.ajax({
            url: "{{ url('pms/requisition/tracking-show') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
        })
            .done(function (response) {
                if (response.result === 'success') {
                    $('#requisitionDetailModal').find('.modal-title').html(`Requisition Tracking`);
                    $('#requisitionDetailModal').find('#tableData').html(response.body);
                    $('#requisitionDetailModal').modal('show');
                } else {
                    notify(response.message, response.result);
                }
            })
            .fail(function () {
                notify('Something went wrong!', 'error');
            });
    }


    function rejectItem(element) {
        $.confirm({
            title: 'Reject Requisition',
            content: '<hr>' +
                '<form action="" class="formName">' +
                '<h5 class="text-danger mb-2"><strong>Are you sure?</strong></h5>' +
                '<h6>Once you reject this requisition, it will be marked as rejected and cannot be processed further.</h6>' +
                '<hr>' +
                '<div class="form-group">' +
                '<textarea placeholder="Your Reason for Rejection..." class="message form-control" required style="min-height: 200px; resize: none;"></textarea>' +
                '</div>' +
                '</form>',
            buttons: {
                reject: {
                    text: 'Reject',
                    btnClass: 'btn-red',
                    action: function() {
                        let message = this.$content.find('.message').val();
                        if (!message.trim()) {
                            $.alert('Please provide a reason for rejection.');
                            return false;
                        }

                        $.ajax({
                            type: 'POST',
                            url: element.attr('data-src'),
                            dataType: "json",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                _method: 'DELETE', // Required for Laravel DELETE routes
                                requisition_id: element.attr('data-id'),
                                message: message
                            },
                            success: function(data) {
                                if (data.result === 'success') {
                                    $('.jconfirm').remove();
                                    notify(data.message, 'success');
                                    reloadDatatable();
                                } else {
                                    notify(data.message || 'Something went wrong.', 'error');
                                }
                            },
                            error: function() {
                                notify('Server error occurred.', 'error');
                            }
                        });

                        return false;
                    }
                },
                close: {
                    text: 'Close',
                    btnClass: 'btn-dark'
                }
            }
        });
    }




    function openModal(requisitionId) {
        $('#tableData').load('{{URL::to("pms/rfp/store-inventory-compare")}}/' + requisitionId);

        $('#requisitionDetailModal .modal-title').html('Requisition Details');
        $('#requisitionDetailModal').modal('show');
    }
</script>
@endsection