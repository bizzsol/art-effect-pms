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
                                        <h5 class="text-white"><strong><i class="las la-chevron-circle-right la-spin"></i>&nbsp;Search</strong>
                                        </h5>
                                    </button>
                                </h5>
                            </div>

                            <div id="filter" class="collapse show"
                                 aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="{{ url('pms/requisition/re-assess-requisitions-by-user') }}" method="get" accept-charset="utf-8">
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
                                                    <label for="category_id"><strong>Product Category</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="category_id" id="category_id"
                                                                class="form-control">
                                                            <option value="{{null}}">--Select One--</option>
                                                            @if(isset($categories[0]))

                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ request()->has('category_id') && request()->get('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name.'('.$category->code.')'}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="from"><strong>Start Date</strong></label>
                                                            <input type="date" name="from" id="from" value="{{ $from }}"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="to"><strong>End Date</strong></label>
                                                            <input type="date" name="to" id="to" value="{{ $to }}"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="row">
                                                    <div class="col-md-6 pt-4">
                                                        <button class="btn btn-sm btn-block btn-success report-button mt-2" type="submit"><i class="la la-search"></i>&nbsp;Search</button>
                                                    </div>
                                                    <div class="col-md-6 pt-4">
                                                        <a class="btn btn-sm btn-block btn-danger mt-2" href="{{ url('pms/requisition/re-assess-requisitions-by-user') }}"><i class="la la-times"></i>&nbsp;Clear</a>
                                                    </div>
                                                </div>
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
        function acknowledge(element) {
            var content = element.html();
            element.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure to proceed ?',
                buttons: {
                    yes: {
                        text: 'Yes',
                        btnClass: 'btn-blue',
                        action: function(){
                            $.ajax({
                                url: "{{ url('pms/requisition/re-assess-requisitions-by-user') }}",
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    requisition_id: element.attr('data-requisition-id')
                                },
                            })
                            .done(function(response) {
                                if(response.success){
                                    toastr.success(response.message);
                                    reloadDatatable();
                                }else{
                                    toastr.error(response.message);
                                    element.html(content).prop('disabled', false);
                                }
                            });
                        }
                    },
                    no: {
                        text: 'No',
                        btnClass: 'btn-dark',
                        action: function(){
                            element.html(content).prop('disabled', false);
                        }
                    }
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
    </script>
@endsection
