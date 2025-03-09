<table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
	<thead>
		<tr class="text-center">
			<th width="5%">{{__('SL')}}</th>
			<th>{{__('Requisition No')}}</th>
			<th>{{__('Req. Date')}}</th>
			<th>{{__('Product Category')}}</th>
			<th>{{__('Unit')}}</th>
			<th>{{__('Req. User Name')}}</th>
			<th>{{__('Department')}}</th>
			@if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Department-Head') || auth()->user()->hasRole('Purchase-Department'))
			<th>{{__('Attachment')}}</th>
			@endif
			<th class="text-center">{{__('Action')}}</th>
		</tr>
	</thead>
	<tbody>
		@if(count($requisitionData)>0)
		@foreach($requisitionData as $key=> $values)
		<tr id="row{{$values->id}}">
			<td class="text-center">{{$key+1}}</td>
			<td><a href="javascript:void(0)" onclick="openModal({{$values->id}})"  class="btn btn-link">{{$values->reference_no}}</a></td>
			<td>{{date('d-m-Y', strtotime($values->requisition_date))}}</td>
			<td>{{isset($values->items[0]->product->category->category->name)?$values->items[0]->product->category->category->name:''}}</td>
			<td>
				{{isset($values->relUsersList->employee->unit->hr_unit_short_name)?$values->relUsersList->employee->unit->hr_unit_short_name:''}}
			</td>
			<td>{{$values->relUsersList->name}}</td>

			<td>
				{{isset($values->relUsersList->employee->department->hr_department_name)?$values->relUsersList->employee->department->hr_department_name:''}}
			</td>

			@if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Department-Head') || auth()->user()->hasRole('Purchase-Department'))
			<td class="text-center">
				@if(!empty($values->attachment) && file_exists(public_path($values->attachment)))
				<a href="{{ url($values->attachment) }}" target="_blank" class="btn btn-xs btn-primary"><i class="las la-paperclip"></i>&nbsp;Attachment</a>
				@endif
			</td>
			@endif

			<td class="text-center action">
				<div class="btn-group">
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<span id="statusName{{$values->id}}">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
								<path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
							</svg>
						</span>
					</button>
					<ul class="dropdown-menu">

						<li><a class="convertToRfp" data-src="{{route('pms.rfp.convert.to.rfp')}}" data-id="{{$values->id}}" title="Prepare to RFP" >{{ __('Prepare To RFP')}}</a>
						</li>

						<li>
							<a target="__blank" href="{{route('pms.rfp.send.to.purchase',$values->id)}}" title="Direct Purchase">{{ __('Direct Purchase')}}
							</a>
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
	@if(count($requisitionData)>0)
	<ul class="searchPagination">
		{{$requisitionData->links()}}
	</ul>
	@endif
</div>

