
@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)

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
						<div class="row">
							<div class="col-md-12">
								<form action="{{ route('pms.product-management.standard-costs.store') }}" method="post" >
								@csrf
									<div class="form-group row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="name"><strong>Name</strong></label>
												<input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Write Standard Cost Name" class="form-control">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="description"><strong>Description</strong></label>
												<input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Write Standard Cost Description" class="form-control">
											</div>
										</div>
										<div class="col-md-2 pt-4 pr-0">
											<button type="submit" class="mt-2 btn btn-success rounded btn-block pull-right mr-3"><i class="la la-save"></i>&nbsp;{{ __('Save Standard Cost') }}</button>
										</div>
										<div class="col-md-1 pt-4 pl-0">
											<a class="mt-2 btn btn-danger rounded btn-block" href="{{ url('pms/product-management/standard-costs') }}"><i class="la la-times"></i>&nbsp;{{ __('Cancel') }}</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection