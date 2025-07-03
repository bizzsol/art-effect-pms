<tbody>
    <tr>
        <td class="text-center" colspan="19">
            <h1><strong>{{ $title }}</strong></h1>
        </td>
    </tr>
    <tr>
        <td class="text-center"><strong>SL</strong></td>
        <td class="text-center"><strong>Reference</strong></td>
        <td class="text-center"><strong>Requisition Date</strong></td>
        <td class="text-center"><strong>User Department</strong></td>
        <td class="text-center"><strong>Matarials Name</strong></td>
        <td class="text-center"><strong>Quantity</strong></td>
        <td class="text-center"><strong>Unit Price</strong></td>
        <td class="text-center"><strong>Total Amount</strong></td>
        <td class="text-center"><strong>Negotiation Savings</strong></td>
        <td class="text-center"><strong>Company</strong></td>
        <td class="text-center"><strong>RFP Number</strong></td>
        <td class="text-center"><strong>PO Number</strong></td>
        <td class="text-center"><strong>Supplier's Name</strong></td>
        <td class="text-center"><strong>Order Date</strong></td>
        <td class="text-center"><strong>PO Delivery Date</strong></td>
        <td class="text-center"><strong>Actual Delivery Date</strong></td>
        <td class="text-center"><strong>Delivery Location</strong></td>
        <td class="text-center"><strong>Assigned person</strong></td>
        <td class="text-center"><strong>Remarks</strong></td>
    </tr>

    @if(isset($requisitions[0]))
    @foreach($requisitions as $key => $requisition)
    @php
        $grn = false;
        if($requisition->purchaseOrders->count() > 0){
            foreach($requisition->purchaseOrders as $po){
                $grn = isset($po->purchaseOrder->relGoodReceiveNote->first()->created_at) ? $po->purchaseOrder->relGoodReceiveNote->first() : false;
            }
        };
    @endphp
        @if(isset($requisition->items[0]))
        @foreach($requisition->items as $item_key => $item)
        @php
            $purchaseOrderItem = false;
            if($requisition->purchaseOrders->count() > 0){
                foreach($requisition->purchaseOrders as $po){
                    if($po->purchaseOrder->relPurchaseOrderItems->count() > 0){
                        $purchaseOrderItem = $po->purchaseOrder->relPurchaseOrderItems->where('product_id', $item->product_id)->first();
                    }
                }
            }

            $warehouse = false;
            if(isset($grn->relGoodsReceivedItems[0])){
                if($grn->relGoodsReceivedItems->first()->relGoodsReceivedItemStockIn[0]){
                    $warehouse = $grn->relGoodsReceivedItems->first()->relGoodsReceivedItemStockIn->first()->relWarehouse;
                }
            }
        @endphp
        <tr>
            @if($item_key == 0)
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">{{ $key+1 }}</td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">{{ $requisition->reference_no }}</td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">{{ date('Y-m-d g:i a', strtotime($requisition->requisition_date)) }}</td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">{{ $requisition->relUsersList->employee->department->hr_department_name }}</td>
            @endif

            <td class="text-center">
                {{ $item->product->name }} {{ getProductAttributesFaster($item->product) }} {{ getProductAttributesFaster($item) }}
            </td>
            <td class="text-center">{{ isset($purchaseOrderItem->id) ? $purchaseOrderItem->qty.' '.$item->product->productUnit->unit_name : '-' }}</td>
            <td class="text-center">{{ isset($purchaseOrderItem->id) ? systemMoneyFormat($purchaseOrderItem->unit_price) : '-' }}</td>
            <td class="text-center">{{ isset($purchaseOrderItem->id) ? systemMoneyFormat($purchaseOrderItem->total_price) : '-' }}</td>
            <td class="text-center">-</td>

            @if($item_key == 0)
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->unit->hr_unit_short_name }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->requestProposalRequisition->pluck('relRequestProposal.reference_no')->implode(', ') }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->purchaseOrders->pluck('purchaseOrder.reference_no')->implode(', ') }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->purchaseOrders->pluck('purchaseOrder.relQuotation.relSuppliers.name')->implode(', ') }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->purchaseOrders->pluck('purchaseOrder.po_date')->implode(', ') }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->purchaseOrders->pluck('purchaseOrder.relQuotation.delivery_date')->implode(', ') }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $grn ? $grn->created_at : '-' }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $warehouse ? $warehouse->name : '' }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                {{ $requisition->assignedUser ? $requisition->assignedUser->name : '' }}
            </td>
            <td class="text-center" rowspan="{{ $requisition->items->count() }}">
                -
            </td>
            @endif
        </tr>
        @endforeach
        @endif
    @endforeach
    @endif
</tbody>