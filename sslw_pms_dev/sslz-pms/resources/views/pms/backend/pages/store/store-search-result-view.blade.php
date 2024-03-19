<table class="table table-striped table-bordered table-head datatable-exportable" id="dataTable" data-table-name="Store Search" border="1">
	<thead>
		<tr class="text-center">
			<tr class="text-center">
				<th width="5%">{{__('SL')}}</th>
				<th>{{__('Requisition No')}}</th>
				<th>{{__('Requisition Date')}}</th>
				<th>{{__('Department')}}</th>
				<th>{{__('Product Category')}}</th>
				<th>{{__('Approved By')}}</th>
				<th class="text-center">{{__('Action')}}</th>
			</tr>
		</tr>
	</thead>
	<tbody id="viewResult">
		@if(count($requistionData)>0)
		@foreach($requistionData as $key=> $values)

		<tr id="row{{$values->id}}" >
			<td class="text-center">{{$key+1}}</td>
			<td><a href="javascript:void(0)" onclick="openModal({{$values->id}})"  class="btn btn-link">{{$values->reference_no}}</a></td>
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
						@can('confirm-delivery')
						@if(requisitionHasStock($values->id))
						<li><a href="{{route('pms.store-manage.store-requisition.delivery',$values->id)}}" title="Click Here To Confirm Delivery" >{{ __('Confirm Delivery')}}</a>
						</li>
						@endif
						@endcan

						@can('send-to-rfp')
						@if($values->is_send_to_rfp=='no')
						<li>
							<a class="sendToPurchaseDepartment" data-src="{{route('pms.store-manage.send.to.purchase.department')}}" data-id="{{$values->id}}"  title="Send To Procurement">{{ __('Send To Procurement')}}
							</a>
						</li>
						@endif
						@endcan
					</ul>
				</div>
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>

</table>
<div class="py-2 col-md-12">
	@if(count($requistionData)>0)
	<ul  class="searchPagination">
		{{$requistionData->links()}}
	</ul>
	@endif
</div>