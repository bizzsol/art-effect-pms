
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
						<form action="{{ route('pms.product-management.category.update', $category->id) }}
							" method="post">
							@csrf
							@method('PUT')
							<input type="hidden" name="product_type" id="product_type" value="{{ $category->is_fixed_asset == 1 ? 'fixed_asset' : ($category->is_cwip == 1 ? 'cwip' : 'products') }}">
							<input type="hidden" name="is_service" id="is_service" value="{{ $category->is_service }}">
							<div class="row">
								<div class="col-md-3">
									<p class="mb-1 font-weight-bold"><label for="code"><strong>{{ __('Code') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('code')? '<span class="text-danger text-capitalize">'. $errors->first('code').'</span>':'' !!}</p>
									<div class="input-group input-group-md mb-3 d-">
										<input type="text" readonly name="code" id="code" class="form-control rounded bg-white" aria-label="Large" placeholder="{{__('Category Code')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ $category->code }}">
									</div>
								</div>
								<div class="col-md-3">
									<p class="mb-1 font-weight-bold"><label for="parent"><strong>{{ __('Type') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('type')? '<span class="text-danger text-capitalize">'. $errors->first('type').'</span>':'' !!}</p>
									<div class="select-search-group input-group input-group-md mb-3 d-">
										<select name="type" id="type" class="form-control select2 types">
											<option value="uncommon" {{ ($category->type=='uncommon')?'selected':'' }}>Uncommon</option>
											<option value="common" {{ ($category->type=='common')?'selected':'' }}> Common</option>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<p class="mb-1 font-weight-bold"><label for="name"><strong>{{ __('Name') }}<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('name')? '<span class="text-danger text-capitalize">'. $errors->first('name').'</span>':'' !!}</p>
									<div class="input-group input-group-md mb-3 d-">
										<input type="text" name="name" id="name" class="form-control rounded" aria-label="Large" placeholder="{{__('Category Name')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ ($category->name)?$category->name: old('name') }}">
									</div>
								</div>

								@if($departments->count() > 0)
								<div class="col-md-12">
									<p class="mb-1 font-weight-bold"><label for="parent"><strong>{{ __('Departments') }}</strong>:</label> {!! $errors->has('hr_department_id')? '<span class="text-danger text-capitalize">'. $errors->first('hr_department_id').'</span>':'' !!}</p>
									<div class="select-search-group input-group input-group-md mb-3 d-">
										<select name="hr_department_id[]" id="hr_department_id" class="form-control select2 hr-departments" multiple>
											@foreach($departments as $key => $department)

											<option value="{{ $department->hr_department_id }}" {{ in_array($department->hr_department_id, $departmentsId) ? 'selected' : '' }}>{{ $department->hr_department_name}}</option>

											@endforeach
										</select>
									</div>
								</div>
								@endif

								<div class="col-md-12 mt-4">
                                    <div class="row pr-3">
                                        <div class="col-md-4 ledgers-div inventory-div">
											<label for="inventory_account_id"><strong><span id="inventory-title">Inventory Accounts</span>:<span class="text-danger">&nbsp;*</span></strong></label>
											<div class="input-group input-group-md mb-3 d-">
												<select name="inventory_account_id" id="inventory_account_id" class="form-control select-me rounded" data-selected="{{ $category->inventory_account_id }}">
													{!! $chartOfAccountsOptions !!}
												</select>
											</div>
										</div>

										<div class="col-md-4 ledgers-div consumption-div">
											<label for="cogs_account_id"><strong>{{ __('Consumption Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
											<div class="input-group input-group-md mb-3 d-">
												<select name="cogs_account_id" id="cogs_account_id" class="form-control select-me rounded" data-selected="{{ $category->cogs_account_id }}">
													{!! $chartOfAccountsOptions !!}
												</select>
											</div>
										</div>

										<div class="col-md-4 ledgers-div sales-div">
											<label for="sales_account_id"><strong>{{ __('Sales Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
											<div class="input-group input-group-md mb-3 d-">
												<select name="sales_account_id" id="sales_account_id" class="form-control select-me rounded" data-selected="{{ $category->sales_account_id }}">
													{!! $chartOfAccountsOptions !!}
												</select>
											</div>
										</div>

										<div class="col-md-4 ledgers-div asset-div">
											<label for="cwip_asset_account_id"><strong>{{ __('Asset Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
											<div class="input-group input-group-md mb-3 d-">
												<select name="cwip_asset_account_id" id="cwip_asset_account_id" class="form-control select-me rounded" data-selected="{{ $category->cwip_asset_account_id }}">
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
															<label for="depreciation_disposal_account_id"><strong>{{ __('Disposal Account') }}:<span class="text-danger">&nbsp;*</span></strong></label>
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

								<div class="col-md-12">
									<a class="btn btn-danger rounded pull-right " href="{{ url('pms/product-management/category') }}"><i class="la la-times"></i>&nbsp;{{ __('Close') }}</a>
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
<script type="text/javascript">
    $(document).ready(function() {
        $.each($('.select-me'), function(index, val) {
            $(this).select2().val($(this).attr('data-selected')).trigger("change");
        });
    });

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
</script>
@endsection