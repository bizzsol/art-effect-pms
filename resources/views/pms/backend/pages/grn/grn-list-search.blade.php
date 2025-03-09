 <div class="table-responsive">
    <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
        <thead>
            <tr class="text-center">
                <th>{{__('SL')}}</th>
                <th>{{__('P.O Reference')}}</th>
                <th>{{__('P.O Date')}}</th>
                <th>{{__('Challan No')}}</th>
                <th>{{__('GRN Reference')}}</th>
                <th>{{__('GRN Date')}}</th>
                <th>{{__('Po Qty')}}</th>
                <th>{{__('Received Qty')}}</th>
                <th>{{__('Receive Status')}}</th>
                @can('quality-ensure')
                <th>{{__('Quality Ensure')}}</th>
                @endcan
            </tr>
        </thead>
        <tbody>

            @if(count($purchaseOrder)>0)
            @foreach($purchaseOrder as $pkey=> $po)
            @if($po->relGoodReceiveNote->count() > 0)
            @foreach($po->relGoodReceiveNote as $rkey => $grn)
            @if($grn->received_status==$received_status)
            <tr>
                <td class="text-center">{{ ($purchaseOrder->currentpage()-1) * $purchaseOrder->perpage() + $pkey + 1 }}</td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-link showGateInPODetails" data-src="{{route('pms.purchase.order-list.show',$grn->relPurchaseOrder->id)}}" data-title="Purchase Order Details">{{isset($grn->relPurchaseOrder->reference_no)?$grn->relPurchaseOrder->reference_no:''}}
                    </a>
                </td>
                <td>
                    {{date('d-M-Y',strtotime($grn->relPurchaseOrder->po_date))}}
                </td>
                <td>{{$grn->challan}}</td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-link showGateInPODetails" data-src="{{route('pms.grn.grn-process.show',$grn->id)}}" data-title="GRN Details">{{$grn->reference_no}}
                    </a>
                </td>
                <td>
                    {{date('d-M-Y',strtotime($grn->received_date))}}
                </td>
                <td class="text-center">{{isset($grn->relPurchaseOrder->relPurchaseOrderItems)?$grn->relPurchaseOrder->relPurchaseOrderItems->sum('qty'):''}}</td>
                <td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>
                <td class="text-center">
                    @if($grn->received_status == 'partial')
                    <a class="btn btn-warning btn-xs">Partial Received</a>
                    @elseif($grn->received_status == 'full')
                    <a class="btn btn-success btn-xs">Full Received</a>
                    @else
                    <a class="btn btn-dark btn-xs">{{ ucwords($grn->received_status) }}</a>
                    @endif
                </td>
                @can('quality-ensure')
                <td class="text-center">
                 @if($grn->relGoodsReceivedItems()->whereIn('quality_ensure',['pending'])->count() > 0)
                 <a href="{{route('pms.quality.ensure.check',$grn->id)}}" title="Quality Ensure" class="btn btn-success btn-sm"><i class="las la-check-circle"> {{ __('Quality Ensure')}}</i></a>
                 @else
                 @if(!isSupplierRating('goods_received_notes',$grn->id, 'qc') && in_array((isset($grn->relPurchaseOrder->relQuotation->type) ? $grn->relPurchaseOrder->relQuotation->type : ''), ['manual','online']))
                 <a href="{{url('pms/supplier/rating/'.$grn->relPurchaseOrder->relQuotation->supplier_id.'/'.$grn->id)}}?table=goods_received_notes&field={{$grn->id}}&type=qc" class="btn btn-info btn-sm">Ratings</a>
                 @endif
                 @endif
             </td>
             @endcan
         </tr>
         @elseif(empty($received_status))
         <tr>
            @if($rkey == 0)
            <td class="text-center" rowspan="{{ $po->relGoodReceiveNote->count() }}">{{ ($purchaseOrder->currentpage()-1) * $purchaseOrder->perpage() + $pkey + 1 }}</td>

            <td rowspan="{{ $po->relGoodReceiveNote->count() }}">
                <a href="javascript:void(0)" class="btn btn-link showGateInPODetails" data-src="{{route('pms.purchase.order-list.show',$grn->relPurchaseOrder->id)}}" data-title="Purchase Order Details">{{$grn->relPurchaseOrder->reference_no}}
                </a>
            </td>
            <td rowspan="{{ $po->relGoodReceiveNote->count() }}">
                {{date('d-M-Y',strtotime($po->po_date))}}
            </td>
            <td rowspan="{{ $po->relGoodReceiveNote->count() }}">{{$grn->challan}}</td>
            @endif
            <td>
                <a href="javascript:void(0)" class="btn btn-link showGateInPODetails" data-src="{{route('pms.grn.grn-process.show',$grn->id)}}" data-title="GRN Details">{{$grn->reference_no}}
                </a>
            </td>

            <td>
                {{date('d-M-Y',strtotime($grn->received_date))}}
            </td>

            @if($rkey == 0)
            <td class="text-center" rowspan="{{ $po->relGoodReceiveNote->count() }}">
                {{$po->relPurchaseOrderItems->sum('qty')}}
            </td>
            @endif

            <td class="text-center">{{$grn->relGoodsReceivedItems->sum('qty')}}</td>

            <td class="text-center">
                @if($grn->received_status == 'partial')
                <a class="btn btn-warning btn-xs">Partial Received</a>
                @elseif($grn->received_status == 'full')
                <a class="btn btn-success btn-xs">Full Received</a>
                @else
                <a class="btn btn-dark btn-xs">{{ ucwords($grn->received_status) }}</a>
                @endif
            </td>
            @can('quality-ensure')
            <td class="text-center">
             @if($grn->relGoodsReceivedItems()->whereIn('quality_ensure',['pending'])->count() > 0)
             <a href="{{route('pms.quality.ensure.check',$grn->id)}}" title="Quality Ensure" class="btn btn-success btn-sm"><i class="las la-check-circle"> {{ __('Quality Ensure')}}</i></a>
             @else
             @if(!isSupplierRating('goods_received_notes',$grn->id, 'qc') && in_array((isset($grn->relPurchaseOrder->relQuotation->type) ? $grn->relPurchaseOrder->relQuotation->type : ''), ['manual','online']))
             <a href="{{url('pms/supplier/rating/'.$grn->relPurchaseOrder->relQuotation->supplier_id.'/'.$grn->id)}}?table=goods_received_notes&field={{$grn->id}}&type=qc" class="btn btn-info btn-sm">Ratings</a>
             @endif
             @endif
         </td>
         @endcan
       </tr>
       @endif
       @endforeach
       @endif
       @endforeach
       @endif
   </tbody>
</table>
<div class="col-12 py-2">
    @if(count($purchaseOrder)>0)
    <ul class="searchPagination">
        {{$purchaseOrder->links()}}
    </ul>

    @endif
</div>
</div>