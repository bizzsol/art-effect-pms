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
                    <div class="panel panel-info">
                        <form method="post" action="{{ route('pms.rfp.quotations.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="quotation_date"><strong>{{ __('Quotation Date') }}<span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="quotation_date" id="quotation_date"
                                                   class="form-control rounded air-datepicker" aria-label="Large"
                                                   aria-describedby="inputGroup-sizing-sm" required readonly
                                                   value="{{ old('quotation_date')?old('quotation_date'):date('d-m-Y h:i:s') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <p><label class="font-weight-bold" for="delivery_date"><strong>Delivery
                                                    Date</strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="date" name="delivery_date" id="delivery_date"
                                                   value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="reference_no"><strong>{{ __('Reference No') }}<span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="reference_no" id="reference_no"
                                                   class="form-control rounded" readonly aria-label="Large"
                                                   aria-describedby="inputGroup-sizing-sm" required
                                                   value="{{ ($refNo)?($refNo):0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="discount_percent">{{ __('Discount Percentage %') }}:</label>
                                        </p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            {{ Form::number('discount_percent', old('discount_percent')?old('discount_percent'):0, ['class'=>'form-control rounded', 'placeholder'=>'0%','min'=>'0','max'=>'100','step'=>'any','id'=>'discount_percent','oninput'=>"this.value = Math.abs(this.value)"]) }}
                                            <input type="checkbox" name="checkall_discount" id="checkAllDiscount"
                                                   class="form-control">
                                        </div>
                                    </div>


                                    <div class="col-md-3 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="supplier_id"><strong>{{ __('Supplier') }}<span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="supplier_id" id="supplier_id" class="form-control rounded"
                                                    required onchange="getSupplierInfo()">
                                                <option value="{{ null }}">{{ __('Select One') }}</option>
                                                @if(count($requestProposal->defineToSupplier->whereNotIn('supplier_id', $quotationSupplierArray)) > 0)
                                                    @foreach($requestProposal->defineToSupplier->whereNotIn('supplier_id', $quotationSupplierArray) as $key => $data)
                                                        <option value="{{ $data->supplier->id }}">{{ $data->supplier->name }}
                                                            ({{ $data->supplier->code }})
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p><label class="font-weight-bold"
                                                  for="currency_id"><strong>{{ __('Currency') }} <span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="currency_id" id="currency_id" class="form-control rounded">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label for="supplier_payment_terms_id"><strong>Payment
                                                    Terms<span class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="supplier_payment_terms_id" id="supplier_payment_terms_id"
                                                    class="form-control rounded" required>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-10">
                                    <table class="table table-striped table-bordered table-head quotation-table"
                                           cellspacing="0" width="100%" id="dataTable">
                                        <thead>
                                        <tr class="text-center">
                                            <th style="width: 12.5%">Product</th>
                                            <th style="width: 5%">UOM</th>
                                            <th style="width: 10%">Unit Price</th>
                                            <th style="width: 7.5%">Qty</th>
                                            <th style="width: 10%">Gross Total</th>
                                            <th style="width: 7.5%">Discount (%)</th>
                                            <th style="width: 10%">Total Discount</th>
                                            <th style="width: 10%">Net Total</th>
                                            <th style="width: 7.5%">Vat (%)</th>
                                            <th style="width: 10%">Vat Amount</th>
                                            <th style="width: 10%">Final Total</th>
                                            <th style="width: 10%">Technical Specification</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if(isset($requestProposal->requestProposalDetails))

                                            @foreach($requestProposal->requestProposalDetails as $key=>$item)
                                                <tr>

                                                    <td>
                                                        {{isset($item->product->name)?$item->product->name:''}}
                                                        ({{isset($item->product->sku)?$item->product->sku:''}}
                                                        ) {{ getProductAttributesFaster($item->product) }}
                                                        <input type="hidden" name="product_id[]" class="form-control"
                                                               value="{{$item->product->id}}">
                                                    </td>
                                                    <td>
                                                        {{$item->product->productUnit->unit_name}}
                                                    </td>
                                                    <td>
                                                        <input type="text" name="unit_price[{{$item->product->id}}]"
                                                               required class="form-control text-right" min="0.0"
                                                               step='any' value="0.00"
                                                               id="unitPrice{{$item->product->id}}" placeholder="0.00"
                                                               onkeyup="calculateSubtotal({{$item->product->id}})"
                                                               onchange="calculateSubtotal({{$item->product->id}})">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[{{$item->product->id}}]" required
                                                               class="form-control summation-qty" min="0"
                                                               id="qty{{$item->product->id}}"
                                                               value="{{round($item->request_qty)}}"
                                                               onKeyPress="if(this.value.length==4) return false;"
                                                               onkeyup="calculateSubtotal({{$item->product->id}})"
                                                               step='any'
                                                               onchange="calculateSubtotal({{$item->product->id}})">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                               name="sub_total_price[{{$item->product->id}}]" required
                                                               class="form-control calculateSumOfSubtotal" readonly
                                                               id="subTotalPrice_{{$item->product->id}}"
                                                               placeholder="0.00" value="0.00">
                                                    </td>
                                                    <td>
                                                        <input type="text" step='any'
                                                               name="item_discount_percent[{{$item->product->id}}]"
                                                               class="form-control calculateDiscount bg-white"
                                                               id="itemDiscountPercent{{$item->product->id}}"
                                                               placeholder="0"
                                                               onKeyPress="if(this.value.length==5) return false;"
                                                               onkeyup="calculateItemDiscount('{{$item->product->id}}', true)"
                                                               onchange="calculateItemDiscount('{{$item->product->id}}', true)"
                                                               data-id="{{$item->product->id}}" min="0">

                                                        <input type="hidden"
                                                               name="item_discount_amount[{{$item->product->id}}]"
                                                               id="itemWiseDiscount_{{$item->product->id}}"
                                                               class="itemWiseDiscount">

                                                        <input type="hidden"
                                                               name="sub_total_vat_price[{{$item->product->id}}]"
                                                               required class="form-control calculateSumOfVat" readonly
                                                               id="sub_total_vat_price{{$item->product->id}}"
                                                               placeholder="0.00" value="0.00">
                                                    </td>
                                                    <td class="text-right" id="discount_amount_{{$item->product->id}}"
                                                        style="padding-right: 2.25rem !important;">0.00
                                                    </td>
                                                    <td class="text-right summation-after-discount-amount"
                                                        id="after_discount_amount_{{$item->product->id}}">0.00
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="text" step="0.01"
                                                               name="product_vat[{{$item->product->id}}]"
                                                               id="product_vat_{{$item->product->id}}"
                                                               data-id="{{$item->product->id}}"
                                                               value="{{systemDoubleValue($item->product->vat, 2)}}"
                                                               class="form-control calculateProductVat mask-money"
                                                               onkeyup="calculateSumOfVatValue('{{$item->product->id}}', true)"
                                                               onchange="calculateSumOfVatValue('{{$item->product->id}}', true)">
                                                    </td>
                                                    <td class="text-right"
                                                        id="sub_total_vat_price{{$item->product->id}}_show"
                                                        style="padding-right: 2.25rem !important;">0.00
                                                    </td>
                                                    <td class="text-right summation-gross-amount"
                                                        id="item_gross_total_{{$item->product->id}}">0.00
                                                    </td>

                                                    <td class="text-center">
                                                        <input type="file"
                                                               name="item_technical_specification_file[{{$item->product->id}}]"
                                                               accept="application/pdf"
                                                               id="item_technical_specification_file{{$item->product->id}}"
                                                               placeholder="Upload technical file"
                                                        >
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                        <tr class="total-row">
                                            <td colspan="3" class="text-right">
                                                <strong>Total: </strong>
                                            </td>
                                            <td class="text-right" style="padding-right: 2.25rem !important;">

                                            </td>
                                            <td>
                                                <input type="text" name="sum_of_subtoal" readonly
                                                       class="form-control mask-money" id="sumOfSubtoal"
                                                       placeholder="0.00">
                                            </td>
                                            <td class="text-right"></td>
                                            <td>
                                                <input type="text" name="discount"
                                                       class="form-control bg-white mask-money" step='any' id="discount"
                                                       placeholder="0.00" value="0.00">
                                                <input type="hidden" id="sub_total_with_discount"
                                                       name="sub_total_with_discount" min="0" placeholder="0.00">
                                            </td>
                                            <td class="text-right total_value_after_discount"></td>
                                            <td class="text-right"></td>
                                            <td>
                                                <input type="text" step='any' name="vat"
                                                       class="form-control bg-white mask-money" id="vat"
                                                       placeholder="0.00" value="0.00">
                                                <input type="hidden" name="gross_price" id="grossPrice">
                                            </td>
                                            <td class="text-right"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-row mt-5">

                                    <input type="hidden" name="request_proposal_id" value="{{$requestProposal->id}}">
                                    <input type="hidden" name="type" value="manual">

                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="QuotationFile"><strong>{{ __('Quotation File (Pdf)') }}
                                                    :</strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="file" name="quotation_file" id="QuotationFile"
                                                       class="btn btn-outline-primary" aria-label="Large"
                                                       aria-describedby="inputGroup-sizing-sm" value=""
                                                       accept="application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <div class="form-group">
                                            <label for="technicalSpecification"><strong>{{ __('Technical Specification File (Pdf)') }}
                                                    :</strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="file" name="technical_specification_file"
                                                       id="technicalSpecification"
                                                       class="btn btn-outline-success" aria-label="Large"
                                                       aria-describedby="inputGroup-sizing-sm" value=""
                                                       accept="application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pt-4 offset-md-4">
                                        <button type="submit" class="mt-2 btn btn-block btn-success rounded">
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
                // notify('Please Enter Unit Price & Quantity','error');
            }

            summation();

            return false;
        }

        $('#discount_percent').on('change', function () {
            distributeDiscount();
        });

        // $('#discount_percent').on('keyup', function () {
        //     distributeDiscount();
        // });

        $('#checkAllDiscount:checkbox').on('change', function () {
            distributeDiscount();
        });

        function distributeDiscount() {
            //console.log('distributeDiscount');

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
            //item wise sum
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
            //console.log('calculateSumOfVatValue');

            var sub_total_price = parseFloat($('#subTotalPrice_' + id).val() != '' ? $('#subTotalPrice_' + id).val() : 0).toFixed(2);
            var discount_amount = parseFloat($('#itemWiseDiscount_' + id).val() != "" ? $('#itemWiseDiscount_' + id).val() : 0).toFixed(2);

            var discounted = parseFloat(parseFloat(sub_total_price) - parseFloat(discount_amount)).toFixed(2);
            var product_vat = parseFloat($('#product_vat_' + id).val() != '' ? $('#product_vat_' + id).val() : 0);
            var value = parseFloat(product_vat > 0 && discounted > 0 ? (discounted * (product_vat / 100)) : 0).toFixed(2);
            $('#sub_total_vat_price' + id).val(parseFloat(value).toFixed(2));
            $('#sub_total_vat_price' + id + '_show').html(parseFloat(value).toFixed(2));

            $('#item_gross_total_' + id).html(parseFloat(parseFloat(discounted) + parseFloat(value)).toFixed(2));
            //item wise sum
            var total = 0;
            $(".calculateSumOfVat").each(function () {
                total += parseFloat($(this).val() != '' ? $(this).val() : 0);
            });

            $("#vat").val(parseFloat(total).toFixed(2));

            if (summ) {
                summation();
            }
        }

        $('#vat').on('change', function () {
            calculateVatValue();
        });

        // $('#vat').on('keyup', function () {
        //     calculateVatValue();
        // });

        function calculateVatValue() {
            //console.log('calculateVatValue');

            $(".calculateProductVat").val(0);
            $(".calculateSumOfVat").val(0);

            var vat = parseFloat($('#vat').val() != '' ? $('#vat').val() : 0).toFixed(2);
            var sumOfSubtoal = parseFloat($(".total_value_after_discount").text() != '' ? $(".total_value_after_discount").text() : 0).toFixed(2);
            var vatPercetage = parseFloat(vat > 0 ? ((vat * 100) / sumOfSubtoal) : 0).toFixed(2);

            $(".calculateProductVat").val(parseFloat(vatPercetage).toFixed(2));
            ////console.log(vatPercetage);

            var calculateProductVat = document.querySelectorAll('.calculateProductVat');
            Array.from(calculateProductVat).map((item, key) => {
                calculateSumOfVatValue(item.getAttribute('data-id'));
            });

            summation();
        }

        $('#discount').on('change', function () {
            calculateDiscountValue();
        });

        // $('#discount').on('keyup', function () {
        //     calculateDiscountValue();
        // });

        function calculateDiscountValue() {
            //console.log('calculateDiscountValue');

            var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0).toFixed(2);
            var sumOfSubtoal = parseFloat($("#sumOfSubtoal").val() != '' ? $("#sumOfSubtoal").val() : 0).toFixed(2);
            var discountPercetage = parseFloat(discount > 0 ? (discount * 100) / sumOfSubtoal : 0).toFixed(2);

            $(".calculateDiscount").val(parseFloat(discountPercetage).toFixed(2));
            ////console.log(discountPercetage);
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
            //console.log('summation');

            var qty = 0;
            $.each($('.summation-qty'), function (index, val) {
                qty += parseInt($(this).val());
            });
            $('.total-row').find('td:nth-child(2)').html(qty);

            var sumOfSubtoal = parseFloat($('#sumOfSubtoal').val() != '' ? $('#sumOfSubtoal').val() : 0).toFixed(2);
            var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0).toFixed(2);
            var discounted = parseFloat(parseFloat(sumOfSubtoal) - parseFloat(discount)).toFixed(2);
            $('#sub_total_with_discount').val(parseFloat(discounted).toFixed(2));

            var vat = parseFloat($('#vat').val() != '' ? $('#vat').val() : 0).toFixed(2);
            var total = parseFloat(parseFloat(discounted) + parseFloat(vat)).toFixed(2);

            $('.total-row').find('td:nth-child(6)').html(parseFloat(discounted).toFixed(2));
            $('.total-row').find('td:nth-child(9)').html(parseFloat(total).toFixed(2));
            $('#grossPrice').val(parseFloat(total).toFixed(2));
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
                    $('#supplier_payment_terms_id').html(response.terms).change();
                });
        }
    </script>
@endsection