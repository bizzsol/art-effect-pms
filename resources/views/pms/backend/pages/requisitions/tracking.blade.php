
<div class="card card-timeline px-2 border-none">
	@if($requisition->requisitionTracking->count() > 0)
	@php 
		$note = ''; 
		$tracking = [
			'la la-clock-o' => 'Pending',
			'las la-check' => 'Approved',
			'las la-spinner' => 'Processing',
			'las la-file-invoice' => 'PO-Issue',
			'las la-truck' => 'Delivered',
			'las la-receipt' => 'Received'
		];

		$tracking_array = [];
		foreach($requisition->requisitionTracking->groupBy('status') as $key => $values){
			if(isset($values[0]->status) && $values[0]->status == 'halt'){
				$note = (isset($values[0]->note));
			}
			array_push($tracking_array, ucfirst(isset($values[0]->status) ? $values[0]->status : ''));
		}
	@endphp
	<ul class="bs4-order-tracking">
		@if($requisition->status == 2)
			<li class="step active">
				<div class="bg-danger"><i class="la la-ban"></i></div> <span class="text-danger">Halt</span>
			</li>
		@else
			@foreach($tracking as $key => $status)
			@php
				$partial = false;
				if($status == "PO-Issue"){
					$partial = ($requisition->items->sum('purchase_qty') > 0 && $requisition->items->sum('qty') > $requisition->items->sum('purchase_qty') ? true : false);
				}elseif($status == "Delivered"){
					$partial = ($requisition->items->sum('delivery_qty') > 0 && $requisition->items->sum('purchase_qty') > $requisition->items->sum('delivery_qty') ? true : false);
				}
			@endphp
				<li class="step {{(in_array($status, $tracking_array)) ? 'active' : '' }}">
					<div><i class="{{$key}}"></i></div>&nbsp;{{$status.' '.($partial ? '(Partial)': '')}}
				</li>
				@if($status == 'Processing')
					@php
						$csApproved = \App\Models\PmsModels\Quotations::where('is_approved', 'approved')
						->whereHas('relRequestProposal.requestProposalRequisition', function($query) use($requisition){
							return $query->where('requisition_id', $requisition->id);
						})
						->count();
						$csCancelled = \App\Models\PmsModels\Quotations::where('is_approved', 'halt')
						->whereHas('relRequestProposal.requestProposalRequisition', function($query) use($requisition){
							return $query->where('requisition_id', $requisition->id);
						})
						->count();

						$cancelled = ($csApproved > 0 ? false : ($csCancelled > 0 ? true : false));
					@endphp
					@if($cancelled)
						<li class="step active">
							<div class="bg-danger"><i class="la la-ban"></i></div> <span class="text-danger">Cancelled</span>
						</li>
						@break
					@endif
				@elseif($status == 'PO-Issue')
					@php
						$poCancelled = \App\Models\PmsModels\Purchase\PurchaseOrder::whereHas('purchaseOrderRequisitions',  function($query) use($requisition){
							return $query->where('requisition_id', $requisition->id);
						})->where('is_send', 'halt')->count();
					@endphp
					@if($poCancelled > 0)
						<li class="step active">
							<div class="bg-danger"><i class="la la-ban"></i></div> <span class="text-danger">Cancelled</span>
						</li>
						@break
					@endif
				@endif
			@endforeach
		@endif
	</ul>

	{!! !empty($note) ? '<h5 class="text-center"><b>Notes: </b>'.$note.'</h5>' : '' !!}
	@endif
</div>