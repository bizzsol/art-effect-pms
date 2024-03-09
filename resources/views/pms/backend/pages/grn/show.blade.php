@if(isset($grn))

<div class="row">

<?php 
    $TS = systemDoubleValue($grn->relPurchaseOrder->relQuotation->relSuppliers->SupplierRatings->sum('total_score'),2);
    $TC = $grn->relPurchaseOrder->relQuotation->relSuppliers->SupplierRatings->count();

    $totalScore = isset($TS)?$TS:0;
    $totalCount = isset($TC)?$TC:0;
?>

<div class="col-md-12">
    <div class="panel panel-info">

        <div class="col-lg-12 invoiceBody">
            <div class="invoice-details mt25 row">

                <div class="well col-6">
                    <ul class="list-unstyled mb0">
                        <li>
                            <div class="ratings">
                                <a href="{{route('pms.supplier.profile',$grn->relPurchaseOrder->relQuotation->relSuppliers->id)}}" target="_blank"><span>Rating:</span></a> {!!ratingGenerate($totalScore,$totalCount)!!}
                            </div>
                            <h5 class="review-count"></h5>
                        </li>
                        <li><strong>{{__('Supplier') }} :</strong> {{isset($grn->relPurchaseOrder->relQuotation->relSuppliers->name)?$grn->relPurchaseOrder->relQuotation->relSuppliers->name:''}}</li>
                        <li><strong>{{__('Email')}} :</strong> {{isset($grn->relPurchaseOrder->relQuotation->relSuppliers->email)?$grn->relPurchaseOrder->relQuotation->relSuppliers->email:''}}</li>
                        <li><strong>{{__('Phone')}} :</strong> {{isset($grn->relPurchaseOrder->relQuotation->relSuppliers->phone)?$grn->relPurchaseOrder->relQuotation->relSuppliers->phone:''}}</li>
                        <li><strong>{{__('Address')}}:</strong> {{isset($grn->relPurchaseOrder->relQuotation->relSuppliers->address)?$grn->relPurchaseOrder->relQuotation->relSuppliers->address:''}}</li>

                    </ul>
                </div>
                <div class="col-6">
                    <ul class="list-unstyled mb0 pull-right">
                        <li><strong>{{__('Date')}} :</strong> {{date('d-m-Y',strtotime($grn->received_date))}}</li>
                        <li><strong>{{__('Gate-In Ref: No')}}:</strong> {{$grn->reference_no}}</li>
                        <li><strong>{{__('GRN Ref: No')}}:</strong> {{$grn->grn_reference_no}}</li>
                        <li><strong>{{__('Challan No.')}}:</strong> {{$grn->challan}}</li>
                        <li><strong>{{__('Receive Qty.')}}:</strong> {{$grn->relGoodsReceivedItems->sum('qty')}}</li>
                        <li><strong>{{__('Receive Status.')}}:</strong> <span class="capitalize"> {{$grn->received_status}}</span></li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th>SL</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>UOM</th>
                        @if(!auth()->user()->hasRole('Gate Permission'))
                        <th>Unit Price ({{ isset($grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code)?$grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code:'' }})</th>
                        @endif
                        <th>Qty</th>
                        @if(!auth()->user()->hasRole('Gate Permission'))
                        <th>Price ({{ isset($grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code)?$grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code:'' }})</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($grn->relGoodsReceivedItems as $key=>$item)
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td>{{isset($item->relProduct->category->name)?$item->relProduct->category->name:''}}</td>
                        <td>{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}</td>
                        <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}</td>
                        @if(!auth()->user()->hasRole('Gate Permission'))
                        <td class="text-right"> {{systemMoneyFormat($item->unit_amount)}}</td>
                        @endif
                        <td class="text-center">{{systemDoubleValue($item->qty,2)}}</td>
                        @if(!auth()->user()->hasRole('Gate Permission'))
                        <td class="text-right">{{systemMoneyFormat($item->sub_total)}}</td>
                        @endif
                    </tr>
                    @endforeach

                    <tr>
                        <td colspan="4" class="text-right">Total</td>
                        @if(!auth()->user()->hasRole('Gate Permission'))
                        <td class="text-right">{{systemMoneyFormat($grn->relGoodsReceivedItems->sum('unit_amount'))}}</td>
                        @endif
                        <td class="text-center">{{systemDoubleValue($grn->relGoodsReceivedItems->sum('qty'),2)}}</td>
                        @if(!auth()->user()->hasRole('Gate Permission'))
                        <td class="text-right">{{systemMoneyFormat($grn->relGoodsReceivedItems->sum('sub_total'))}}</td>
                        @endif
                    </tr>
                    @if(!auth()->user()->hasRole('Gate Permission'))
                    <tr>
                        <td colspan="6" class="text-right">(+) Vat</td>
                        <td class="text-right">{{systemMoneyFormat($grn->vat)}}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total Amount</strong></td>
                        <td class="text-right"><strong>{{systemMoneyFormat($grn->gross_price)}}</strong></td>
                    </tr>
                    @endif
                </tbody>
            </table>

        </div>
        <div class="form-group">
            <label for="remarks"><strong>Notes</strong>:</label>
            <span>{!! $grn->note?$grn->note:'' !!}</span>
        </div>

    </div>
</div>

</div>
@endif