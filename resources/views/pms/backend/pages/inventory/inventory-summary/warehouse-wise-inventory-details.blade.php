@php
$stockQty = collect($product->relInventoryDetails);
$stockQty = (isset(auth()->user()->employee->as_unit_id) ? $stockQty->where('hr_unit_id',auth()->user()->employee->as_unit_id) : $stockQty);
$stockQty = $stockQty->sum('qty');
@endphp
<div class="col-md-12">
    <div class="panel panel-info">
        <div class="col-lg-12 invoiceBody">
            <div class="invoice-details mt25 row">
                <div class="well col-12">
                    <ul class="list-unstyled mb0">
                        <li><strong>{{__('Category') }} :</strong> {{isset($product->category->name)?$product->category->name:''}}</li>

                        <li><strong>{{__('Product Name')}} :</strong> {{$product->name}} {{ getProductAttributes($product->id) }}</li>
                        <li><strong>{{__('Total Qty')}} :</strong>{{$stockQty}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="table-responsive style-scroll">
            <table class="table table-striped table-bordered miw-500 dac_table" cellspacing="0" width="100%" id="dataTable">
                <thead>
                    <tr class="text-center">
                        <th width="5%">{{__('SL')}}</th>
                        <th>{{__('Warehouses')}}</th>
                        <th>{{__('Unit Price')}}</th>
                        <th>{{__('Qty')}}</th>
                        <th>{{__('Price')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($product->relInventoryDetails))
                    @foreach($product->relInventoryDetails()
                    ->whereIn('warehouse_id', $warehouse_ids)
                    ->when(isset(auth()->user()->employee->as_unit_id) && auth()->user()->employee->as_unit_id > 0, function($query){
                        return $query->where('hr_unit_id', auth()->user()->employee->as_unit_id);
                    })
                    ->get() as $key=> $values)
                    <tr>
                        <td class="text-center">
                            {{$key+1}}
                        </td>
                        <td>{{isset($values->relWarehouse->name)?$values->relWarehouse->name:''}}</td>
                        <td class="text-right">{{number_format($values->unit_price,2)}}</td>
                        <td class="text-center">{{$values->qty}}</td>
                        <td class="text-right">{{number_format($values->unit_price*$values->qty,2)}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-center">{{$stockQty}}</td>
                        <td class="text-right">{{number_format($product->relInventorySummary->unit_price*$stockQty,2)}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>