<table class="table table-striped table-bordered table-head datatable-exportable" id="dataTable"
data-table-name="RFP Requisition" border="1">
<thead>
	<tr class="text-center">
		<th width="5%">{{__('SL')}}</th>
		<th>{{__('Requisition No')}}</th>
		<th>{{__('Requisition Date')}}</th>
		<th>{{__('Department')}}</th>
		<th>{{__('Product Category')}}</th>
		<th>{{__('Approved By')}}</th>
		<th class="text-center">{{__('Action')}}</th>
	</tr>
</thead>
<tbody>
	@php
	$totalStockQty = 0;
	$totalReqQty = 0;
	$sl = 0;
	@endphp
	@if(isset($requisition[0]))
	@foreach($requisition as $key=> $values)
	@php
	$stockQty=0;
	foreach($values->items as $item){
		$stockQty += isset($item->product->relInventoryDetails)? collect($item->product->relInventoryDetails)->when(isset(auth()->user()->employee->as_unit_id),
			function($query){
				return $query->where('hr_unit_id',auth()->user()->employee->as_unit_id);
			})->sum('qty'):0;
	}

	@endphp

	@php
	$totalStockQty += $stockQty;
	$totalReqQty += $values->items->sum('qty');
	$sl++;
	@endphp
	<tr id="row{{$values->id}}">
		<td>{{$sl}}</td>
		<td><a href="javascript:void(0)" onclick="openModal({{$values->id}})"
			class="btn btn-link">{{$values->reference_no}}</a></td>
			<td>{{date('d-m-Y', strtotime($values->requisition_date))}}</td>
			<td>
				{{isset($values->relUsersList->employee->department->hr_department_name)?$values->relUsersList->employee->department->hr_department_name:''}}
			</td>

			<td>{{isset($values->items[0]->product->category->category->name)?$values->items[0]->product->category->category->name:''}}</td>
			<td>{{isset($values->approvedBy->name)?$values->approvedBy->name:''}}</td>

			<td class="text-center action">
				<div class="btn-group">
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
							<path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
						</svg>
					</button>
					<ul class="dropdown-menu">
						@if($stockQty >0)
						@can('confirm-delivery')
						@php
						$nonNotified = \App\Models\PmsModels\RequisitionItem::doesntHave('notification')
						->where('requisition_id', $values->id)
						->count();
						@endphp
						@if($nonNotified > 0)
						<li>
							<a href="{{route('pms.store-manage.requisition.items.list',$values->id)}}">{{ __('Show
						Notification')}}</a>
					</li>
					@endif

					<li><a href="{{route('pms.store-manage.store-requisition.delivery',$values->id)}}"
						title="Click Here To Confirm Delivery">{{ __('Confirm Delivery')}}</a>
					</li>
					@endif
					@endcan
					<li><a href="javascript:void(0)" title="Track Progress" class="trackingRequistionStatus"
						data-id="{{$values->id}}"><i class="la la-map"></i> {{ __('Track Progress')}}</a>
					</li>
				</ul>
			</div>

		</td>
	</tr>

	@endforeach
	@endif
</tbody>
</table>

<div class="py-2 col-md-12">
	@if(isset($requisition[0]))
	<ul class="searchPagination">
		{{$requisition->links()}}
	</ul>
	@endif
</div>