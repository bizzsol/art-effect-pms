@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css" media="screen">
        .quotation-table th {
            font-size: 10px !important;
        }

        input[type="number"] {
            text-align: right !important;
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            display: none !important;
        }

        .mask-money {
            text-align: right !important;
        }

        .select2-container {
            min-width: 100px !important;
            max-width: 100% !important;
            width: 100% !important;
        }
    </style>
@endsection
@section('main-content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{ route('pms.dashboard') }}">Home</a>
                    </li>
                    <li>
                        <a href="#">PMS</a>
                    </li>
                    <li class="active">{{ $title }}</li>
                    <li class="top-nav-btn">
                        <a href="javascript:history.back()"
                           class="btn btn-sm btn-warning text-white"
                           data-toggle="tooltip" title="Back"> <i class="las la-chevron-left"></i>Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <form method="post" action="{{ route('pms.rfp.quotations.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label
                                                                    for="quotation_date"><strong>{{ __('Quotation Date') }}
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="datetime-local"
                                                                   name="quotation_date"
                                                                   id="quotation_date"
                                                                   class="form-control rounded"
                                                                   value="{{ old('quotation_date')?old('quotation_date'):date('Y-m-d H:i:s') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><label class="font-weight-bold"
                                                                  for="delivery_date"><strong>Delivery
                                                                                              Date</strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="date" name="delivery_date"
                                                                   id="delivery_date"
                                                                   value="{{ date('Y-m-d') }}"
                                                                   min="{{ date('Y-m-d') }}"
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1 font-weight-bold"><label
                                                                    for="reference_no"><strong>{{ __('Reference No') }}
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="reference_no"
                                                                   id="reference_no"
                                                                   class="form-control rounded"
                                                                   readonly aria-label="Large"
                                                                   aria-describedby="inputGroup-sizing-sm"
                                                                   required
                                                                   value="{{ ($refNo)?($refNo):0 }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label
                                                                    for="supplier_quotation_date"><strong>{{ __('Supplier Quotation Date') }}
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="date"
                                                                   name="supplier_quotation_date"
                                                                   id="quotation_date"
                                                                   class="form-control rounded"
                                                                   value="{{ old('supplier_quotation_date') ? old('supplier_quotation_date') : date('Y-m-dF') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <p class="mb-1 font-weight-bold"><label
                                                                    for="supplier_quotation_reference_no"><strong>{{ __('Supplier Reference No') }}
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" name="supplier_quotation_ref_no"
                                                                   id="reference_no"
                                                                   class="form-control rounded"
                                                                   aria-label="Large"
                                                                   placeholder="EX-QC-23-SSL-003"
                                                                   aria-describedby="inputGroup-sizing-sm"
                                                                   required
                                                                   value="{{ old('supplier_quotation_ref_no') }}">
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label
                                                                    for="supplier_id"><strong>{{ __('Supplier') }}
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="supplier_id"
                                                                    id="supplier_id"
                                                                    class="form-control rounded"
                                                                    required
                                                                    onchange="getSupplierInfo()">
                                                                <option value="{{ null }}">{{ __('Select One') }}</option>
                                                                @if(count($requestProposal->defineToSupplier->whereNotIn('supplier_id', $quotationSupplierArray)) > 0)
                                                                    @foreach($requestProposal->defineToSupplier->whereNotIn('supplier_id', $quotationSupplierArray) as $key => $data)
                                                                        <option value="{{ $data->supplier->id }}">{{ $data->supplier->name }}
                                                                            ({{ $data->supplier->code }}
                                                                            )
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12">
                                                        <p><label class="font-weight-bold"
                                                                  for="currency_id"><strong>{{ __('Currency') }}
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="currency_id"
                                                                    id="currency_id"
                                                                    class="form-control rounded">

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label
                                                                    for="validation_days"><strong>Validation
                                                                                                  Days<span
                                                                            class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text"
                                                                   name="validation_days"
                                                                   id="validation_days"
                                                                   class="form-control" value="7"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-4 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label for="discount_percent"><strong>Discount (%)</strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            {{ Form::number('discount_percent', old('discount_percent')?old('discount_percent'):0, ['class'=>'form-control rounded', 'placeholder'=>'0%','min'=>'0','max'=>'100','step'=>'any','id'=>'discount_percent','oninput'=>"this.value = Math.abs(this.value)"]) }}
                                                            <input type="checkbox" name="checkall_discount" id="checkAllDiscount" class="form-control" checked style="display: none">
                                                        </div>
                                                    </div> --}}
                                                    {{-- <div class="col-md-4 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label for="vat_type"><strong>VAT Type</strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select class="form-control vat_type" name="vat_type" id="vat_type" onchange="distributeVAT()">
                                                                <option value="exempted">Exempted</option>
                                                                <option value="inclusive">Inclusive</option>
                                                                <option value="exclusive">Exclusive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-12">
                                                        <p class="mb-1 font-weight-bold"><label for="vat_type"><strong>VAT(%)</strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="text" step="0.01" name="vat" id="vat" value="0" class="form-control mask-money" onkeyup="distributeVAT()" onchange="distributeVAT()">
                                                        </div>
                                                    </div> --}}
                                                    {{-- <div class="col-md-1 col-sm-12 pt-4">
                                                        <div class="input-group input-group-md mb-3 d- pt-2">
                                                            <input type="checkbox" name="checkall_discount" id="checkAllDiscount" class="form-control" style="margin-left: -35px;">
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <p><label class="font-weight-bold"
                                                                  for="payment_term_id"><strong>Payment
                                                                                                Term
                                                                    <span class="text-danger">*</span></strong></label>
                                                        </p>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="payment_term_id"
                                                                    id="payment_term_id"
                                                                    class="form-control rounded"
                                                                    onchange="printPaymentTermInformation()">
                                                                @if(isset($paymentTerms[0]))
                                                                    @foreach($paymentTerms as $paymentTerm)
                                                                        <option value="{{ $paymentTerm->id }}"
                                                                                data-percentage="{{ $paymentTerm->percentage }}"
                                                                                data-type="{{ $paymentTerm->type }}"
                                                                                data-remarks="{{ $paymentTerm->remarks }}">{{ $paymentTerm->term}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <h6 class="mb-2"><strong>Payment
                                                                                 Percentages</strong>
                                                        </h6>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th style="width:20%">Percentage</th>
                                                            {{-- <th style="width:25%">Day Duration</th> --}}
                                                            <th style="width:20%">Payment Type</th>
                                                            <th style="width:45%">Payment Mode</th>
                                                            <th style="width:15%"
                                                                class="text-center">Actions
                                                            </th>
                                                            </thead>
                                                            <tbody id="payment-percentages-div">

                                                            </tbody>
                                                        </table>
                                                        <a class="btn btn-xs btn-success text-white pull-right"
                                                           onclick="newPaymentTerm()"><i
                                                                    class="las la-plus"></i>&nbsp;
                                                                                            New Line</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-10">
                                    <h6 class="mb-2"><strong>Products</strong></h6>
                                    <table class="table table-striped table-bordered table-head quotation-table"
                                           cellspacing="0" width="100%" id="dataTable">
                                        <thead>
                                        <tr class="text-center">
                                            <th style="width: 20%">Product</th>
                                            <th style="width: 35%">Description</th>
                                            <th style="width: 7.5%">UOM</th>
                                            <th style="width: 7.5%">Quantity</th>
                                            <th style="width: 12.5%">Unit Price</th>
                                            <th style="width: 12.5%">Gross Total</th>
                                            {{-- <th style="width: 7.5%">Discount (%)</th>
                                            <th style="width: 10%">Total Discount</th>
                                            <th style="width: 10%">Net Total</th>
                                            <th>VAT Type</th>
                                            <th style="width: 7.5%">VAT (%)</th>
                                            <th style="width: 10%">Vat Amount</th>
                                            <th style="width: 10%">Final Total</th> --}}
                                            <th style="width: 5%" title="Technical Specification">
                                                Specs
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(isset($requestProposal->requestProposalDetails))
                                            @foreach($requestProposal->requestProposalDetails as $key=>$item)
                                                <tr>
                                                    <td>
                                                        {{ isset($item->product->name) ? $item->product->name : '' }}
                                                        ({{ isset($item->product->sku) ? $item->product->sku : '' }}
                                                        )
{{--                                                        {{ getProductAttributesFaster($item->product) }} {{ getProductAttributesFaster($requisitionItems->where('uid', $item->uid)->first()) }}--}}
                                                        <input type="hidden" name="product_id[]"
                                                               class="form-control"
                                                               value="{{$item->uid}}">
                                                    </td>
                                                    <td>
                                                        <textarea
                                                                name="product_description[{{$item->uid}}]"
                                                                class="form-control"
                                                                style="min-height: 80px;">{{ getProductAttributesFaster($requisitionItems->where('uid', $item->uid)->first()) }}</textarea>
                                                    </td>
                                                    <td class="text-center">
                                                        {{$item->product->productUnit->unit_name}}
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                               name="qty[{{$item->uid}}]" required
                                                               class="form-control summation-qty"
                                                               min="0"
                                                               id="qty{{$item->uid}}"
                                                               value="{{round($item->request_qty)}}"
                                                               onKeyPress="if(this.value.length==4) return false;"
                                                               onkeyup="calculateSubtotal('{{ $item->uid }}')"
                                                               step='any'
                                                               onchange="calculateSubtotal('{{ $item->uid }}')">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               name="unit_price[{{$item->uid}}]"
                                                               required
                                                               class="form-control text-right"
                                                               min="0.0"
                                                               step='any' value="0.00"
                                                               id="unitPrice{{$item->uid}}"
                                                               placeholder="0.00"
                                                               onkeyup="calculateSubtotal('{{ $item->uid }}')"
                                                               onchange="calculateSubtotal('{{ $item->uid }}')">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                               name="sub_total_price[{{$item->uid}}]"
                                                               required
                                                               class="form-control calculateSumOfSubtotal"
                                                               readonly
                                                               id="subTotalPrice_{{$item->uid}}"
                                                               placeholder="0.00" value="0.00">

                                                        <input type="hidden" step='any'
                                                               name="item_discount_percent[{{$item->uid}}]"
                                                               class="form-control calculateDiscount bg-white"
                                                               id="itemDiscountPercent{{$item->uid}}"
                                                               placeholder="0"
                                                               data-id="{{$item->uid}}" min="0">
                                                        <input type="hidden"
                                                               name="item_discount_amount[{{$item->uid}}]"
                                                               id="itemWiseDiscount_{{$item->uid}}"
                                                               class="itemWiseDiscount">

                                                        <input type="hidden"
                                                               name="sub_total_vat_price[{{$item->uid}}]"
                                                               required
                                                               class="form-control calculateSumOfVat"
                                                               readonly
                                                               id="sub_total_vat_price{{$item->uid}}"
                                                               placeholder="0.00" value="0.00">
                                                        <input type="hidden"
                                                               class="product_vat_type"
                                                               name="product_vat_type[{{$item->uid}}]"
                                                               id="product_vat_type_{{$item->uid}}"
                                                               value="exempted"
                                                               onchange="calculateSumOfVatValue('{{$item->uid}}', true)">
                                                        <input type="hidden" step="0.01"
                                                               name="product_vat[{{$item->uid}}]"
                                                               id="product_vat_{{$item->uid}}"
                                                               data-id="{{$item->uid}}"
                                                               value="0"
                                                               class="form-control calculateProductVat mask-money"
                                                               onchange="calculateSumOfVatValue('{{$item->uid}}', true)">
                                                    </td>
                                                    {{-- <td>
                                                        <input type="text" step='any'
                                                               name="item_discount_percent[{{$item->uid}}]"
                                                               class="form-control calculateDiscount bg-white"
                                                               id="itemDiscountPercent{{$item->uid}}"
                                                               placeholder="0"
                                                               onKeyPress="if(this.value.length==5) return false;"
                                                               onkeyup="calculateItemDiscount('{{$item->uid}}', true)"
                                                               onchange="calculateItemDiscount('{{$item->uid}}', true)"
                                                               data-id="{{$item->uid}}" min="0">

                                                        <input type="hidden"
                                                               name="item_discount_amount[{{$item->uid}}]"
                                                               id="itemWiseDiscount_{{$item->uid}}"
                                                               class="itemWiseDiscount">

                                                        <input type="hidden"
                                                               name="sub_total_vat_price[{{$item->uid}}]"
                                                               required class="form-control calculateSumOfVat" readonly
                                                               id="sub_total_vat_price{{$item->uid}}"
                                                               placeholder="0.00" value="0.00">
                                                    </td>
                                                    <td class="text-right" id="discount_amount_{{$item->uid}}"
                                                        style="padding-right: 2.25rem !important;">0.00
                                                    </td>
                                                    <td class="text-right summation-after-discount-amount"
                                                        id="after_discount_amount_{{$item->uid}}">0.00
                                                    </td>
                                                    <td>
                                                        <select class="form-control product_vat_type" name="product_vat_type[{{$item->uid}}]" id="product_vat_type_{{$item->uid}}"
                                                               onchange="calculateSumOfVatValue('{{$item->uid}}', true)">
                                                            <option value="inclusive" {{ $item->product->vat_type == 'inclusive' ? 'selected' : '' }}>Inclusive</option>
                                                            <option value="exclusive" {{ $item->product->vat_type == 'exclusive' ? 'selected' : '' }}>Exclusive</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" step="0.01"
                                                               name="product_vat[{{$item->uid}}]"
                                                               id="product_vat_{{$item->uid}}"
                                                               data-id="{{$item->uid}}"
                                                               value="{{systemDoubleValue($item->product->vat, 2)}}"
                                                               class="form-control calculateProductVat mask-money"
                                                               onkeyup="calculateSumOfVatValue('{{$item->uid}}', true)"
                                                               onchange="calculateSumOfVatValue('{{$item->uid}}', true)">
                                                    </td>
                                                    <td class="text-right vat_amounts" id="sub_total_vat_price{{$item->uid}}_show">
                                                        0.00
                                                    </td>
                                                    <td class="text-right summation-gross-amount" id="item_gross_total_{{$item->uid}}">
                                                        0.00
                                                    </td> --}}

                                                    <td class="text-center">
                                                        <label class="btn btn-sm btn-dark custom-file-upload">
                                                            <input type="file"
                                                                   name="item_technical_specification_file[{{$item->uid}}]"
                                                                   accept="application/pdf"
                                                                   id="item_technical_specification_file{{$item->uid}}"
                                                                   style="display: none"
                                                                   onchange="checkFile($(this))"
                                                            >
                                                            <span class="custom-file-upload-text">Upload</span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>Total: </strong>
                                            </td>
                                            <td>
                                                <input type="text" name="sum_of_subtotal" readonly
                                                       class="form-control mask-money"
                                                       id="sumOfSubtoal" placeholder="0.00">
                                            </td>
                                            <td class="text-right" rowspan="7"></td>
                                            {{-- <td>
                                                <input type="text" name="discount" class="form-control bg-white mask-money" step='any' id="discount" placeholder="0.00" value="0.00">
                                                <input type="hidden" id="sub_total_with_discount" name="sub_total_with_discount" min="0" placeholder="0.00">
                                            </td>
                                            <td class="text-right total_value_after_discount"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right" id="total_vat_amount"></td>
                                            <td class="text-right" id="total_gross_amount"></td>
                                            <td></td>
                                            <input type="hidden" name="gross_price" id="grossPrice"> --}}
                                        </tr>

                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>Discount Amount: </strong>
                                            </td>
                                            <td>
                                                <input type="text" name="discount"
                                                       class="form-control bg-white mask-money"
                                                       step='any' id="discount" placeholder="0.00"
                                                       value="0.00">
                                                <input type="hidden" id="sub_total_with_discount"
                                                       name="sub_total_with_discount" min="0"
                                                       placeholder="0.00">

                                                {{ Form::hidden('discount_percent', old('discount_percent')?old('discount_percent'):0, ['class'=>'form-control rounded', 'placeholder'=>'0%','min'=>'0','max'=>'100','step'=>'any','id'=>'discount_percent','oninput'=>"this.value = Math.abs(this.value)"]) }}
                                                <input type="checkbox" name="checkall_discount"
                                                       id="checkAllDiscount" class="form-control"
                                                       checked style="display: none">
                                            </td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>After Discount: </strong>
                                            </td>
                                            <td class="text-right total_value_after_discount">

                                            </td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>VAT Type: </strong>
                                            </td>
                                            <td>
                                                <select class="form-control vat_type"
                                                        name="vat_type" id="vat_type"
                                                        onchange="distributeVATType()">
                                                    <option value="exempted">Exempted</option>
                                                    <option value="inclusive">Inclusive</option>
                                                    <option value="exclusive">Exclusive</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>VAT(%): </strong>
                                            </td>
                                            <td class="text-right">
                                                <input type="text" step="0.01" name="vat" id="vat"
                                                       value="0" class="form-control mask-money"
                                                       onkeyup="distributeVATPercentage()"
                                                       onchange="distributeVATPercentage()">
                                            </td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>Total VAT: </strong>
                                            </td>
                                            <td class="text-right" id="total_vat_amount">

                                            </td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="5" class="text-right">
                                                <strong>Grand Total: </strong>
                                            </td>
                                            <td class="text-right" id="total_gross_amount">

                                            </td>
                                            <input type="hidden" name="gross_price" id="grossPrice">
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 font-weight-bold">
                                        <label for="note">
                                            <strong>{{ __('Note') }}</strong>
                                        </label>
                                    </p>
                                    <div class="input-group input-group-md mb-3">
                                        <textarea name="note" id="note" class="form-control rounded" rows="3" placeholder="Write any note..."></textarea>
                                    </div>
                                </div>
                                @if($requisitionItems->count() > 0 && $requisitionItems->filter(fn($item) => $item->requisition->attachments->count() > 0)->count() > 0)
                                    <div class="col-md-6 pl-5">
                                        <h4>Attachment List:</h4>
                                        @foreach($requisitionItems as $item)
                                            @if($item->requisition->attachments->count() > 0)
                                                <ol type="number">
                                                    @foreach($item->requisition->attachments as $key => $attachment)
                                                        <li>
                                                            <a href="{{ url($attachment->file_location) }}" target="_blank">
                                                                Attachment{{ $key + 1 }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                </div>


                                <div class="form-row mt-5">

                                    <input type="hidden" name="request_proposal_id"
                                           value="{{$requestProposal->id}}">
                                    <input type="hidden" name="type" value="manual">

                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="QuotationFile"><strong>{{ __('Quotation File (Pdf)') }}
                                                    :</strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="file" name="quotation_file"
                                                       id="QuotationFile"
                                                       class="btn btn-outline-primary"
                                                       aria-label="Large"
                                                       aria-describedby="inputGroup-sizing-sm"
                                                       value=""
                                                       accept="application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="technicalSpecification"><strong>{{ __('Technical Specification File (Pdf)') }}
                                                    :</strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="file"
                                                       name="technical_specification_file"
                                                       id="technicalSpecification"
                                                       class="btn btn-outline-success"
                                                       aria-label="Large"
                                                       aria-describedby="inputGroup-sizing-sm"
                                                       value=""
                                                       accept="application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pt-4 offset-md-4">
                                        <button type="submit"
                                                class="mt-2 btn btn-block btn-success rounded">
                                            <i class="la la-check"></i>&nbsp;{{ __('Generate Quotation') }}
                                        </button>
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
    <script>
        $('body').addClass('sidebar-main');

        "use strcit"

        summation();

        function calculateSubtotal(id) {
            var unit_price = parseFloat($('#unitPrice' + id).val());
            var qty = parseFloat($('#qty' + id).val());

            if (unit_price != '' && qty != '') {

                var sub_total = parseFloat(unit_price * qty);
                $('#subTotalPrice_' + id).val(parseFloat(sub_total).toFixed(2));

                var total = 0;
                $(".calculateSumOfSubtotal").each(function () {
                    total += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
                $("#sumOfSubtoal").val(parseFloat(total).toFixed(2));

                calculateItemDiscount(id);
                calculateSumOfVatValue(id);

            } else {
                toastr.error('Please Enter Unit Price & Quentity');
            }

            summation();

            return false;
        }

        $('#discount_percent').on('change', function () {
            distributeDiscount();
        });

        $('#checkAllDiscount:checkbox').on('change', function () {
            distributeDiscount();
        });

        function distributeDiscount() {
            var discount_parcent = ($('#discount_percent').val() != '' ? $('#discount_percent').val() : 0);

            if ($('#checkAllDiscount:checkbox').prop('checked')) {
                $(".calculateDiscount").val(parseFloat(discount_parcent).toFixed(2));
                $(".calculateDiscount").attr('readonly', true);
            } else {
                $(".calculateDiscount").val(parseFloat(0).toFixed(2));
                $(".calculateDiscount").attr('readonly', false);
                $('#discount_percent').val(parseFloat(0).toFixed(2));
            }

            var discountFields = document.querySelectorAll('.calculateDiscount');

            Array.from(discountFields).map((item, key) => {
                calculateItemDiscount(item.getAttribute('data-id'));
                calculateSumOfVatValue(item.getAttribute('data-id'));
            });

            summation();
        }

        function calculateItemDiscount(id, summ = false) {

            var sub_total_price = parseFloat($('#subTotalPrice_' + id).val() != '' ? $('#subTotalPrice_' + id).val() : 0).toFixed(2);
            var item_discount_percent = parseFloat($('#itemDiscountPercent' + id).val() != '' ? $('#itemDiscountPercent' + id).val() : 0);
            var value = parseFloat((item_discount_percent * sub_total_price) / 100).toFixed(2);
            $('#itemWiseDiscount_' + id).val(parseFloat(value).toFixed(2));
            $('#discount_amount_' + id).html(parseFloat(value).toFixed(2));
            $('#after_discount_amount_' + id).html(parseFloat(sub_total_price - value).toFixed(2));

            var total = 0;
            $(".itemWiseDiscount").each(function () {
                total += parseFloat($(this).val() != '' ? $(this).val() : 0);
            });

            $("#discount").val(parseFloat(total).toFixed(2));

            if (summ) {
                summation();
            }

            calculateSumOfVatValue(id, summ);
        }

        function calculateSumOfVatValue(id, summ = false) {
            var sub_total_price = parseFloat($('#subTotalPrice_' + id).val() != '' ? $('#subTotalPrice_' + id).val() : 0).toFixed(2);
            var discount_amount = parseFloat($('#itemWiseDiscount_' + id).val() != "" ? $('#itemWiseDiscount_' + id).val() : 0).toFixed(2);

            var discounted = parseFloat(parseFloat(sub_total_price) - parseFloat(discount_amount)).toFixed(2);
            var product_vat = parseFloat($('#product_vat_' + id).val() != '' ? $('#product_vat_' + id).val() : 0);
            var product_vat_type = $('#product_vat_type_' + id).val();

            if (product_vat_type == 'inclusive') {
                var vat_amount = parseFloat(product_vat > 0 && discounted > 0 ? ((discounted * product_vat) / (100 + product_vat)) : 0).toFixed(2);
                $('#item_gross_total_' + id).html(parseFloat(parseFloat(discounted)).toFixed(2));
            } else if (product_vat_type == 'exclusive') {
                var vat_amount = parseFloat(product_vat > 0 && discounted > 0 ? (discounted * (product_vat / 100)) : 0).toFixed(2);
                $('#item_gross_total_' + id).html(parseFloat(parseFloat(discounted) + parseFloat(vat_amount)).toFixed(2));
            } else {
                var vat_amount = parseFloat(0).toFixed(2);
                $('#item_gross_total_' + id).html(parseFloat(parseFloat(discounted)).toFixed(2));
            }


            $('#sub_total_vat_price' + id).val(parseFloat(vat_amount).toFixed(2));
            $('#sub_total_vat_price' + id + '_show').html(parseFloat(vat_amount).toFixed(2));

            if (summ) {
                summation();
            }
        }

        $('#discount').on('change', function () {
            calculateDiscountValue();
        });

        function calculateDiscountValue() {
            var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0).toFixed(2);
            var sumOfSubtoal = parseFloat($("#sumOfSubtoal").val() != '' ? $("#sumOfSubtoal").val() : 0).toFixed(2);
            var discountPercetage = discount > 0 && sumOfSubtoal > 0 ? (discount * 100) / sumOfSubtoal : 0;

            $(".calculateDiscount").val(discountPercetage);

            var calculateDiscount = document.querySelectorAll('.calculateDiscount')
            Array.from(calculateDiscount).map(item => {
                var id = item.getAttribute('data-id');

                var sub_total_price = parseFloat($('#subTotalPrice_' + id).val() != '' ? $('#subTotalPrice_' + id).val() : 0).toFixed(2);
                var item_discount_percent = parseFloat($('#itemDiscountPercent' + id).val() != '' ? $('#itemDiscountPercent' + id).val() : 0).toFixed(2);
                var value = parseFloat(item_discount_percent > 0 && sub_total_price > 0 ? (item_discount_percent * sub_total_price) / 100 : 0).toFixed(2);
                $('#itemWiseDiscount_' + id).val(parseFloat(value).toFixed(2));
                $('#discount_amount_' + id).html(parseFloat(value).toFixed(2));
            });

            var discountFields = document.querySelectorAll('.calculateDiscount');
            Array.from(discountFields).map((item, key) => {
                calculateItemDiscount(item.getAttribute('data-id'));
                calculateSumOfVatValue(item.getAttribute('data-id'));
            });

            summation();
        }

        function summation() {
            var sumOfSubtoal = parseFloat($('#sumOfSubtoal').val() != '' ? $('#sumOfSubtoal').val() : 0).toFixed(2);
            var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0).toFixed(2);
            var discounted = parseFloat(parseFloat(sumOfSubtoal) - parseFloat(discount)).toFixed(2);
            $('#sub_total_with_discount').val(parseFloat(discounted).toFixed(2));

            var inclusive_vat = 0;
            var exclusive_vat = 0;
            $(".calculateSumOfVat").each(function () {
                if ($(this).parent().find('.product_vat_type').val() == 'inclusive') {
                    inclusive_vat += parseFloat($(this).val() != '' ? $(this).val() : 0);
                } else if ($(this).parent().find('.product_vat_type').val() == 'exclusive') {
                    exclusive_vat += parseFloat($(this).val() != '' ? $(this).val() : 0);
                }
            });

            var total = parseFloat(parseFloat(discounted) + parseFloat(exclusive_vat)).toFixed(2);

            $('.total_value_after_discount').html(parseFloat(discounted).toFixed(2));
            $('#total_vat_amount').html(parseFloat(inclusive_vat + exclusive_vat).toFixed(2));
            $('#total_gross_amount').html(parseFloat(total).toFixed(2));
            $('#grossPrice').val(parseFloat(total).toFixed(2));
        }

        function distributeVATType() {
            if ($('#vat_type').find(':selected').val() == 'exempted') {
                $('#vat').val(0);
                $.each($('.calculateProductVat'), function (index, val) {
                    $(this).val(0).change();
                });
            }

            $.each($('.product_vat_type'), function (index, val) {
                $(this).val($('#vat_type').find(':selected').val()).change();
            });
        }

        function distributeVATPercentage() {
            if ($('#vat_type').find(':selected').val() == 'exempted') {
                $('#vat').val(0);
                $.each($('.calculateProductVat'), function (index, val) {
                    $(this).val(0).change();
                });
            } else {
                $.each($('.calculateProductVat'), function (index, val) {
                    $(this).val($('#vat').val()).change();
                });
            }
        }

        getSupplierInfo();

        function getSupplierInfo() {
            var supplier_id = $('#supplier_id').val();
            $.ajax({
                url: "{{ url('pms/rfp/quotations/generate/'.$proposal_id) }}?get-supplier-info&supplier_id=" + $('#supplier_id').val(),
                type: 'GET',
                dataType: 'json',
                data: {},
            })
                .done(function (response) {
                    var currencies = '';
                    $.each(response.currencies, function (index, type) {
                        if (type.currencies[0]) {
                            currencies += '<optgroup label="' + type.name + '">';
                            $.each(type.currencies, function (index, currency) {
                                currencies += '<option value="' + currency.id + '">&nbsp;&nbsp;&nbsp;' + currency.name + ' (' + currency.code + ' | ' + currency.symbol + ')</option>';
                            });
                            currencies += '</optgroup>';
                        }
                    });
                    $('#currency_id').html(currencies != '' ? currencies : '<option value="">Choose Currency</option>').change();
                });
        }

        function checkFile(element) {
            if (element[0].files.length == 0) {
                element.parent().removeClass('btn-success').addClass('btn-dark');
                element.parent().find('.custom-file-upload-text').html('Upload');
            } else {
                element.parent().removeClass('btn-dark').addClass('btn-success');
                element.parent().find('.custom-file-upload-text').html('Uploded');
            }
        }

        printPaymentTermInformation();

        function printPaymentTermInformation() {
            var percentage = $('#payment_term_id').find(':selected').attr('data-percentage');
            var type = $('#payment_term_id').find(':selected').attr('data-type');
            var remarks = $('#payment_term_id').find(':selected').attr('data-remarks');
            $('#payment-percentages-div').html('<tr>' +
                '<td>' +
                '<input type="number" name="payment_percentages[]" value="' + percentage + '" class="form-control payment-percentages" min="1" max="100" placeholder="%" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" required />' +
                '</td>' +
                // '<td>'+
                //     '<input type="number" name="payment_durations[]" value="1" class="form-control day-durations" min="1" max="9999" placeholder="Day" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" required />'+
                // '</td>'+
                '<td>' +
                '<select name="payment_types[]" class="form-control payment-types" required>' +
                '<option value="paid" ' + (type == 'paid' ? 'selected' : '') + '>Advance</option>' +
                '<option value="due" ' + (type == 'due' ? 'selected' : '') + '>Credit</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input type="text" name="payment_modes[]" class="form-control payment-modes" value="' + remarks + '" required />' +
                '</td>' +
                '<td class="text-center">' +
                '<a class="btn btn-xs btn-danger text-white" onclick="removePaymentTerm($(this))"><i class="las la-trash"></i></a>' +
                '</td>' +
                '</tr>');
            $('.payment-types').select2();
        }

        function newPaymentTerm() {
            $('#payment-percentages-div').append('<tr>' +
                '<td>' +
                '<input type="number" name="payment_percentages[]" value="0" class="form-control payment-percentages" min="1" max="100" placeholder="%" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" required />' +
                '</td>' +
                // '<td>'+
                //     '<input type="number" name="payment_durations[]" value="1" class="form-control day-durations" min="1" max="9999" placeholder="Day" onchange="validatePaymentTerms()" onkeyup="validatePaymentTerms()" required />'+
                // '</td>'+
                '<td>' +
                '<select name="payment_types[]" class="form-control payment-types" required>' +
                '<option value="paid">Advance</option>' +
                '<option value="due">Credit</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input type="text" name="payment_modes[]" class="form-control payment-modes" required />' +
                '</td>' +
                '<td class="text-center">' +
                '<a class="btn btn-xs btn-danger text-white" onclick="removePaymentTerm($(this))"><i class="las la-trash"></i></a>' +
                '</td>' +
                '</tr>');
            $('.payment-types').select2();
        }

        function removePaymentTerm(element) {
            element.parent().parent().remove();
        }

        function validatePaymentTerms() {
            var percentages = 0;
            $.each($('.payment-percentages'), function (index, val) {
                var max = parseInt($(this).attr('max'));

                if ($(this).val() > max) {
                    $(this).val(0);
                } else {
                    percentages += parseInt($(this).val());
                }

                if (percentages > max) {
                    $(this).val(0);
                }
            });

            // $.each($('.day-durations'), function(index, val) {
            //     var max = parseInt($(this).attr('max'));

            //     if($(this).val() > max){
            //         $(this).val(0);
            //     }
            // });
        }
    </script>
@endsection