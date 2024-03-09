<table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
    <thead>
        <tr class="text-center">
            <th width="5%">{{__('SL')}}</th>
            <th>{{__('Date')}}</th>
            <th>{{__('Delivery Date')}}</th>
            <th>{{__('Reference No')}}</th>
            <th>{{__('Supplier')}}</th>
            <th>{{__('Request Proposal')}}</th>
            <th>{{__('Price')}}</th>
            <th>{{__('Discount')}}</th>
            <th>{{__('Vat')}}</th>
            <th>{{__('Total Price')}}</th>
            <th>{{__('Notes')}}</th>
            <th>{{__('Quotation Type')}}</th>
            <th class="text-center">{{__('Option')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($quotationList)>0)
        @foreach($quotationList as $key=> $values)
        <tr>
            <td>{{ ($quotationList->currentpage()-1) * $quotationList->perpage() + $key + 1 }}</td>
            <td>{{date('d-m-Y',strtotime($values->quotation_date))}}</td>
            <td>{{ $values->delivery_date !=null ? date('d-m-Y',strtotime($values->delivery_date)):''}}</td>
            <td> <a href="javascript:void(0)" class="btn btn-link" onclick="openModal({{$values->id}})">{{$values->reference_no}}</a></td>
            <td>{{isset($values->relSuppliers->name) ? $values->relSuppliers->name.' ('.$values->relSuppliers->code.')' : ''}}</td>
            <td>{{isset($values->relRequestProposal->reference_no)?$values->relRequestProposal->reference_no:''}}</td>
            <td class="text-right">{{number_format($values->total_price,2)}}</td>
            <td class="text-right">{{number_format($values->discount,2)}} </td>
            <td class="text-right">{{number_format($values->vat,2)}} </td>
            <td class="text-right">{{number_format($values->gross_price,2)}}</td>
            <td>{{ucfirst($values->note)}}</td>
            <td>{{ucfirst($values->type)}}</td>


            <td class="text-center action">
                <div class="btn-group">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                        <span id="statusName{{$values->id}}">
                            {{ucfirst($values->is_approved)}}
                        </span>
                    </button>
                    <ul class="dropdown-menu">
                     <li>
                        <a href="javascript:void(0)" onclick="openModal({{$values->id}})">View</a>

                    </li>
                    
                    @if($values->is_approved === 'approved')
                    @can('quotation-halt')
                    <li>
                        <a class="requisitionApprovedBtn" data-id="{{$values->id}}" data-status="halt">{{ __('Halt')}}
                        </a>
                    </li>
                    @endcan
                    @endif
                    @if($values->is_po_generate === 'no')
                        @can('generate-po') 
                        <li>
                            <a href="{{route('pms.quotation.generate.po.process',$values->id)}}">{{ __('Generate PO')}}</a>
                            
                            <a class="completeQuotation" data-id="{{$values->id}}" data-status="completeQuotation">{{ __('Complete')}}</a>
                        </li>
                        @endcan
                    @endif
                </ul>
            </div>

        </td>
    </tr>
    @endforeach
    @endif


</tbody>

</table>
<div class="p-3">
    @if(count($quotationList)>0)
    <ul class="searchPagination">
        {{$quotationList->links()}}
    </ul>

    @endif
</div>