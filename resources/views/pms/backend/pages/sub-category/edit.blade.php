
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
				<li>
					<a href="#">PMS</a>
				</li>
				<li class="active">{{__($title)}} List</li>
				 <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Back" > <i class="las la-chevron-left"></i>Back</a>
                </li>
			</ul>

		</div>

		<div class="page-content">
			<div class="">
				<div class="panel panel-info">
					<div class="panel-body">
						<form action="{{ route('pms.product-management.sub-category.update', $category->id) }}
							" method="post">
							@csrf
							@method('PUT')
							
							<div class="row">
								<div class="col-md-2">
									<p class="mb-1 font-weight-bold"><label for="code"><strong>{{ __('Code') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('code')? '<span class="text-danger text-capitalize">'. $errors->first('code').'</span>':'' !!}</p>
									<div class="input-group input-group-md mb-3 d-">
										<input type="text" readonly name="code" id="code" class="form-control rounded bg-white" aria-label="Large" placeholder="{{__('Category Code')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ $category->code }}">
									</div>
								</div>

			                    <div class="col-md-3">
			                        <p class="mb-1 font-weight-bold"><label for="parent"><strong>{{ __('Main Category') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('parent')? '<span class="text-danger text-capitalize">'. $errors->first('parent').'</span>':'' !!}</p>
			                        <div class="select-search-group input-group input-group-md mb-3 d-">
			                            <select name="parent_id" id="parent" class="form-control parent-category" onchange="getCategoryInformation()">
			                                @if(isset($categories[0]))
			                                    @foreach($categories as $c)
			                                        <option value="{{ $c->id }}" {{ $c->id == $category->parent_id ? 'selected' : '' }} data-departments="{{ $c->departmentsList->pluck('hr_department_id')->implode(',') }}" inventory_account_id="{{ $c->inventory_account_id }}" cwip_asset_account_id="{{ $c->cwip_asset_account_id }}" cogs_account_id="{{ $c->cogs_account_id }}" inventory_adjustments_account_id="{{ $c->inventory_adjustments_account_id }}" is_fixed_asset="{{ $c->is_fixed_asset }}" is_cwip="{{ $c->is_cwip }}"  depreciation_rate="{{ $c->depreciation_rate }}" sales_account_id="{{ $c->sales_account_id }}" depreciation_cost_account_id="{{ $c->depreciation_cost_account_id }}" depreciation_disposal_account_id="{{ $c->depreciation_disposal_account_id }}" data-product-type="{{ $c->is_fixed_asset == 1 ? 'fixed_asset' : ($c->is_cwip == 1 ? 'cwip' : 'products') }}" data-service="{{ $c->is_service }}">{{ $c->name }} ({{ $c->code }} )</option>
			                                    @endforeach
			                                @endif
			                            </select>
			                        </div>
			                    </div>
								
								<div class="col-md-7">
									<p class="mb-1 font-weight-bold"><label for="name"><strong>{{ __('Name') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('name')? '<span class="text-danger text-capitalize">'. $errors->first('name').'</span>':'' !!}</p>
									<div class="input-group input-group-md mb-3 d-">
										<input type="text" name="name" id="name" class="form-control rounded" aria-label="Large" placeholder="{{__('Category Name')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ ($category->name)?$category->name: old('name') }}">
									</div>
								</div>

								<div class="col-md-12 mt-3" style="display: none">
			                        <div class="row pr-3">
			                            <div class="col-md-2">
			                                <p class="mb-1 font-weight-bold"><label for="parent"><strong>{{ __('Product Type') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('product_type')? '<span class="text-danger text-capitalize">'. $errors->first('product_type').'</span>':'' !!}</p>
			                                <div class="select-search-group input-group input-group-md mb-3 d-">
			                                    <select name="product_type" id="product_type" class="form-control select2 types" onchange="updateFinanceSection()">
			                                        <option value="products" {{ $category->is_fixed_asset == 0 && $category->is_cwip == 0 ? 'selected' : '' }}>Product</option>
			                                        <option value="fixed_asset" {{ $category->is_fixed_asset == 1 ? 'selected' : '' }}>Fixed Asset</option>
			                                        <option value="cwip" {{ $category->is_cwip == 1 ? 'selected' : '' }}>CWIP</option>
			                                    </select>
			                                </div>
			                            </div>
			                            <div class="col-md-2 service-div">
			                                <p class="mb-1 font-weight-bold"><label for="is_service"><strong>{{ __('Service ?') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('is_service')? '<span class="text-danger text-capitalize">'. $errors->first('is_service').'</span>':'' !!}</p>
			                                <div class="select-search-group input-group input-group-md mb-3 d-">
			                                    <select name="is_service" id="is_service" class="form-control select2 types" onchange="updateFinanceSection()">
			                                        <option value="0" {{ $category->is_service == 0 ? 'selected' : '' }}>No</option>
			                                        <option value="1" {{ $category->is_service == 1 ? 'selected' : '' }}>Yes</option>
			                                    </select>
			                                </div>
			                            </div>
			                            <div class="col-md-2 sale-item-div">
			                                <p class="mb-1 font-weight-bold"><label for="is_sale_item"><strong>{{ __('Sale Item ?') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('is_sale_item')? '<span class="text-danger text-capitalize">'. $errors->first('is_sale_item').'</span>':'' !!}</p>
			                                <div class="select-search-group input-group input-group-md mb-3 d-">
			                                    <select name="is_sale_item" id="is_sale_item" class="form-control select2 types" onchange="updateFinanceSection()">
			                                        <option value="0" {{ $category->is_sale_item == 0 ? 'selected' : '' }}>No</option>
			                                        <option value="1" {{ $category->is_sale_item == 1 ? 'selected' : '' }}>Yes</option>
			                                    </select>
			                                </div>
			                            </div>
			                            <div class="col-md-3 ledgers-div inventory-div">
			                                <label for="inventory_account_id"><strong><span id="inventory-title">Inventory Accounts</span>:<span class="text-danger">&nbsp;*</span></strong></label>
			                                <div class="input-group input-group-md mb-3 d-">
			                                    <select name="inventory_account_id" id="inventory_account_id" class="form-control select-me rounded" data-selected="{{ $category->inventory_account_id }}">
			                                        {!! $chartOfAccountsOptions !!}
			                                    </select>
			                                </div>
			                            </div>

			                            <div class="col-md-3 ledgers-div consumption-div">
			                                <label for="cogs_account_id"><strong>{{ __('Consumption Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
			                                <div class="input-group input-group-md mb-3 d-">
			                                    <select name="cogs_account_id" id="cogs_account_id" class="form-control select-me rounded" data-selected="{{ $category->cogs_account_id }}">
			                                        {!! $chartOfAccountsOptions !!}
			                                    </select>
			                                </div>
			                            </div>

			                            <div class="col-md-3 ledgers-div sales-div">
			                                <label for="sales_account_id"><strong>{{ __('Sales Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
			                                <div class="input-group input-group-md mb-3 d-">
			                                    <select name="sales_account_id" id="sales_account_id" class="form-control select-me rounded" data-selected="{{ $category->sales_account_id }}">
			                                        {!! $chartOfAccountsOptions !!}
			                                    </select>
			                                </div>
			                            </div>

			                            <div class="col-md-3 ledgers-div asset-div">
			                                <label for="cwip_asset_account_id"><strong>{{ __('Asset Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
			                                <div class="input-group input-group-md mb-3 d-">
			                                    <select name="cwip_asset_account_id" id="cwip_asset_account_id" class="form-control select-me rounded" data-selected="{{ $category->cwip_asset_account_id }}">
			                                        {!! $chartOfAccountsOptions !!}
			                                    </select>
			                                </div>
			                            </div>
			                            
			                        </div>
			                    </div>

			                    <div class="col-md-12 mb-4 mt-4 fixed-asset-info" style="display: none">
			                        <div class="card">
			                            <div class="card-body bordered">
			                                <h5 class="floating-title">Fixed Asset Information</h5>
			                                <div class="row">
			                                    <div class="col-md-2">
			                                        <label for="depreciation_rate"><strong>Depreciation Rate</strong></label>
			                                        <input type="number" name="depreciation_rate" value="{{ $category->depreciation_rate }}" step="0.01" class="form-control">
			                                    </div>
			                                    <div class="col-md-10">
			                                        <div class="row">
			                                            <div class="col-md-4">
			                                                <label for="depreciation_cost_account_id"><strong>{{ __('Depreciation Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
			                                                <div class="input-group input-group-md mb-3 d-">
			                                                    <select name="depreciation_cost_account_id" id="depreciation_cost_account_id" class="form-control select-me rounded" data-selected="{{ $category->depreciation_cost_account_id }}">
			                                                        {!! $chartOfAccountsOptions !!}
			                                                    </select>
			                                                </div>
			                                            </div>
			                                            <div class="col-md-4">
			                                                <label for="inventory_adjustments_account_id"><strong>Accumulated Depreciation Account:<span class="text-danger">&nbsp;*</span></strong></label>
			                                                <div class="input-group input-group-md mb-3 d-">
			                                                    <select name="inventory_adjustments_account_id" id="inventory_adjustments_account_id" class="form-control select-me rounded" data-selected="{{ $category->inventory_adjustments_account_id }}">
			                                                        {!! $chartOfAccountsOptions !!}
			                                                    </select>
			                                                </div>
			                                            </div>
			                                            <div class="col-md-4">
			                                                <label for="depreciation_disposal_account_id"><strong>{{ __('Asset Disposal Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
			                                                <div class="input-group input-group-md mb-3 d-">
			                                                    <select name="depreciation_disposal_account_id" id="depreciation_disposal_account_id" class="form-control select-me rounded" data-selected="{{ $category->depreciation_disposal_account_id }}">
			                                                        {!! $chartOfAccountsOptions !!}
			                                                    </select>
			                                                </div>
			                                            </div>
			                                        </div>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>

								<div class="col-md-12 mt-10">
			                        <a class="btn btn-danger rounded pull-right" href="{{ url('pms/product-management/sub-category') }}"><i class="la la-times"></i>&nbsp;{{ __('Close') }}</a>
									<button type="submit" class="btn btn-success rounded pull-right mr-3"><i class="la la-save"></i>&nbsp;{{ __('Save Category') }}</button>
								</div>
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('page-script')
<script>
    $(document).ready(function() {
        $.each($('.select-me'), function(index, val) {
            $(this).select2().val($(this).attr('data-selected')).trigger("change");
        });
    });

    function getCategoryInformation() {
        var product_type = $('#parent').find(':selected').attr('data-product-type');
        var is_service = $('#parent').find(':selected').attr('data-service');
        var inventory_account_id = $('#parent').find(':selected').attr('inventory_account_id');
        var cwip_asset_account_id = $('#parent').find(':selected').attr('cwip_asset_account_id');
        var cogs_account_id = $('#parent').find(':selected').attr('cogs_account_id');
        var inventory_adjustments_account_id = $('#parent').find(':selected').attr('inventory_adjustments_account_id');
        
        var depreciation_rate = $('#parent').find(':selected').attr('depreciation_rate');
        var sales_account_id = $('#parent').find(':selected').attr('sales_account_id');
        var depreciation_cost_account_id = $('#parent').find(':selected').attr('depreciation_cost_account_id');
        var depreciation_disposal_account_id = $('#parent').find(':selected').attr('depreciation_disposal_account_id');

        if(inventory_account_id && inventory_account_id != 0){
            $("#inventory_account_id").select2().val(inventory_account_id).trigger("change");
        }

        if(cwip_asset_account_id && cwip_asset_account_id != 0){
            $("#cwip_asset_account_id").select2().val(cwip_asset_account_id).trigger("change");
        }

        if(cogs_account_id && cogs_account_id != 0){
            $("#cogs_account_id").select2().val(cogs_account_id).trigger("change");
        }

        if(inventory_adjustments_account_id && inventory_adjustments_account_id != 0){
            $("#inventory_adjustments_account_id").select2().val(inventory_adjustments_account_id).trigger("change");
        }

        $("#depreciation_rate").val(depreciation_rate);

        if(sales_account_id && sales_account_id != 0){
            $("#sales_account_id").select2().val(sales_account_id).trigger("change");
        }

        if(depreciation_cost_account_id && depreciation_cost_account_id != 0){
            $("#depreciation_cost_account_id").select2().val(depreciation_cost_account_id).trigger("change");
        }

        if(depreciation_disposal_account_id && depreciation_disposal_account_id != 0){
            $("#depreciation_disposal_account_id").select2().val(depreciation_disposal_account_id).trigger("change");
        }

        $('#product_type').select2().val(product_type).trigger("change");
        $('#is_service').select2().val(is_service).trigger("change");
    }

    // updateFinanceSection();
    function updateFinanceSection() {
        // var product_type = $('#product_type').val();
        // var is_service = $('#is_service').val();
        // $('.ledgers-div').hide();

        // if(product_type == 'products'){
        //     $('.fixed-asset-info').hide();
            
        //     if(is_service == 1){
        //         $('.inventory-div').hide();
        //     }else{
        //         $('.inventory-div').show();
        //     }
            
        //     $('.consumption-div').show();
        //     $('.sales-div').show();
        //     $('.service-div').show();
        //     $('.asset-div').hide();

        //     $('#inventory-title').html('Inventory Account');
        // }else if(product_type == 'fixed_asset'){
        //     $('.fixed-asset-info').show();
        //     $('.inventory-div').show();
        //     $('.consumption-div').hide();
        //     $('.sales-div').hide();
        //     $('.service-div').hide();
        //     $('.asset-div').hide();

        //     $('#inventory-title').html('Asset Account');
        // }else if(product_type == 'cwip'){
        //     $('.fixed-asset-info').show();
        //     $('.inventory-div').show();
        //     $('.consumption-div').hide();
        //     $('.sales-div').hide();
        //     $('.service-div').hide();
        //     $('.asset-div').show();

        //     $('#inventory-title').html('CWIP Account');
        // }
    }
</script>
@endsection