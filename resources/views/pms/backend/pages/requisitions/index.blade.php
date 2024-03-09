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
                    <li class="active">{{__($title)}} List</li>

                    @can('requisition-create')
                        @if(!auth()->user()->hasRole('Super Admin'))
                            <li class="top-nav-btn">
                                <a href="{{route('pms.requisition.requisition.create')}}"
                                   class="btn btn-sm btn-primary text-white" data-toggle="tooltip"
                                   title="Add New Requisition" id="addProductBtn"> <i class="las la-plus"></i>Add</a>
                            </li>
                        @endif
                    @endcan
                </ul>
            </div>

            <div class="page-content">
                <div class="panel panel-info mt-2 p-2">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header bg-primary p-0" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#filter"
                                            aria-expanded="true" aria-controls="filter">
                                        <h5 class="text-white"><strong><i
                                                        class="las la-chevron-circle-right la-spin"></i>&nbsp;Search</strong>
                                        </h5>
                                    </button>
                                </h5>
                            </div>

                            <div id="filter" class="collapse {{ !request()->has('from') ? 'show' : '' }}"
                                 aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="{{ url('pms/requisition/requisition') }}" method="get"
                                          accept-charset="utf-8">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="search_text"><strong>Search By Text</strong></label>
                                                    <input type="text" name="search_text" id="search_text"
                                                           value="{{ request()->has('search_text')?request()->get('search_text'):'' }}"
                                                           class="form-control" placeholder="Search by ref no..">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="status"><strong>Status</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="-1">--Select One--</option>
                                                            @foreach(requisitionStatus() as $key=>$value)
                                                                <option value="{{$key}}" {{ request()->has('status')?(request()->get('status')==$key?'selected':''):'' }}>{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="category_id"><strong>Product Category</strong></label>
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

                                            <div class="col-md-1 pt-1 pl-0 mt-4">
                                                <button class="btn btn-sm btn-block btn-success report-button"
                                                        type="submit"><i class="la la-search"></i>&nbsp;Search
                                                </button>
                                            </div>
                                            <div class="col-md-1 pt-1 pl-0 mt-4">
                                                <a class="btn btn-sm btn-block btn-danger"
                                                   href="{{ url('pms/requisition/requisition') }}"><i
                                                            class="la la-times"></i>&nbsp;Clear</a>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
@endsection

@section('page-script')
    @include('yajra.js')
    <script>
        (function ($) {
            "use script";

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
                    // if(value) form.reset();
                });
            };

        })(jQuery)

        function deleteBtn(element) {

            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can not recover this data and related files.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Delete",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        type: 'DELETE',
                        url: element.attr('data-src'),
                        success: function (response) {
                            if (response.success) {
                                element.parent().parent().parent().parent().parent().remove();
                                swal({
                                    icon: 'success',
                                    text: 'Data deleted successfully',
                                    button: false
                                });
                                setTimeout(() => {
                                    swal.close();
                                }, 1500);
                            } else {
                                showAlert('error', response.message);
                                return;
                            }
                        },
                    });
                }
            });
        }

        function trackingRequisitionStatus(element) {
            let id = element.attr('data-id');
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
            return false;
        }

        function sendRequisition(element) {
            let sendButton = element.parent('li');
            let id = element.attr('data-id');
            let status = element.attr('data-status');

            let texStatus = 'Send';
            let textContent = 'Would you like to send this requisition to your {{ auth()->user()->hasRole('Department-Head') ? 'SBU head' : (auth()->user()->hasRole('SBU Head') ? 'Management' : 'Department head') }}?';

            swal({
                title: "{{__('Are you sure?')}}",
                text: textContent,
                icon: "warning",
                dangerMode: false,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: texStatus,
                        value: true,
                        visible: true,
                        closeModal: true,
                        className: 'bg-success'
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
                                $('#status' + id).html('<span class="btn btn-sm btn-warning">' + response.new_text + '</span>');
                                notify(response.message, 'success');
                                sendButton.parent().find('.requisition-edit').remove();
                                sendButton.parent().find('.requisition-delete').remove();
                                sendButton.remove();
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

        function duplicateRequisition(element) {
            swal({
                title: "{{__('Are you sure?')}}",
                icon: "warning",
                dangerMode: false,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Duplicate",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        type: 'get',
                        url: element.attr('data-src'),
                        success: function (response) {
                            if (response.success) {
                                location.reload();
                                swal({
                                    icon: 'success',
                                    text: 'Data duplicate successfully',
                                    button: false
                                });
                                setTimeout(() => {
                                    swal.close();
                                }, 1500);
                            } else {
                                showAlert('error', response.message);
                                return;
                            }
                        },
                    });
                }
            });
        }
    </script>
@endsection
