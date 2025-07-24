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
                        <a href="javascript:history.back()"
                           class="btn btn-sm btn-warning text-white"
                           data-toggle="tooltip" title="Back"> <i class="las la-chevron-left"></i>Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            {!! Form::open(['route' => 'pms.rfp.request-proposal.separate.store', 'files'=> false, 'id'=>'', 'class' => 'form-horizontal rfp-form']) !!}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('request_date', 'Date', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong><span
                                                        class="text-danger">&nbsp;*</span></strong>
                                            {!! Form::text('request_date',request()->old('request_date')?request()->old('request_date'):date('d-m-Y'),['id'=>'request_date','class' => 'form-control rounded air-datepicker','placeholder'=>'','readonly'=>'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('reference_no', 'Ref No', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong><span
                                                        class="text-danger">&nbsp;*</span></strong>
                                            {!! Form::text('reference_no',(request()->old('reference_no'))?request()->old('reference_no'):'',['id'=>'reference_no','required'=>true,'class' => 'form-control rounded','readonly'=>'readonly']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="already_quotation"
                                                   name="already_quotation" value="1"
                                                   onclick="getRequestProposalCombineInfo()">
                                            <label class="form-check-label font-weight-bold text-danger" for="already_quotation">
                                                Have Quotation?
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('supplier_id', 'Select Supplier', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong><span
                                                        class="text-danger">&nbsp;*</span></strong>
                                            <select name="supplier_id[]" id="supplier_id" multiple
                                                    required
                                                    class="form-control rounded select2 select2-tags">

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-line">

                                            {!! Form::label('project_name', 'Project Name', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <span
                                                    class="text-danger">&nbsp;*</span>
                                            {!! Form::text('project_name',(request()->old('project_name'))?request()->old('project_name'):'',['id'=>'project_name','required'=>true,'class' => 'form-control rounded']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('department_id', 'Choose Department', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong></strong>
                                            <select name="department_id" id="department_id"
                                                    class="form-control rounded select2"
                                                    onchange="getProducts()">
                                                <option value="{{ null }}">All Departments</option>
                                                @if(isset($departments[0]))
                                                    @foreach($departments as $department)
                                                        <option value="{{ $department->hr_department_id }}" {{ $department->hr_department_id == request()->get('department_id') ? 'selected' : '' }}>{{ $department->hr_department_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-line">
                                            {!! Form::label('product_id', 'Choose Product', array('class' => 'mb-1 font-weight-bold')) !!}
                                            <strong></strong>
                                            <select name="product_id" id="product_id"
                                                    class="form-control rounded select2">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group pt-4">
                                        <button type="button"
                                                class="btn btn-success btn-md mt-1 btn-block"
                                                onclick="search()"><i class="la la-search"></i>&nbsp;Filter
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive style-scroll">
                                <table class="table table-striped table-bordered miw-500 dac_table"
                                       cellspacing="0" width="100%" id="dataTable">
                                    <thead>
                                    <tr class="text-center">
                                        <th style="width: 5%">SL</th>
                                        <th style="width: 20%">Requisitions</th>
                                        <th style="width: 20%">Products</th>
                                        <th style="width: 40%">Attributes</th>
                                        <th style="width: 5%">Quantity</th>
                                        <th style="width: 5%">UOM</th>
                                        <th style="width: 5%" class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="chkbx_all_first" id="checkAllProduct"
                                                       onclick="return CheckAll()">
                                                <label class="form-check-label mt-8"
                                                       for="checkAllProduct">
                                                    <strong>All</strong>
                                                </label>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total_requisition_qty = 0;
                                        $total_approved_qty = 0;
                                        $total_sumOfRFP = 0;
                                    @endphp
                                    @if(isset($filteredRequisitions[0]))
                                        @foreach($filteredRequisitions as $key => $requisition)
                                            @php $rsl = 0; @endphp
                                            @if($requisition->requisitionItems->where('is_send','no')->count() > 0)
                                                @foreach($requisition->requisitionItems->where('is_send','no') as $rkey => $values)
                                                    @php
                                                        $rsl++;
                                                        $sumOfSendRFP = sumOfSendRFP($values->product_id, $values->requisition_id);
                                                        $total_requisition_qty += $values->requisition_qty;
                                                        $total_approved_qty += $values->qty;
                                                        $total_sumOfRFP += ($values->qty-$sumOfSendRFP);
                                                    @endphp
                                                    <tr>
                                                        @if($rsl == 1)
                                                            <td class="text-center"
                                                                rowspan="{{ $requisition->requisitionItems->where('is_send','no')->count()}}">
                                                                {{$key + 1}}
                                                            </td>
                                                            <td rowspan="{{ $requisition->requisitionItems->where('is_send','no')->count()}}">
                                                                <a href="javascript:void(0)"
                                                                   data-src="{{route('pms.requisition.list.view.show',$requisition->id)}}"
                                                                   class="btn btn-link requisition m-1 rounded showRequistionDetails">{{ $requisition->reference_no }}</a>
                                                            </td>
                                                        @endif

                                                        <td>
                                                            {{isset($values->product->name)?$values->product->name:''}}
                                                            ({{isset($values->product->sku)?$values->product->sku:''}}
                                                            ) {{ getProductAttributesFaster($values->product) }}
                                                        </td>
                                                        <td>
                                                            {{ getProductAttributesFaster($values) }}
                                                        </td>

                                                        <td>
                                                            {{ ($sumOfSendRFP>0) ? number_format($values->qty-$sumOfSendRFP,0) : $values->qty }}

                                                            <input type="hidden"
                                                                   name="request_qty[{{$values->product_id}}&{{$values->id}}][]"
                                                                   min="1" max="99999999"
                                                                   value="{{ ($sumOfSendRFP>0) ? number_format($values->qty-$sumOfSendRFP,0) : $values->qty }}"
                                                                   class="request_qty"/>

                                                            @if($sumOfSendRFP > 0)
                                                                <input type="hidden"
                                                                       name="qty[{{$values->product_id}}&{{$values->id}}][]"
                                                                       value="{{$values->qty-$sumOfSendRFP}}">
                                                            @else
                                                                <input type="hidden"
                                                                       name="qty[{{$values->product_id}}&{{$values->id}}][]"
                                                                       value="{{$values->qty}}">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{isset($values->product->productUnit->unit_name)?$values->product->productUnit->unit_name:''}}
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox"
                                                                   name="requisition_item_id[]"
                                                                   class="element_first"
                                                                   value="{{$values->id}}"
                                                                   onclick="getRequestProposalCombineInfo()">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <p class="mb-1 font-weight-bold"><label
                                                for="remarks"><strong>Explaination:</strong></label>
                                    </p>
                                    <div class="form-group form-group-lg mb-3 d-">
                                    <textarea rows="2" name="remarks" id="remarks"
                                              class="form-control rounded word-restrictions" aria-label="Large"
                                              aria-describedby="inputGroup-sizing-sm"
                                              data-input="recommended" placeholder="Enter Explaination"></textarea>
                                    </div>

                                </div>

                                <div class="col-md-12 mb-3">
                                    <button style="width: 200px; float: right !important;" data-placement="top"
                                            data-content="click save changes button for send rfp"
                                            type="submit"
                                            class="btn-block btn btn-success rounded font-10 mt-10 submit-button"
                                            id="send_button_text">
                                        <i
                                                class="la la-check"></i>&nbsp;Send to supplier
                                    </button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="requisitionDetailModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Requisitions Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body" id="tableData"></div>
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
        getRequestProposalCombineInfo();

        function getRequestProposalCombineInfo() {
            $('#supplier_id').html('');

            var items = [];
            $.each($("input.element_first:checked"), function (key, value) {
                items.push(value.value);
            });

            // Check whether the checkbox is checked
            var alreadyQuotation = $('#already_quotation').is(':checked') ? true : false;

            // alert(alreadyQuotation);

            // Change button text based on checkbox
            if (alreadyQuotation) {
                $('#send_button_text').text('Submit');
            } else {
                $('#send_button_text').text('Send to supplier');
            }

            $.ajax({
                url: "{{ url('pms/rfp/request-proposal-separate-info') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    items: items,
                    alreadyQuotation: alreadyQuotation
                },
            })
                .done(function (response) {
                    $('#reference_no').val(response.reference_no);
                    var suppliers = '';
                    $.each(response.suppliers, function (index, val) {
                        suppliers += '<option value="' + (val.id) + '">' + (val.name) + ' (' + (val.code) + ')</option>';
                    });
                    $('#supplier_id').html(suppliers);
                })
                .fail(function (response) {
                    $('#reference_no').val('');
                    $('#supplier_id').html('');
                });
        }


        function CheckAll() {
            if ($('#checkAllProduct').is(':checked')) {
                $('input.element_first').prop('checked', true);
            } else {
                $('input.element_first').prop('checked', false);
            }

            $.each($('.element_first'), function (index, val) {
                updateElement($(this));
            });

            getRequestProposalCombineInfo();
        }

        $('.element_first').click(function (event) {
            updateElement($(this));
        });

        function updateElement(element) {
            if (element.is(':checked')) {
                element.parent().prev().find('input').prop('disabled', false);
                element.parent().prev().prev().find('input').prop('disabled', false);
                element.prop('checked', true);
            } else {
                element.parent().prev().find('input').prop('disabled', true);
                element.parent().prev().prev().find('input').prop('disabled', true);
                element.prop('checked', false);
            }

            calculateTotal();
        }

        calculateTotal();

        function calculateTotal() {
            var total = 0;
            $.each($('.request_qty'), function (index, val) {
                if (!$(this).prop('disabled')) {
                    total += ($(this).val() != "" ? parseInt($(this).val()) : 0);
                }
            });

            $('#request_qty').html(total);
        }

        $(document).ready(function () {
            var form = $('.rfp-form');
            var button = $('.submit-button');
            var button_content = button.html();
            form.submit(function (event) {
                event.preventDefault();
                button.html('<i class="las la-spinner"></i>&nbsp;Please wait...').prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    data: form.serializeArray(),
                })
                    .done(function (response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                        button.html(button_content).prop('disabled', false);
                    })
                    .fail(function (response) {
                        button.html(button_content).prop('disabled', false);
                        $.each(response.responseJSON.errors, function (index, val) {
                            toastr.error(val[0]);
                        });
                    });
            });
        });

        getProducts();

        function getProducts() {
            $.ajax({
                url: "{{ url('pms/rfp/request-proposal/create/separate') }}?get-products&department_id=" + $('#department_id').val() + "&selected={{ request()->get('product_id') }}",
                type: 'GET',
                data: {},
            })
                .done(function (response) {
                    $('#product_id').html(response).change();
                });
        }

        function search() {
            window.open("{{ url('pms/rfp/request-proposal/create/separate') }}?department_id=" + $('#department_id').val() + "&product_id=" + $('#product_id').val(), "_parent");
        }
    </script>
@endsection