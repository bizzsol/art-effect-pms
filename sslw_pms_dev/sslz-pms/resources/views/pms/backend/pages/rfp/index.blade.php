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
    @php
        use Illuminate\Support\Facades\URL;
        use Illuminate\Support\Facades\Request;
    @endphp
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
                        @can('rfp-create')
                            <a href="{{route('pms.rfp.request-proposal.create')}}"
                               class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Create RFP"
                               id="addRequestProposalBtn"> <i class="las la-plus"></i>Add</a>
                        @endcan
                    </li>
                </ul>
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


    <div class="modal" id="requestProposalDetailModal">
        <div class="modal-dialog modal-lg" style="width: 80%">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Request Proposal Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" id="modalContent"></div>
                <!-- Modal footer -->
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

        function openModal(requestId) {
            $('#modalContent').empty().load('{{URL::to(Request()->route()->getPrefix()."request-proposal")}}/' + requestId);
            $('#requestProposalDetailModal').modal('show');
        }

        function completeQG(element) {
            let req_proposal_id = element.attr('data-id');
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you Complete, You can not generate quotation from this proposal.')}}",
                icon: "warning",
                dangerMode: false,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Complete",
                        value: true,
                        visible: true,
                        closeModal: true,
                        className: 'bg-success'
                    },
                },
            }).then((value) => {
                if (value) {
                    $.ajax({
                        type: 'post',
                        url: element.attr('data-src'),
                        dataType: "json",
                        data: {_token: "{{ csrf_token() }}", req_proposal_id: req_proposal_id},
                        success: function (data) {
                            if (data.result == 'success') {
                                //$('#rowId'+req_proposal_id).hide();
                                element.parent().parent().parent().parent().parent().remove();
                                notify(data.message, data.result);
                            } else {
                                notify(data.message, data.result);
                            }
                        }
                    });
                    return false;
                }
            });
        }
    </script>
@endsection