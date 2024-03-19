<div class="col-md-12">
    <div class="panel panel-info">
        <div class="col-lg-12 invoiceBody">
            <div class="invoice-details mt25 row">
                <div class="well col-6">
                    <ul class="list-unstyled mb0">
                        <li><strong>{{__('Name') }} :</strong> {{isset($requisitionDelivery->relRequisition->relUsersList->name)?$requisitionDelivery->relRequisition->relUsersList->name:''}}</li>

                        <li><strong>{{__('Unit')}} :</strong> {{isset($requisitionDelivery->relRequisition->relUsersList->employee->unit->hr_unit_short_name)?$requisitionDelivery->relRequisition->relUsersList->employee->unit->hr_unit_short_name:''}}</li>
                        <li><strong>{{__('Location')}} :</strong> {{isset($requisitionDelivery->relRequisition->relUsersList->employee->location->hr_location_name)?$requisitionDelivery->relRequisition->relUsersList->employee->location->hr_location_name:''}}</li>
                        <li><strong>{{__('Department')}} :</strong> {{isset($requisitionDelivery->relRequisition->relUsersList->employee->department->hr_department_name)?$requisitionDelivery->relRequisition->relUsersList->employee->department->hr_department_name:''}}</li>
                    </ul>
                </div>
                <div class="col-6">
                    <ul class="list-unstyled mb0 pull-right">

                        <li><strong>{{__('Date')}} :</strong> {{date('d-m-Y',strtotime($requisitionDelivery->relRequisition->requisition_date))}}</li>
                        <li><strong>{{__('Reference No')}}:</strong> {{$requisitionDelivery->relRequisition->reference_no}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>UOM</th>
                        <th>Stock Qty</th>
                        <th>Delivery Qty</th>
                    </tr>
                </thead>
                <tbody>

                    @if(isset($requisitionDelivery))
                    @php
                    $totalDeliveryQty = 0;
                    $totalStockQty = 0;
                    $stockQty = 0;
                    @endphp
                    @foreach($requisitionDelivery->relDeliveryItems as $key=>$item)
                    @php
                        $stockQty = collect($item->product->relInventoryDetails)->where('hr_unit_id',auth()->user()->employee->as_unit_id)->sum('qty');
                    @endphp
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td>{{isset($item->product->category->name)?$item->product->category->name:''}}</td>
                        <td>{{isset($item->product->name)?$item->product->name:''}} {{ getProductAttributes($item->product->id) }}</td>
                        <td>{{isset($item->product->productUnit->unit_name)?$item->product->productUnit->unit_name:''}}</td>
                        <td class="text-center">{{$stockQty}}</td>
                        <td class="text-center">{{round($item->delivery_qty)}}</td>
                    </tr>
                    @php
                    $totalDeliveryQty += $item->delivery_qty;
                    $totalStockQty += $stockQty;
                    @endphp

                    @endforeach
                    @endif

                    <tr>
                        <td colspan="4" class="text-right">Total</td>
                        <td class="text-center">{{$totalStockQty}}</td>
                        <td class="text-center">{{$totalDeliveryQty}}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>