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
                    <a href="{{ route('pms.grn.po.list') }}" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel p-20">
                <form  method="post" action="{{ route('pms.grn.grn-process.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="received_date" value="{{ date('Y-m-d') }}">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-12">
                                <p class="mb-1 font-weight-bold"><label for="po_reference_no">{{ __('PO No') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                   <input type="text" value="{{$purchaseOrder->reference_no}}" readonly class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-12">
                                <p class="mb-1 font-weight-bold"><label for="reference_no">{{ __('Reference No') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="reference_no" id="reference_no" class="form-control rounded" readonly aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ ($refNo)?($refNo):0 }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <p class="mb-1 font-weight-bold"><label for="supplier_id">{{ __('Supplier') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" readonly class="form-control rounded" value="{{ $purchaseOrder->relQuotation->relSuppliers->name.' ('.$purchaseOrder->relQuotation->relSuppliers->code.')' }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <p class="mb-1 font-weight-bold"><label for="challan">{{ __('Challan No.') }} <span class="text-danger">&nbsp;*</span></label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    {{ Form::text('challan', '', ['class'=>'form-control', 'placeholder'=>'Enter Challan Number here','id'=>'challan','required'=>'required']) }}
                                </div>
                            </div>
                            <input type="hidden" name="purchase_order_id" value="{{$purchaseOrder->id}}">
                        </div>

                        <div class="table-responsive mt-10">
                            <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
                                <thead>
                                    <tr class="text-center">
                                        <th>SL</th>
                                        <th>Product</th>
                                        <th>Desription</th>
                                        <th>UOM</th>
                                        <th>Unit Price</th>
                                        <th>Po Qty</th>
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
                                            {{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }} {{ getProductAttributesFaster($requisitionItems->where('product_id', $item->product_id)->first()) }}
                                            <input type="hidden" name="product_id[]" class="form-control" value="{{isset($item->relProduct->id)?$item->relProduct->id:0}}">
                                        </td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            {{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
                                        </td>
                                        <td>
                                            <input type="text" name="unit_price[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" class="form-control text-right"   min="0.0"  id="unit_price_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" value="{{$item->unit_price}}" readonly placeholder="0">
                                        </td>
                                        <td class="text-center">
                                            {{$item->qty}}
                                            <input type="hidden" value="{{$leftQty}}" id="main_qty_{{isset($item->relProduct->id)?$item->relProduct->id:0}}">
                                        </td>
                                        <td class="text-center" id="pre_rcv_qty_{{isset($item->relProduct->id)?$item->relProduct->id:0}}">
                                            {{$receiveQty}}
                                        </td>
                                        <td class="text-center">
                                            <input type="number" name="qty[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]"  class="form-control bg-white rcvQty" min="0" max="{{$leftQty}}" id="receive_qty_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" data-id="{{isset($item->relProduct->id)?$item->relProduct->id:0}}" value="{{$leftQty}}" placeholder="0" oninput="this.value = Math.abs(this.value)">
                                        </td>

                                        <td class="text-center" id="left_qty_{{isset($item->relProduct->id)?$item->relProduct->id:0}}"></td>


                                        <input type="hidden" name="unit_amount[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" value="{{round($unit_amount,2)}}" required readonly class="form-control calculateSumOfSubtotal" id="sub_total_price_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" placeholder="0">
                                        
                                        <input type="hidden" name="discount_percentage[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" class="form-control readonly rounded discountPercentage" id="discount_percentage_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" readonly value="{{$item->discount_percentage}}">

                                        <input type="hidden" name="item_discount_amount[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" id="item_wise_discount_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" class="itemWiseDiscount" value="{{$discount_amount}}">
                                        
                                        <input type="hidden" name="vat_type[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" id="vat_type_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" data-id="{{isset($item->relProduct->id)?$item->relProduct->id:0}}" value="{{$item->vat_type}}" class="form-control calculateProductVat" readonly>

                                        <input type="hidden" name="vat_percentage[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" id="vat_percentage_{{isset($item->relProduct->id)?$item->relProduct->id:0}}" data-id="{{isset($item->relProduct->id)?$item->relProduct->id:0}}" value="{{$item->vat_percentage}}" class="form-control calculateProductVat" readonly>

                                        <input type="hidden" name="sub_total_vat_price[{{isset($item->relProduct->id)?$item->relProduct->id:0}}]" required class="form-control calculateSumOfVat" readonly id="sub_total_vat_price{{isset($item->relProduct->id)?$item->relProduct->id:0}}" placeholder="0.00" value="{{$vat_amount}}" data-vat-type="{{$item->vat_type}}">
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <h4 class="mb-2 mt-4">#Replace Items</h4>

                            <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
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
                                        'product_id' => $gateOut->relGoodsReceivedItems->product_id
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
                                            <input type="number" name="replace_qty[{{$gateOut->id}}]"  class="form-control bg-white" onchange="checkReplaceQty($(this))" onkeyup="checkReplaceQty($(this))"  min="0" max="{{$leftQty}}" value="{{$leftQty}}" placeholder="0">
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
                                    <textarea name="note" class="form-control" rows="2" placeholder="Write Here...."></textarea>
                                </div>
                            </div>
                            <input type="hidden" value="{{round($totalPrice,2)}}" name="total_price" required readonly class="form-control" id="sumOfSubtoal" placeholder="0.00" step="0.0">

                            <input type="hidden" name="discount" class="form-control bg-white" step="0.25" id="discount" readonly placeholder="0.00" value="{{$total_discount_amount}}">

                            <input type="hidden" id="sub_total_with_discount" name="sub_total_with_discount" value="{{($totalPrice-$total_discount_amount)}}"  min="0" placeholder="0.00">

                            <input type="hidden" name="vat" class="form-control bg-white" id="vat" readonly placeholder="0.00" value="{{$total_vat_amount}}">

                            <input type="hidden" value="{{($totalPrice-$total_discount_amount)+$total_vat_amount}}" name="gross_price" readonly required class="form-control" id="grossPrice" placeholder="0.00">

                            <div class="col-md-12">
                                <div class="mb-3 text-right">
                                    <button type="submit" class="btn btn-primary rounded" style="float:right">{{ __('Proceed Gate In') }}</button>
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
    "use strcit"

    const validateReceiveQty = (item, key) => {
        var UnitPrice = item.querySelectorAll('td')[4];
        var PoQty = item.querySelectorAll('td')[5];
        var PrvRcvQty = item.querySelectorAll('td')[6];
        var ReceivingQty = item.querySelectorAll('td')[7];
        var LeftQty = item.querySelectorAll('td')[8];

        
        var SubPrice = item.querySelector('.calculateSumOfSubtotal');
        var DiscountPercentage = item.querySelector('.discountPercentage');

        ReceivingQty.onkeyup = function (){
            validateData();
        }

        ReceivingQty.onchange = function (){
            validateData();
        }

        function validateData() {
            if(PrvRcvQty.innerText.trim() == 0){
                ReceivingQty.querySelector('input').value>parseInt(PoQty.innerText.trim()) ? ReceivingQty.querySelector('input').value=parseInt(PoQty.innerText.trim()): (ReceivingQty.querySelector('input').value === 0 ? ReceivingQty.querySelector('input').value=0:ReceivingQty.querySelector('input').value);
            } else {
                ReceivingQty.querySelector('input').value>(parseInt(PoQty.innerText.trim()) - parseInt(PrvRcvQty.innerText.trim())) ? ReceivingQty.querySelector('input').value=(parseInt(PoQty.innerText.trim()) - parseInt(PrvRcvQty.innerText.trim())): (ReceivingQty.querySelector('input').value<0 ? ReceivingQty.querySelector('input').value=0:ReceivingQty.querySelector('input').value);
            }
            LeftQty.innerText = (parseInt(PoQty.innerText.trim()) - parseInt(PrvRcvQty.innerText.trim()) - ReceivingQty.querySelector('input').value);

        
            SubPrice.value= parseFloat(ReceivingQty.querySelector('input').value) * parseFloat(UnitPrice.querySelector('input').value);

            // For Discount Calculation
            var ProductId=ReceivingQty.querySelector('input').getAttribute('data-id');
            var DiscountPercentageValue=DiscountPercentage.value;
            var ItemWiseSubPrice=SubPrice.value;
            
            var DiscountAmount = (parseFloat(ItemWiseSubPrice) * (parseFloat(DiscountPercentageValue)/100));
            
            $('#item_wise_discount_'+ProductId).val(parseFloat(DiscountAmount));
            //Item wise sum
            var TotalDiscountAmount=0;
            $(".itemWiseDiscount").each(function(){
                TotalDiscountAmount += parseFloat($(this).val() != "" ? $(this).val() : 0);
            });
            $("#discount").val(parseFloat(TotalDiscountAmount));
            //End Discount Calculation

            var TotalSubTotal=0;
            $('.calculateSumOfSubtotal').each(function(){
                TotalSubTotal += parseFloat($(this).val() != "" ? $(this).val() : 0);
            });

            $('#sumOfSubtoal').val(parseFloat(TotalSubTotal));
            DiscountCalculate();
            CalculateSumOfVat(ProductId);
        }
    }

    const getAllContent = () => {
        var contents = document.querySelectorAll('.grnItemContent');
        Array.from(contents).map((item, key) => {
            validateReceiveQty(item, key);
        })
    }
    getAllContent();

    const DiscountCalculate=()=>{
        var sumOfSubtoal = parseFloat($('#sumOfSubtoal').val() != "" ? $('#sumOfSubtoal').val() : 0);
        var discount = parseFloat($('#discount').val() != "" ? $('#discount').val() : 0);
        if(sumOfSubtoal != null && discount != null){
            var grossPrice = parseFloat(sumOfSubtoal)-parseFloat(discount);
            $('#grossPrice').val(parseFloat(grossPrice));
            $('#sub_total_with_discount').val(parseFloat(grossPrice));

            console.log('grossPrice='+grossPrice)
        }
        return false;
    };
    DiscountCalculate();

    function CalculateSumOfVat(id) {
        var sub_total_price = parseFloat($('#sub_total_price_'+id).val() != "" ? $('#sub_total_price_'+id).val() : 0);
        var vat_type= parseFloat($('#vat_type_'+id).val() != "" ? $('#vat_type_'+id).val() : 0);
        var vat_percentage= parseFloat($('#vat_percentage_'+id).val() != "" ? $('#vat_percentage_'+id).val() : 0);

        if(sub_total_price != null && vat_percentage !=null){
            if(vat_type == 'inclusive'){
                var value = vat_percentage > 0 && sub_total_price > 0 ? ((sub_total_price*vat_percentage)/(100+vat_percentage)) : 0;
            }else if(vat_type == 'exclusive'){
                var value = vat_percentage > 0 ? (sub_total_price*(vat_percentage/100)) : 0;
            }

            $('#sub_total_vat_price'+id).val(parseFloat(value));
            
            var total = 0;
            $(".calculateSumOfVat").each(function(){
                total += parseFloat($(this).val() != "" ? $(this).val() : 0);
            });

            $("#vat").val(parseFloat(total));

            DiscountCalculate();
            VatCalculate();
        }

        return false;
    }

    const VatCalculate=()=> {
        var price = parseFloat($('#sub_total_with_discount').val());
        var vat = parseFloat($('#vat').val());
        
        var inclusive_vat = 0;
        $(".calculateSumOfVat").each(function(){
            if($(this).attr('data-vat-type') == 'inclusive'){
                inclusive_vat += parseFloat($(this).val() != "" ? $(this).val() : 0);
            }
        });
        
        var total = parseFloat(price)+parseFloat(vat)-parseFloat(inclusive_vat);
        $('#grossPrice').val(parseFloat(total));
    }
    VatCalculate();


    function checkReplaceQty(element) {
        var value = (element.val() != "" ? parseInt(element.val()) : 0);
        var min = parseInt(element.attr('min'));
        var max = parseInt(element.attr('max'));
        var replace = parseInt(element.parent().prev().prev().text());
        var prev = parseInt(element.parent().prev().text());

        if(value < min){
            element.val(min);
            value = min;
        }

        if(value > max){
            element.val(max);
            value = max;
        }

        element.parent().next().html((replace-prev)-value);
    }

</script>
@endsection