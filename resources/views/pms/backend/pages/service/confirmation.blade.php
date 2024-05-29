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
            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info p-4">
                    <form action="{{ url('pms/grn/service-confirmation/'.$purchaseOrder->id) }}" method="post" id="service-confirmation-form">
                    @csrf
                    @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h4 class="mb-2"><strong><i class="las la-handshake"></i>&nbsp;&nbsp;Purchase Order</strong></h4>
                                <table class="table table-striped table-bordered table-head">
                                    <thead>
                                        <tr>
                                            <th>Approval Date</th>
                                            <th>Reference</th>
                                            <th>Supplier</th>
                                            <th>Quotation Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ date('Y-m-d', strtotime($purchaseOrder->po_date)) }}</td>
                                            <td>{{ $purchaseOrder->reference_no }}</td>
                                            <td>{{ isset($purchaseOrder->relQuotation->relSuppliers) ? (isset($purchaseOrder->relQuotation->relSuppliers->name) ? $purchaseOrder->relQuotation->relSuppliers->name.' ('.$purchaseOrder->relQuotation->relSuppliers->code.')' : '') : '' }}</td>
                                            <td>{{ isset($purchaseOrder->relQuotation->reference_no) ? $purchaseOrder->relQuotation->reference_no : '' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 mb-2">
                                <h4 class="mb-2"><strong><i class="las la-handshake"></i>&nbsp;&nbsp;Services</strong></h4>
                                <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 5%">SL</th>
                                            <th style="width: 10%">Category</th>
                                            <th style="width: 10%">Service</th>
                                            <th style="width: 20%">Attributes</th>
                                            <th style="width: 30%">Description</th>
                                            <th style="width: 5%">UOM</th>
                                            <th style="width: 10%">Previously Confirmed</th>
                                            <th style="width: 10%">Confirming Now</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($purchaseOrder->relPurchaseOrderItems))
                                        @foreach($purchaseOrder->relPurchaseOrderItems as $key => $item)
                                        @if($item->relProduct->is_service == 1)
                                        @php
                                            $received_qty = 0;
                                            if($purchaseOrder->relGoodReceiveNote->count() > 0){
                                                foreach($purchaseOrder->relGoodReceiveNote as $key => $grn){
                                                    $received_qty += $grn->relGoodsReceivedItems->where('product_id', $item->product_id)->sum('received_qty');
                                                }
                                            }
                                        @endphp
                                        @if($item->qty > $received_qty)
                                        <tr>
                                            <td class="text-center">{{$key+1}}</td>
                                            <td class="text-center">{{ isset($item->relProduct->category)?$item->relProduct->category->name:'' }}</td>
                                            <td>
                                                {{ isset($item->relProduct->name)?$item->relProduct->name:'' }} {{ getProductAttributesFaster($item->relProduct) }}
                                            </td>
                                            <td>
                                                {{ getProductAttributesFaster($requisitionItems->where('product_id', $item->product_id)->first()) }}
                                            </td>
                                            <td>
                                                {{ $purchaseOrder->relQuotation->relQuotationItems->where('product_id', $item->product_id)->first()->description }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:'' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $received_qty }}
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="received_qty[{{ $item->id }}]" id="received_qty_{{ $item->id }}" value="{{ $item->qty-$received_qty }}" class="form-control text-right" min="0" max="{{ $item->qty-$received_qty }}">
                                            </td>
                                        </tr>
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="date"><strong>Date <span class="text-danger">*</span></strong></label>
                                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
                                            </div>
                                            <div class="col-md-10">
                                                <label for="note"><strong>Note</strong></label>
                                                <input type="text" name="note" id="note" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-right pt-4">
                                        <button class="btn btn-success btn-md btn-block mt-2 service-confirmation-button"><i class="las la-check"></i>&nbsp;Confirm Service</button>
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
@endsection

@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#service-confirmation-form');
        var button = $('.service-confirmation-button');

        form.submit(function(event) {
            event.preventDefault();
            var content = button.html();
            button.html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...').prop('disabled', true);

            $.confirm({
                title: 'Confirm!',
                content: '<hr><h4 class="text-center">Are you sure to confirm services?</h4><hr>',
                buttons: {
                    yes: {
                        text: '<i class="las la-check"></i>&nbsp;Yes',
                        btnClass: 'btn-blue',
                        action: function(){
                            $.ajax({
                                url: form.attr('action'),
                                type: form.attr('method'),
                                dataType: 'json',
                                data: new FormData(form[0]),
                                cache: false,
                                contentType: false,
                                processData: false
                            })
                            .done(function(response) {
                                if(response.success){
                                    window.open("{{ url('pms/supplier/rating') }}/"+response.supplier_id+"/"+response.grn_id+"?table=goods_received_notes&field="+response.grn_id+"&type=grn", '_parent');
                                }else{
                                    toastr.error(response.message);
                                }
                                button.html(content).prop('disabled', false);
                            })
                            .fail(function(response){
                                $.each(response.responseJSON.errors, function(index, val) {
                                    toastr.error(val[0]);
                                });
                                button.html(content).prop('disabled', false);
                            });
                        }
                    },
                    no: {
                        text: '<i class="las la-ban"></i>&nbsp;No',
                        btnClass: 'btn-red',
                        action: function(){
                            button.html(content).prop('disabled', false);
                        }
                    }
                }
            });
        });
    });
</script>
@endsection