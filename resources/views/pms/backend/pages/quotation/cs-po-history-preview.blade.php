@if($quotations->count() > 0)
<li class="top-nav-btn">
    <a href="{{url('pms/quotation/cs-compare-view-pdf/'.$requestId).'?downloadpdf'}}" class="btn btn-sm btn-danger text-white" title="Download CS"> <i class="las la-download"></i>Download CS</a>
</li>
<hr class="pt-0 mt-0">

<table class="table preview-table table-bordered table-hover" style="width: {{ ($quotations->count()*300)+500 }}px">
    <tbody>
        <tr>
            <td style="width: 50%">
                @foreach($quotations as $key=>$quotation)
                @if($key==0)
                <ul class="list-unstyled mb0">
                    <li><strong>CS Number :</strong> {{$quotation->relRequestProposal->reference_no}}</li>
                    @if($requisitionItems->isNotEmpty())
                    <li><strong>Requisition Ref Number :</strong> {{ $requisitionItems->first()->requisition->reference_no }}</li>
                    @endif
                    <li>
                        <strong>Department:</strong>
                        @php
                        $departmentName= $quotation->relRequestProposal->requestProposalRequisition->first()->relRequisition->relUsersList->employee->department->hr_department_name;
                        @endphp

                        {{isset($departmentName)?$departmentName:''}}
                    </li>

                </ul>
                @endif
                @endforeach
            </td>
            <td style="width: 50%">
                @foreach($quotations as $key=>$quotation)
                @if($key==0)
                <ul class="list-unstyled mb0">
                    <li><strong>CS Provide By :</strong>
                        {{isset($quotation->relRequestProposal->createdBy->name) ? $quotation->relRequestProposal->createdBy->name : ''}}
                    </li>
                    <li><strong>CS Date :</strong>
                        {{date('d-m-Y h:i:s A',strtotime($quotation->relRequestProposal->request_date))}}
                    </li>
                </ul>
                @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
<table class="table preview-table table-bordered table-hover" style="width: {{ ($quotations->count()*300)+500 }}px">
    <thead class="thead">
        <tr>
            <th colspan="5" style="width: 500px">Supplier Name</th>
            @foreach($quotations as $q_key => $quotation)
            @php
            $TS = number_format($quotation->relSuppliers->SupplierRatings->sum('total_score'), 2);
            $TC = $quotation->relSuppliers->SupplierRatings->count();

            $totalScore = isset($TS) ? $TS : 0;
            $totalCount = isset($TC) ? $TC : 0;
            @endphp
            <th class="invoiceBody" colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}">
                <p class="ratings text-dark">
                    <a href="{{route('pms.supplier.profile',$quotation->relSuppliers->id)}}"
                        target="_blank"><span>{{$quotation->relSuppliers->name}}
                            ({{$quotation->relSuppliers->code}})</span></a>
                    @if(in_array($quotation->type,['manual','online']))
                    ({!!ratingGenerate($totalScore,$totalCount)!!})
                    @endif
                </p>
                <p class="text-dark"><strong>Q:Ref:No:</strong> {{$quotation->reference_no}}</p>
            </th>
            @endforeach
        </tr>
        <tr class="text-center">
            <th style="width: 10px">SL</th>
            <th style="width: 100px">Product</th>
            <th style="width: 300px">Description</th>
            <th style="width: 45px">UOM</th>
            <th style="width: 45px">Qty</th>
            @foreach($quotations as $quotation)
            <th class="text-center">Unit Price
                ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})</th>
            <th class="text-center">Item Total
                ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})</th>
            @if($systemCurrency->code != ($quotation->exchangeRate ?
            $quotation->exchangeRate->currency->code : ''))
            <th class="text-center">Item Total ({{ $systemCurrency->code }})</th>
            @endif
            @endforeach
        </tr>
    </thead>
    <tbody class="tbody">
        @php
        $total_qty=0;
        @endphp
        @if(isset($quotation->id))
        @foreach($quotation->relQuotationItems as $key=>$item)
        <tr>
            <td class="text-center">{{$key+1}}</td>
            <td>
                {{ isset($item->relProduct->name) ? $item->relProduct->name : '' }} {{ getProductAttributesFaster($item->relProduct) }}
            </td>
            <td>{{ $item->description }}</td>
            <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
            </td>
            <td class="text-center">{{$item->qty}}</td>

            @foreach($quotations as $key=>$quotation)
            <td class="text-right">
                {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['unit_price'] ,) }}
            </td>
            <td class="text-right">
                {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['sub_total'] ,2) }}
            </td>
            @if($systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : ''))
            <td class="text-right">
                {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['exchange_sub_total'] ,2) }}
            </td>
            @endif
            @endforeach
        </tr>
        @php
        $total_qty += $item->qty;
        @endphp
        @endforeach
        @endif

        <tr>
            <td colspan="4" class="text-right"><strong>Total</strong></td>
            <td class="text-center"><strong>{{$total_qty}}</strong></td>
            @foreach($quotations as $key=>$quotation)
            <td><strong>Sub Total</strong></td>
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['sub_total'], 2) }}</strong>
            </td>
            @if($systemCurrency->code != ($quotation->exchangeRate ?
            $quotation->exchangeRate->currency->code : ''))
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['exchange_sub_total'], 2) }}</strong>
            </td>
            @endif
            @endforeach
        </tr>

        <tr>
            <td colspan="5" class="text-right"></td>

            @foreach($quotations as $key=>$quotation)
            <td><strong>(-) Discount</strong></td>
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['discount'], 2) }}</strong>
            </td>
            @if($systemCurrency->code != ($quotation->exchangeRate ?
            $quotation->exchangeRate->currency->code : ''))
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['exchange_discount'], 2) }}</strong>
            </td>
            @endif
            @endforeach
        </tr>

        <tr>
            <td colspan="5" class="text-right"></td>

            @foreach($quotations as $key=>$quotation)
            <td><strong>(+) VAT ({{ ucwords($quotation->relQuotationItems->first()->vat_type) }}{{ $quotation->relQuotationItems->first()->vat_type != 'exempted' ? ', '.$quotation->relQuotationItems->first()->vat_percentage.'%' : '' }})</strong></td>
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['vat'], 2) }}</strong>
            </td>
            @if($systemCurrency->code != ($quotation->exchangeRate ?
            $quotation->exchangeRate->currency->code : ''))
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['exchange_vat'], 2) }}</strong>
            </td>
            @endif
            @endforeach
        </tr>
        <tr>
            <td colspan="5" class="text-right"></td>

            @foreach($quotations as $key=>$quotation)
            <td><strong>Gross Total</strong></td>
            <td class="text-right">
                <strong>{{ number_format($quotationInfo[$quotation->id]['gross'], 2) }}</strong>
            </td>
            @if($systemCurrency->code != ($quotation->exchangeRate ?
            $quotation->exchangeRate->currency->code : ''))
            <td class="text-right">
                <strong>
                    {{ number_format($quotationInfo[$quotation->id]['exchange_gross'], 2) }}
                </strong>
            </td>
            @endif
            @endforeach
        </tr>

        <tr>
            <td colspan="5"></td>
            @foreach($quotations as $key=>$quotation)
            <td colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}"
                class="text-left">
                Payment Term:
                <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }})</strong>
            </td>
            @endforeach
        </tr>
        <tr>
            <td colspan="5" class="text-right"></td>
            @foreach($quotations as $key => $quotation)
            <td
                colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}">
                Delivery Date: <span><strong>{!! date('d M Y', strtotime($quotation->delivery_date))
                        !!}</strong></span></td>
            @endforeach
        </tr>
        <tr>
            <td colspan="5" class="text-right">Purchaser Notes</td>
            @foreach($quotations as $key => $quotation)
            <td
                colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}">
                <span><strong>@if(!empty($quotation->note)) {{ $quotation->creator->name }}:
                        @endif</strong> {!! $quotation->note !!} </span>
            </td>
            @endforeach
        </tr>
        <tr>
            <td colspan="5" class="text-right">Approver Remarks</td>
            @foreach($quotations as $key => $quotation)
            <td
                colspan="2">
                <span><strong>@if(!empty($quotation->remarks)) {{ $quotation->editor->name }}:
                        @endif</strong> {!! $quotation->remarks !!} </span>
            </td>
            @endforeach
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 10%" class="text-center">Priority</th>
            <th style="width: 55%">Approver</th>
            <th style="width: 15%" class="text-center">Response</th>
            <th style="width: 20%" class="text-center">Approved at</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($quotations->first()->relRequestProposal->approvals[0]))
        <tr>
            <td class="text-center"></td>
            <td>CS Raised at</td>
            <td class="text-center">Raised</td>
            <td class="text-center">{{ date('Y-m-d g:i a', strtotime($quotations->first()->relRequestProposal->approvals->first()->created_at)) }}</td>
        </tr>
        @foreach($quotations->first()->relRequestProposal->approvals as $key => $approval)
        <tr>
            <td class="text-center">{{ $approval->priority }}</td>
            <td>{{ $approval->user->name }}</td>
            <td class="text-center">{{ ucwords($approval->response) }}</td>
            <td class="text-center">{{ date('Y-m-d g:i a', strtotime($approval->updated_at)) }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>
@endif