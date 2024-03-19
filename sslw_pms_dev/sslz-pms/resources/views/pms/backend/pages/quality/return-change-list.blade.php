@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .list-unstyled .ratings {
        display: none;
    }
</style>
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
                <li class="top-nav-btn">
                    <a href="{{ route('pms.quality.ensure.return.change.list') }}" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">

            <div class="panel">
                <form action="{{route('pms.quality.ensure.return.change.received')}}" method="POST">
                    @csrf
                    <div class="panel-body">
                        <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th>SL</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Gate-In Qty</th>
                                    <th>Previous Received Qty</th>
                                    <th>Return Qty</th>
                                    {{-- <th>Receive Qty</th> --}}
                                    {{-- <th>Price</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                $sumOfReceivedtQty=0;
                                $sumOfPrice=0;
                                $sumOfReturnQty=0;
                                @endphp
                                @if(isset($changed))
                                @foreach($changed as $key=>$item)
                                @php 
                                $received_qty = isset($item->relGoodsReceivedItems->received_qty)?$item->relGoodsReceivedItems->received_qty:0;

                                $sumOfReceivedtQty +=($received_qty);
                                $returnQty=($item->relGoodsReceivedItems->qty-$received_qty);
                                $sumOfPrice +=($returnQty*$item->relGoodsReceivedItems->unit_amount);
                                $sumOfReturnQty +=$returnQty;

                                @endphp

                                <tr id="removeApprovedRow{{$item->id}}">
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->category->name)?$item->relGoodsReceivedItems->relProduct->category->name:''}}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->name)?$item->relGoodsReceivedItems->relProduct->name:''}} {{ getProductAttributes($item->relGoodsReceivedItems->relProduct->id) }}</td>
                                    <td class="text-center">
                                        <input type="number" readonly class="form-control text-right" id="unitAmount{{$item->id}}" value="{{$item->relGoodsReceivedItems->unit_amount}}">
                                    </td>
                                    <td class="text-center">{{number_format($item->relGoodsReceivedItems->qty,0)}}</td>
                                    <td class="text-center">{{$received_qty}}</td>
                                    <td class="text-center">{{$returnQty}}</td>
                                    
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-right">Total</td>
                                    <td colspan="" class="text-center">{{isset($changed)?number_format($changed->sum('qty'),0):0}}</td>
                                    <td class="text-center">{{isset($sumOfReceivedtQty)?number_format($sumOfReceivedtQty,0):0}}</td>
                                    <td class="text-center">{{isset($sumOfReturnQty)?$sumOfReturnQty:0}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="9" class="text-right">No Data Found</td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

