@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('main-content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li><a href="#">PMS</a></li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-3">
                <div class="panel-boby p-3">
                    <form action="{{ url('pms/product-management/product/'.$product->id.'/standard-costs') }}" method="post">
                    @csrf
                    <h4 class="mb-2">
                        <strong>#Standard costs of {{ $product->name }} {{ getProductAttributesFaster($product) }}</strong>
                    </h4>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;">SL</th>
                                <th style="width: 70%;">Standard Cost Header</th>
                                <th style="width: 25%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($standardCosts[0]))
                            @foreach($standardCosts as $key => $cost)
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>
                                    {{ $cost->name }}
                                    <p>{{ $cost->description }}</p>
                                </td>
                                <td>
                                    <input type="number" name="amounts[{{ $cost->id }}]" value="{{ $product->productStandardCosts->where('standard_cost_id', $cost->id)->sum('amount') }}" class="form-control text-right">
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center p-3">
                                    <button type="submit" class="btn btn-success btn-md"><i class="las la-check"></i>&nbsp;Update Product Standard Costs</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection