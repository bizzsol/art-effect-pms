@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css" media="screen">
        .po-table th {
            font-size: 10px !important;
        }

        .note-editor {
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
                        <form method="post" action="{{ route('pms.quotation.quotations.generate.po.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label for="po_date"><strong>{{ __(' Date') }}
                                                    <span class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="po_date" id="po_date"
                                                   class="form-control rounded air-datepicker" aria-label="Large"
                                                   aria-describedby="inputGroup-sizing-sm" required readonly
                                                   value="{{ old('po_date')?old('po_date'):date('d-m-Y h:i:s') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="rfp_reference_no"><strong>CS Number <span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="rfp_reference_no" id="rfp_reference_no"
                                                   class="form-control rounded" readonly aria-label="Large"
                                                   aria-describedby="inputGroup-sizing-sm" required
                                                   value="{{ ($quotation->relRequestProposal->reference_no)?($quotation->relRequestProposal->reference_no):0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="quotation_ref_no"><strong>{{ __('Quotation Ref No') }} <span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="quotation_ref_no" id="quotation_ref_no"
                                                   class="form-control rounded" readonly aria-label="Large"
                                                   aria-describedby="inputGroup-sizing-sm" required
                                                   value="{{ ($quotation->reference_no)?($quotation->reference_no):0 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="hr_unit_id"><strong>{{ __('Unit') }} <span class="text-danger">*</span></strong></label>
                                        </p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="hr_unit_id" id="hr_unit_id" class="form-control rounded"
                                                    onchange="getRequisitions()">
                                                @if($units->count() > 0)
                                                    @if($units->count() > 1)
                                                        <option value="{{ null }}">{{ __('Select One') }}</option>
                                                    @endif
                                                    @foreach($units as $key => $unit)
                                                        <option value="{{ $unit->hr_unit_id }}">{{ $unit->hr_unit_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    @if($uncommon > 0)
                                        <div class="col-md-3 col-sm-12">
                                            <p class="mb-1 font-weight-bold"><label
                                                        for="hr_department_id"><strong>{{ __('Department') }} <span
                                                                class="text-danger">*</span></strong></label></p>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="hr_department_id" id="hr_department_id"
                                                        class="form-control rounded" onchange="getRequisitions()">
                                                    @if($departments->count() > 0)
                                                        @if($departments->count() > 1)
                                                            <option value="{{ null }}">{{ __('Select One') }}</option>
                                                        @endif
                                                        @foreach($departments as $key => $department)
                                                            <option value="{{ $department->hr_department_id }}">{{ $department->hr_department_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-9 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="requisition_id"><strong>{{ __('Requisitions') }} <span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="requisition_id[]" id="requisition_id"
                                                    class="form-control rounded select2 select2-tags requisition-wise-items"
                                                    multiple onchange="getCostCentres()">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <p class="mb-1 font-weight-bold"><label
                                                    for="cost_centre_id"><strong>{{ __('Cost Centre') }} <span
                                                            class="text-danger">*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="cost_centre_id" id="cost_centre_id"
                                                    class="form-control rounded">
                                                <option value="0">Choose Cost Centre</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-10">
                                    <table class="table table-striped table-bordered table-head po-table"
                                           cellspacing="0" width="100%" id="dataTable">
                                        <thead>
                                        <tr class="text-center">
                                            <th style="width: 3%">#</th>
                                            {{-- <th style="width: 5%">Category</th> --}}
                                            <th style="width: 22%">Product</th>
                                            <th style="width: 30%">Description</th>
                                            <th style="width: 7.5%">UOM</th>
                                            <th style="width: 7.5%">Quantity</th>
                                            <th style="width: 15%">Unit Price ({{ $currency }})</th>
                                            <th style="width: 15%">Unit Total ({{ $currency }})</th>
                                            {{-- <th style="width: 7%">Discount (%)</th>
                                            <th style="width: 7.5%">Unit Price after Discount ({{ $currency }})</th>
                                            <th style="width: 5%">Quotation Qty</th>
                                            <th style="width: 5%">Unit Total ({{ $currency }})</th>
                                            <th style="width: 5%">Discount ({{ $currency }})</th>
                                            <th style="width: 7.5%">Unit Total After Discount ({{ $currency }})</th>
                                            <th style="width: 8.5%">Requisition Qty</th>
                                            <th style="width: 7.5%">PO Qty</th>
                                            <th style="width: 7.5%">PO Amount ({{ $currency }})</th>
                                            <th style="width: 7.5%">PO Amount ({{ $systemCurrency->code }})</th> --}}
                                        </tr>
                                        </thead>
                                        <tbody id="fetchQuotation">
                                        @foreach($quotation->relQuotationItems->where('is_approved', 'approved') as $key=>$item)
                                            <tr>
                                                <td class="text-center">
                                                    {{$key+1}}
                                                    <input type="hidden" name="product_id[]" value="{{$item->product_id}}">
                                                    <input type="hidden" name="unit_price" id="unit_price" value="{{$item->unit_price}}">
                                                    <input type="hidden" name="discount_percentage[{{ $item->id }}]" id="discount_percentage" value="{{$item->discount}}" class="discount_percentage">
                                                    <input type="hidden" name="vat_type[{{ $item->id }}]" id="vat_type" value="{{$item->vat_type}}">
                                                    <input type="hidden" name="vat_percentage[{{ $item->id }}]" id="vat_percentage" value="{{$item->vat_percentage}}">
                                                </td>
                                                {{-- <td>{{isset($item->relProduct->category->name) ? $item->relProduct->category->name : ''}}</td> --}}
                                                <td>
                                                    @if(isset($item->relProduct->name))
                                                        {{$item->relProduct->name}} {{ getProductAttributesFaster($item->relProduct) }} {{ getProductAttributesFaster($requisitionItems->where('product_id', $item->product_id)->first()) }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->description }}</td>
                                                <td class="text-center">
                                                    @if(isset($item->relProduct->name))
                                                        {{$item->relProduct->productUnit->unit_name}}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" id="po-{{ $item->id }}"
                                                           name="po_qty[{{$item->product_id}}]"
                                                           class="form-control bg-white po-qty check-po-qty"
                                                           data-id="{{$item->product_id}}"
                                                           onkeypress="return isNumberKey(event);"
                                                           onchange="checkPOQty()" onkeyup="checkPOQty()">
                                                    <span class="view-data">0</span>
                                                </td>
                                                <td class="text-right">{{ systemDoubleValue($item->unit_price, 2) }}</td>
                                                <td class="text-right"><span class="po-amount">0.00</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                {{-- <td class="text-right">{{$item->discount}}</td>
                                                <td class="text-right">
                                                    {{ $item->unit_price-($item->discount > 0 ? ($item->unit_price*($item->discount/100)) : 0) }}
                                                </td>
                                                <td class="quotation-qty text-center">{{$item->qty}}</td>
                                                <td class="text-right">{{$item->sub_total_price}}</td>
                                                <td class="text-right">
                                                    {{$item->discount_amount}}
                                                </td>
                                                <td class="text-right">
                                                    {{ (float)($item->sub_total_price)-(float)($item->discount_amount) }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" id="item-{{ $item->id }}"
                                                           name="requisition_qty[{{$item->product_id}}]"
                                                           class="form-control bg-white requisition-qty po-qty"
                                                           data-id="{{$item->product_id}}" readonly>
                                                    <span class="view-data">0</span>
                                                </td>

                                                <td class="text-center">
                                                    <input type="hidden" id="po-{{ $item->id }}"
                                                           name="po_qty[{{$item->product_id}}]"
                                                           class="form-control bg-white po-qty check-po-qty"
                                                           data-id="{{$item->product_id}}"
                                                           onkeypress="return isNumberKey(event);"
                                                           onchange="checkPOQty()" onkeyup="checkPOQty()">
                                                    <span class="view-data">0</span>
                                                </td>
                                                <td class="text-right po-amount">0.00</td>
                                                <td class="text-right po-amount-system-currency">0.00</td> --}}
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Sub Total</strong></td>
                                            <td class="text-right"><strong class="po-sub-total">0.00</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>Discount</strong></td>
                                            <td class="text-right">
                                                <input type="number" step="any" min="0" value="0" class="form-control text-right po-discount" style="font-weight: bold;" onchange="distributeDiscount()" onkeyup="distributeDiscount()">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>After Discount</strong></td>
                                            <td class="text-right"><strong class="po-after-discount">0.00</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>VAT ({{ ucwords($quotation->relQuotationItems->first()->vat_type) }} {{ $quotation->relQuotationItems->first()->vat_percentage > 0 ? ', '.systemDoubleValue($quotation->relQuotationItems->first()->vat_percentage, 2).'%' : '' }})</strong></td>
                                            <td class="text-right"><strong class="po-vat-amount">0.00</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-right"><strong>PO Total Amount</strong></td>
                                            <td class="text-right"><strong class="total-po-amount"></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>

                                        {{-- <tr>
                                            <td colspan="7" class="text-right">Quotation Total</td>
                                            <td class="text-center">
                                                {{$quotation->relQuotationItems->sum('qty')}}
                                            </td>
                                            <td class="text-right">
                                                {{$quotation->relQuotationItems->sum('sub_total_price')}}
                                            </td>
                                            <td class="text-right">
                                                {{$quotation->relQuotationItems->sum('discount_amount')}}
                                            </td>
                                            <td class="text-right">
                                                {{$quotation->relQuotationItems->sum('sub_total_price')-$quotation->relQuotationItems->sum('discount_amount')}}
                                            </td>
                                            <td colspan="2" class="text-right">PO Total</td>
                                            <td class="text-right total-po-item-amount">0.00</td>
                                            <td class="text-right total-po-item-amount-system-currency">0.00</td>
                                        </tr>

                                        <tr>
                                            <td colspan="10" class="text-right">(+) Quotation Vat</td>
                                            <td class="text-right">{{$quotation->vat}}</td>
                                            <td colspan="2" class="text-right">(+) PO Vat</td>
                                            <td class="text-right total-po-vat-amount">0.00</td>
                                            <td class="text-right total-po-vat-amount-system-currency">0.00</td>
                                        </tr>

                                        <tr>
                                            <td colspan="10" class="text-right">Quotation Total Amount</td>
                                            <td class="text-right">{{$quotation->gross_price}}</td>
                                            <td colspan="2" class="text-right">PO Total Amount</td>
                                            <td class="text-right total-po-amount">0.00</td>
                                            <td class="text-right total-po-amount-system-currency">0.00</td>
                                        </tr> --}}

                                        <input type="hidden" class="quotation-id" name="quotation_id" value="{{$quotation->id}}">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <h6 class="mb-2"><strong>Work Progresses</strong></h6>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 70%">Milestone</th>
                                                    <th style="width: 20%">Payment Percentage (%)</th>
                                                    <th style="width: 10%">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="milestones">
                                                
                                            </tbody>
                                        </table>
                                        <a class="btn btn-xs btn-success text-white pull-right" onclick="addMilestone()"><i class="las la-plus"></i>&nbsp;Add new Milstone</a>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-12">
                                        <label for="remarks"><strong>{{ __('Notes') }}</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <textarea name="remarks"
                                                      id="remarks"
                                                      class="form-control rounded summer-editor"
                                                      rows="2"
                                                      cols="5"
                                                      placeholder="Write here...">

                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="terms"><strong>{{ __('Terms & Conditions') }}</strong></label>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <textarea name="terms"
                                                      id="terms"
                                                      class="form-control rounded summer-editor"
                                                      rows="3"
                                                      placeholder="Write here...">{!! isset($quotation->relSuppliers->term_condition)?$quotation->relSuppliers->term_condition:'' !!}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-right mt-10">
                                        <button type="submit"
                                                class="btn btn-success rounded"><i
                                                    class="fa fa-plus"></i> {{ __('Generate PO') }}</button>
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
        (function ($) {
            "use strcit";

            const isNumberKey = (evt) => {
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            };


            const requisitionItemSum = () => {
                $('.requisition-wise-items').on('change', function () {
                    let requisitionId = $(this).val();
                    let quotationId = $('.quotation-id').val();
                    $('.view-data').html(0);
                    if (requisitionId != "") {
                        $.ajax({
                            url: "{{url('pms/quotation/requisition-wise-item-qty')}}",
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                _token: '{!! csrf_token() !!}',
                                requisitionId: requisitionId,
                                quotationId: quotationId
                            },
                            success: function (response) {
                                $.each(response, function (item_id, po_qty) {
                                    $('#item-' + item_id).val(po_qty);
                                    $('#item-' + item_id).parent().find('.view-data').html(po_qty);
                                    $('#po-' + item_id).val(po_qty);
                                    $('#po-' + item_id).parent().find('.view-data').html(po_qty);
                                });

                                checkPOQty();
                            }
                        });
                    } else {
                        $('.po-qty').val(0);
                        checkPOQty();
                    }
                })
            }
            requisitionItemSum();

        })(jQuery);

        getRequisitions();

        function getRequisitions() {
            let hrUnitId = $('#hr_unit_id').val() != "" ? $('#hr_unit_id').val() : 0;
            let hr_department_id = $('#hr_department_id').val();
            let quotationId = $('.quotation-id').val();
            $.ajax({
                url: "{{url('pms/quotation/unit-wise-requisition')}}/" + hrUnitId + '/' + quotationId + "?hr_department_id=" + hr_department_id + "&uncommon={{ $uncommon }}",
                type: 'GET',
                dataType: 'json',
                data: {},
            })
                .done(function (response) {
                    var requisition = '';
                    $.each(response, function (index, val) {
                        requisition += '<option value="' + val.id + '" selected>' + val.reference_no + '</option>';
                    });
                    $('#requisition_id').html(requisition).change();
                    $('.po-qty').val(0);
                    checkPOQty();

                    getCostCentres();
                })
                .fail(function () {
                    $('#requisition_id').html('').change();
                    checkPOQty();

                    getCostCentres();
                });
        }

        function checkPOQty() {
            $.each($('.check-po-qty'), function (event) {
                var po_qty = parseInt($(this).val());
                var quotation_qty = parseInt($(this).parent().parent().find('.quotation-qty').text());
                var requision_qty = parseInt($(this).parent().parent().find('.requisition-qty').val());

                if (requision_qty <= 0 || po_qty > quotation_qty) {
                    po_qty = (requision_qty > 0 ? po_qty : 0);
                    po_qty = (po_qty <= quotation_qty ? po_qty : quotation_qty);

                    $(this).val(po_qty);
                }
            });


            calculatePayAmount();
        }

        function calculatePayAmount(show_discount = true) {
            var exchangeRate = parseFloat("{{ $exchangeRate }}");
            var sub_total = 0;
            var total_discount = 0;
            var total_discounted = 0;
            var total_vat = 0;
            var grand_total = 0;
            $.each($('.check-po-qty'), function (event) {
                var po_qty = parseFloat($(this).val());
                var unit_price = parseFloat($(this).parent().parent().find('#unit_price').val());
                var discount_percentage = parseFloat($(this).parent().parent().find('#discount_percentage').val());
                var vat_type = $(this).parent().parent().find('#vat_type').val();
                var vat_percentage = parseFloat($(this).parent().parent().find('#vat_percentage').val());
                var unit_total = (po_qty * unit_price);
                var discount = (discount_percentage > 0 & unit_total > 0 ? (unit_total * (discount_percentage / 100)) : 0);
                var discounted = (unit_total - discount);

                if(vat_type == 'inclusive'){
                    var vat = parseFloat(vat_percentage > 0 && discounted > 0 ? ((discounted*vat_percentage)/(100+vat_percentage)) : 0);
                    var total = discounted;
                }else if(vat_type == 'exclusive'){
                    var vat = (vat_percentage > 0 & discounted > 0 ? (discounted * (vat_percentage / 100)) : 0);
                    var total = (discounted + vat);
                }else{
                    var vat = 0;
                    var total = discounted;
                }

                sub_total += unit_total;
                total_discount += discount;
                total_discounted += discounted;
                total_vat += vat;
                grand_total += total;

                $(this).parent().parent().find('.po-amount').html(parseFloat(unit_total).toFixed(2));
                // $(this).parent().parent().find('.po-amount-system-currency').html(parseFloat(discounted * exchangeRate).toFixed(2));
            });

            $('.po-sub-total').html(parseFloat(sub_total).toFixed(2));
            if(show_discount){
                $('.po-discount').val(parseFloat(total_discount).toFixed(2));
            }
            $('.po-after-discount').html(parseFloat(total_discounted).toFixed(2));
            $('.po-vat-amount').html(parseFloat(total_vat).toFixed(2));
            $('.total-po-amount').html(parseFloat(grand_total).toFixed(2));

            // $('.total-po-item-amount').html(parseFloat(sub_total).toFixed(2));
            // $('.total-po-item-amount-system-currency').html(parseFloat(sub_total * exchangeRate).toFixed(2));
            // $('.total-po-vat-amount').html(parseFloat(total_vat).toFixed(2));
            // $('.total-po-vat-amount-system-currency').html(parseFloat(total_vat * exchangeRate).toFixed(2));
            // $('.total-po-amount').html(parseFloat(grand_total).toFixed(2));
            // $('.total-po-amount-system-currency').html(parseFloat(grand_total * exchangeRate).toFixed(2));
        }

        function distributeDiscount() {
            var discount = parseFloat($('.po-discount').val());
            var sub_total = parseFloat($('.po-sub-total').text());

            var percentage = discount > 0 && sub_total > 0 ? (discount/sub_total)*100 : 0;
            console.log(percentage);

            $('.discount_percentage').val(percentage);
            calculatePayAmount(false);
        }

        function getCostCentres() {
            var hr_unit_id = $('#hr_unit_id').val();
            var requisitions = $("#requisition_id :selected").map(function (i, el) {
                return $(el).val();
            }).get();

            if (requisitions.length > 0) {
                $.ajax({
                    url: '{{ url('pms/quotation/get-cost-centres') }}',
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        hr_unit_id: hr_unit_id,
                        requisitions: requisitions,
                    },
                })
                    .done(function (costCentres) {
                        $('#cost_centre_id').html(costCentres);
                    })
                    .fail(function (response) {
                        $('#cost_centre_id').html('<option value="0">Choose Cost Centre</option>');
                    });
            } else {
                $('#cost_centre_id').html('<option value="0">Choose Cost Centre</option>');
            }
        }


        addMilestone();
        function addMilestone() {
            $('#milestones').append('<tr>'+
                                        '<td>'+
                                            '<input type="text" name="milestone_names[]" class="form-control" required>'+
                                        '</td>'+
                                        '<td>'+
                                            '<input type="number" name="milestone_percentages[]" min="0" max="100" value="0" class="form-control text-right milestones" onchange="calculateMilestone($(this))" onkeyup="calculateMilestone($(this))" required>'+
                                        '</td>'+
                                        '<td class="text-center">'+
                                            '<a class="btn btn-xs btn-danger text-white" onclick="removeMilestone($(this))"><i class="las la-trash"></i>&nbsp;Remove</a>'+
                                        '</td>'+
                                    '</tr>');
        }

        function removeMilestone(element) {
            element.parent().parent().remove();
        }

        function calculateMilestone(element) {
            var value = parseInt(element.val());
            var max = parseInt(element.attr('max'));
            var min = parseInt(element.attr('min'));

            if(value > max){
                element.val(max);
            }

            if(value < min){
                element.val(min);
            }

            var milestone = 0;
            $.each($('.milestones'), function(index, val) {
                milestone += parseInt($(this).val());
            });

            if(milestone > max){
                element.val(0);
            }
        }
    </script>
@endsection