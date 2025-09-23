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


    <div class="modal" id="quotationHoldModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Hold the Quotations</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form action="{{route('pms.quotation.halt.status')}}" method="POST">
                    @csrf

                    <div class="modal-body">

                        <div class="form-group">
                            <label for="remarks">Remarks :</label>

                            <input type="hidden" readonly required name="id" id="quotationId">

                            <textarea class="form-control" name="remarks" rows="3" id="remarks"
                                      placeholder="Write down here reason for hold"></textarea>

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
@endsection
@section('page-script')
    @include('yajra.js')
    <script>
        function openModal(quotation_id) {
            $('#tableData').load('{{URL::to(Request()->route()->getPrefix()."/quotation-items")}}/' + quotation_id + '?approved');
            $('#requisitionDetailModal').find('.modal-title').html(`Quotation Details`);
            $('#requisitionDetailModal').modal('show');
        }

        function requestProposalDetails(request_proposal_id) {
            $('#tableData').load('{{URL::to(Request()->route()->getPrefix()."/request-proposal-details")}}/' + request_proposal_id);
            $('#requisitionDetailModal').find('.modal-title').html(`Proposal Details`);
            $('#requisitionDetailModal').modal('show');
        }

        function requisitionApprovedBtn(element) {
            let id = element.attr('data-id');
            let status = element.attr('data-status');

            if (status === 'halt') {

                $('#quotationId').val(id);
                return $('#quotationHoldModal').modal('show').on('hidden.bs.modal', function (e) {
                    let form = document.querySelector('#quotationHoldModal').querySelector('form').reset();

                })
            }

            $.ajax({
                url: "{{ url('pms/quotation/approved-status') }}",
                type: 'POST',
                dataType: 'json',
                data: {_token: "{{ csrf_token() }}", id: id, status: status},
            })
                .done(function (response) {
                    if (response.success) {

                        $('#statusName' + id).html(response.new_text);
                        $('#removeRow' + id).hide();

                        notify(response.message, 'success');
                    } else {
                        notify(response.message, 'error');
                    }
                })
                .fail(function (response) {
                    notify('Something went wrong!', 'error');
                });
        }

        function completeQuotation(element) {
            let quotation_id = element.attr('data-id');
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you Complete, You can not generate po from this quotation.')}}",
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
                        url: '{{ url('pms/quotation/complete-quotation') }}',
                        dataType: "json",
                        data: {_token: "{{ csrf_token() }}", quotation_id: quotation_id},
                        success: function (data) {
                            if (data.result == 'success') {
                                //$('#removeRow'+quotation_id).hide();
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
    <script>
        function openModalNew(requestId) {
            $.dialog({
                title: 'CS Preview',
                content: "url:{{url("pms/rfp/request-proposal")}}/"+requestId+"?preview",
                animation: 'scale',
                columnClass: 'col-md-12',
                closeAnimation: 'scale',
                backgroundDismiss: true,
            });
        }
    </script>
@endsection