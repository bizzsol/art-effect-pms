@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
@include('yajra.css')
@endsection
@section('main-content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{__($title)}}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="">
                @if($purchaseOrder->relGoodReceiveNote->count() > 0)
                @foreach($purchaseOrder->relGoodReceiveNote as $key => $grn)
                <div class="panel panel-info p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-2"><strong><i class="las la-receipt"></i>&nbsp;Goods Received Note</strong></h6>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>PO Reference</th>
                                        <th>GRN Reference</th>
                                        <th>GRN Date</th>
                                        <th>Total Price</th>
                                        <th>VAT</th>
                                        <th>Gross Price</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $purchaseOrder->reference_no }}</td>
                                        <td>{{ $grn->grn_reference_no }}</td>
                                        <td>{{ date('Y-m-d', strtotime($grn->received_date)) }}</td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($grn->total_price) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($grn->vat) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($grn->gross_price) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $grn->note }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-2"><strong><i class="las la-receipt"></i>&nbsp;Confirmed Services</strong></h6>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sub Category</th>
                                        <th>Service</th>
                                        <th>UOM</th>
                                        <th>Quantity</th>
                                        <th>Unit Amount</th>
                                        <th>Sub Total</th>
                                        <th>VAT</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sl = 0;
                                    @endphp
                                    @if($grn->relGoodsReceivedItems->count() > 0)
                                    @foreach($grn->relGoodsReceivedItems as $key => $item)
                                    @if($item->relProduct->is_service == 1)
                                    @php
                                        $sl++;
                                    @endphp
                                    <tr>
                                        <td>{{ $sl }}</td>
                                        <td>{{ $item->relProduct->category->name }}</td>
                                        <td>{{ $item->relProduct->name }} {{ getProductAttributesFaster($item->relProduct) }}</td>
                                        <td>{{ $item->relProduct->productUnit->unit_name }}</td>
                                        <td class="text-right">
                                            {{ systemMoneyFormat($item->received_qty) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($item->unit_amount) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($item->sub_total) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($item->vat) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $currency }} {{ systemMoneyFormat($item->total_amount) }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
@endsection