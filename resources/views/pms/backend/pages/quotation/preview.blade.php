@if($quotations->count()>2)
    <style type="text/css">

        .custom-thead, .custom-tbody .custom-tr {
            display: table;
            width: 2000px;
            table-layout: fixed;
        }

        .custom-thead {
            width: calc(2000px)
        }

        ul {
            list-style: none;
        }

        .modal-body {
            overflow-x: auto !important;
        }
    </style>
@endif

@if($quotations->count() > 0)
    <hr class="pt-0 mt-0">
    <div class="page-content">
        <div class="">
            <div class="panel panel-body modal-body">

                @if($quotations)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="col-lg-12 invoiceBody">
                                    <div class="invoice-details mt25 row">

                                        @foreach($quotations as $key=>$quotation)
                                            @if($key==0)
                                                <div class="col-md-6 well">
                                                    <ul class="list-unstyled mb0">
                                                        <li><strong>CS Number
                                                                :</strong> {{$quotation->relRequestProposal->reference_no}}
                                                        </li>
                                                        @if($requisitionItems->isNotEmpty())
                                                            <li><strong>Requisition Ref Number
                                                                    :</strong> {{$requisitionItems->first()->requisition->reference_no }}
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <strong>Department:</strong>
                                                            @php
                                                                $departmentName= $quotation->relRequestProposal->requestProposalRequisition->first()->relRequisition->relUsersList->employee->department->hr_department_name;
                                                            @endphp

                                                            {{isset($departmentName)?$departmentName:''}}
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6 well">
                                                    <ul class="list-unstyled mb0">
                                                        <li><strong>{{__('CS Provide By')}} :</strong>
                                                            {{isset($quotation->relRequestProposal->createdBy->name) ? $quotation->relRequestProposal->createdBy->name : ''}}
                                                        </li>
                                                        <li><strong>{{__('CS Date')}} :</strong>
                                                            {{date('d-m-Y h:i:s A',strtotime($quotation->relRequestProposal->request_date))}}
                                                        </li>
                                                        <li>
                                                            <strong>{{ __('Project Name') }}
                                                                :</strong> {{ $quotation->relRequestProposal->project_name ?? 'n/a' }}
                                                        </li>

                                                    </ul>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="table-responsive" style="width: auto;">
                                    <table class="table table-bordered table-hover">
                                        <thead class="thead custom-thead">
                                        <tr class="custom-tr">
                                            <th colspan="5">Supplier Name</th>
                                            @foreach($quotations as $q_key => $quotation)
                                                @php
                                                    $TS = number_format($quotation->relSuppliers->SupplierRatings->sum('total_score'), 2);
                                                    $TC = $quotation->relSuppliers->SupplierRatings->count();

                                                    $totalScore = isset($TS) ? $TS : 0;
                                                    $totalCount = isset($TC) ? $TC : 0;
                                                @endphp
                                                <th class="invoiceBody"
                                                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                                                    <p class="ratings text-dark">
                                                        <a href="{{route('pms.supplier.profile',$quotation->relSuppliers->id)}}"
                                                           target="_blank"><span>{{$quotation->relSuppliers->name}}
                                ({{$quotation->relSuppliers->code}})</span></a>
                                                        @if(in_array($quotation->type,['manual','online']))
                                                            ({!!ratingGenerate($totalScore,$totalCount)!!})
                                                        @endif
                                                    </p>
                                                    <p class="text-dark"><strong>{{__('Q:Ref:No')}}
                                                            :</strong> {{$quotation->reference_no}}</p>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr class="text-center custom-tr">
                                            <th>SL</th>
                                            <th>Product</th>
                                            <th style="width: 300px">Description</th>
                                            <th>UOM</th>
                                            <th>Qty</th>
                                            @foreach($quotations as $quotation)
                                                {{-- <th class="text-center">{!! $quotation->is_approved == 'approved' ? '<i class="las la-check-square text-success" style="font-size: 18px;"></i>' : '<i class="las la-ban text-danger" style="font-size: 18px;"></i>' !!}</th> --}}
                                                <th class="text-center">Approved Qty</th>
                                                <th class="text-center">Unit Price
                                                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }}
                                                    )
                                                </th>
                                                <th class="text-center">Item Total
                                                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }}
                                                    )
                                                </th>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                    <th class="text-center">Item Total
                                                        ({{ $systemCurrency->code }})
                                                    </th>
                                                @endif
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody class="tbody custom-tbody">
                                        @php
                                            $total_qty=0;
                                        @endphp
                                        @if(isset($quotation->id))
                                            @foreach($quotation->relQuotationItems as $key=>$item)
                                                <tr class="custom-tr">
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>
                                                        <a class="text-primary" style="cursor: pointer;"
                                                           onclick="productLogs('{{ $item->uid }}')">{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}</a>
                                                    </td>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
                                                    </td>
                                                    <td class="text-center">{{$item->qty}}</td>

                                                    @foreach($quotations as $key=>$quotation)
                                                        {{-- <td class="text-center">
                                                            {!! $quotationInfo[$quotation->id]['items'][$item->uid]['is_approved'] == 'approved' ? '<i class="las la-check-square text-success" style="font-size: 18px;"></i>' : '<i class="las la-ban text-danger" style="font-size: 18px;"></i>' !!}
                                                        </td> --}}
                                                        <td class="text-center {{ $quotationInfo[$quotation->id]['items'][$item->uid]['approved_qty'] > 0 ? 'text-success' : 'text-danger' }}">
                                                            {{ $quotationInfo[$quotation->id]['items'][$item->uid]['approved_qty'] }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['unit_price'] ,) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['sub_total'] ,2) }}
                                                        </td>
                                                        @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                        $quotation->exchangeRate->currency->code : ''))
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

                                        <tr class="custom-tr">
                                            <td colspan="4" class="text-right"><strong>Total</strong></td>
                                            <td class="text-center"><strong>{{$total_qty}}</strong></td>
                                            @foreach($quotations as $key=>$quotation)
                                                <td colspan="2"><strong>Sub Total</strong></td>
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

                                        <tr class="custom-tr">
                                            <td colspan="5" class="text-right"></td>

                                            @foreach($quotations as $key=>$quotation)
                                                <td colspan="2"><strong>(-) Discount</strong></td>
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

                                        <tr class="custom-tr">
                                            <td colspan="5" class="text-right"></td>

                                            @foreach($quotations as $key=>$quotation)
                                                <td colspan="2"><strong>(+) VAT
                                                        ({{ ucwords($quotation->relQuotationItems->first()->vat_type) }}{{ $quotation->relQuotationItems->first()->vat_type != 'exempted' ? ', '.$quotation->relQuotationItems->first()->vat_percentage.'%' : '' }}
                                                        )</strong></td>
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

                                        <tr class="custom-tr">
                                            <td colspan="5" class="text-right"></td>

                                            @foreach($quotations as $key=>$quotation)
                                                <td colspan="2"><strong>Gross Total</strong></td>
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

                                        <tr class="custom-tr">
                                            <td colspan="5"></td>
                                            @foreach($quotations as $key=>$quotation)
                                                <td colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}"
                                                    class="text-left">
                                                    Payment Term:
                                                    <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }}
                                                        )</strong>
                                                </td>
                                            @endforeach

                                        </tr>

                                        <tr  class="custom-tr">
                                            <td colspan="5" class="text-right"></td>
                                            @foreach($quotations as $key => $quotation)
                                                <td
                                                        colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                                                    Delivery Date: <span><strong>{!! date('d M Y', strtotime($quotation->delivery_date))
                                                            !!}</strong></span></td>
                                            @endforeach
                                        </tr>

                                        <tr class="custom-tr">
                                            <td colspan="5" class="text-right">Purchaser Notes</td>
                                            @foreach($quotations as $key => $quotation)
                                                <td
                                                        colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                                                            <span><strong>@if(!empty($quotation->note))
                                                                        {{ $quotation->creator->name }}:
                                                                    @endif</strong> {!! $quotation->note !!}
                                                            </span>
                                                </td>
                                            @endforeach
                                        </tr>

                                        <tr class="custom-tr">
                                            @php
                                                $approvals = $quotations->first()->relRequestProposal->approvals
                                                    ->where('response', 'approved')
                                                    ->sortByDesc('updated_at')
                                                    ->values();
                                            @endphp
                                            <td colspan="5" class="text-right">Approver Remarks</td>
                                            @foreach($quotations as $key => $quotation)
                                                <td
                                                        colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                                                        <span><strong>
                                                                    {{ $approvals->first()->user->name }}:
                                                                </strong> {!! !empty($quotation->remarks) ? $quotation->remarks : 'No Remarks' !!}
                                                        </span>
                                                </td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered"
                                           style="table-layout: fixed; width:100%;">
                                        <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10%">Priority</th>
                                            <th class="text-center" style="width: 15%">Approver</th>
                                            <th class="text-center" style="width: 30%">Logs</th>
                                            <th class="text-center" style="width: 10%">Response</th>
                                            <th class="text-center" style="width: 10%">Approved at</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($quotations->first()->relRequestProposal->approvals[0]))
                                            <tr>
                                                <td class="text-center" style="width: 10%"></td>
                                                <td class="text-center" style="width: 15%">CS Raised at</td>
                                                <td class="text-center" style="width: 30%"></td>
                                                <td class="text-center" style="width: 10%">Raised</td>
                                                <td class="text-center"
                                                    style="width: 10%">{{ date('Y-m-d g:i a', strtotime($quotations->first()->relRequestProposal->approvals->first()->created_at)) }}</td>
                                            </tr>

                                            @foreach($quotations->first()->relRequestProposal->approvals as $approval)
                                                <tr>
                                                    <td class="text-center"
                                                        style="width: 10%">{{ $approval->priority }}</td>
                                                    <td class="text-center"
                                                        style="width: 15%">{{ $approval->user->name }}</td>
                                                    <td style="padding:0;width: 30%">
                                                        @php
                                                            $logs = $approval->logs ? json_decode($approval->logs, true) : [];
                                                        @endphp

                                                        @if(count($logs))
                                                            <table class="table table-bordered table-sm mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-center">
                                                                        Reference No
                                                                    </th>
                                                                    <th class="text-center">
                                                                        Remarks
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($logs as $log)
                                                                    @php
                                                                        $supplierName = $quotations->where('id', $log['id'])->first()->relSuppliers->name ?? 'N/A';
                                                                        $approvedQty = collect($log['rel_quotation_items'] ?? [])->sum('approved_qty');
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-center"
                                                                            style="width: 10%">{{ $log['reference_no'] }}</td>
                                                                        <td class="text-left"
                                                                            style="width: 10%">{{ $log['remarks'] ?? 'No Remarks' }}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <em>No Remarks</em>
                                                        @endif
                                                    </td>
                                                    <td class="text-center"
                                                        style="width: 10%">{{ ucwords($approval->response) }}</td>
                                                    <td class="text-center"
                                                        style="width: 10%">{{ date('Y-m-d g:i a', strtotime($approval->updated_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif