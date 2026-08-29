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
                <li class="active">{{__($title)}}</li>

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


<div class="modal" id="requisitionDetailModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Quotations Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" id="tableData">

            </div>
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
    function openModal(quotation_id) {
        $('#tableData').load('{{URL::to(Request()->route()->getPrefix()."/quotation-items")}}/'+quotation_id);
        $('#requisitionDetailModal').find('.modal-title').html(`Quotation Details`);
        $('#requisitionDetailModal').modal('show');
    }

    function requestProposalDetails(request_proposal_id) {
        $('#tableData').load('{{URL::to(Request()->route()->getPrefix()."/request-proposal-details")}}/'+request_proposal_id);
        $('#requisitionDetailModal').find('.modal-title').html(`Proposal Details`);
        $('#requisitionDetailModal').modal('show');
    }

    function reopenCs(link) {
        swal({
            title: "Reopen this CS ?",
            text: "The CS goes back to the compare screen so you can revise it. Approvers who already approved keep their approval, and on resend it returns to the approver who rejected it.",
            icon: "warning",
            dangerMode: true,
            buttons: {
                cancel: true,
                confirm: {
                    text: "Reopen",
                    value: true,
                    visible: true,
                    closeModal: true
                },
            },
        }).then((value) => {
            if (value) {
                var form = $('<form>', {method: 'POST', action: link});
                form.append($('<input>', {type: 'hidden', name: '_token', value: '{{ csrf_token() }}'}));
                form.appendTo('body').submit();
            }
        });
    }
</script>
@endsection