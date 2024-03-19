@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
@include('yajra.css')
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

				</li>
			</ul><!-- /.breadcrumb -->
		</div>

		<div class="page-content">
			<div class="">
				<div class="panel panel-info">
					<div class="panel-body">
						<form action="{{ url('pms/inventory/inventory-summary') }}" method="get" accept-charset="utf-8">
							<div class="row">
								<div class="col-md-3 col-sm-6">
									<p class="mb-1 font-weight-bold"><label for="category_id">{{ __('Category')
											}}:</label></p>
									<div class="input-group input-group-md mb-3 d-">
										<select name="category_id" id="category_id" onchange="getSubCategory()"
											class="form-control rounded">
											<option value="0">{{ __('Select One') }}</option>
											@foreach($categories as $values)
											<option value="{{ $values->id }}" {{$category_id==$values->id ? 'selected' :
												''}}>{{ $values->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<p class="mb-1 font-weight-bold"><label for="sub_category_id">{{ __('Sub Category')
											}}:</label></p>
									<div class="input-group input-group-md mb-3 d-">
										<select name="sub_category_id" id="sub_category_id" onchange="getProduct()"
											class="form-control rounded">

										</select>
									</div>
								</div>
								<div class="col-md-3">
									<p class="mb-1 font-weight-bold"><label for="product_id">{{ __('Product')
											}}:</label></p>
									<div class="input-group input-group-md mb-3">
										<select class="form-control rounded" name="product_id" id="product_id">

										</select>
									</div>
								</div>

								<div class="col-md-3 col-sm-6">
									<p class="mb-1 font-weight-bold"><label for="searchRequisitonBtn"></label></p>
									<button type="submit" class="btn btn-sm btn-success mt-8"> <i
											class="las la-search"></i>Search</button>
									<a class="btn btn-sm btn-danger mt-8"
										href="{{ url('pms/inventory/inventory-summary') }}"> <i
											class="las la-times"></i>Reset</a>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="panel panel-info mt-2 p-2">
	                @include('yajra.datatable')
	            </div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="warehouseDetailModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Inventory Details</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="tableData">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection
@section('page-script')
@include('yajra.js')
<script>
	getSubCategory();
	function getSubCategory(){
		var category_id = $('#category_id').val();
		var sub_category_id = "{{$sub_category_id}}";
		$('#sub_category_id').html('<option value="0">Select One</option>');

		$.ajax({
			url: "{{ url('pms/inventory/inventory-summary') }}/"+category_id+"/get-products?sub-categories",
			type: 'GET',
			dataType: 'json',
			data: {},
		})
		.done(function(response) {
			var subCategories = '<option value="0">Select One</option>';
			$.each(response, function(index, val) {
				var selected = (sub_category_id == val.id ? 'selected' : '');
				subCategories += '<option value="'+(val.id)+'" '+(selected)+'>'+(val.name)+'</option>';
			});

			$('#sub_category_id').html(subCategories);
			getProduct();
		});
	}

	function getProduct(){
		var category_id = $('#category_id').val();
		var sub_category_id = $('#sub_category_id').val();
		var product_id = "{{$product_id}}";
		$('#product_id').html('<option value="0">Select One</option>');

		$.ajax({
			url: "{{ url('pms/inventory/inventory-summary') }}/"+category_id+"/get-products?sub_category_id="+sub_category_id,
			type: 'GET',
			dataType: 'json',
			data: {},
		})
		.done(function(response) {
			var product = '<option value="0">Select One</option>';
			$.each(response.products, function(index, val) {
				var selected = '';
				if(product_id == val.id){
					selected = 'selected';
				}
				product += '<option value="'+(val.id)+'" '+(selected)+'>'+(val.name)+' ('+(response.attributes[val.id])+')</option>';
			});

			$('#product_id').html(product);
		});
	}

	function openModal(product_id) {
		$('#tableData').load('{{URL::to('pms/inventory/warehouse-wise-product-inventory-details')}}/'+product_id);
		$('#warehouseDetailModal').modal('show');
	}
</script>
@endsection