@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
@endsection
@section('main-content')
    @php
        use \App\Models\PmsModels\Purchase\PurchaseOrderItem;
    @endphp
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
                        <a href="{{ route('pms.grn.po.list') }}" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="panel p-20">
                    <form method="post" action="{{ route('pms.grn.grn-process.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="received_date" value="{{ date('Y-m-d') }}">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="po_reference_no">{{ __('PO No') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" value="{{$purchaseOrder->reference_no}}" readonly
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="reference_no">{{ __('Reference No') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" name="reference_no" id="reference_no"
                                               class="form-control rounded" readonly aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm" required
                                               value="{{ ($refNo)?($refNo):0 }}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="supplier_id">{{ __('Supplier') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" readonly class="form-control rounded"
                                               value="{{ $purchaseOrder->relQuotation->relSuppliers->name.' ('.$purchaseOrder->relQuotation->relSuppliers->code.')' }}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="challan">{{ __('Challan No.') }} <span
                                                    class="text-danger">&nbsp;*</span></label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        {{ Form::text('challan', '', ['class'=>'form-control', 'placeholder'=>'Enter Challan Number here','id'=>'challan','required'=>'required']) }}
                                    </div>
                                </div>
                                <input type="hidden" name="purchase_order_id" value="{{$purchaseOrder->id}}">
                            </div>

                            <div class="table-responsive mt-10">
                                <table class="table table-striped table-bordered table-head" cellspacing="0"
                                       width="100%" id="dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>UOM</th>
                                        <th>Po Qty</th>
                                        <th>Unit Price</th>
                                        <th>Prv.Rcv Qty</th>
                                        <th>Receiving Qty</th>
                                        <th>Left Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $totalReceiveQty=0;
                                        $totalPrice=0;
                                        $discount_amount=0;
                                        $total_discount_amount=0;
                                        $vat_amount=0;
                                        $total_vat_amount=0;
                                    @endphp
                                    @if(isset($purchaseOrder->relPurchaseOrderItems))
                                        @foreach($purchaseOrder->relPurchaseOrderItems as $key=>$item)
                                            @php

                                                $leftQty=isset($item->grn_qty)?$item->grn_qty:$item->qty;
                                                $receiveQty=0;

                                                if ($item->relReceiveProduct->count() > 0){
                                                    $receiveQty =  isset($item->grn_qty) && $item->grn_qty > 0  ? $item->grn_qty : 0;
                                                    $totalReceiveQty += $receiveQty;
                                                }
                                                $leftQty = $item->qty-$receiveQty;
                                                $unit_amount = $leftQty*$item->unit_price;
                                                $totalPrice += $unit_amount;

                                                $discount_amount = ($unit_amount*($item->discount_percentage/100));
                                                $total_discount_amount += $discount_amount;

                                                $vat_amount = $item->vat;
                                                $total_vat_amount += $vat_amount;
                                            @endphp
                                            @if($leftQty > 0)
                                                <tr class="grnItemContent">
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        {{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }} {{ getProductAttributesFaster($requisitionItems->where('uid', $item->uid)->first()) }}
                                                        <input type="hidden" name="product_id[]" class="form-control"
                                                               value="{{ $item->uid }}">
                                                    </td>
                                                    <td>{{ $purchaseOrder->relQuotation->relQuotationItems->where('uid', $item->uid)->first()->description }}</td>
                                                    <td>
                                                        {{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
                                                    </td>

                                                    <td class="text-center">
                                                        {{$item->qty}}
                                                        <input type="hidden" value="{{$leftQty}}"
                                                               id="main_qty_{{ $item->uid }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="unit_price[{{ $item->uid }}]"
                                                               class="form-control text-right" min="0.0"
                                                               id="unit_price_{{ $item->uid }}"
                                                               value="{{$item->unit_price}}" readonly placeholder="0">
                                                    </td>
                                                    <td class="text-center" id="pre_rcv_qty_{{ $item->uid }}">
                                                        {{$receiveQty}}
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" name="qty[{{ $item->uid }}]"
                                                               class="form-control bg-white rcvQty" min="0"
                                                               max="{{$leftQty}}" id="receive_qty_{{ $item->uid }}"
                                                               data-id="{{ $item->uid }}" value="{{$leftQty}}"
                                                               placeholder="0"
                                                               oninput="this.value = Math.abs(this.value)">
                                                    </td>

                                                    <td class="text-center" id="left_qty_{{ $item->uid }}"></td>


                                                    <input type="hidden" name="unit_amount[{{ $item->uid }}]"
                                                           value="{{round($unit_amount,2)}}" required readonly
                                                           class="form-control calculateSumOfSubtotal"
                                                           id="sub_total_price_{{ $item->uid }}" placeholder="0">

                                                    <input type="hidden" name="discount_percentage[{{ $item->uid }}]"
                                                           class="form-control readonly rounded discountPercentage"
                                                           id="discount_percentage_{{ $item->uid }}" readonly
                                                           value="{{$item->discount_percentage}}">

                                                    <input type="hidden" name="item_discount_amount[{{ $item->uid }}]"
                                                           id="item_wise_discount_{{ $item->uid }}"
                                                           class="itemWiseDiscount" value="{{$discount_amount}}">

                                                    <input type="hidden" name="vat_type[{{ $item->uid }}]"
                                                           id="vat_type_{{ $item->uid }}" data-id="{{ $item->uid }}"
                                                           value="{{$item->vat_type}}"
                                                           class="form-control calculateProductVat" readonly>

                                                    <input type="hidden" name="vat_percentage[{{ $item->uid }}]"
                                                           id="vat_percentage_{{ $item->uid }}"
                                                           data-id="{{ $item->uid }}" value="{{$item->vat_percentage}}"
                                                           class="form-control calculateProductVat" readonly>

                                                    <input type="hidden" name="sub_total_vat_price[{{ $item->uid }}]"
                                                           required class="form-control calculateSumOfVat" readonly
                                                           id="sub_total_vat_price{{ $item->uid }}" placeholder="0.00"
                                                           value="{{$vat_amount}}" data-vat-type="{{$item->vat_type}}">
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                                <h4 class="mb-2 mt-4">#Replace Items</h4>

                                <table class="table table-striped table-bordered table-head" cellspacing="0"
                                       width="100%" id="dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th>Product</th>
                                        <th>UOM</th>
                                        <th>Replace Qty</th>
                                        <th>Prv. Rcv Qty</th>
                                        <th>Receiving Qty</th>
                                        <th>Left Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($gateOuts))
                                        @foreach($gateOuts as $key => $gateOut)
                                            @php

                                                $leftQty = $gateOut->gateOuts->sum('qty')-$gateOut->received_qty;
                                                $receiveQty = $gateOut->received_qty;
                                                $totalReceiveQty += $receiveQty;

                                                $item = PurchaseOrderItem::with([
                                                    'relProduct.productUnit',
                                                    'relProduct.category.category',
                                                    'relProduct.attributes.attributeOption.attribute',
                                                ])
                                                ->where([
                                                    'po_id' => $gateOut->relGoodsReceivedItems->relGoodsReceivedNote->purchase_order_id,
                                                    'uid' => $gateOut->relGoodsReceivedItems->uid
                                                ])->first();

                                                $unit_amount=$leftQty*$item->unit_price;
                                                $totalPrice +=$unit_amount;

                                                $discount_amount=($item->discount_percentage*$unit_amount)/100;
                                                $total_discount_amount +=$discount_amount;

                                                $vat_amount=($item->relProduct->vat*$unit_amount)/100;
                                                $total_vat_amount +=$vat_amount;
                                            @endphp
                                            @if($leftQty != 0)
                                                <tr class="grnItemContent">
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        {{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}
                                                    </td>
                                                    <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}</td>
                                                    <td class="text-center">
                                                        {{$gateOut->return_qty}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$receiveQty}}
                                                    </td>
                                                    <td>
                                                        <input type="number" name="replace_qty[{{$gateOut->id}}]"
                                                               class="form-control bg-white"
                                                               onchange="checkReplaceQty($(this))"
                                                               onkeyup="checkReplaceQty($(this))" min="0"
                                                               max="{{$leftQty}}" value="{{$leftQty}}" placeholder="0">
                                                    </td>
                                                    <td>0</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif

                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <p class="mb-1 font-weight-bold"><label for="note">{{ __('Notes.') }}:</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <textarea name="note" class="form-control" rows="2"
                                                  placeholder="Write Here...."></textarea>
                                    </div>
                                </div>
                                <input type="hidden" value="{{round($totalPrice,2)}}" name="total_price" required
                                       readonly class="form-control" id="sumOfSubtoal" placeholder="0.00" step="0.0">

                                <input type="hidden" name="discount" class="form-control bg-white" step="0.25"
                                       id="discount" readonly placeholder="0.00" value="{{$total_discount_amount}}">

                                <input type="hidden" id="sub_total_with_discount" name="sub_total_with_discount"
                                       value="{{($totalPrice-$total_discount_amount)}}" min="0" placeholder="0.00">

                                <input type="hidden" name="vat" class="form-control bg-white" id="vat" readonly
                                       placeholder="0.00" value="{{$total_vat_amount}}">

                                <input type="hidden" value="{{($totalPrice-$total_discount_amount)+$total_vat_amount}}"
                                       name="gross_price" readonly required class="form-control" id="grossPrice"
                                       placeholder="0.00">

                                <div class="col-md-12">
                                    <div class="mb-3 text-right">
                                        <button type="submit" class="btn btn-primary rounded"
                                                style="float:right">{{ __('Proceed Gate In') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="purchase_order_id" value="{{$purchaseOrder->id}}">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('page-script')
    <script>
        "use strict";

        // ✅ Utility: safe number parsing
        const toFloat = (val, fallback = 0) => parseFloat(val) || fallback;
        const toInt = (val, fallback = 0) => parseInt(val) || fallback;

        function validateReceiveQty($row) {
            const $poQty = $row.find("td:eq(4)");
            const $unitPrice = $row.find("td:eq(5) input");
            const $prevQty = $row.find("td:eq(6)");
            const $receivingQty = $row.find("td:eq(7) input");
            const $leftQty = $row.find("td:eq(8)");

            const $subPrice = $row.find(".calculateSumOfSubtotal");
            const $discountPercentage = $row.find(".discountPercentage");

            function validateData() {
                const poQty = toInt($poQty.text());
                const prevQty = toInt($prevQty.text());
                let recvQty = toInt($receivingQty.val());

                // Clamp receiving qty
                if (prevQty === 0) {
                    recvQty = Math.min(recvQty, poQty);
                } else {
                    recvQty = Math.min(recvQty, poQty - prevQty);
                }
                recvQty = Math.max(recvQty, 0);
                $receivingQty.val(recvQty);

                // Left qty
                $leftQty.text(poQty - prevQty - recvQty);

                // Subtotal
                const subTotal = recvQty * toFloat($unitPrice.val());
                $subPrice.val(subTotal);

                // Discount calc
                const productId = $receivingQty.data("id");
                const discountPct = toFloat($discountPercentage.val());
                const discountAmt = subTotal * (discountPct / 100);
                $("#item_wise_discount_" + productId).val(discountAmt);

                // Total discount
                let totalDiscount = 0;
                $(".itemWiseDiscount").each(function () {
                    totalDiscount += toFloat($(this).val());
                });
                $("#discount").val(totalDiscount);

                // Subtotal sum
                let totalSub = 0;
                $(".calculateSumOfSubtotal").each(function () {
                    totalSub += toFloat($(this).val());
                });
                $("#sumOfSubtoal").val(totalSub);

                DiscountCalculate();
                CalculateSumOfVat(productId);
            }

            // Bind once
            $receivingQty.on("keyup change", validateData);
        }

        function getAllContent() {
            $(".grnItemContent").each(function () {
                validateReceiveQty($(this));
            });
        }

        // ✅ Discount calculation
        function DiscountCalculate() {
            const subTotal = toFloat($("#sumOfSubtoal").val());
            const discount = toFloat($("#discount").val());
            const gross = subTotal - discount;

            $("#grossPrice, #sub_total_with_discount").val(gross);
            return gross;
        }

        // ✅ VAT calculation per item
        function CalculateSumOfVat(id) {
            const subTotal = toFloat($("#sub_total_price_" + id).val());
            const vatType = $("#vat_type_" + id).val();
            const vatPct = toFloat($("#vat_percentage_" + id).val());

            let value = 0;
            if (vatType === "inclusive") {
                value = vatPct > 0 && subTotal > 0 ? (subTotal * vatPct) / (100 + vatPct) : 0;
            } else if (vatType === "exclusive") {
                value = vatPct > 0 ? subTotal * (vatPct / 100) : 0;
            }

            $("#sub_total_vat_price" + id).val(value);

            // Sum VAT
            let totalVat = 0;
            $(".calculateSumOfVat").each(function () {
                totalVat += toFloat($(this).val());
            });
            $("#vat").val(totalVat);

            DiscountCalculate();
            VatCalculate();
        }

        // ✅ Total VAT + gross update
        function VatCalculate() {
            const price = toFloat($("#sub_total_with_discount").val());
            const vat = toFloat($("#vat").val());

            let inclusiveVat = 0;
            $(".calculateSumOfVat").each(function () {
                if ($(this).data("vat-type") === "inclusive") {
                    inclusiveVat += toFloat($(this).val());
                }
            });

            const total = price + vat - inclusiveVat;
            $("#grossPrice").val(total);
        }

        // ✅ Replace qty validation
        function checkReplaceQty($el) {
            let value = toInt($el.val());
            const min = toInt($el.attr("min"));
            const max = toInt($el.attr("max"));
            const replace = toInt($el.parent().prev().prev().text());
            const prev = toInt($el.parent().prev().text());

            value = Math.max(min, Math.min(value, max));
            $el.val(value);

            $el.parent().next().text((replace - prev) - value);
        }

        // --- INIT ---
        $(document).ready(function () {
            getAllContent();
            DiscountCalculate();
            VatCalculate();
        });
    </script>

@endsection