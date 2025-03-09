<table class="table table-striped table-bordered table-head datatable-exportable" cellspacing="0" width="100%" id="dataTable" data-table-name="User Requisition">
	<thead>
		<tr>
			<th width="5%">{{__('SL')}}</th>
			
			<th>{{__('Requisition No')}}</th>
			<th>{{__('Requisition Date')}}</th>
			<th>{{__('Requisition By')}}</th>
			<th>{{__('Unit ')}}</th>

			@if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Department-Head') || auth()->user()->hasRole('Purchase-Department'))
			<th>{{__('Attachment')}}</th>
			@endif
			
			<th class="text-center">{{__('Status')}}</th>
			<th class="text-center">{{__('Actions')}}</th>
		</tr>
	</thead>
	<tbody>
		@if(count($requisitionData)>0)
		@foreach($requisitionData as $key=> $values)
		<?php 
			$createdDate=date('Y-m-d',strtotime($values->created_at));
			$requisitionDate=date('Y-m-d',strtotime($values->requisition_date));
		?>
	<tr id="removeRow{{$values->id}}" class="{{ ($createdDate==$requisitionDate)?'':'bg-warning color-white' }}">
		<td class="text-center">{{ ($requisitionData->currentpage()-1) * $requisitionData->perpage() + $key + 1 }}</td>
		
		
		<td><a href="javascript:void(0)" data-src="{{route('pms.requisition.list.view.show',$values->id)}}"  class="btn btn-link showRequistionDetails">{{$values->reference_no}}</a></td>
		<td>{{date('d-m-Y', strtotime($values->requisition_date))}}</td>


		<td>{{isset( $values->relUsersList->name)? $values->relUsersList->name :''}}</td>

		<td>
			{{isset($values->relUsersList->employee->unit->hr_unit_short_name)?$values->relUsersList->employee->unit->hr_unit_short_name:''}}
		</td>

		@if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Department-Head') || auth()->user()->hasRole('Purchase-Department'))
		<td class="text-center">
			@if(!empty($values->attachment) && file_exists(public_path($values->attachment)))
			<a href="{{ url($values->attachment) }}" target="_blank" class="btn btn-xs btn-primary"><i class="las la-paperclip"></i>&nbsp;Attachment</a>
			@endif
		</td>
		@endif

		<td class="text-center">
			@if($values->status==0)
			{{ __('Pending')}}
			@elseif($values->status==1)
			{{ __('Acknowledge')}}
			@else
			{{ __('Halt')}}
			@endif
		</td>

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

					@if($values->is_send_to_rfp=='no')
					@if($values->status !=0)
					@can('pending')
					<li><a href="javascript:void(0)" title="Click Here To Pending" class="requisitionApprovedBtn" data-id="{{$values->id}}"  data-data="pending" data-status="0"><i class="la la-pause"></i> {{ __('Pending')}}</a>
					</li>
					@endcan
					@endif

					@if($values->status !=1)
					<li><a href="{{ route('pms.requisition.requisition.edit',$values->id) }}" title="Click Here To Edit"><i class="la la-edit"></i> {{ __('Edit')}}</a>
					</li>
					@can('requisition-acknowledge')
					<li><a href="javascript:void(0)" title="Click Here To Acknowledge" class="requisitionApprovedBtn" data-id="{{$values->id}}" data-data="acknowledged" data-status="1"><i class="la la-check"></i> {{ __('Acknowledge')}}</a>
					</li>
					@endcan
					@endif

					@can('halt')
					@if($values->status != 2)
					<li><a href="javascript:void(0)" title="Click Here To Halt" class="requisitionApprovedBtn"  data-data="halt" data-id="{{$values->id}}" data-status="2"><i class="la la-ban"></i> {{ __('Halt')}}</a>
					</li>
					@endif
					@endcan
					@endif
					@can('send-to-rfp')
					<li>
						<a class="sendToPurchaseDepartment" data-src="{{route('pms.store-manage.send.to.purchase.department')}}" data-id="{{$values->id}}"  title="Send To procurement ">{{ __('Send To Procurement ')}}
						</a>
					</li>
					@endcan
					<li><a href="javascript:void(0)" title="Tracking Requisition" onclick="trackingRequistionStatus('{{$values->id}}')"><i class="la la-map"></i> {{ __('Track Progress')}}</a>
					</li>

				</ul>
			</div>
		</td>
		
	</tr>
	@endforeach
	@endif
</tbody>

</table>
<div class="p-3">
	@if(count($requisitionData)>0)
	<ul class="searchPagination">
		{{$requisitionData->links()}}
	</ul>
	@endif
</div>