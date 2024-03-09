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
            width: 240px !important;
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
                </ul><!-- /.breadcrumb -->
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
                                                <li><strong>{{__('Request Proposal No')}}
                                                        :</strong> {{$quotation->relRequestProposal->reference_no}}</li>
                                                <li><strong>Project Name:</strong></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul>
                                                <li><strong>{{__('RFP Provide By')}}
                                                        :</strong> {{$quotation->relRequestProposal->createdBy->name}}
                                                </li>
                                                <li><strong>{{__('RFP Date')}}
                                                        :</strong> {{date('d-m-Y h:i:s A',strtotime($quotation->relRequestProposal->request_date))}}
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
                                                    <th colspan="5">Party Name</th>
                                                    @if(isset($quotations[0]))
                                                        @foreach($quotations as $quotation)
                                                                <?php
                                                                $TS = number_format($quotation->relSuppliers->SupplierRatings->sum('total_score'), 2);
                                                                $TC = $quotation->relSuppliers->SupplierRatings->count();

                                                                $totalScore = isset($TS) ? $TS : 0;
                                                                $totalCount = isset($TC) ? $TC : 0;
                                                                ?>
                                                            <th class="invoiceBody fixed-width"
                                                                colspan="{{ $systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '') ? 3 : 2 }}">

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
                                                                    <input class="form-check-input setRequiredOnSupplierPaymentTerm setRequiredOnSupplierPaymentTerm-{{$quotation->id}}"
                                                                           type="checkbox" name="quotation_id[]"
                                                                           id="is_approved_{{$quotation->id}}"
                                                                           value="{{$quotation->id}}" {{ $quotation->is_approved == 'pre-processing' ? 'checked' : '' }}>
                                                                    <input type="hidden" name="request_proposal_id"
                                                                           value="{{$quotation->request_proposal_id}}">
                                                                    <label class="form-check-label"
                                                                           for="is_approved_{{$quotation->id}}"
                                                                           style="cursor: pointer">
                                                                        <strong>Request For Approval</strong>
                                                                    </label>
                                                                </div>
                                                                </p>

                                                                <p>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                           name="recommendations"
                                                                           id="recommendations_{{$quotation->id}}"
                                                                           value="{{$quotation->id}}"
                                                                           {{ $quotation->recommendations == 'yes' ? 'checked' : '' }} required
                                                                           onchange="checkMe($(this))">
                                                                    <label class="form-check-label"
                                                                           for="recommendations_{{$quotation->id}}"
                                                                           style="cursor: pointer">
                                                                        <strong>Recommend</strong>
                                                                    </label>
                                                                </div>
                                                                </p>

                                                            </th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                                <tr class="text-center">
                                                    <th width="1%" class="text-center">SL</th>
                                                    <th>Category</th>
                                                    <th>Product</th>
                                                    <th>UOM</th>
                                                    <th class="text-center">Qty</th>
                                                    @if(isset($quotations[0]))
                                                        @foreach($quotations as $quotation)
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
                                                        <?php $total_qty = 0; ?>
                                                    @if(isset($quotation->relQuotationItems[0]))
                                                        @foreach($quotation->relQuotationItems as $key=>$item)
                                                            <tr>
                                                                <td>{{$key+1}}</td>
                                                                <td>{{isset($item->relProduct->category->name)?$item->relProduct->category->name:''}}</td>

                                                                <td>{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}</td>

                                                                <td>{{ isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:'' }}</td>

                                                                <td class="text-center">{{$item->qty}}</td>
                                                                @if(isset($quotations[0]))
                                                                    @foreach($quotations as $key => $quotation)
                                                                        @php
                                                                            $this_prices = ($quotation->relQuotationItems->where('product_id', $item->product_id)->count() > 0 ? collect($quotation->relQuotationItems->where('product_id', $item->product_id)->first()) : false);
                                                                        @endphp
                                                                        <td class="text-right">{{isset($this_prices['unit_price']) ? systemMoneyFormat($this_prices['unit_price']) : 0}}</td>

                                                                        <td class="text-right">{{ isset($this_prices['sub_total_price']) ? systemMoneyFormat($this_prices['sub_total_price']) : 0}}</td>

                                                                        @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                            <td class="text-right">{{systemMoneyFormat($this_prices['sub_total_price']*exchangeRate($quotation->exchangeRate, $systemCurrency->id))}}</td>
                                                                        @endif

                                                                    @endforeach
                                                                @endif

                                                            </tr>
                                                            @php $total_qty +=$item->qty; @endphp
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td colspan="4" class="text-right"><strong>Total</strong></td>
                                                        <td class="text-center"><strong>{{$total_qty}}</strong></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                @php
                                                                    $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                                @endphp
                                                                <td style="width:120px !important"><strong>Sub
                                                                        Total</strong></td>
                                                                <td style="width:120px !important" class="text-right">
                                                                    <strong>{{isset($quotation->relQuotationItems[0])? number_format($quotation->relQuotationItems->sum('sub_total_price'),2):0}}</strong>
                                                                </td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong>{{number_format($quotation->relQuotationItems->sum('sub_total_price')*$exchangeRate,2)}}</strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-right"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                @php
                                                                    $total_price= $quotation->relQuotationItems->sum('sub_total_price');
                                                                    $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                                @endphp
                                                                <td><strong>(-) Discount</strong></td>
                                                                <td class="text-right">
                                                                    <strong> <?= number_format(($quotation->discount), 2); ?></strong>
                                                                </td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong> <?= number_format(($quotation->discount) * $exchangeRate, 2); ?></strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        @endif
                                                    </tr>

                                                    <tr>
                                                        <td colspan="5" class="text-right"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                    <?php
                                                                    $total_price = $quotation->relQuotationItems->sum('sub_total_price');
                                                                    $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                                    ?>
                                                                <td><strong>(+) Vat</strong></td>
                                                                <td class="text-right">
                                                                    <strong> {{$quotation->vat}}</strong></td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong> {{$quotation->vat*$exchangeRate}}</strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-right"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                    <?php
                                                                    $total_price = $quotation->relQuotationItems->sum('sub_total_price');
                                                                    $exchangeRate = exchangeRate($quotation->exchangeRate, $systemCurrency->id);
                                                                    ?>
                                                                <td><strong>Gross Total</strong></td>
                                                                <td class="text-right">
                                                                    <strong><?= number_format($quotation->gross_price, 2); ?> </strong>
                                                                </td>
                                                                @if($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : ''))
                                                                    <td class="text-right">
                                                                        <strong><?= number_format($quotation->gross_price * $exchangeRate, 2); ?> </strong>
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </tr>

                                                    <tr>
                                                        <td colspan="5"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                <td class="text-left"
                                                                    colspan="{{ ($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '')) ? 3 : 2 }}">
                                                                    Supplier Payment Term:
                                                                    <br>
                                                                    <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }}</strong>
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                <td colspan="{{ ($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '')) ? 3 : 2 }}">
                                                                    Delivery Date:
                                                                    <strong>{{ $quotation->delivery_date }}</strong>
                                                                </td>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        @if(isset($quotations[0]))
                                                            @foreach($quotations as $key=>$quotation)
                                                                <td colspan="{{ ($systemCurrency->id != ($quotation->exchangeRate ? $quotation->exchangeRate->currency_id : '')) ? 3 : 2 }}">
                                                                    <textarea style="width:240px !important"
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
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success submit-button"><i
                                                    class="la la-check"></i>&nbsp;Send for Management Approval
                                        </button>
                                        <a type="button" class="btn btn-danger"
                                           href="{{route('pms.quotation.quotations.cs.analysis')}}">Close</a>
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
        (function ($) {
            "use script";

            const setRequiredOnSupplierPaymentTerm = () => {
                $('.setRequiredOnSupplierPaymentTerm').on('click', function () {
                    let quotationId = $(this).val();
                    if (quotationId) {
                        if ($('#supplier_payment_terms_id' + quotationId).attr("required") == 'required') {
                            $('#supplier_payment_terms_id' + quotationId).attr("required", false);
                        } else {
                            $('#supplier_payment_terms_id' + quotationId).attr("required", true);
                        }

                        if ($('#delivery_date' + quotationId).attr("required") == 'required') {
                            $('#delivery_date' + quotationId).attr("required", false);
                        } else {
                            $('#delivery_date' + quotationId).attr("required", true);
                        }
                    }
                });
            };

            setRequiredOnSupplierPaymentTerm();
        })(jQuery)


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
        });

        function checkMe(element) {
            if (element.is(':checked')) {
                if (!element.parent().parent().parent().find('.setRequiredOnSupplierPaymentTerm-' + element.val()).is(':checked')) {
                    element.parent().parent().parent().find('.setRequiredOnSupplierPaymentTerm-' + element.val()).attr('checked', 'checked');
                }
            }
        }
    </script>
@endsection