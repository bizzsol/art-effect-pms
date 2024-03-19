@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
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
                        <a href="{{ route('pms.grn.po.list') }}" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <div class="panel p-20">
                    <form method="post" action="{{ route('pms.grn.gate-out.update', $purchaseOrder->id) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="po_reference_no">{{ __('PO No') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" value="{{$purchaseOrder->reference_no}}" readonly
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="supplier_id">{{ __('Supplier') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" readonly class="form-control rounded"
                                               value="{{isset($purchaseOrder->relQuotation->relSuppliers->name)?$purchaseOrder->relQuotation->relSuppliers->name:''}}">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="batch">{{ __('Gate out Code') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="text" readonly class="form-control rounded" name="batch"
                                               value="{{ $batch }}">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12">
                                    <p class="mb-1 font-weight-bold"><label for="get_out_file">{{ __('Gate out File') }}
                                            :</label></p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="file" readonly class="form-control rounded" name="get_out_file" accept="application/pdf">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive mt-10">
                                <h4><i class="las la-border-all"></i>&nbsp;Gate Out Items</h4>
                                <table class="table table-striped table-bordered table-head mt-2" cellspacing="0"
                                       width="100%" id="dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th style="width: 5%">#</th>
                                        <th>Sub Category</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Return Qty</th>
                                        <th>Prv. Gate Out Qty</th>
                                        <th>Gate Out Qty</th>
                                        <th>Remaining Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(isset($products[0]))
                                        @foreach($products as $key => $product)
                                            @php
                                                $gate_out_qty = $product->gateOuts->sum('qty');
                                            @endphp
                                            @if($product->return_qty-$gate_out_qty > 0)
                                                <tr>
                                                    <td class="text-center">{{$key+1}}</td>
                                                    <td>{{isset($product->relGoodsReceivedItems->relProduct->category)?$product->relGoodsReceivedItems->relProduct->category->name:''}}</td>
                                                    <td>
                                                        {{isset($product->relGoodsReceivedItems->relProduct->name)?$product->relGoodsReceivedItems->relProduct->name:''}}
                                                        ({{ isset($product->relGoodsReceivedItems->product_id)? getProductAttributes($product->relGoodsReceivedItems->product_id):'' }}
                                                        )
                                                    </td>
                                                    <td>{{ ucwords(str_replace('-', ' ', $product->status)) }}</td>
                                                    <td class="text-center">{{ $product->return_qty }}</td>
                                                    <td class="text-center">{{ $gate_out_qty }}</td>
                                                    <td>
                                                        <input type="number" name="gate_out_qty[{{$product->id}}]"
                                                               class="form-control gate-out-qty text-center" min="0"
                                                               max="{{ $product->return_qty-$gate_out_qty }}"
                                                               data-return-qty="{{ $product->return_qty }}"
                                                               data-gate-out-qty="{{ $gate_out_qty }}"
                                                               value="{{ $product->return_qty-$gate_out_qty }}"
                                                               onchange="checkMax($(this))" onkeyup="checkMax($(this))">
                                                    </td>
                                                    <td class="text-center">0</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                    @php
                                        $total_gate_out_qty = DB::table('purchase_return_gate_outs')->whereIn('purchase_return_id', $products->pluck('id')->toArray())->sum('qty');
                                    @endphp
                                    <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>Total:</strong></td>
                                        <td class="text-center">
                                            <strong>{{ $products->sum('return_qty')-$total_gate_out_qty }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>
                                        </td>
                                        <td class="text-center">
                                            <strong>0</strong>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 text-right">
                                        <button type="submit" class="btn btn-primary rounded"
                                                style="float:right">{{ __('Proceed Gate Out') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script type="text/javascript">
        function checkMax(element) {
            var max = parseInt(element.attr('max'));
            var value = element.val() != "" ? parseInt(element.val()) : 0;
            if (value < 0) {
                element.val(1);
            }

            if (value > max) {
                element.val(max);
            }

            calculateQuantity();
        }

        function calculateQuantity() {
            var total_gate_out_qty = 0;
            var total_return_qty = 0;
            var total_prev_gate_out_qty = 0;
            var total_remaining = 0;
            $.each($('.gate-out-qty'), function (index, val) {
                total_gate_out_qty += parseInt($(this).val());
                total_return_qty += parseInt($(this).attr('data-return-qty'));
                total_prev_gate_out_qty += parseInt($(this).attr('data-gate-out-qty'));
                var remaining = (parseInt($(this).attr('data-return-qty')) - parseInt($(this).attr('data-gate-out-qty'))) - parseInt($(this).val());
                total_remaining += remaining;

                $(this).parent().next().html(remaining);
            });

            $('tfoot').find('td:eq(3)').html('<strong>' + (total_gate_out_qty) + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>');
            $('tfoot').find('td:eq(4)').html('<strong>' + (total_remaining) + '</strong>');
        }
    </script>
@endsection