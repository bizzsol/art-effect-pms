
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
						<form action="{{ route('pms.requisition-explanations.update', $explanation->id) }}
							" method="post">
						@csrf
						@method('PUT')
							<div class="col-md-12">
								<p class="mb-1 font-weight-bold"><label for="explanation"><strong>Expalanation<span class="text-danger">&nbsp;*</span></strong></label> {!! $errors->has('explanation')? '<span class="text-danger text-capitalize">'. $errors->first('explanation').'</span>':'' !!}</p>
								<div class="select-search-group input-group input-group-md mb-3 d-">
									<input type="text" name="explanation" id="explanation" value="{{ old('explanation', $explanation->explanation) }}" placeholder="Write your explanation..." class="form-control">
								</div>
							</div>

							<div class="col-md-12">
								<a class="btn btn-danger rounded pull-right" href="{{ url('pms/requisition-explanations') }}"><i class="la la-times"></i>&nbsp;{{ __('Close') }}</a>
								<button type="submit" class="btn btn-success rounded pull-right mr-3"><i class="la la-save"></i>&nbsp;Update Explanation</button>
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
@endsection