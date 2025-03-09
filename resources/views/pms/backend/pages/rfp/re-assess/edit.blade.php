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
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{ $title }}</li>
                <li class="top-nav-btn">
                   <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Back" > <i class="las la-chevron-left"></i>Back</a>
               </li>
           </ul>
       </div>

       <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <form action="{{ url('pms/rfp/re-assess-requisitions/'.$requisition->id) }}" method="POST" id="editRequisitionForm" enctype="multipart/form-data" class="formInputValidation">
                @csrf
                @method('PUT')
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%">Reference No</th>
                                            <th style="width: 20%">Requisition Date</th>
                                            <th style="width: 20%">Requisitioned By</th>
                                            <th style="width: 20%">Unit</th>
                                            <th style="width: 20%">Re-Assess Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $requisition->reference_no }}</td>
                                            <td>{{ date('Y-m-d g:i a', strtotime($requisition->requisition_date)) }}</td>
                                            <td>{{ $requisition->relUsersList->name }}</td>
                                            <td>{{ $requisition->unit->hr_unit_name }}</td>
                                            <td>
                                                <select name="assessment_option" class="form-control">
                                                    <option value="employee">Employee Acknowledgement</option>
                                                    <option value="management">Management Approval</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12  table-responsive style-scroll">
                                <table class="table table-striped table-bordered miw-500 dac_table" cellspacing="0" width="100%" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 7.5%">Sub Category</th>
                                            <th style="width: 10%">Product</th>
                                            <th style="width: 60%">Attributes</th>
                                            <th style="width: 7.5%">Unit price</th>
                                            <th style="width: 5%">Quantity</th>
                                            <th style="width: 10%">Approved Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody class="field_wrapper">
                                        @forelse($requisition->items as $key => $item)
                                        <tr>
                                            <td>{{ isset($item->product->category->name) ? $item->product->category->name : '' }}</td>
                                            <td>{{ $item->product->name }} {{ getProductAttributesFaster($item->product) }}</td>
                                            <td class="product-attributes product-attributes-{{ $key+1 }}" data-serial="{{ $key+1 }}" data-product-id="{{ $item->product_id }}" data-item-id="{{ $item->id }}">

                                            </td>
                                            <td class="text-right">{{ $item->unit_price }}</td>
                                            <td class="text-right">{{ $item->requisition_qty }} {{ $item->product->productUnit->unit_name }}</td>
                                            <td class="text-right">{{ $item->qty }} {{ $item->product->productUnit->unit_name }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary rounded pull-right"><i class="la la-plus"></i> Send for Re-Assessment</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection
@section('page-script')
<script type="text/javascript">
    $('body').addClass('sidebar-main');

    $.each($('.product-attributes'), function(index, val) {
        getAttributes($(this));
    });

    function getAttributes(element) {
        var product_id = parseInt(element.attr('data-product-id'));
        var item_id = parseInt(element.attr('data-item-id'));
        if(product_id > 0){
            $.ajax({
                url: "{{ url('pms/requisition/requisition/create') }}?get-attributes&product_id="+product_id+"&item_id="+item_id+"&serial="+element.attr('data-serial'),
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                $('.product-attributes-'+element.attr('data-serial')).html(response);
            });
        }else{
            $('.product-attributes-'+element.attr('data-serial')).html('');
        }
    }

    function showAttributeoptions(element) {
        var attributes = element.parent().parent().find('.attributes:checked').map(function () {
            return this.value;
        }).get();

        element.parent().parent().parent().parent().find('.attribute-option-div').hide();
        $.each(attributes, function(index, attribute) {
            element.parent().parent().parent().parent().find('.attribute-option-div-'+attribute).show();
        });
    }
</script>
@endsection
