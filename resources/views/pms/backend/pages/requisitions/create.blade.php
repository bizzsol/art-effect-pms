@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css" media="screen">
    .select2-container{
        width: 100% !important;
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
                    <li class="active">{{ $title }} List</li>
                    <li class="top-nav-btn">
                        <a class="btn btn-sm btn-success text-white" title="Upload Excel" data-toggle="modal" data-target="#uploadExcel"><i class="las la-upload"></i>&nbsp;Upload Excel</a>
                        <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white"
                           data-toggle="tooltip" title="Back"> <i class="las la-chevron-left"></i>Back</a>
                    </li>
                </ul>
            </div>

            <div class="page-content">
                <div class="">
                    <div class="panel panel-info">
                        <form action="{{ route('pms.requisition.requisition.store') }}" method="post"
                              id="addRequisitionForm" data-src="{{ $requisition ? $requisition->id : null }}"
                              enctype="multipart/form-data" class="formInputValidation">
                            @csrf
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-6">
                                        <p class="mb-1 font-weight-bold"><label for="reference"><strong>{{ __('Reference No.') }} <span class="text-danger">&nbsp;*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="text" name="reference_no" id="reference"
                                                   class="form-control rounded" aria-label="Large"
                                                   aria-describedby="inputGroup-sizing-sm" required readonly
                                                   value="{{ old('reference_no',$refNo) }}">
                                            @if ($errors->has('reference_no'))
                                                <span class="help-block">
                                                    <strong class="text-danger">{{ $errors->first('reference_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-6">
                                        <p class="mb-1 font-weight-bold"><label for="requisition_date"><strong>{{ __('Date') }}:<span class="text-danger">&nbsp;*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <input type="datetime-local" name="requisition_date" id="requisition_date" class="form-control rounded" required value="{{ old('requisition_date', date('Y-m-d H:i:s')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <p class="mb-1 font-weight-bold"><label for="hr_unit_id"><strong>Unit:<span class="text-danger">&nbsp;*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="hr_unit_id" id="hr_unit_id" class="form-control">
                                                @if(isset($units[0]))
                                                @foreach($units as $unit)
                                                <option value="{{ $unit->hr_unit_id }}">{{ $unit->hr_unit_short_name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <p class="mb-1 font-weight-bold"><label for="saleable"><strong>Saleable ?<span class="text-danger">&nbsp;*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">
                                            <select name="saleable" id="saleable" class="form-control">
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-4">
                                        <p class="mb-1 font-weight-bold"><label for="category_id"><strong>{{ __('Category') }}:<span class="text-danger">&nbsp;*</span></strong></label></p>
                                        <div class="input-group input-group-md mb-3 d-">

                                            <select name="category_id" id="category_id" class="form-control category"
                                                    onchange="getSubCategories()">
                                                <option value="{{ null }}" disabled {{ request()->has('category_id') && request()->get('category_id') > 0 ? '' : 'selected' }} data-sub-category-ids=""
                                                        data-sub-category-names="" data-sub-category-codes="">{{ __('Select Category') }}</option>

                                                @if(isset($categories[0]) && !isset($task->id))
                                                    <optgroup label="Products">
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ request()->has('category_id') && request()->get('category_id') == $category->id ? 'selected' : (isset(session()->get('requisition-items')['category_id']) && session()->get('requisition-items')['category_id'] == $category->id ? 'selected' : '') }} data-sub-category-ids="{{ $category->subCategory->pluck('id')->implode(',') }}"
                                                                    data-sub-category-names="{{ $category->subCategory->pluck('name')->implode(',') }}"
                                                                    data-sub-category-codes="{{ $category->subCategory->pluck('code')->implode(',') }}">{{ $category->name.'('.$category->code.')'}}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif

                                                @if(isset($fixedAssetCategories[0]) && !isset($task->id))
                                                    <optgroup label="Fixed Assets">
                                                        @foreach($fixedAssetCategories as $category)
                                                            <option value="{{ $category->id }}" {{ request()->has('category_id') && request()->get('category_id') == $category->id ? 'selected' : '' }} data-sub-category-ids="{{ $category->subCategory->pluck('id')->implode(',') }}"
                                                                    data-sub-category-names="{{ $category->subCategory->pluck('name')->implode(',') }}"
                                                                    data-sub-category-codes="{{ $category->subCategory->pluck('code')->implode(',') }}">{{ $category->name.'('.$category->code.')'}}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif

                                                @if(isset($cwipCategories[0]))
                                                    <optgroup label="CWIP">
                                                        @foreach($cwipCategories as $category)
                                                            <option value="{{ $category->id }}" {{ request()->has('category_id') && request()->get('category_id') == $category->id ? 'selected' : '' }} data-sub-category-ids="{{ $category->subCategory->pluck('id')->implode(',') }}"
                                                                    data-sub-category-names="{{ $category->subCategory->pluck('name')->implode(',') }}"
                                                                    data-sub-category-codes="{{ $category->subCategory->pluck('code')->implode(',') }}">{{ $category->name.'('.$category->code.')'}}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif

                                            </select>
                                        </div>
                                    </div>


                                    @if(!empty($task))
                                        <div class="col-md-3 col-sm-6">
                                            <p class="mb-1 font-weight-bold"><label
                                                        for="project_task_id">{{ __('Project Information') }}:</label>
                                            </p>
                                            <div class="input-group input-group-md mb-3 d-">
                                                <ul>
                                                    <li>Project
                                                        : {{isset($task->subDeliverable->deliverable->project->name)?$task->subDeliverable->deliverable->project->name:''}}</li>
                                                    <li>Phase
                                                        : {{isset($task->subDeliverable->deliverable->name)?$task->subDeliverable->deliverable->name:''}}</li>
                                                    <li>Milestone
                                                        : {{isset($task->subDeliverable->name)?$task->subDeliverable->name:''}}</li>
                                                    <li>Task : {{$task->name}}</li>
                                                </ul>
                                                <input type="hidden" name="project_task_id" value="{{$task->id}}">
                                            </div>
                                        </div>
                                    @endif


                                    <div class="col-md-12 table-responsive style-scroll">

                                        <table class="table table-striped table-bordered miw-500 dac_table"
                                               cellspacing="0"
                                               width="100%" id="dataTable">
                                            <thead>
                                                <tr class="text-center">
                                                    <th style="width: 10%" rowspan="2">Sub Category</th>
                                                    <th style="width: 20%" rowspan="2">Product</th>
                                                    <th style="width: 35%" rowspan="2">Attributes</th>
                                                    <th style="width: 10%" rowspan="2">Quantity</th>
                                                    <th style="width: 20%" colspan="2">Budgeted Amount</th>
                                                    <th style="width: 5%" rowspan="2" class="text-center">Action</th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th style="width: 10%">Unit Amount</th>
                                                    <th style="width: 10%">Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="field_wrapper">

                                            @if(isset(session()->get('requisition-items')['items'][0]))
                                                @foreach(session()->get('requisition-items')['items'] as $key => $item)
                                                    <tr>
                                                        <td>
                                                            <div class="input-group input-group-md">
                                                                <select name="sub_category_id[]" id="sub_category_id_{{ $key+1 }}" style="width: 100%" class="form-control subcategory" onchange="getProduct($(this))" data-category-id="{{ $item['category_id'] }}">

                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="product-td">
                                                            <div class="input-group input-group-md">
                                                                <select name="product_id[]" id="product_{{ $key+1 }}" class="form-control product requisition-products"required  data-product-id="{{ $item['product_id'] }}" onchange="getAttributes($(this))" data-serial="{{ $key+1 }}">
                                                                    <option value="{{ null }}" data-uom="">{{ __('Product') }}</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="product-attributes-{{ $key+1 }}">
                                                            
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-md">
                                                                <input type="number" name="qty[]" min="1" max="99999999"
                                                                       id="qty_{{ $key+1 }}" class="form-control requisition-qty text-right calculate-total"
                                                                       aria-label="Large"
                                                                       aria-describedby="inputGroup-sizing-sm"
                                                                       onKeyPress="if(this.value.length==6) return false;"
                                                                       min="1"
                                                                       required data-input="recommended"
                                                                       oninput="this.value = Math.abs(this.value)"
                                                                       data-quantity="{{ $item['quantity'] }}"

                                                                       onchange="calculateTotal($(this))"
                                                                       onkeyup="calculateTotal($(this))"
                                                                       >
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-md">
                                                                <input type="number" name="unit_price[]"
                                                                   min="0"
                                                                   step="any"
                                                                   id="unit_price_{{ $key+1 }}" class="form-control requisition-unit_price text-right calculate-total"
                                                                   aria-label="Large"
                                                                   aria-describedby="inputGroup-sizing-sm"
                                                                   required data-input="recommended"
                                                                   data-unit-price="{{ $item['unit_price'] }}"
                                                                   value="0"

                                                                    onchange="calculateTotal($(this))"
                                                                    onkeyup="calculateTotal($(this))"
                                                                >
                                                            </div>
                                                        </td>
                                                        <td class="total-amount text-right">0.00</td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);" id="remove_{{ $key+1 }}"
                                                               class="remove_button btn btn-xs btn-danger" title="Remove"
                                                               style="color:#fff;">
                                                                <i class="las la-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <div class="input-group input-group-md">
                                                            <select name="sub_category_id[]" id="sub_category_id_1"
                                                                    style="width: 100%" class="form-control subcategory"
                                                                    onchange="getProduct($(this))">

                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="product-td">
                                                        <div class="input-group input-group-md">
                                                            <select name="product_id[]" id="product_1"
                                                                    class="form-control product requisition-products" data-product-id="" required onchange="getAttributes($(this))" data-serial="1">
                                                                <option value="{{ null }}" data-uom="">{{ __('Select Product') }}</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="product-attributes-1">
                                                        
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-md">
                                                            <input type="number" name="qty[]" min="1" max="99999999"
                                                                   id="qty_1" class="form-control requisition-qty text-right calculate-total"
                                                                   aria-label="Large"
                                                                   aria-describedby="inputGroup-sizing-sm"
                                                                   onKeyPress="if(this.value.length==6) return false;"
                                                                   min="1"
                                                                   required data-input="recommended"
                                                                   oninput="this.value = Math.abs(this.value)"
                                                                   data-quantity=""
                                                                   onchange="calculateTotal($(this))"
                                                                    onkeyup="calculateTotal($(this))"
                                                                   >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-md">
                                                            <input type="number" name="unit_price[]"
                                                               min="0"
                                                               step="any"
                                                               id="unit_price_1" class="form-control requisition-unit_price text-right calculate-total"
                                                               aria-label="Large"
                                                               aria-describedby="inputGroup-sizing-sm"
                                                               required data-input="recommended"
                                                               data-unit-price=""
                                                               value="0"

                                                               onchange="calculateTotal($(this))"
                                                                onkeyup="calculateTotal($(this))"
                                                            >
                                                        </div>
                                                    </td>
                                                    <td class="total-amount text-right">0.00</td>
                                                    <td class="text-center">
                                                        <a href="javascript:void(0);" id="remove_1"
                                                           class="remove_button btn btn-xs btn-danger" title="Remove"
                                                           style="color:#fff;">
                                                            <i class="las la-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif

                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-right"><strong>Total Budgeted Amount:</strong></td>
                                                    <td class="grand-total-amount text-right">0.00</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>

                                        </table>
                                        <a href="javascript:void(0);"
                                           class="add_button btn btn-sm btn-success pull-right"
                                           style="margin-right:17px;" title="Add More Product">
                                            <i class="las la-plus"></i>
                                        </a>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <p class="mb-1 font-weight-bold"><label for="file"><strong>Attachment:</strong></label>
                                            {!! $errors->has('file')? '<span class="text-danger text-capitalize">'.
                                            $errors->first('file').'</span>':'' !!}</p>
                                        <div class="form-group form-group-lg mb-3 d-">
                                            <input type="file" name="file" id="file" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <p class="mb-1 font-weight-bold"><label for="explanations"><strong>Explanations:</strong><strong class="text-danger">*</strong></label> {!! $errors->has('explanations')? '<span class="text-danger text-capitalize">'.$errors->first('explanations').'</span>':'' !!}</p>
                                        <div class="form-group form-group-lg mb-3 d-">
                                            {{-- <select name="explanations[]" id="explanations" multiple
                                                    class="form-control">
                                                @if(isset($explanations[0]))
                                                    @foreach($explanations as $key => $explanation)
                                                        <option>{{ $explanation->explanation }}</option>
                                                    @endforeach
                                                @endif
                                            </select> --}}
                                            <textarea rows="3" name="explanations" id="explanations" class="form-control rounded word-restrictions" aria-label="Large" aria-describedby="inputGroup-sizing-sm" data-input="recommended" required>{!! old('explanations') !!}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <p class="mb-1 font-weight-bold"><label for="remarks"><strong>Notes:</strong></label> {!! $errors->has('remarks')? '<span class="text-danger text-capitalize">'.$errors->first('remarks').'</span>':'' !!}</p>
                                        <div class="form-group form-group-lg mb-3 d-">
                                            <textarea rows="3" name="remarks" id="remarks" class="form-control rounded word-restrictions" aria-label="Large" aria-describedby="inputGroup-sizing-sm" data-input="recommended">{!! old('remarks') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-4"></div>
                                    <div class="col-4 mt-4">
                                        <input type="hidden" name="where_to_go" id="where_to_go" value="index">
                                        <button type="button" class="btn btn-primary rounded pull-right save-button" onclick="submitRequisition('index');"><i class="la la-plus"></i>&nbsp;Save Requisition</button>
                                        <button type="button" class="btn btn-info rounded pull-right mr-2 save-new-button" onclick="submitRequisition('back');"><i class="la la-plus"></i>&nbsp;Save & New Requisition</button>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="las la-upload"></i>&nbsp;Upload Excel</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="las la-times-circle mt-1"></i>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{ url('pms/requisition/requisition-upload-excel') }}" method="post" enctype="multipart/form-data" id="excel-upload-form" onsubmit="return false;">
            @csrf
                <div class="form-group">
                    <label for="category"><strong>Choose Category <span class="text-danger">*</span></strong></label>
                    <select name="category" id="category" class="form-control">
                        @if(isset($categories[0]))
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->code }} | {{ $category->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    <a href="{{ asset('samples/requisition-excel-sample.xlsx') }}" download="Requisition Excel Sample.xlsx"><small>Download Sample Excel file</small></a>
                </div>
                <div class="form-group">
                    <label for="excel_file"><strong>Choose Excel File <span class="text-danger">*</span></strong></label>
                    <input type="file" name="excel_file" id="excel_file" class="form-control">
                </div>
                <button type="button" class="btn btn-success btn-md excel-upload-button" onclick="uploadExcelFile()"><i class="las la-uplaod"></i>&nbsp;Upload Requisition Excel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('page-script')

    <script type="text/javascript">
        $('body').addClass('sidebar-main');

        "use strict";
        var selectedProductIds = [];

        function changeSelectedProductIds() {
            selectedProductIds = [];
            $('.product').each(function () { //
                selectedProductIds.push($(this).val());
            })
        }

        function formInputValidation() {
            let activeSection = document.querySelector(".formInputValidation");
            let inputs = activeSection.querySelectorAll("input");
            let textareas = activeSection.querySelectorAll("textarea");

            let inputBoxAlert = []
            inputs.forEach(input => {
                if (input.getAttribute('data-input') === "recommended" && input.value === "") {
                    inputBoxAlert.push({
                        name: input.getAttribute('name')
                    })
                }
            })

            textareas.forEach(input => {
                if (input.getAttribute('data-input') === "recommended" && input.value === "") {
                    inputBoxAlert.push({
                        name: input.getAttribute('name')
                    })
                }
            })

            if (inputBoxAlert.length > 0) {
                inputBoxAlert.forEach(item => {
                    toastr.error(`${item.name.replaceAll("[]", "")} can't empty. Please fill that input field.`, 'Error!')
                });
                return false;
            }

            return true;
        }

        function submitRequisition(where_to_go) {
            $('#where_to_go').val(where_to_go);

            var error_count = 0;
            if ($('#explanations').val() == "") {
                error_count++;
                toastr.error("Please write explanations.");
            }

            if ($('#category_id').find(':selected').val() == "") {
                error_count++;
                toastr.error("Please Choose Category");
            }

            $.each($('.requisition-products'), function (index, val) {
                if ($(this).val() == "" || parseInt($(this).val()) <= 0) {
                    error_count++;
                    toastr.error("Please choose Product");
                }
            });

            $.each($('.requisition-qty'), function (index, val) {
                if ($(this).val() == "" || parseInt($(this).val()) <= 0) {
                    error_count++;
                    toastr.error("Please write Product Quantity");
                }
            });

            if (error_count == 0) {
                $('#addRequisitionForm').submit();
            }
        }


        $(document).ready(function () {

            var form = $('#addRequisitionForm');

            var maxField = 200;
            var addButton = $('.add_button');
            var x = 1;
            var wrapper = $('.field_wrapper');

            getSubCategories();

            $(addButton).click(function () {

                x++;

                var fieldHTML = '<tr>\n' +
                    '                                            <td>\n' +
                    '\n' +
                    '                                                <div class="input-group input-group-md">\n' +
                    '                                                    <select name="sub_category_id[]" style="width: 100%" id="sub_category_id_' + x + '" class="form-control select2 subcategory" onchange="getProduct($(this))" required></select>\n' +
                    '                                                </div>\n' +
                    '\n' +
                    '                                            </td>\n' +

                    '                                            <td class="product-td">\n' +
                    '\n' +
                    '                                                <div class="input-group input-group-md">\n' +
                    '                                                    <select name="product_id[]" id="product_' + x + '" class="form-control select2 product requisition-products" data-product-id="" required onchange="getAttributes($(this))" data-serial="'+x+'">\n' +
                    '                                                        <option value="{{ null }}">{{ __("Select Product") }}</option>\n' +
                    '                                                    </select>\n' +
                    '                                                </div>\n' +
                    '\n' +
                    '                                            </td>\n' +
                    '<td class="product-attributes-'+x+'">'+
                        
                    '</td>'+
                    '                                            <td>\n' +
                    '                                                <div class="input-group input-group-md">\n' +
                    '                                                    <input type="number" name="qty[]" min="1" max="9999" onKeyPress="if(this.value.length==6) return false;" id="qty_' + x + '" class="form-control requisition-qty text-right calculate-total" aria-label="Large" aria-describedby="inputGroup-sizing-sm" oninput="this.value = Math.abs(this.value)" required data-quantity="" onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">\n' +
                    '                                                </div>\n' +
                    '                                            </td>\n' +
                    '                                            <td>\n' +
                    '                                                <div class="input-group input-group-md">\n' +
                    '                                                    <input type="number" name="unit_price[]" min="0" step="any" id="unit_price_' + x + '" class="form-control requisition-unit_price text-right calculate-total" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required data-unit-price="" value="0" onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">\n' +
                    '                                                </div>\n' +
                    '                                            </td>\n' +
                    '\n' +
                    '<td class="total-amount text-right">0.00</td>'+
                    '                                            <td class="text-center">\n' +
                    '                                                <a href="javascript:void(0);" id="remove_' + x + '" class="remove_button btn btn-xs btn-danger" title="Remove" style="color:#fff;">\n' +
                    '                                                    <i class="las la-trash"></i>\n' +
                    '                                                    \n' +
                    '                                                </a>\n' +
                    '                                            </td>\n' +
                    '\n' +
                    '                                        </tr>';

                $(wrapper).append(fieldHTML);
                $('#sub_category_id_' + x, wrapper).select2();
                $('#product_' + x, wrapper).select2();
                generateSubCategories($('#sub_category_id_' + x, wrapper))

            });

            $(wrapper).on('click', '.remove_button', function (e) {
                e.preventDefault();
                if (x > 1) {
                    x--;

                    var incrementNumber = $(this).attr('id').split("_")[1];
                    var productVal = $('#product_' + incrementNumber).val()

                    const index = selectedProductIds.indexOf(productVal);
                    if (index > -1) {
                        selectedProductIds.splice(index, 1);
                    }
                    $(this).parent('td').parent('tr').remove();
                }
            });

            var saveButton = $('.save-button');
            var saveButtonContent = saveButton.html();
            var saveNewButton = $('.save-new-button');
            var saveNewButtonContent = saveNewButton.html();
            form.submit(function(event) {
                event.preventDefault();
                
                saveButton.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');
                saveNewButton.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    data: new FormData(form[0]),
                })
                .done(function(response) {
                    if(response.success){
                        window.open(response.redirect, '_parent');
                    }else{
                        toastr.error(response.message);
                        saveButton.prop('disabled', false).html(saveButtonContent);
                        saveNewButton.prop('disabled', false).html(saveNewButtonContent);
                    }
                })
                .fail(function(response) {
                    $.each(response.responseJSON.errors, function(index, val) {
                        toastr.error(val[0]);
                    });

                    saveButton.prop('disabled', false).html(saveButtonContent);
                    saveNewButton.prop('disabled', false).html(saveNewButtonContent);
                });
            });

        });

        $(document).ready(function () {
            $.each($('.subcategory'), function (index, val) {
                getProduct($(this));
            });

            var wrapper = $('.field_wrapper');
            $(wrapper).on('change', '.product', function (e) {
                changeSelectedProductIds();

                var incrementNumber = $(this).attr('id').split("_")[1];
                $('#qty_' + incrementNumber).val($('#qty_' + incrementNumber).attr('data-quantit'));

                $(this).parent().parent().parent().find('.subcategory').val(parseInt($(this).find(':selected').attr('data-sub-category-id'))).select2();
            });
        });

        function calculateTotal(element) {
            var unit_price = element.parent().parent().parent().find('.requisition-unit_price').val() != "" ? parseFloat(element.parent().parent().parent().find('.requisition-unit_price').val()) : 0;
            var qty = element.parent().parent().parent().find('.requisition-qty').val() != "" ? parseFloat(element.parent().parent().parent().find('.requisition-qty').val()) : 0;
            var total_amount = parseFloat(unit_price*qty);

            element.parent().parent().parent().find('.total-amount').html(parseFloat(total_amount).toFixed(2));


            var grand_total = 0;
            $.each($('.total-amount'), function(index, val) {
                grand_total += $(this).text() != "" ? parseFloat($(this).text()) : 0;
            });
            $('.grand-total-amount').html(parseFloat(grand_total).toFixed(2));
        }

        function getSubCategories() {
            $.each($('.subcategory'), function (index, val) {
                generateSubCategories($(this));
            });
        }

        function generateSubCategories(element) {
            var category = $('#category_id').find(':selected');
            var ids = (category.attr('data-sub-category-ids') ? category.attr('data-sub-category-ids').split(',') : []);
            var names = (category.attr('data-sub-category-names') ? category.attr('data-sub-category-names').split(',') : []);
            var codes = (category.attr('data-sub-category-codes') ? category.attr('data-sub-category-codes').split(',') : []);

            var value = element.val() != null ? element.val() : element.attr('data-category-id');
            var subCategories = '<option value="">Select Subcategory</option>';
            $.each(ids, function (index, val) {
                if (val > 0) {
                    subCategories += '<option value="' + (val) + '" ' + (val == value ? 'selected' : '') + '>' + (names[index]) + ' (' + (codes[index]) + ')</option>';
                }
            });

            element.html(subCategories).change();
        }


        function getProduct(element) {
            var incrementNumber = element.attr('id').split("_")[3];

            changeSelectedProductIds();

            var subcategory_id = $('#sub_category_id_' + incrementNumber).val();
            if (subcategory_id.length === 0) {
                subcategory_id = 0;
            }
            $('#qty_' + incrementNumber).val($('#qty_' + incrementNumber).attr('data-quantity'));

            $('#product_' + incrementNumber).html('<option value="">Please Wait...</option>').load('{{URL::to(Request()->route()->getPrefix()."requisition/load-category-wise-product")}}/' + subcategory_id + '?parent_id=' + $('#category_id').val() + '&selected=' + ($('#product_' + incrementNumber).attr('data-product-id')) + '&products_id=' + selectedProductIds);
        }

        function getUOM() {
            // $.each($('.product'), function (index, val) {
            //     $(this).parent().parent().next().html($(this).find(':selected').attr('data-uom'));
            // });
        }

        function uploadExcelFile() {
            var excel_form = $('#excel-upload-form');
            var excel_button = $('.excel-upload-button');
            var excel_button_content = excel_button.html();

            excel_button.prop('disabled', true).html("<i class='las la-spinner la-spin'></i>&nbsp;Please wait...");

            $.ajax({
                url: excel_form.attr('action'),
                type: excel_form.attr('method'),
                dataType: 'json',
                processData: false,
                contentType: false,
                data: new FormData(excel_form[0]),
            })
            .done(function(response) {
                if(response.success){
                    location.reload();
                }else{
                    toastr.error(response.message);
                }

                excel_button.prop('disabled', false).html(excel_button_content);
            })
            .fail(function(response) {
                var errors = '<ul class="">';
                $.each(response.responseJSON.errors, function(index, val) {
                    errors += '<li class="text-white">'+val[0]+'</li>';
                });
                errors += '</ul>';
                toastr.error(errors);

                excel_button.prop('disabled', false).html(excel_button_content);
            });
        }


        function getAttributes(element) {
            element.parent().parent().parent().find('.requisition-unit_price').val(element.find(':selected').attr('data-unit-price'));
            var product_id = parseInt(element.find(':selected').val());
            if(product_id > 0){
                $.ajax({
                    url: "{{ url('pms/requisition/requisition/create') }}?get-attributes&product_id="+product_id+"&serial="+element.attr('data-serial'),
                    type: 'GET',
                    data: {},
                })
                .done(function(response) {
                    element.parent().parent().parent().find('.product-attributes-'+element.attr('data-serial')).html(response);
                });
            }else{
                element.parent().parent().parent().find('.product-attributes-'+element.attr('data-serial')).html('');
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