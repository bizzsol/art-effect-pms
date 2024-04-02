@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
    .bordered{
        border: 1px #ccc solid
    }
    .floating-title{
        position: absolute;
        top: -13px;
        left: 15px;
        background: white;
        padding: 0px 5px 5px 5px;
        font-weight: 500;
    }

    .label{
        font-weight:  bold !important;
    }

    .tab-pane{
        padding-top: 15px;
    }

    .select2-container{
        width:  100% !important;
    }

    .select2-container--default .select2-results__option[aria-disabled=true]{
        color: black !important;
        font-weight: bold !important;
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
                <li><a href="#">PMS</a></li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    {{-- <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-basic-tab" data-toggle="tab" href="#nav-basic" role="tab" aria-controls="nav-basic" aria-selected="true">Basic Information</a>
                        <a class="nav-item nav-link" id="nav-attributes-tab" data-toggle="tab" href="#nav-attributes" role="tab" aria-controls="nav-attributes" aria-selected="false">Attributes</a>
                      </div>
                    </nav> --}}

                    <form action="{{ route('pms.product-management.product.update', $product->id) }}" method="post" id="product-edit-form">
                    @csrf
                    @method('PUT')
                        {{-- <div class="tab-content" id="nav-tabContent">
                          <div class="tab-pane fade show active" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab"> --}}
                            <div class="form-row">
                                <div class="col-md-2 mb-3">
                                    <p class="mb-1 font-weight-bold"><label for="sku"><strong>{{ __('Code') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('sku')? '<span class="text-danger text-capitalize">'. $errors->first('sku').'</span>':'' !!}</p>
                                    @if(auth()->user()->hasRole('Employee'))
                                    {{ $product->sku }}
                                    @else
                                    <div class="input-group input-group-md d-">
                                        <input type="text" name="sku" id="sku" class="form-control rounded" aria-label="Large" placeholder="{{__('Item Code')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('sku')?old('sku'):$product->sku }}"  data-sku="{{ old('sku')?old('sku'):$product->sku }}">
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-4 mb-3">
                                    <p class="mb-1 font-weight-bold"><label for="name"><strong>{{ __('Name') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('name')? '<span class="text-danger text-capitalize">'. $errors->first('name').'</span>':'' !!}</p>
                                    @if(auth()->user()->hasRole('Employee'))
                                    {{ $product->name }}
                                    @else
                                    <div class="input-group input-group-md d-">
                                        <input type="text" name="name" id="name" class="form-control rounded" aria-label="Large" placeholder="{{__('Item Name')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('name', $product->name) }}">
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <p class="mb-1 font-weight-bold"><label for="category_type"><strong>Category Type<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('category_type')? '<span class="text-danger text-capitalize">'. $errors->first('category_type').'</span>':'' !!}</p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-" onchange="getSubCategories()">
                                        <select name="category_type" id="category_type" class="form-control parent-category">
                                            <option value="products" {{ $category_type == 'products' ? 'selected' : '' }}>Product</option>
                                            <option value="fixed_asset" {{ $category_type == 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                                            <option value="cwip" {{ $category_type == 'cwip' ? 'selected' : '' }}>CWIP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <p class="mb-1 font-weight-bold"><label for="category_id"><strong>{{ __('Sub Category') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('category_id')? '<span class="text-danger text-capitalize">'. $errors->first('category_id').'</span>':'' !!}</p>
                                    <div class="select-search-group input-group input-group-md d-">
                                        <select name="category_id" id="category_id" class="form-control rounded select2 sub-category" required onchange="checkAttributes();getSubCategoryInformation();">
                                            
                                        </select>
                                    </div>
                                </div>

                                    {{-- <div class="col-md-3">
                                    <p class="mb-1 font-weight-bold"><label for="brand_id">{{ __('Brand') }}<span class="text-danger">&nbsp;*</span></label> 
                                        {!! $errors->has('brand_id')? '<span class="text-danger text-capitalize">'. $errors->first('brand_id').'</span>':'' !!}</p>
                                        <div class="select-search-group  input-group input-group-md mb-3 d-">
                                           {!! Form::Select('brand_id',$brands,old('brand_id', $product->brand_id),['id'=>'brand_id', 'class'=>'form-control selectheighttype select2']) !!}
                                       </div>
                                   </div> --}}

                                </div>

                                <div class="form-row">
                                   <div class="col-md-1 mb-3">
                                    <p class="mb-1 font-weight-bold"><label for="supplier"><strong>{{ __('Safety Stock') }}</strong></label> {!! $errors->has('buffer_inventory')? '<span class="text-danger text-capitalize">'. $errors->first('buffer_inventory').'</span>':'' !!}</p>
                                    @if(auth()->user()->hasRole('Employee'))
                                    {{ ($product->relInventorySummary ? $product->relInventorySummary->buffer_inventory : 0) }}
                                    @else
                                    <div class="select-search-group input-group input-group-md d-">
                                        <input type="number" name="buffer_inventory" id="bufferInventory" class="form-control rounded" aria-label="Large" placeholder="{{__('Safety Stock Limit')}}" aria-describedby="inputGroup-sizing-sm" value="{{ old('buffer_inventory', ($product->relInventorySummary ? $product->relInventorySummary->buffer_inventory : 0)) }}" oninput="this.value = Math.abs(this.value)">
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-1">
                                    <p class="mb-1 font-weight-bold"><label for="product_unit_id"><strong>{{ __('UOM') }}<span class="text-danger">&nbsp;*</span></strong></label> 
                                        {!! $errors->has('product_unit_id')? '<span class="text-danger text-capitalize">'. $errors->first('product_unit_id').'</span>':'' !!}</p>
                                        <div class="select-search-group  input-group input-group-md mb-3 d-">
                                           {!! Form::Select('product_unit_id',$unit,old('product_unit_id', $product->product_unit_id),['id'=>'product_unit_id', 'class'=>'form-control selectheighttype select2']) !!}
                                       </div>
                                   </div>
                                    <div class="col-md-3 row">
                                        <div class="col-md-6">
                                            <p class="mb-1 font-weight-bold"><label for="unit_price"><strong>{{ __('Unit Price') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('unit_price')? '<span class="text-danger text-capitalize">'. $errors->first('unit_price').'</span>':'' !!}</p>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="text" name="unit_price" id="unit_price" class="form-control rounded mask-money" aria-label="Large" placeholder="{{__('Item unit price')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('unit_price', systemDoubleValue($product->unit_price, 2)) }}" >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1 font-weight-bold"><label for="sales_price"><strong>{{ __('Sales Price') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('sales_price')? '<span class="text-danger text-capitalize">'. $errors->first('sales_price').'</span>':'' !!}</p>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="text" name="sales_price" id="sales_price" class="form-control rounded mask-money" aria-label="Large" placeholder="{{__('Item unit price')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('sales_price', systemDoubleValue($product->sales_price, 2)) }}" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 row">
                                        <div class="col-md-4">
                                            <p class="mb-1 font-weight-bold"><label for="tax"><strong>
                                                {{ __('TAX') }}<span class="text-danger">&nbsp;*</span>
                                            </strong></label> {!! $errors->has('tax')? '<span class="text-danger text-capitalize">'. $errors->first('tax').'</span>':'' !!}</p>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="text" name="tax" id="tax" class="form-control rounded mask-money" aria-label="Large" placeholder="{{__('Item tax(%)')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('tax', systemDoubleValue($product->tax, 2)) }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 font-weight-bold"><label for="vat_type"><strong>{{ __('Type') }}<span class="text-danger">&nbsp;*</span></strong></label>
                                                {!! $errors->has('vat_type')? '<span class="text-danger text-capitalize">'. $errors->first('vat_type').'</span>':'' !!}
                                            </p>
                                            <div class="select-search-group  input-group input-group-md mb-3 d-">
                                                {!! Form::Select('vat_type',['exclusive'=>'Exclusive','inclusive'=>'Inclusive'],old('vat_type',$product->vat_type),['id'=>'vat_type', 'class'=>'form-control select2']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 font-weight-bold"><label for="vat"><strong>
                                                {{ __('VAT') }}<span class="text-danger">&nbsp;*</span>
                                            </strong></label> {!! $errors->has('vat')? '<span class="text-danger text-capitalize">'. $errors->first('vat').'</span>':'' !!}</p>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <input type="text" name="vat" id="vat" class="form-control rounded mask-money" aria-label="Large" placeholder="{{__('Item VAT(%)')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('vat', systemDoubleValue($product->vat, 2)) }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-3 {{ freezeProduct($product) ? 'd-none' : '' }}">
                                    <label for="attributes">Attributes</label>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <select name="productAttributes[]" id="attributes" class="form-control rounded product-attributes" multiple data-placeholder="Choose Item Attributes" onchange="updateAttributes()">
                                            @if(isset($attributes[0]))
                                            @foreach($attributes as $key => $attribute)
                                                <option value="{{ $attribute->id }}" {{ in_array($attribute->id, $categoryAttributes) && in_array($attribute->id, $productAttributes) ? 'selected' : '' }} {{ !in_array($attribute->id, $categoryAttributes) ? 'hidden' : '' }}>{{ $attribute->name }}</attribute>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        @if(isset($attributes[0]))
                                        @foreach($attributes as $key => $attribute)
                                            <div class="col-md-2 mb-3 attributes attribute-{{ $attribute->id }}" style="display: {{ in_array($attribute->id, $categoryAttributes) && in_array($attribute->id, $productAttributes) ? 'block' : 'none' }}">
                                                <label for="attribute-{{ $attribute->id }}">{{ $attribute->name }}</label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <select name="attribute_option_id[{{ $attribute->id }}]" id="attribute-{{ $attribute->id }}" class="form-control rounded attribute-options">
                                                        <option value="0">Not Required</option>
                                                        @if(isset($attribute->options[0]))
                                                        @foreach($attribute->options as $key => $option)
                                                            <option value="{{ $option->id }}" {{ in_array($option->id, $categoryAttributeOptions) && $product->attributes->where('attribute_option_id', $option->id)->count() > 0 ? 'selected' : '' }}  {{ !in_array($option->id, $categoryAttributeOptions) ? 'hidden' : '' }}>{{ $option->name }}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div> 
                                            </div>
                                            {{-- <div class="col-md-1 attributes attribute-{{ $attribute->id }}" style="display: {{ in_array($attribute->id, $categoryAttributes) && in_array($attribute->id, $productAttributes) ? 'block' : 'none' }}">
                                                <label for="serial_no{{ $attribute->id }}"><strong>{{ __('Serial') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                <div class="input-group input-group-md mb-3 d-">
                                                     <input min="0"  type="number" id="serial_no-{{ $attribute->id }}" name="serial_no[{{ $attribute->id }}]" value="{{isset($productAttribute->serial_no) ? $productAttribute->serial_no : 0}}" class="form-control">
                                                </div>
                                            </div> --}}
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 ledgers-div asset-div" style="display: none">
                                    <p class="mb-1 font-weight-bold"><label for="parent_id"><strong>{{ __('Main Asset') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('parent_id')? '<span class="text-danger text-capitalize">'. $errors->first('parent_id').'</span>':'' !!}</p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="parent_id" id="parent_id" class="form-control rounded select2" required onchange="getSubAssetCode()">
                                            <option value="0" data-code=''>N/A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="mb-1 font-weight-bold"><label for="supplier">{{ __('Supplier') }}</label> {!! $errors->has('supplier')? '<span class="text-danger text-capitalize">'. $errors->first('supplier').'</span>':'' !!}</p>
                                    <div class="select-search-group input-group input-group-md mb-3 d-">
                                        <select name="supplier[]" id="supplier" class="form-control rounded select2" multiple style="width: 100%">
                                            @if(isset($suppliers[0]))
                                            @foreach($suppliers as $key => $supplier)
                                            <option value="{{ $supplier->id }}" {{ in_array($supplier->id, $existedSuppliers) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                               {{--  <div class="col-md-12 mt-3">
                                    <div class="row pr-3">
                                        <div class="col-md-3">
                                            <label for="inventory_account_id"><strong>{{ request()->has('fixed-assets') ? 'Fixed Asset' : (request()->has('cwip') ? 'CWIP' : 'Inventory') }} Accounts:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="inventory_account_id" id="inventory_account_id" class="form-control select-me rounded" data-selected="{{ $product->inventory_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>
                                        @if(!request()->has('fixed-assets') && !request()->has('cwip'))
                                        <div class="col-md-3">
                                            <label for="cogs_account_id"><strong>{{ __('Consumption Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="cogs_account_id" id="cogs_account_id" class="form-control select-me rounded" data-selected="{{ $product->cogs_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-3">
                                            <label for="cwip_asset_account_id"><strong>{{ __('CWIP to Asset Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="cwip_asset_account_id" id="cwip_asset_account_id" class="form-control select-me rounded" data-selected="{{ $product->cwip_asset_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="inventory_adjustments_account_id"><strong>{{ request()->has('fixed-assets') ? 'Fixed Asset' : (request()->has('cwip') ? 'CWIP' : 'Inventory') }} Adjustments Account:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="inventory_adjustments_account_id" id="inventory_adjustments_account_id" class="form-control select-me rounded" data-selected="{{ $product->inventory_adjustments_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-md-12 mt-3">
                                    <div class="row pr-3">
                                        <div class="col-md-2">
                                            <p class="mb-1 font-weight-bold"><label for="parent"><strong>{{ __('Product Type') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('product_type')? '<span class="text-danger text-capitalize">'. $errors->first('product_type').'</span>':'' !!}</p>
                                            <div class="select-search-group input-group input-group-md mb-3 d-">
                                                <select name="product_type" id="product_type" class="form-control select2 types" onchange="updateFinanceSection()">
                                                    <option value="products" {{ $product_type == 'products' ? 'selected' : '' }}>Product</option>
                                                    <option value="fixed_asset" {{ $product_type == 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                                                    <option value="cwip" {{ $product_type == 'cwip' ? 'selected' : '' }}>CWIP</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 service-div">
                                            <p class="mb-1 font-weight-bold"><label for="is_service"><strong>{{ __('Service ?') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('is_service')? '<span class="text-danger text-capitalize">'. $errors->first('is_service').'</span>':'' !!}</p>
                                            <div class="select-search-group input-group input-group-md mb-3 d-">
                                                <select name="is_service" id="is_service" class="form-control select2 types" onchange="updateFinanceSection()">
                                                    <option value="0" {{ $product->is_service == 0 ? 'selected' : '' }}>No</option>
                                                    <option value="1" {{ $product->is_service == 1 ? 'selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 sale-item-div">
                                            <p class="mb-1 font-weight-bold"><label for="is_sale_item"><strong>{{ __('Sale Item ?') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('is_sale_item')? '<span class="text-danger text-capitalize">'. $errors->first('is_sale_item').'</span>':'' !!}</p>
                                            <div class="select-search-group input-group input-group-md mb-3 d-">
                                                <select name="is_sale_item" id="is_sale_item" class="form-control select2 types" onchange="updateFinanceSection()">
                                                    <option value="0" {{ $product->is_sale_item == 0 ? 'selected' : '' }}>No</option>
                                                    <option value="1" {{ $product->is_sale_item == 1 ? 'selected' : '' }}>Yes</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 ledgers-div inventory-div">
                                            <label for="inventory_account_id"><strong><span id="inventory-title">Inventory Accounts</span>:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="inventory_account_id" id="inventory_account_id" class="form-control select-me rounded" data-selected="{{ $product->inventory_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 ledgers-div consumption-div">
                                            <label for="cogs_account_id"><strong>{{ __('Consumption Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="cogs_account_id" id="cogs_account_id" class="form-control select-me rounded" data-selected="{{ $product->cogs_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 ledgers-div sales-div">
                                            <label for="sales_account_id"><strong>{{ __('Sales Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="sales_account_id" id="sales_account_id" class="form-control select-me rounded" data-selected="{{ $product->sales_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 ledgers-div asset-div">
                                            <label for="cwip_asset_account_id"><strong>{{ __('Asset Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <select name="cwip_asset_account_id" id="cwip_asset_account_id" class="form-control select-me rounded" data-selected="{{ $product->cwip_asset_account_id }}">
                                                    {!! $chartOfAccountsOptions !!}
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4 mt-4 fixed-asset-info">
                                    <div class="card">
                                        <div class="card-body bordered">
                                            <h5 class="floating-title">Fixed Asset Information</h5>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="depreciation_method_id"><strong>Depreciation Method</strong></label>
                                                    <select name="depreciation_method_id" id="depreciation_method_id" class="form-control rounded" onchange="getDepreciationMethodOptions()">
                                                        @if(isset($depreciationMethods[0]))
                                                        @foreach($depreciationMethods as $key => $depreciationMethod)
                                                        <option value="{{ $depreciationMethod->id }}" data-code="{{ $depreciationMethod->code }}" data-base-rate="{{ $depreciationMethod->base_rate }}" data-depreciation-rate="{{ $depreciationMethod->depreciation_rate }}" {{ $product->depreciation_method_id == $depreciationMethod->id ? 'selected' : '' }}>{{ $depreciationMethod->code }} - {{ $depreciationMethod->name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="row">
                                                        {{-- <div class="col-md-4">
                                                            <label for="sales_account_id"><strong>{{ __('Sales Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                            <div class="input-group input-group-md mb-3 d-">
                                                                <select name="sales_account_id" id="sales_account_id" class="form-control select-me rounded" data-selected="{{ $product->sales_account_id }}">
                                                                    {!! $chartOfAccountsOptions !!}
                                                                </select>
                                                            </div>
                                                        </div> --}}
                                                        <div class="col-md-4">
                                                            <label for="depreciation_cost_account_id"><strong>{{ __('Depreciation Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                            <div class="input-group input-group-md mb-3 d-">
                                                                <select name="depreciation_cost_account_id" id="depreciation_cost_account_id" class="form-control select-me rounded" data-selected="{{ $product->depreciation_cost_account_id }}">
                                                                    {!! $chartOfAccountsOptions !!}
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="inventory_adjustments_account_id"><strong>Accumulated Depreciation Account:<span class="text-danger">&nbsp;*</span></strong></label>
                                                            <div class="input-group input-group-md mb-3 d-">
                                                                <select name="inventory_adjustments_account_id" id="inventory_adjustments_account_id" class="form-control select-me rounded" data-selected="{{ $product->inventory_adjustments_account_id }}">
                                                                    {!! $chartOfAccountsOptions !!}
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="depreciation_disposal_account_id"><strong>{{ __('Disposal Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
                                                            <div class="input-group input-group-md mb-3 d-">
                                                                <select name="depreciation_disposal_account_id" id="depreciation_disposal_account_id" class="form-control select-me rounded" data-selected="{{ $product->depreciation_disposal_account_id }}">
                                                                    {!! $chartOfAccountsOptions !!}
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2 method-options base_rate" style="display: none">
                                                    <label for="base_rate"><strong>Base Rate (%)</strong></label>
                                                    <input type="text" name="base_rate" id="base_rate" value="{{ $product->base_rate }}" step="0.01" class="form-control mask-money">
                                                </div>
                                                <div class="col-md-2 method-options years" style="display: none">
                                                    <label for="years"><strong>Depreciation Years:</strong></label>
                                                    <input type="number" name="years" id="years" value="{{ $product->years }}" class="form-control" onchange="calculateDepreciationPercentage()" onkeyup="calculateDepreciationPercentage()" oninput="this.value = Math.abs(this.value)">
                                                </div>
                                                <div class="col-md-2 method-options depreciation_rate" style="display: none">
                                                    <label for="depreciation_rate"><strong>Depreciation Rate (%)</strong></label>
                                                    <input type="text" name="depreciation_rate" id="depreciation_rate" value="{{ $product->depreciation_rate }}" step="0.0001" class="form-control mask-money">
                                                </div>
                                                <div class="col-md-2 method-options rate_multiplier" style="display: none">
                                                    <label for="rate_multiplier"><strong>Rate Multiplier:</strong></label>
                                                    <input type="text" name="rate_multiplier" id="rate_multiplier" value="{{ $product->rate_multiplier }}" step="0.01" class="form-control mask-money">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="is_final_asset"><strong>{{ __('Final Asset ? ') }}</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="is_final_asset" id="is_final_asset" class="form-control rounded">
                                                            <option value="0" {{ $product->is_final_asset == 0 ? 'selected' : '' }}>No</option>
                                                            <option value="1" {{ $product->is_final_asset == 1 ? 'selected' : '' }}>Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-2">
                                                    <label for="depreciation_start_date"><strong>Depreciation Start:</strong></label>
                                                    <input type="date" name="depreciation_start_date" id="depreciation_start_date" value="{{ date('Y-m-d', strtotime($product->depreciation_start_date)) }}" class="form-control">
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          {{-- </div>
                          <div class="tab-pane fade" id="nav-attributes" role="tabpanel" aria-labelledby="nav-attributes-tab"> --}}
                            
                          {{-- </div> --}}
                          <div class="col-md-12 pb-5">
                            <a class="btn btn-danger rounded pull-right ml-2" href="{{ url('pms/product-management/product') }}"><i class="la la-times"></i>&nbsp;{{ __('Close') }}</a>
                              <button type="submit" class="btn btn-success rounded pull-right save-button"><i class="fa fa-check"></i>&nbsp;Save Item Information</button>
                          </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
<script type="text/javascript">
    $(document).ready(function() {
        $.each($('.select-me'), function(index, val) {
            $(this).select2().val($(this).attr('data-selected')).trigger("change");
        });


        var form = $('#product-edit-form');
        var save_button = form.find('.save-button');
        var save_button_content = save_button.html();

        form.submit(function(event) {
            event.preventDefault();
            save_button.prop('disabled', true).html('<i class="la la-spinner"></i>&nbsp;Please Wait...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serializeArray(),
            })
            .done(function(response) {
                if(response.success){
                    window.open(response.url, "_parent");
                }else{
                    toastr.error(response.message);
                }

                save_button.prop('disabled', false).html(save_button_content);
            })
            .fail(function(response) {
                $.each(response.responseJSON.errors, function(index, val) {
                    toastr.error(val[0]);
                });
                save_button.prop('disabled', false).html(save_button_content);
            });
        });
    });
    
    $(document).ready(function(){
        var tab = location.href.split('?tab=')[1];
        if(tab !== undefined){
            $('.nav-tabs').find('.nav-link').removeClass('active');
            $('.nav-tabs').find('#nav-'+(tab)+'-tab').addClass('active');

            $('.tab-content').find('.tab-pane').removeClass('show active');
            $('.tab-content').find('#nav-'+(tab)).addClass('show active');
        }

        $('.product-attributes').select2({
           templateResult: function(option) {
              if(option.element && (option.element).hasAttribute('hidden')){
                 return null;
              }
              return option.text;
           }
        });

        $('.attribute-options').select2({
           templateResult: function(option) {
              if(option.element && (option.element).hasAttribute('hidden')){
                 return null;
              }
              return option.text;
           }
        });
    });
    
    function checkAttributes() {
        if($('#category_id').find(':selected').attr('data-attributes') != undefined){
            var attributes = $('#category_id').find(':selected').attr('data-attributes').split(',');
            var attributeOptions = $('#category_id').find(':selected').attr('data-attribute-options').split(',');

            $.each($(".product-attributes"), function(name, attribute_id) {
              $.each($(this).find('option'), function(index, val) {
                   if($.inArray($(this).attr('value'), attributes) != -1){
                    $(".product-attributes option[value='" + $(this).attr('value') + "']").removeAttr('hidden');
                   }else{
                    $(".product-attributes option[value='" + $(this).attr('value') + "']").attr('hidden', 'hidden');
                   }
              });
              
              $(this).select2({
               templateResult: function(option) {
                  if(option.element && (option.element).hasAttribute('hidden')){
                     return null;
                  }
                  return option.text;
               }
              });
            });

            $.each($('.attribute-options'), function(index, val) {
                $.each($(this).find('option'), function(index, val) {
                   if($.inArray($(this).attr('value'), attributeOptions) != -1){
                    $(this).parent().find("option[value='" + $(this).attr('value') + "']").removeAttr('hidden');
                   }else{
                    $(this).parent().find("option[value='" + $(this).attr('value') + "']").attr('hidden', 'hidden');
                   }
                });

                $(this).select2({
                    templateResult: function(option) {
                      if(option.element && (option.element).hasAttribute('hidden')){
                         return null;
                      }
                      return option.text;
                    }
                });
            });

            $(".product-attributes").val("").select2();

            $('.product-attributes').select2({
                allowClear: true,
                templateResult: function(option) {
                  if(option.element && (option.element).hasAttribute('hidden')){
                    return null;
                  }

                  return option.text;
                },
            });
        }else{
            $.each($(".product-attributes"), function(name, attribute_id) {
              $.each($(this).find('option'), function(index, val) {
                $(".product-attributes option[value='" + $(this).attr('value') + "']").removeAttr('hidden');
              });
              
              $(this).select2({
               templateResult: function(option) {
                  return null;
               }
              });
            });
        }

        getDepreciationMethodOptions();
    }

    function updateAttributes(){
        var attributes = $("#attributes :selected").map(function(i, el) {
            return $(el).val();
        }).get();

        $('.attributes').hide();
        $.each(attributes, function(index, attribute) {
            $('.attribute-'+attribute).show();
        });
    }

    function getSubCategoryInformation(){
        var category = $('#category_id');

        $('#product_type').select2().val(category.find(':selected').attr('data-product-type')).trigger("change");
        $('#is_service').select2().val(category.find(':selected').attr('data-service')).trigger("change");

        var inventory_account_id = category.find(':selected').attr('inventory_account_id');
        var cwip_asset_account_id = category.find(':selected').attr('cwip_asset_account_id');
        var cogs_account_id = category.find(':selected').attr('cogs_account_id');
        var inventory_adjustments_account_id = category.find(':selected').attr('inventory_adjustments_account_id');

        var sales_account_id = category.find(':selected').attr('sales_account_id');
        var depreciation_cost_account_id = category.find(':selected').attr('depreciation_cost_account_id');
        var depreciation_disposal_account_id = category.find(':selected').attr('depreciation_disposal_account_id');

        if (inventory_account_id && inventory_account_id != 0) {
            $("#inventory_account_id").select2().val(inventory_account_id).trigger("change");
        }

        if (cwip_asset_account_id && cwip_asset_account_id != 0) {
            $("#cwip_asset_account_id").select2().val(cwip_asset_account_id).trigger("change");
        }

        if (cogs_account_id && cogs_account_id != 0) {
            $("#cogs_account_id").select2().val(cogs_account_id).trigger("change");
        }

        if (inventory_adjustments_account_id && inventory_adjustments_account_id != 0) {
            $("#inventory_adjustments_account_id").select2().val(inventory_adjustments_account_id).trigger("change");
        }

        if (sales_account_id && sales_account_id != 0) {
            $("#sales_account_id").select2().val(sales_account_id).trigger("change");
        }

        if (depreciation_cost_account_id && depreciation_cost_account_id != 0) {
            $("#depreciation_cost_account_id").select2().val(depreciation_cost_account_id).trigger("change");
        }

        if (depreciation_disposal_account_id && depreciation_disposal_account_id != 0) {
            $("#depreciation_disposal_account_id").select2().val(depreciation_disposal_account_id).trigger("change");
        }

        updateFinanceSection();
        getDepreciationMethodOptions();
        getSubAssets();
    }

    updateFinanceSection();
    function updateFinanceSection() {
        var product_type = $('#product_type').val();
        var is_service = $('#is_service').val();
        $('.ledgers-div').hide();

        if(product_type == 'products'){
            $('.fixed-asset-info').hide();
            
            if(is_service == 1){
                $('.inventory-div').hide();
            }else{
                $('.inventory-div').show();
            }
            
            $('.consumption-div').show();
            $('.sales-div').show();
            $('.service-div').show();
            $('.asset-div').hide();

            $('#inventory-title').html('Inventory Account');
        }else if(product_type == 'fixed_asset'){
            $('.fixed-asset-info').show();
            $('.inventory-div').show();
            $('.consumption-div').hide();
            $('.sales-div').hide();
            $('.service-div').hide();
            $('.asset-div').hide();

            $('#inventory-title').html('Asset Account');
        }else if(product_type == 'cwip'){
            $('.fixed-asset-info').show();
            $('.inventory-div').show();
            $('.consumption-div').hide();
            $('.sales-div').hide();
            $('.service-div').hide();
            $('.asset-div').show();

            $('#inventory-title').html('CWIP Account');
        }
    }

    // checkFixedAsset();
    // function checkFixedAsset() {
    //     if($('.sub-category').find(':selected').attr('is_fixed_asset') == 1 || $('.sub-category').find(':selected').attr('is_cwip') == 1){
    //         $('.fixed-asset-info').show();
    //     }else{
    //         $('.fixed-asset-info').hide();
    //     }

    //     getDepreciationMethodOptions();
    // }

    getDepreciationMethodOptions();
    function getDepreciationMethodOptions() {
        var depreciation_method = $('#depreciation_method_id');
        var id = depreciation_method.val();
        var code = depreciation_method.find(':selected').attr('data-code');
        
        $('.method-options').hide();
        if(code == "D"){
            $('.base_rate').show();

            $('.rate_multiplier').show();
        }else if(code == "S"){
            $('.years').show();
            $('.depreciation_rate').show();
        }else if(code == "N"){
            $('.years').show();
        }else if(code == "O"){
            $('.depreciation_rate').show();
        }
    }

    function calculateDepreciationPercentage() {
        if($('#depreciation_method_id').find(':selected').attr('data-code') == "S"){
            var years = $('#years').val();
            $('#depreciation_rate').val(parseFloat(years != "" && years > 0 ? (parseFloat(100)/parseFloat(years)) : parseFloat(0)).toFixed(4));
        }
    }

    getSubAssets();
    function getSubAssets() {
        $.ajax({
            url: "{{ url('pms/cwip/product') }}/"+$('#category_id').val()+"/get-sub-assets?selected={{ $product->parent_id }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#parent_id').html(response).change();
        });
    }

    function getSubAssetCode() {
        var parent_id = $('#parent_id').val();
        var code = $('#parent_id').find(':selected').attr('data-code');
        if(code == '' || parent_id == "{{ $product->parent_id }}"){
            $('#sku').val($('#sku').attr('data-sku'));
        }else{
            $('#sku').val(code);
        }
    }

    getSubCategories();
    function getSubCategories(){
        $.ajax({
            url: "{{ url('pms/product-management/product/create') }}?get-sub-categories&"+$('#category_type').val()+"&chosen={{ $product->category_id }}",
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            $('#category_id').html(response);
        });
    }
</script>
@endsection