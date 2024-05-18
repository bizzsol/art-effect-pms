<div class="col-md-12">
    <div class="panel panel-info">
        <div class="col-lg-12 invoiceBody">
            <div class="invoice-details mt25 row">
                <div class="well col-6">
                    <ul class="list-unstyled mb0">
                        <li><strong>{{__('Name') }}
                                :</strong> {{isset($requisition->relUsersList->name)?$requisition->relUsersList->name:''}}
                        </li>

                        <li><strong>{{__('Unit')}}
                                :</strong> {{isset($requisition->relUsersList->employee->unit->hr_unit_short_name)?$requisition->relUsersList->employee->unit->hr_unit_short_name:''}}
                        </li>
                        <li><strong>{{__('Location')}}
                                :</strong> {{isset($requisition->relUsersList->employee->location->hr_location_name)?$requisition->relUsersList->employee->location->hr_location_name:''}}
                        </li>
                        <li><strong>{{__('Department')}}
                                :</strong> {{isset($requisition->relUsersList->employee->department->hr_department_name)?$requisition->relUsersList->employee->department->hr_department_name:''}}
                        </li>
                    </ul>
                </div>
                <div class="col-6">
                    <ul class="list-unstyled mb0 pull-right">

                        <li><strong>{{__('Date')}}
                                :</strong> {{date('d-m-Y',strtotime($requisition->requisition_date))}}</li>
                        <li><strong>{{__('Reference No')}}:</strong> {{$requisition->reference_no}}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            @if($requisition->projectTask)
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr class="text-center">
                        <th>Project</th>
                        <th>Phase</th>
                        <th>Milestone</th>
                        <th>Task</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ isset($requisition->projectTask->subDeliverable->deliverable->project)?$requisition->projectTask->subDeliverable->deliverable->project->name:'' }}</td>
                        <td>{{ isset($requisition->projectTask->subDeliverable->deliverable)?$requisition->projectTask->subDeliverable->deliverable->name:'' }}</td>
                        <td>{{ isset($requisition->projectTask->subDeliverable)?$requisition->projectTask->subDeliverable->name:'' }}</td>
                        <td>{{ isset($requisition->projectTask)?$requisition->projectTask->name:'' }}</td>

                    </tr>
                    </tbody>
                </table>
            @endif
            <table class="table table-bordered table-hover">
                <thead>
                <tr class="text-center">
                    <th>SL</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Attributes</th>
                    <th>UOM</th>
                    @can('requisition-acknowledge')
                        <th>Stock Qty</th>
                    @endcan
                    <th>Requisition Qty</th>
                    @if($requisition->status==1)
                        <th>Approved Qty</th>
                    @endif
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Estimated Amount</th>
                </tr>
                </thead>

                <tbody>
                @php
                    $total_stock_qty = 0;
                    $total_requisition_qty = 0;
                    $total_approved_qty = 0;
                    $totalEstimation = 0;
                @endphp
                @forelse($requisition->items as $key=>$item)
                @php
                    $totalEstimation += ($item->unit_price*($requisition->status == 1 ? $item->qty : $item->requisition_qty));
                @endphp
                    <tr>
                        <td class="text-center">{{$key+1}}</td>
                        <td>{{isset($item->product->category->name)?$item->product->category->name:''}}</td>
                        <td>{{isset($item->product->name)?$item->product->name:''}}
                            ( {{isset($item->product->sku)?$item->product->sku:''}}
                            ) {{ getProductAttributesFaster($item->product) }}</td>
                        <td>{{ getProductAttributesFaster($item) }}</td>
                        <td>{{ isset($item->product->productUnit->unit_name)?$item->product->productUnit->unit_name:'' }}</td>
                        @can('requisition-acknowledge')
                            <td class="text-center">{{isset($item->product->relInventorySummary->qty)?$item->product->relInventorySummary->qty:0}}</td>
                        @endcan
                        <td class="text-center">{{number_format($item->requisition_qty,0)}}</td>
                        @if($requisition->status==1)
                            <td class="text-center">{{$item->qty}}</td>
                        @endif
                        <td class="text-right">{{ systemMoneyFormat($item->unit_price) }}</td>
                        <td class="text-right">{{ systemMoneyFormat($item->unit_price*($requisition->status == 1 ? $item->qty : $item->requisition_qty)) }}</td>
                    </tr>
                    @can('requisition-acknowledge')
                        @php

                            $total_stock_qty += isset($item->product->relInventorySummary->qty)?$item->product->relInventorySummary->qty:0;

                        @endphp
                    @endcan
                    @php
                        $total_requisition_qty += $item->requisition_qty;
                        $total_approved_qty += $item->qty;
                    @endphp
                @empty
                @endforelse
                </tbody>
            </table>

            {{-- @if(auth()->user()->hasRole('Department-Head') || auth()->user()->hasRole('Accounts') || auth()->user()->hasRole('Management')) --}}
                <div>
                    <strong>Estimated Total Amount:</strong>&nbsp;{{systemMoneyFormat($totalEstimation)}}
                    BDT
                </div>
            {{-- @endif --}}

            <div>
                <strong> Explanations: </strong>
                {{ !empty($requisition->explanations) ? implode(', ', json_decode($requisition->explanations, true)) : '' }}
            </div>
            <div>
                <strong> Notes: </strong>
                {{$requisition->remarks}}
            </div>
            @if($requisition->status==2 && !empty($requisition->admin_remark))
                <div>
                    <strong> Holding Reason: </strong>
                    {!!$requisition->admin_remark!!}
                </div>
            @endif
        </div>
    </div>
</div>