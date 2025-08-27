@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .form-check-input {
            margin-top: -4px !important;
        }

        ul {
            list-style: none;
        }

        .select2 .select2-container .select2-container--default {
            width: 100px !important;
        }

        .select2-container {
            width: 100% !important;
        }

        @if(count($quotations)>2)
        tbody {
            display: contents;
            /* width: 2000px;*/
            table-layout: auto;
        }
        @endif
    </style>

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
                    <div class="panel panel-body">
                        {!! Form::open(['route' => 'pms.quotation.quotations.cs.compare.store',  'files'=> false, 'id'=>'send-to-management-form', 'class' => '']) !!}
                        <div class="row">
                            @if(isset($quotations[0]))
                                @foreach($quotations as $key=>$quotation)
                                    @if($key==0)
                                        <div class="col-md-6">
                                            <ul>
                                                <li><strong>CS Number:</strong> {{$quotation->relRequestProposal->reference_no}}</li>
                                                @if($requisitionItems->isNotEmpty())
                                                    <li><strong>Requisition Ref Number :</strong> {{ $requisitionItems->first()->requisition->reference_no }}</li>
                                                @endif
                                                <li><strong>Project Name: </strong>{{ $quotation->relRequestProposal-> project_name}}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul>
                                                <li><strong>CS Provide By:</strong> {{$quotation->relRequestProposal->createdBy->name}}
                                                </li>
                                                <li><strong>CS Date:</strong> {{date('d-m-Y h:i:s A',strtotime($quotation->relRequestProposal->request_date))}}
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-md-12">
                                    <div class="panel panel-info">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover ">
                                                <tr>
                                                    <th colspan="6">Supplier Name</th>
                                                    @if(isset($quotations[0]))
                                                        @foreach($quotations as $quotation)
                                                        @php
                                                            $TS = number_format($quotation->relSuppliers->SupplierRatings->sum('total_score'), 2);
                                                            $TC = $quotation->relSuppliers->SupplierRatings->count();

                                                            $totalScore = isset($TS) ? $TS : 0;
                                                            $totalCount = isset($TC) ? $TC : 0;
                                                        @endphp
                                                            <th class="invoiceBody fixed-width"
                                                                colspan="{{ $systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '') ? 4 : 3 }}">

                                                                <p class="ratings text-dark">
                                                                    <a href="{{route('pms.supplier.profile',$quotation->relSuppliers->id)}}"
                                                                       target="_blank"><span>{{$quotation->relSuppliers->name}} ({{$quotation->relSuppliers->code}})</span></a>
                                                                    @if(in_array($quotation->type,['manual','online']))
                                                                        ({!!ratingGenerate($totalScore,$totalCount)!!})
                                                                    @endif
                                                                </p>

                                                                <p class="text-dark"><strong>{{__('Q:Ref:No')}}
                                                                        :</strong> {{$quotation->reference_no}}</p>

                                                                <p>
                                                                <div class="form-check">
                                                                    <input type="hidden" name="quotation_id[]" value="{{$quotation->id}}">
                                                                    <input class="form-check-input setRequiredOnSupplierPaymentTerm setRequiredOnSupplierPaymentTerm-{{$quotation->id}}"
                                                                           type="checkbox" name="quotation_recommendations[]"
                                                                           id="is_approved_{{$quotation->id}}"
                                                                           value="{{$quotation->id}}" {{ $quotation->recommendation == 'yes' ? 'checked' : '' }} onchange="checkRecommendationFields($(this))">
                                                                    <input type="hidden" name="request_proposal_id"
                                                                           value="{{$quotation->request_proposal_id}}">
                                                                    <label class="form-check-label"
                                                                           for="is_approved_{{$quotation->id}}"
                                                                           style="cursor: pointer">
                                                                        <strong>Recommendation</strong>
                                                                    </label>
                                                                </div>
                                                                </p>
                                                            </th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <tr class="text-center">
                                                    <th width="1%" class="text-center">SL</th>
                                                    <th>Product</th>
                                                    <th>Attributes</th>
                                                    <th>Description</th>
                                                    <th>UOM</th>
                                                    <th class="text-center">Qty</th>
                                                    @if(isset($quotations[0]))
                                                        @foreach($quotations as $quotation)
                                                            <th class="text-center">Recommend</th>
                                                            <th class="text-center">Unit Price
                                                                ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }}
                                                                )
                                                            </th>
                                                            <th class="text-center">Item Total
                                                                ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }}
                                                                )
                                                            </th>
                                                            @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                <th class="text-center">Item Total
                                                                    ({{ $systemCurrency->code }})
                                                                </th>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tr>

                                                <tbody>
                                                @if(isset($quotation->id))
                                                @php
                                                    $total_qty = 0;
                                                @endphp
                                                    @if(isset($quotation->relQuotationItems[0]))
                                                        @foreach($quotation->relQuotationItems as $key=>$item)
                                                            <tr>
                                                                <td>{{$key+1}}</td>
                                                                <td>{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}</td>
                                                                <td>{{ getProductAttributesFaster($requisitionItems->where('uid', $item->uid)->first()) }}</td>
                                                                <td>{{ $item->description }}</td>
                                                                <td>{{ isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:'' }}</td>
                                                                <td class="text-center max-quantity">{{$item->qty}}</td>
                                                                @if(isset($quotations[0]))
                                                                    @foreach($quotations as $quotation_key => $quotation)
                                                                        @php
                                                                            $this_prices = ($quotation->relQuotationItems->where('uid', $item->uid)->count() > 0 ? collect($quotation->relQuotationItems->where('uid', $item->uid)->first()) : false);
                                                                        @endphp
                                                                        <td>
                                                                            <input type="number" name="recommendations[{{ $this_prices['id'] }}]" id="recommendations-{{ $this_prices['id'] }}" value="{{ $quotation_key == 0 ? $item->qty : 0 }}" min="0" max="{{ $item->qty }}"
                                                                               
                                                                               data-quotation-id="{{ $quotation->id }}"
                                                                               data-item-id="{{ $item->id }}"
                                                                               data-exchange-rate="{{ exchangeRate($quotation->exchangeRate, $systemCurrency->id) }}"
                                                                               data-unit-price="{{ isset($this_prices['unit_price']) ? $this_prices['unit_price'] : 0 }}"
                                                                               data-discount="{{ isset($this_prices['discount']) ? $this_prices['discount'] : 0 }}"
                                                                               data-vat-percentage="{{ isset($this_prices['vat_percentage']) ? $this_prices['vat_percentage'] : 0 }}"

                                                                                class="form-control recommendations recommendations-{{ $quotation->id }}" onchange="checkRecommendation($(this))" onkeyup="checkRecommendation($(this))"
                                                                            >

                                                                            <span style="display: none"
                                                                                  class="this-discount-amount discount-amount-{{ $quotation->id }}">{{ isset($this_prices['discount_amount']) ? $this_prices['discount_amount'] : 0 }}</span>
                                                                            <span style="display: none"
                                                                                  class="this-exchange-discount-amount exchange-discount-amount-{{ $quotation->id }}">{{ isset($this_prices['discount_amount']) ? $this_prices['discount_amount']*exchangeRate($quotation->exchangeRate, $systemCurrency->id) : 0 }}</span>
                                                                            <span style="display: none"
                                                                                  class="this-vat-amount vat-amount-{{ $quotation->id }}">{{ isset($this_prices['vat']) ? $this_prices['vat'] : 0 }}</span>
                                                                            <span style="display: none"
                                                                                  class="this-exchange-vat-amount exchange-vat-amount-{{ $quotation->id }}">{{ isset($this_prices['vat']) ? $this_prices['vat']*exchangeRate($quotation->exchangeRate, $systemCurrency->id) : 0 }}</span>
                                                                        </td>
                                                                        <td class="text-right">{{isset($this_prices['unit_price']) ? systemMoneyFormat($this_prices['unit_price']) : 0}}</td>

                                                                        <td class="text-right sub-total-price-{{ $quotation->id }}">{{ isset($this_prices['sub_total_price']) ? systemMoneyFormat($this_prices['sub_total_price']) : 0}}</td>

                                                                        @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                            <td class="text-right  exchange-sub-total-price-{{ $quotation->id }}">{{systemMoneyFormat($this_prices['sub_total_price']*exchangeRate($quotation->exchangeRate, $systemCurrency->id))}}</td>
                                                                        @endif

                                                                    @endforeach
                                                                @endif

                                                            </tr>
                                                            @php $total_qty += $item->qty; @endphp
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td colspan="5" class="text-right"><strong>Total</strong></td>
                                                        <td class="text-center"><strong>{{$total_qty}}</strong></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                @php
                                                                    $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                                @endphp
                                                                <td style="width:120px !important" colspan="2"><strong>Sub Total</strong></td>
                                                                <td style="width:120px !important" class="text-right">
                                                                    <strong id="total-sub-total-price-{{ $quotation->id }}">{{isset($quotation->relQuotationItems[0])? number_format($quotation->relQuotationItems->sum('sub_total_price'),2):0}}</strong>
                                                                </td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong id="total-exchange-sub-total-price-{{ $quotation->id }}">{{number_format($quotation->relQuotationItems->sum('sub_total_price')*$exchangeRate,2)}}</strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                @php
                                                                    $total_price= $quotation->relQuotationItems->sum('sub_total_price');
                                                                    $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                                @endphp
                                                                <td colspan="2"><strong>(-) Discount</strong></td>
                                                                <td class="text-right">
                                                                    <strong id="total-discount-amount-{{ $quotation->id }}"> <?= number_format(($quotation->discount), 2); ?></strong>
                                                                </td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong id="total-exchnage-discount-amount-{{ $quotation->id }}"> <?= number_format(($quotation->discount) * $exchangeRate, 2); ?></strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        @endif
                                                    </tr>

                                                    <tr>
                                                        <td colspan="6" class="text-right"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                            @php
                                                                $total_price = $quotation->relQuotationItems->sum('sub_total_price');
                                                                $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                            @endphp
                                                                <td colspan="2"><strong>(+) VAT ({{ ucwords($quotation->relQuotationItems->first()->vat_type) }}{{ $quotation->relQuotationItems->first()->vat_type != 'exempted' ? ', '.$quotation->relQuotationItems->first()->vat_percentage.'%' : '' }})</strong></td>
                                                                <td class="text-right">
                                                                    <strong
                                                                id="total-vat-amount-{{ $quotation->id }}"> {{$quotation->vat}}</strong></td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong id="total-exchange-vat-amount-{{ $quotation->id }}"> {{$quotation->vat*$exchangeRate}}</strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6" class="text-right"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                            @php
                                                                $total_price = $quotation->relQuotationItems->sum('sub_total_price');
                                                                $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                            @endphp
                                                                <td colspan="2"><strong>Gross Total</strong></td>
                                                                <td class="text-right">
                                                                    <strong id="total-gross-amount-{{ $quotation->id }}">@php number_format($quotation->gross_price, 2); @endphp</strong>
                                                                </td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong id="total-exchange-gross-amount-{{ $quotation->id }}">@php number_format($quotation->gross_price * $exchangeRate, 2); @endphp</strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tr>

                                                    <tr>
                                                        <td colspan="6"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                <td class="text-left"
                                                                    colspan="{{ ($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '')) ? 4 : 3 }}">
                                                                    Supplier Payment Term:
                                                                    <br>
                                                                    <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }}</strong>
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                <td colspan="{{ ($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '')) ? 4 : 3 }}">
                                                                    Delivery Date:
                                                                    <strong>{{ $quotation->delivery_date }}</strong>
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                <td colspan="{{ ($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '')) ? 4 : 3 }}">
                                                                    <textarea style="width:100% !important"
                                                                              class="form-control"
                                                                              name="note[{{$quotation->id}}]" rows="1"
                                                                              id="note"
                                                                              placeholder="What is the reason for choosing this supplier?">{{ $quotation->note }}</textarea>
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 90%">Approver</th>
                                                            <th style="width: 10%">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="approvers">
                                                        
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="5" class="text-right">
                                                                <a class="btn btn-xs btn-success text-white" onclick="newApprover()"><i class="las la-plus"></i>&nbsp;New Approver</a>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success submit-button"><i class="la la-check"></i>&nbsp;Send for Approval
                                        </button>
                                        <a type="button" class="btn btn-danger" href="{{route('pms.quotation.quotations.cs.analysis')}}">Close</a>
                                    </div>
                                </div>
                        </div>
                        @endif

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('page-script')
    <script>
        $(document).ready(function () {
            var form = $('#send-to-management-form');
            var button = form.find('.submit-button');
            form.on('submit', function (e) {
                e.preventDefault();
                button.html('<i class="las la-spinner"></i>&nbsp;Sending to Management for Approval').prop('disabled', true);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    data: form.serializeArray(),
                })
                    .done(function (response) {
                        button.html('<i class="la la-check"></i>&nbsp;Send for Management Approval').prop('disabled', false);
                        if (response.success) {
                            window.open(response.url, "_parent");
                        } else {
                            toastr.error(response.message);
                        }
                    })
                    .fail(function (response) {
                        button.html('<i class="la la-check"></i>&nbsp;Send for Management Approval').prop('disabled', false);
                        $.each(response.responseJSON.errors, function (index, val) {
                            toastr.error(val[0]);
                        });
                    });
            });

            $.each($('.recommendations'), function(index, val) {
                checkRecommendation($(this), true);
            });
        });

        function checkRecommendationFields(element){
            // if(element.is(':checked')){
            //     $('.recommendations-'+element.val()).prop('readonly', false);
            // }else{
            //     $('.recommendations-'+element.val()).prop('readonly', true);
            // }
        }

        function checkRecommendation(element, loading = false) {
            var value = loading ? parseInt(element.attr('max')) : parseInt(element.val());
            var max = parseInt(element.attr('max'));
            var min = parseInt(element.attr('min'));

            if(value > max){
                value = max;
                element.val(value);
            }

            if(value < min){
                value = min;
                element.val(value);
            }

            if(!loading){
                var total_recommendation = 0;
                $.each(element.parent().parent().find('.recommendations'), function(index, val) {
                    total_recommendation += parseInt($(this).val());
                });

                if(total_recommendation > max){
                    value = 0;
                    element.val(value);
                }
            }

            var unit_price = parseFloat(element.attr('data-unit-price'));
            var exchange_rate = parseFloat(element.attr('data-exchange-rate'));
            var discount = parseFloat(element.attr('data-discount'));
            var vat_percentage = parseFloat(element.attr('data-vat-percentage'));

            var sub_total = unit_price * parseFloat(loading ? element.attr('max') : element.val());
            element.parent().parent().find('.sub-total-price-' + parseInt(element.attr('data-quotation-id'))).html(sub_total.toFixed(2));

            var exchange_sub_total = sub_total * exchange_rate;
            element.parent().parent().find('.exchange-sub-total-price-' + parseInt(element.attr('data-quotation-id'))).html(exchange_sub_total.toFixed(2));

            if(loading){
                var discount_amount = (sub_total > 0 && discount > 0 ? sub_total * (discount / 100) : 0);
                var exchange_discount_amount = discount_amount * exchange_rate;
                element.parent().find('.this-discount-amount').html(discount_amount);
                element.parent().find('.this-exchange-discount-amount').html(exchange_discount_amount);

                var after_discount = sub_total - discount_amount;
                var vat_amount = (after_discount > 0 && vat_percentage > 0 ? after_discount * (vat_percentage / 100) : 0);
                var exchange_vat_amount = vat_amount * exchange_rate;
                element.parent().find('.this-vat-amount').html(vat_amount);
                element.parent().find('.this-exchange-vat-amount').html(exchange_vat_amount);

                var total_sub_total = 0;
                $.each($('.sub-total-price-' + parseInt(element.attr('data-quotation-id'))), function (index, val) {
                    total_sub_total += parseFloat($(this).text().split(",").join(""));
                });

                var total_exchange_sub_total = 0;
                $.each($('.exchange-sub-total-price-' + parseInt(element.attr('data-quotation-id'))), function (index, val) {
                    total_exchange_sub_total += parseFloat($(this).text().split(",").join(""));
                });

                var total_discount = 0;
                $.each($('.discount-amount-' + parseInt(element.attr('data-quotation-id'))), function (index, val) {
                    total_discount += parseFloat($(this).text().split(",").join(""));
                });

                var total_exchange_discount = 0;
                $.each($('.exchange-discount-amount-' + parseInt(element.attr('data-quotation-id'))), function (index, val) {
                    total_exchange_discount += parseFloat($(this).text().split(",").join(""));
                });

                var total_vat = 0;
                $.each($('.vat-amount-' + parseInt(element.attr('data-quotation-id'))), function (index, val) {
                    total_vat += parseFloat($(this).text().split(",").join(""));
                });

                var total_exchange_vat = 0;
                $.each($('.exchange-vat-amount-' + parseInt(element.attr('data-quotation-id'))), function (index, val) {
                    total_exchange_vat += parseFloat($(this).text().split(",").join(""));
                });

                $('#total-sub-total-price-' + parseInt(element.attr('data-quotation-id'))).html(total_sub_total.toFixed(2));
                $('#total-exchange-sub-total-price-' + parseInt(element.attr('data-quotation-id'))).html(total_exchange_sub_total.toFixed(2));

                $('#total-discount-amount-' + parseInt(element.attr('data-quotation-id'))).html(total_discount.toFixed(2));
                $('#total-exchange-discount-amount-' + parseInt(element.attr('data-quotation-id'))).html(total_exchange_discount.toFixed(2));

                $('#total-vat-amount-' + parseInt(element.attr('data-quotation-id'))).html(total_vat.toFixed(2));
                $('#total-exchange-vat-amount-' + parseInt(element.attr('data-quotation-id'))).html(total_exchange_vat.toFixed(2));

                $('#total-gross-amount-' + parseInt(element.attr('data-quotation-id'))).html(parseFloat(total_sub_total - total_discount + total_vat).toFixed(2));
                $('#total-exchange-gross-amount-' + parseInt(element.attr('data-quotation-id'))).html(parseFloat(total_exchange_sub_total - total_exchange_discount + total_exchange_vat).toFixed(2));
            }
        }

        newApprover();
        function newApprover() {
            var approvers = '';
            $.each(<?php echo json_encode($approvers); ?>, function(index, approver) {
                approvers += '<option value="'+approver.id+'">'+approver.name+'</option>';
            });
            $('#approvers').append('<tr>'+
                                        '<td>'+
                                            '<select class="form-control select2" name="approvers[]">'+approvers+'</select>'+
                                        '</td>'+
                                        '<td class="text-center">'+
                                            '<a class="btn btn-xs btn-danger text-white" onclick="removeApprover($(this))"><i class="las la-trash"></i>&nbsp;Remove</a>'+
                                        '</td>'+
                                    '</tr>');
            $('.select2').select2();
        }

        function removeApprover(element) {
            element.parent().parent().remove();
        }
    </script>
@endsection