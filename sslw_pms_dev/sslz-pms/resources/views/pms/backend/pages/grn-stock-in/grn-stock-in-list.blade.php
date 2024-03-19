@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
    <style type="text/css">
        .list-unstyled .ratings {
            display: none;
        }

        .select2 .select2-container .select2-container--default {
            width: 100px !important;
        }

        .select2-container {
            width: 200px !important;
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
                        <a href="javascript:history.back()" class="btn btn-danger btn-sm"><i
                                    class="las la-arrow-left"></i> Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <div class="panel-body">

                            {!! Form::open(['route' => 'pms.grn.stock.in.store',  'files'=> true, 'id'=>'', 'class' => '']) !!}

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <p class="mb-1 font-weight-bold">
                                        <label for="challanFile"><strong>{{ __('Challan Photo') }}
                                                <span class="text-danger">*</span></strong></label>
                                    </p>
                                    <div class="input-group input-group-md mb-3 d-">
                                        <input type="file" name="challan_file" id="challanFile"
                                               class="rounded btn btn-outline-secondary" aria-label="Large"
                                               aria-describedby="inputGroup-sizing-sm" value="" accept="image/*"
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive style-scroll">
                                <table class="table table-striped table-bordered miw-500 dac_table" cellspacing="0"
                                       width="100%" id="dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th style="width: 5%">SL</th>
                                        <th style="width: 10%">Ref No</th>
                                        <th style="width: 5%">Category</th>
                                        <th style="width: 25%">Product</th>
                                        <th style="width: 10%">Unit Price
                                            ({{ isset($grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code)?$grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code:"" }}
                                            )
                                        </th>
                                        <th style="width: 10%">Qty</th>
                                        <th style="width: 10%">Total Price
                                            ({{ isset($grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code)?$grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code:'' }}
                                            )
                                        </th>
                                        <th style="width: 20%">Warehouses</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($grn_stock_in_lists)>0)
                                        @foreach($grn_stock_in_lists as $key=> $values)
                                            <tr id="hideRow{{$values->id}}">
                                                <td class="text-center"> {{ $key + 1 }}</td>
                                                @if($key==0)
                                                    <td rowspan="{{$grn_stock_in_lists->count()}}">{{isset($values->relGoodsReceivedItems->relGoodsReceivedNote->reference_no)? ucfirst($values->relGoodsReceivedItems->relGoodsReceivedNote->reference_no):''}}</td>
                                                @endif
                                                <td>{{isset($values->relGoodsReceivedItems->relProduct->category->name)?$values->relGoodsReceivedItems->relProduct->category->name:''}}</td>
                                                <td>{{isset($values->relGoodsReceivedItems->relProduct->name)?$values->relGoodsReceivedItems->relProduct->name:''}} {{ getProductAttributesFaster($values->relGoodsReceivedItems->relProduct) }}</td>

                                                <td class="text-right">{{number_format($values->unit_amount,2)}}</td>
                                                <td><input type="text" name="id[{{$values->id}}]"
                                                           value="{{$values->received_qty}}" readonly
                                                           class="form-control rounded text-center"></td>
                                                <td class="text-right">{{number_format($values->total_amount,2)}}</td>
                                                <td>
                                                    <div class="input-group input-group-md">
                                                        <select name="warehouse_id[{{$values->id}}]"
                                                                id="warehouse_id{{$values->id}}"
                                                                class="form-control rounded" required>
                                                            <option value="">Select One</option>
                                                            @foreach($warehouses as $key=> $warehouse)
                                                                <option value="{{ $warehouse->id }}" {{ $warehouses->count() == 1 ? 'selected' : ''  }}>{{ $warehouse->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <input type="hidden" name="goods_received_note_id" value="{{$id}}">
                                </table>
                                <div class="col-12 py-2">
                                    <button type="submit" class="btn btn-success float-right">Submit</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="warehouseDetailModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">GRN Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" id="tableData">
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        (function ($) {
            "use script";

            const grnStockInProcess = () => {
                $('.grnStockInProcess').on('click', function () {
                    let id = $(this).attr('data-id');
                    swal({
                        title: "{{__('Are you sure?')}}",
                        text: 'Would you like to complete GRN process and Stock In?',
                        icon: "warning",
                        dangerMode: false,
                        buttons: {
                            cancel: true,
                            confirm: {
                                text: 'Approved',
                                value: true,
                                visible: true,
                                closeModal: true,
                                className: 'bg-success'
                            },
                        },
                    }).then((value) => {
                        $.ajax({
                            url: $(this).attr('data-src'),
                            type: 'post',
                            dataType: 'json',
                            data: {_token: '{!! csrf_token() !!}', id: id},
                            success: function (data) {
                                if (data.result == 'success') {
                                    $('#hideRow' + id).hide();
                                    notify(data.message, 'success');
                                } else {
                                    notify(data.message, 'error');
                                }
                            }
                        });

                    });
                });
            };
            grnStockInProcess();

        })(jQuery);
    </script>
@endsection