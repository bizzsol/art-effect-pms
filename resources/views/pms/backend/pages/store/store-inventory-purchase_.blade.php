@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
@endsection
@section('main-content')
@php
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
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
                 <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Back" > <i class="las la-chevron-left"></i>Back</a>
             </li>
         </ul><!-- /.breadcrumb -->
     </div>

     <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <form  method="post" id="updateInventoryForm" action="{{ route('pms.rfp.store-requisition.purchase') }}">
                    @csrf
                    <div class="panel-body">

                       <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('request_date', 'Date', array('class' => 'mb-1 font-weight-bold')) !!} 
                                    {!! Form::text('request_date',Request::old('request_date')?Request::old('request_date'):date('d-m-Y'),['id'=>'request_date','class' => 'form-control rounded air-datepicker','placeholder'=>'','readonly'=>'readonly']) !!}

                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="form-line">
                                    {!! Form::label('reference_no', 'Reference No', array('class' => 'mb-1 font-weight-bold')) !!}
                                    {!! Form::text('reference_no',$refNo,['id'=>'reference_no','required'=>true,'class' => 'form-control rounded','placeholder'=>'Enter Reference No','readonly']) !!}
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="supplier_id" id="supplier_id" value="{{ \App\Models\PmsModels\Suppliers::where('deletable', 'no')->first()->id}}">
                        <input type="hidden" name="supplier_payment_terms_id" id="supplier_payment_terms_id" value="{{ \App\Models\PmsModels\SupplierPaymentTerm::where('supplier_id', \App\Models\PmsModels\Suppliers::where('deletable', 'no')->first()->id)->first()->id }}">
                        <div class="col-md-2 col-sm-12">
                            <p><label class="font-weight-bold" for="discount_percent">{{ __('Discount Percent %') }}:</label></p>
                            <div class="input-group input-group-md mb-3 d-">
                                {{ Form::number('discount_percent', old('discount_percent')?old('discount_percent'):0, ['class'=>'form-control rounded', 'placeholder'=>'0%','min'=>'0','max'=>'100','step'=>'any','id'=>'discount_percent']) }}
                                <input type="checkbox" name="checkall_discount" id="checkAllDiscount" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-10">
                        <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th>SL</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>UOM</th>
                                    <th>Unit Price</th>
                                    <th>Requisition Qty</th>
                                    <th>Estimate Qty</th>
                                    <th>Item Total</th>
                                    <th>Discount</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(isset($requisition))
                                @php 
                                $sumOfSubtoal=0;
                                $sumOfVat=0;

                                @endphp
                                @foreach($requisition as $key=>$item)
                                @php 
                                $subtotal=$item->product->unit_price*$item->qty;
                                $sumOfSubtoal +=$subtotal;
                                $vatCalculation= ($item->product->vat * $subtotal)/100;
                                $sumOfVat +=$vatCalculation
                                @endphp
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($item->product->category)?$item->product->category->name:''}}</td>
                                    <td>
                                        {{isset($item->product->name)?$item->product->name:''}}
                                        <input type="hidden" name="product_id[]" class="form-control" value="{{$item->product->id}}">
                                    </td>
                                    <td>
                                        {{isset($item->product->productUnit->unit_name)?$item->product->productUnit->unit_name:''}}
                                    </td>
                                    <td class="text-right"> 
                                        <input type="number" name="unit_price[{{$item->product->id}}]" required class="form-control"  min="0.0" step='any'  id="unitPrice{{$item->product->id}}" placeholder="0.00" onkeyup="calculateSubtotal({{$item->product->id}})" onchange="calculateSubtotal({{$item->product->id}})" value="{{$item->product->unit_price}}">
                                    </td>

                                    <td class="text-center">
                                        {{($item->delivery_qty>0)?number_format($item->qty-$item->delivery_qty,0): $item->qty}}
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="qty[{{$item->product->id}}]" required class="form-control"  min="0" id="qty{{$item->product->id}}" onKeyPress="if(this.value.length==4) return false;" onkeyup="calculateSubtotal({{$item->product->id}})" onchange="calculateSubtotal({{$item->product->id}})" step='any' value="{{($item->delivery_qty>0)?number_format($item->qty-$item->delivery_qty,0): $item->qty}}">

                                        <input type="hidden" name="request_qty[{{$item->product->id}}]" min="1" max="99999999" value="{{($item->delivery_qty>0)?number_format($item->qty-$item->delivery_qty,0): $item->qty}}" class="form-control rounded">

                                        <input type="hidden" name="product_vat" id="product_vat_{{$item->product->id}}" value="{{$item->product->vat}}" class="form-control calculateProductVat">
                                    </td>
                                    <td class="text-right">
                                        <input type="number" name="sub_total_price[{{$item->product->id}}]" required class="form-control calculateSumOfSubtotal" readonly id="subTotalPrice_{{$item->product->id}}" placeholder="0.00" min="0.0" step='any' value="{{$subtotal}}" >

                                        <input type="hidden" name="sub_total_vat_price[{{$item->product->id}}]" required class="form-control calculateSumOfVat" readonly id="sub_total_vat_price{{$item->product->id}}" placeholder="0.00" value="{{($item->product->vat * $subtotal)/100}}" >
                                    </td>
                                    <td class="text-center">

                                         <input type="number" step='any' name="item_discount_percent[{{$item->product->id}}]" class="form-control calculateDiscount bg-white" id="itemDiscountPercent{{$item->product->id}}" placeholder="0" onKeyPress="if(this.value.length==5) return false;" onkeyup="calculateItemDiscount({{$item->product->id}})"  onchange="calculateItemDiscount({{$item->product->id}})" data-id="{{$item->product->id}}">

                                        <input type="hidden" name="item_discount_amount[{{$item->product->id}}]" id="itemWiseDiscount_{{$item->product->id}}" class="itemWiseDiscount" value="{{$item->product->vat}}">
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                <tr>
                                    <td colspan="7" class="text-right">Sub Total</td>
                                    <td class="text-center">
                                        <input type="number" step='any' name="sum_of_subtoal" readonly class="form-control" id="sumOfSubtoal" placeholder="0.00" value="{{$sumOfSubtoal}}">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">(-) Discount</td>
                                    <td>
                                        <input type="number" name="discount" class="form-control bg-white" step='any' id="discount" placeholder="0.00" value="0.00">
                                        <input type="hidden" id="sub_total_with_discount" name="sub_total_with_discount" value="{{$sumOfSubtoal}}"  min="0" placeholder="0.00">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">(+) Vat</td>
                                    <td class="text-center">
                                        <input type="number" step='any' onkeyup="vatCalculate()" onchange="vatCalculate()" name="vat" class="form-control bg-white" id="vat" placeholder="0.00" value="{{$sumOfVat}}">
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">Gross Amount</td>
                                    <td class="text-right"><input type="text" name="gross_price" readonly class="form-control" id="grossPrice" placeholder="0.00" value="{{$sumOfSubtoal+$sumOfVat}}"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-row">
                        <input type="hidden" name="requisition_id" value="{{$req_id}}">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn btn-success rounded">{{ __('Send For Approval') }}</button>
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
    function calculateSubtotal(id) {
        var unit_price = $('#unitPrice'+id).val();
        var qty = $('#qty'+id).val();
        if(unit_price !='' && qty !=''){
            var sub_total = parseFloat(unit_price*qty).toFixed(2);
            $('#subTotalPrice_'+id).val(sub_total);
            var total=0
            $(".calculateSumOfSubtotal").each(function(){
                total += parseFloat($(this).val()||0);
            });
            $("#sumOfSubtoal").val(parseFloat(total).toFixed(2));
            calculateItemDiscount(id);
            calculateSumOfVatValue(id);
        }else{
            notify('Please Enter Unit Price & Quentity!','error');
        }
        return false;
    }

    function discountCalculate() {
        var sumOfSubtoal = parseFloat($('#sumOfSubtoal').val() != '' ? $('#sumOfSubtoal').val() : 0);
        var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0);
        var grossPrice = parseFloat(sumOfSubtoal)-parseFloat(discount);
        $('#grossPrice').val(parseFloat(grossPrice));
        $('#sub_total_with_discount').val(parseFloat(grossPrice));
    }

    //calculate discount
    $('#checkAllDiscount:checkbox').on('change', function () {
        var discount_parcent = ($('#discount_percent').val() != '' ? $('#discount_percent').val() : 0);
       //push discount parcentage in all item and set readonly attribute
       if($('#checkAllDiscount:checkbox').prop('checked')){
        $(".calculateDiscount").val(discount_parcent);
        $(".calculateDiscount").attr('readonly',true);

        var discountFields=document.querySelectorAll('.calculateDiscount');
        Array.from(discountFields).map((item, key)=>{
            calculateItemDiscount(item.getAttribute('data-id'));
        })
    }
    else{

        $(".calculateDiscount").val(0);
        $(".calculateDiscount").attr('readonly',false);
        $('#discount_percent').val(0);

        var discountFields=document.querySelectorAll('.calculateDiscount');
        Array.from(discountFields).map((item, key)=>{
            calculateItemDiscount(item.getAttribute('data-id'));
        })
    }
});

    function calculateItemDiscount(id) {

        var sub_total_price = parseFloat($('#subTotalPrice_'+id).val() != '' ? $('#subTotalPrice_'+id).val() : 0);
        var item_discount_percent= parseFloat($('#itemDiscountPercent'+id).val() != '' ? $('#itemDiscountPercent'+id).val() : 0);
        var value = (item_discount_percent * sub_total_price)/100;
        $('#itemWiseDiscount_'+id).val(parseFloat(value));
        //item wise sum
        var total=0;
        $(".itemWiseDiscount").each(function(){
            total += parseFloat($(this).val() != '' ? $(this).val() : 0);
        });
        $("#discount").val(parseFloat(total));
        discountCalculate();
        vatCalculate();
    }

    function vatCalculate() {

        var price = parseFloat($('#sub_total_with_discount').val() != '' ? $('#sub_total_with_discount').val() : 0);
        var vat = parseFloat($('#vat').val() != '' ? $('#vat').val() : 0);
        //var vat = (parcentage * price)/100;
        var total = parseFloat(price)+parseFloat(vat);
        
        $('#grossPrice').val(parseFloat(total));
    }

    function calculateSumOfVatValue(id) {
        var sub_total_price = parseFloat($('#subTotalPrice_'+id).val() != '' ? $('#subTotalPrice_'+id).val() : 0);
        var product_vat = parseFloat($('#product_vat_'+id).val() != '' ? $('#product_vat_'+id).val() : 0);
        
        var value = (product_vat > 0 && sub_total_price > 0 ? (product_vat * sub_total_price)/100 : 0);
        
        $('#sub_total_vat_price'+id).val(parseFloat(value));

        //item wise sum
        var total=0;
        $(".calculateSumOfVat").each(function(){
            total += parseFloat($(this).val() != '' ? $(this).val() : 0);
        });

        $("#vat").val(parseFloat(total));

        discountCalculate();
        vatCalculate();
    }

    $('#vat').on('change', function () {
        calculateVatValue();
    });

    $('#vat').on('keyup', function () {
        calculateVatValue();
    });


    function calculateVatValue(){
        $(".calculateProductVat").val(0);
        $(".calculateSumOfVat").val(0);

        var vat = ($('#vat').val() != '' ? $('#vat').val() : 0);
        var sumOfSubtoal = ($("#sumOfSubtoal").val() != '' ? $("#sumOfSubtoal").val() : 0);
        var vatPercetage = (vat > 0 ? ((vat*100)/sumOfSubtoal) : 0);

        $(".calculateProductVat").val(vatPercetage);
        //console.log(vatPercetage);

        var calculateProductVat = document.querySelectorAll('.calculateProductVat')
        Array.from(calculateProductVat).map(item => {
            var id = item.getAttribute('data-id');
            var sub_total_price = parseFloat($('#subTotalPrice_'+id).val() != '' ? $('#subTotalPrice_'+id).val() : 0);
            var product_vat = parseFloat($('#product_vat_'+id).val() != '' ? $('#product_vat_'+id).val() : 0);
            var value = parseFloat(product_vat > 0 && sub_total_price > 0 ? (product_vat * sub_total_price)/100 : 0);
            $('#sub_total_vat_price'+id).val(value);
        });
    }

    $('#discount').on('change', function () {
        calculateDiscountValue();
    });

    $('#discount').on('keyup', function () {
        calculateDiscountValue();
    });
    

    function calculateDiscountValue(){
        var discount = parseFloat($('#discount').val() != '' ? $('#discount').val() : 0);
        var sumOfSubtoal = parseFloat($("#sumOfSubtoal").val() != '' ? $("#sumOfSubtoal").val() : 0);
        var discountPercetage = parseFloat(discount > 0 ? (discount*100)/sumOfSubtoal : 0);

        $(".calculateDiscount").val(discountPercetage);
        //console.log(discountPercetage);
        var calculateDiscount = document.querySelectorAll('.calculateDiscount')
        Array.from(calculateDiscount).map(item => {
            var id = item.getAttribute('data-id');

            var sub_total_price = parseFloat($('#subTotalPrice_'+id).val() != '' ? $('#subTotalPrice_'+id).val() : 0);
            var item_discount_percent = parseFloat($('#itemDiscountPercent'+id).val() != '' ? $('#itemDiscountPercent'+id).val() : 0);;
            var value = parseFloat(item_discount_percent > 0 && sub_total_price > 0 ? (item_discount_percent*sub_total_price)/100 : 0);
            $('#itemWiseDiscount_'+id).val(value);
        });

        discountCalculate();
        vatCalculate();
    }
</script>
@endsection