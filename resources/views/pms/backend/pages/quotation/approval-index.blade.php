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



<div class="modal" id="requisitionDetailModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Quotation Comparison</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
            
        
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" id="qty-submit-btn" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
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

                        <textarea class="form-control" name="remarks" rows="3" id="remarks" placeholder="Write down here reason for hold"></textarea>

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
        $('.modal-body').load('{{URL::to(Request()->route()->getPrefix()."/quotation-items")}}/'+quotation_id);
        $('#requisitionDetailModal').find('.modal-title').html(`Quotation Details`);
        $('#requisitionDetailModal').modal('show');
    }

    function requestProposalDetails(request_proposal_id) {
        $('.modal-body').load('{{URL::to(Request()->route()->getPrefix()."/request-proposal-details")}}/'+request_proposal_id);
        $('#requisitionDetailModal').find('.modal-title').html(`Request Proposal Details`);
        $('#requisitionDetailModal').find('.modal-body #qty-submit-btn').hide();
        $('#requisitionDetailModal').modal('show');
    }


    (function ($) {
        "use script";

        //Approved Reject 
        const approveQa = () => {
            $('.requisitionApprovedBtn').on('click', function () {

                let id = $(this).attr('data-id');
                let status = $(this).attr('data-status');


                if (status==='halt'){

                    $('#quotationId').val(id)
                    return $('#quotationHoldModal').modal('show').on('hidden.bs.modal', function (e) {
                        let form = document.querySelector('#quotationHoldModal').querySelector('form').reset();

                    })
                }

                $.ajax({
                    url: "{{ url('pms/quotation/approved-status') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {_token: "{{ csrf_token() }}", id:id, status:status},
                })
                .done(function(response) {
                    if(response.success){

                        $('#statusName'+id).html(response.new_text);
                        notify(response.message,'success');
                    }else{
                        notify(response.message,'error');
                    }
                })
                .fail(function(response){
                    notify('Something went wrong!','error');
                });
            });
        }
})(jQuery)
</script>
@endsection