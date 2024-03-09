@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
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
                                        <form action="{{ url('pms/requisition/passed-requisition-approval') }}" method="get"
                                              accept-charset="utf-8">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-6">
                                                    <p class="mb-1 font-weight-bold"><label
                                                                for="author_id">{{ __('Users') }}:</label></p>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="author_id" id="author_id"
                                                                class="form-control rounded">
                                                            <option value="{{null}}">{{ __('--Select One--') }}</option>
                                                            @foreach($requisitionUserLists as $values)
                                                                <option value="{{ $values->id }}" {{ request()->has('author_id')?(request()->get('author_id')==$values->id?'selected':''):'' }}>{{ $values->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="status"><strong>Status</strong></label>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="status" id="status" class="form-control">
                                                                <option value="-1">--Select One--</option>
                                                                @foreach(statusArrayForHead() as $key=>$value)
                                                                    <option value="{{$key}}" {{ request()->has('status')?(request()->get('status')==$key?'selected':''):'' }}>{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
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

                                                <div class="col-md-1 pt-2 pl-0 mt-4">
                                                    <button class="btn btn-sm btn-block btn-success report-button"
                                                            type="submit"><i class="la la-search"></i>&nbsp;Search
                                                    </button>
                                                </div>
                                                <div class="col-md-1 pt-2 pl-0 mt-4">
                                                    <a class="btn btn-sm btn-block btn-danger"
                                                       href="{{ url('pms/requisition/list-view') }}"><i
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

    <!--Start Requisition Status Change Modal -->
    <div class="modal" id="requisitionHoldModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Halt the Requisition</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <form action="{{route('pms.requisition.halt.status')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="admin_remark">Notes:</label>

                            <input type="hidden" readonly required name="id" id="requisitionId">
                            <textarea class="form-control" required name="admin_remark" rows="3" id="admin_remark"
                                      placeholder="Write here...."></textarea>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!--Start Acknowledge Status -->
    <div class="modal" id="requisitionAckModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Acknowledge Requisition</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ url('pms/requisition/approved-status') }}" method="POST" id="approved-status-form">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="admin_remark">Notes (Optional):</label>

                            <textarea class="form-control" name="admin_remark" rows="3" id="admin_remark"
                                      placeholder="Write here..."></textarea>
                            <input type="hidden" readonly required name="id" id="requisitionApprovedId">
                            <input type="hidden" readonly required name="status" id="requisitionStatus">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary approved-status-button">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!--End Acknowledge Status -->
@endsection
@section('page-script')
    @include('yajra.js')
    <script>
        $(document).ready(function () {
            var form = $('#approved-status-form');
            var button = $('.approved-status-button');
            var content = button.html();

            form.submit(function (event) {
                event.preventDefault();

                button.html('<i class="las la-spinner la-spin"></i>&nbsp;Prease wait...').prop('disabled', true);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    data: form.serializeArray(),
                })
                    .done(function (response) {
                        button.html(content).prop('disabled', false);
                        if (response.success) {
                            toastr.success(response.message);
                            $('#requisitionAckModal').modal('hide');
                            reloadDatatable();
                        } else {
                            toastr.error(response.message);
                        }
                    })
                    .fail(function (response) {
                        var errors = '<ul class="pl-2">';
                        $.each(response.responseJSON.errors, function (index, val) {
                            errors += "<li class='text-white'>" + val[0] + "</li>";
                        });
                        errors += '</ul>';
                        toastr.error(errors);

                        button.html(content).prop('disabled', false);
                    });
            });
        });

        function requisitionApprovedBtn(element) {
            let id = element.attr('data-id');
            let status = element.attr('data-status');

            if (status == 1) {
                $('#requisitionApprovedId').val(id);
                $('#requisitionStatus').val(status);
                return $('#requisitionAckModal').modal('show');
            } else if (status == 2) {
                $('#requisitionId').val(id)
                return $('#requisitionHoldModal').modal('show');
            } else if (status == 0) {
                texStatus = 'Pending';
                textContent = 'Do you want to pending this requisition?';
            }

            swal({
                title: "{{__('Are you sure?')}}",
                text: textContent,
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: texStatus,
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: "{{ url('pms/requisition/approved-status') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {_token: "{{ csrf_token() }}", id: id, status: status},
                    })
                        .done(function (response) {
                            if (response.success) {
                                $('#statusName' + id).html(response.new_text);
                                //element.parent().parent().parent().parent().parent().remove();
                                notify(response.message, 'success');
                            } else {
                                notify(response.message, 'error');
                            }
                        })
                        .fail(function (response) {
                            notify('Something went wrong!', 'error');
                        });

                    return false;
                }
            });
        }

        function showRequisitionDetails(element) {
            $.ajax({
                url: element.attr('data-src'),
                type: 'get',
                dataType: 'json',
                data: '',
                success: function (response) {
                    $('#requisitionDetailModal').find('#tableData').html(response);
                    $('#requisitionDetailModal').find('.modal-title').html(`Requisition`);
                    $('#requisitionDetailModal').modal('show');
                }
            });
        }

        function trackingRequisitionStatus(id) {
            $.ajax({
                url: "{{ url('pms/requisition/tracking-show') }}",
                type: 'POST',
                dataType: 'json',
                data: {_token: "{{ csrf_token() }}", id: id},
            })
                .done(function (response) {
                    if (response.result == 'success') {
                        $('#requisitionDetailModal').find('.modal-title').html(`Requisition Tracking`);
                        $('#requisitionDetailModal').find('#tableData').html(response.body);
                        $('#requisitionDetailModal').modal('show');
                    } else {
                        notify(response.message, response.result);
                    }
                })
                .fail(function (response) {
                    notify('Something went wrong!', 'error');
                });
        }


    </script>
@endsection