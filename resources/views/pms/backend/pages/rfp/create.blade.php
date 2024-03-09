@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
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
                        <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white"
                           data-toggle="tooltip" title="Back"> <i class="las la-chevron-left"></i>Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            {!! Form::open(['route' => 'pms.rfp.request-proposal.store',  'files'=> false, 'id'=>'', 'class' => 'form-horizontal rfp-form']) !!}

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('request_date', 'Date', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong><span class="text-danger">&nbsp;*</span></strong>
                                            {!! Form::text('request_date',request()->old('request_date')?request()->old('request_date'):date('d-m-Y'),['id'=>'request_date','class' => 'form-control rounded air-datepicker','placeholder'=>'','readonly'=>'readonly']) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('reference_no', 'Ref No', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong><span class="text-danger">&nbsp;*</span></strong>
                                            {!! Form::text('reference_no',(request()->old('reference_no'))?request()->old('reference_no'):'',['id'=>'reference_no','required'=>true,'class' => 'form-control rounded','readonly'=>'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('supplier_id', 'Select Supplier', array('class' => 'mb-1 font-weight-bold')) !!} <strong><span class="text-danger">&nbsp;*</span></strong>
                                            <select name="supplier_id[]" id="supplier_id" multiple required class="form-control rounded select2 select2-tags">
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>

                            <div class="table-responsive style-scroll">
                                <table class="table table-striped table-bordered miw-500 dac_table" cellspacing="0" width="100%" id="dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th style="width: 5%">{{__('SL')}}</th>
                                        <th style="width: 65%">{{__('Product Name')}}</th>
                                        <th style="width: 12.5%">{{__('Approved Qty')}}</th>
                                        <th style="width: 7.5%">{{__('UOM')}}</th>
                                        <th style="width: 10%" class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="chkbx_all_first"
                                                       id="checkAllProduct" onclick="return CheckAll()">
                                                <label class="form-check-label mt-8" for="checkAllProduct">
                                                    <strong>All</strong>
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total_sumOfRequisitionQty = 0;
                                        $total_sumOfRFP = 0;
                                    @endphp
                                    @if(count($products) > 0)
                                        @foreach($products as $key=> $values)
                                            @php
                                                $sumOfSendRFP = sumOfSendRFP($values->id, 0, false);
                                                $sumOfRFP = collect($values->requisitionItem)->where('requisition.status', 1)->where('is_send','no')->whereIn('requisition_id',$requisitionIds)->sum('qty');
                                                $sumOfRequisitionQty = collect($values->requisitionItem)->where('requisition.status', 1)->where('is_send','no')->whereIn('requisition_id',$requisitionIds)->sum('requisition_qty');
                                                $total_sumOfRequisitionQty += $sumOfRequisitionQty;
                                                $total_sumOfRFP += $sumOfRFP;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="openModal({{$values->id}})">
                                                        {{$values->name}} ({{$values->sku}}) {{ getProductAttributesFaster($values) }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="request_qty[{{$values->id}}]" min="1"
                                                           max="99999999"
                                                           value="{{($sumOfSendRFP>0)?number_format($sumOfRFP-$sumOfSendRFP,0): $sumOfRFP}}"
                                                           class="form-control rounded rfp_qty text-center"
                                                           onchange="calculateTotal()" readonly
                                                           onkeyup="calculateTotal()">

                                                    @if($sumOfSendRFP>0)
                                                        <input type="hidden" name="qty[{{$values->id}}]"
                                                               value="{{$sumOfRFP-$sumOfSendRFP}}">
                                                    @else
                                                        <input type="hidden" name="qty[{{$values->id}}]"
                                                               value="{{$sumOfRFP}}">
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{isset($values->productUnit->unit_name)?$values->productUnit->unit_name:''}}
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" name="product_id[]"
                                                           class="element_first" value='{{$values->id}}'
                                                           onclick="getRequestProposalCombineInfo()">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    <tr>
                                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-center" id="rfp_qty"></td>
                                        <td colspan="2"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-3 text-right pt-2">
                                    <label class="btn btn-xs rounded font-5 mt-10 pull-right ">
                                        <input type="checkbox" name="type" class="" value='online'> Allow Online
                                        Quotation
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <button data-placement="top" data-content="click save changes button for send rfp"
                                            type="submit"
                                            class="btn-block btn btn-success rounded font-10 mt-10 submit-button"><i
                                                class="la la-check"></i>&nbsp;Send to supplier
                                    </button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="requisitionDetailModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content"><!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Requisitions Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div><!-- Modal body -->
                <div class="modal-body" id="tableData"></div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        getRequestProposalCombineInfo();

        function getRequestProposalCombineInfo() {
            $('#supplier_id').html('');

            var products = [];
            $.each($("input.element_first:checked"), function (key, value) {
                products.push(value.value);
            });

            $.ajax({
                url: "{{ url('pms/rfp/request-proposal-combine-info') }}",
                type: 'POST',
                dataType: 'json',
                data: {_token: "{{ csrf_token() }}", products: products},
            })
                .done(function (response) {
                    $('#reference_no').val(response.reference_no);
                    var suppliers = '';
                    $.each(response.suppliers, function (index, val) {
                        suppliers += '<option value="' + (val.id) + '">' + (val.name) + ' (' + (val.code) + ')</option>';
                    });
                    $('#supplier_id').html(suppliers);
                })
                .fail(function (response) {
                    $('#reference_no').val('');
                    $('#supplier_id').html('');
                });
        }

        function CheckAll() {
            if ($('#checkAllProduct').is(':checked')) {
                $('input.element_first').prop('checked', true);
            } else {
                $('input.element_first').prop('checked', false);
            }

            getRequestProposalCombineInfo();
        }

        function openModal(product_id) {
            $('#tableData').empty().load('{{url(Request()->route()->getPrefix()."request-proposal/details")}}/' + product_id);
            $('#requisitionDetailModal').modal('show')
        }

        calculateTotal();

        function calculateTotal() {
            var total = 0;
            $.each($('.rfp_qty'), function (index, val) {
                total += ($(this).val() != "" ? parseInt($(this).val()) : 0);
            });

            $('#rfp_qty').html(total);
        }

        $(document).ready(function () {
            var form = $('.rfp-form');
            var button = $('.submit-button');
            var button_content = button.html();
            form.submit(function (event) {
                event.preventDefault();
                button.html('<i class="las la-spinner"></i>&nbsp;Please wait...').prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    data: form.serializeArray(),
                })
                    .done(function (response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                        button.html(button_content).prop('disabled', false);
                    })
                    .fail(function (response) {
                        button.html(button_content).prop('disabled', false);
                        $.each(response.responseJSON.errors, function (index, val) {
                            toastr.error(val[0]);
                        });
                    });
            });
        });
    </script>
@endsection