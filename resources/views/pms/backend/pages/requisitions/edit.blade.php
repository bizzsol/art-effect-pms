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
                <li class="active">{{__($title)}} List</li>
                <li class="top-nav-btn">
                   <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Back" > <i class="las la-chevron-left"></i>Back</a>
               </li>
           </ul>
       </div>

       <div class="page-content">
        <div class="">
            <div class="panel panel-info">

                <form action="{{ route('pms.requisition.requisition.update',$requisition->id) }}?redirect={{ request()->get('redirect') }}&editor={{ request()->get('editor') }}" method="POST" id="editRequisitionForm" enctype="multipart/form-data" class="formInputValidation">
                    <input type="hidden" name="_method" value="PUT">
                    @csrf
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="reference">{{ __('Reference No.') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="text" name="reference_no" id="reference" class="form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required readonly value="{{ old('reference_no',$requisition->reference_no) }}">
                                    @if ($errors->has('reference_no'))
                                    <span class="help-block">
                                        <strong class="text-danger">{{ $errors->first('reference_no') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="date">{{ __('Date') }}:</label> </p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="datetime-local" name="requisition_date" id="date" class="form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ date('Y-m-d H:i:s', strtotime($requisition->requisition_date)) }}" >
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="hr_unit_id"><strong>Unit:<span class="text-danger">&nbsp;*</span></strong></label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="hr_unit_id" id="hr_unit_id" class="form-control" onchange="getCostCentres()">
                                        @if($requisition->author_id == auth()->user()->id)
                                            @if(isset($units[0]))
                                            @foreach($units as $unit)
                                            <option value="{{ $unit->hr_unit_id }}" {{ $requisition->hr_unit_id == $unit->hr_unit_id ? 'selected' : '' }}>{{ $unit->hr_unit_short_name }}</option>
                                            @endforeach
                                            @endif
                                        @else
                                            <option value="{{ $requisition->hr_unit_id }}">{{ $requisition->unit->hr_unit_short_name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="saleable"><strong>Saleable ?<span class="text-danger">&nbsp;*</span></strong></label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <select name="saleable" id="saleable" class="form-control">
                                        <option value="yes" {{ $requisition->saleable == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ $requisition->saleable == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="category_id">{{ __('Category') }}:</label> </p>
                                <div class="input-group input-group-md mb-3 d-">
                                    @if($requisition->status==1)
                                        <input type="text" name="category_id" value="{{isset($requisition->items[0]->product->category->category->name)?$requisition->items[0]->product->category->category->name:''}}" class="form-control" readonly>
                                    @else
                                    
                                    <select name="category_id" id="category_id" class="form-control category" onchange="getSubCategories()">
                                        @if(isset($categories[0]))
                                        <optgroup label="Products">
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }} data-sub-category-ids="{{ $category->subCategory->pluck('id')->implode(',') }}" data-sub-category-names="{{ $category->subCategory->pluck('name')->implode(',') }}"  data-sub-category-codes="{{ $category->subCategory->pluck('code')->implode(',') }}">{{
                                                $category->name.'('.$category->code.')'}}
                                            </option>
                                            @endforeach
                                        </optgroup>
                                        @endif

                                        @if(isset($fixedAssetCategories[0]))
                                        <optgroup label="Fixed Assets">
                                            @foreach($fixedAssetCategories as $category)
                                            <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }} data-sub-category-ids="{{ $category->subCategory->pluck('id')->implode(',') }}" data-sub-category-names="{{ $category->subCategory->pluck('name')->implode(',') }}"  data-sub-category-codes="{{ $category->subCategory->pluck('code')->implode(',') }}">{{
                                                $category->name.'('.$category->code.')'}}
                                            </option>
                                            @endforeach
                                        </optgroup>
                                        @endif

                                        @if(isset($cwipCategories[0]))
                                        <optgroup label="CWIP">
                                            @foreach($cwipCategories as $category)
                                            <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }} data-sub-category-ids="{{ $category->subCategory->pluck('id')->implode(',') }}" data-sub-category-names="{{ $category->subCategory->pluck('name')->implode(',') }}"  data-sub-category-codes="{{ $category->subCategory->pluck('code')->implode(',') }}">{{
                                                $category->name.'('.$category->code.')'}}
                                            </option>
                                            @endforeach
                                        </optgroup>
                                        @endif
                                    </select>
                                    @endif
                                </div>
                            </div>

                            @if(isset($task->id))
                            <div class="col-md-3 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="project_task_id">{{ __('Project Information') }}:</label> </p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <ul>
                                        <li>Project : {{$task->subDeliverable->deliverable->project->name}}</li>
                                        <li>Phase : {{$task->subDeliverable->deliverable->name}}</li>
                                        <li>Milestone : {{$task->subDeliverable->name}}</li>
                                        <li>Task : {{$task->name}}</li>
                                    </ul>
                                    <input type="hidden" name="project_task_id" value="{{$task->id}}">
                                </div>
                            </div> 
                            @endif

                            <div class="col-md-12  table-responsive style-scroll">

                                <table class="table table-striped table-bordered miw-500 dac_table" cellspacing="0" width="100%" id="dataTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width: 10%" rowspan="2">Sub Category</th>
                                            <th style="width: 20%" rowspan="2">Product</th>
                                            <th style="width: 35%" rowspan="2">Attributes</th>
                                            <th style="width: 10%" rowspan="2">Quantity</th>
                                            @if(auth()->user()->hasPermissionTo('department-requisition-edit') && $requisition->author_id != auth()->user()->id)
                                            <th style="width: 10%" rowspan="2">Approved Quantity</th>
                                            @php $modifiedName = true; @endphp
                                            @endif
                                            <th style="width: 20%" colspan="2">Budgeted Amount</th>
                                            <th style="width: 5%" rowspan="2" class="text-center">Action</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th style="width: 10%">Unit Amount</th>
                                            <th style="width: 10%">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="field_wrapper">
                                        @php
                                            $oldProductIds = [];
                                        @endphp
                                        @if(isset($requisition->items[0]))
                                        @foreach($requisition->items as $key => $requisitionItem)
                                        <tr>
                                            <td>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    @if($requisition->status == 1)
                                                        <input type="text" name="category_id" value="{{isset($requisitionItem->product->category->name)?$requisitionItem->product->category->name:''}}" class="form-control" readonly>
                                                    @else
                                                        <select name="sub_category_id[]" id="subCategoryId_{{$key}}" class="form-control subcategory" onchange="getProduct($(this))" data-selected="{{ $requisitionItem->product->category_id }}">
                                                            {!! $subCategories !!}
                                                        </select>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    @if($requisition->status==1)
                                                        <input type="text" name="category_id" value="{{isset($requisitionItem->product->name)?$requisitionItem->product->name:''}}" class="form-control" readonly>
                                                    @else
                                                        <select name="product_id[]" id="product_{{$key}}" class="form-control product existing-products requisition-products" data-selected-product="{{ $requisitionItem->product_id }}" data-selected-sub-category="{{ $requisitionItem->product->category_id }}" required onchange="getAttributes($(this))" data-item-id="{{ $requisitionItem->id }}" data-serial="{{ $key+1 }}">
                                                            {!! $subCategoryProducts[$requisitionItem->product->category_id] !!}
                                                        </select>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="product-attributes-{{ $key+1 }}">
                                                {!! $productAttributes[$requisitionItem->id] !!}
                                            </td>
                                            <td>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="number" @if($modifiedName) readonly name="old_qty[]" value="{{ old('qty',$requisitionItem->requisition_qty) }}" @else name="qty[]" value="{{ old('qty',$requisitionItem->qty) }}" @endif  min="1" max="99999999" id="qty_{{$key}}" onKeyPress="if(this.value.length==6) return false;" class="form-control {{ !$modifiedName ? 'requisition-qty' : ''  }} text-right" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required {{($requisition->status==1)?'readonly':''}} onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">
                                                </div>
                                            </td>
                                            @if(auth()->user()->hasPermissionTo('department-requisition-edit') && $requisition->author_id != auth()->user()->id)
                                            <td>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="number" name="qty[]" min="1" max="99999999" id="qty_{{$key}}" onKeyPress="if(this.value.length==6) return false;" class="form-control requisition-qty text-right" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ old('qty',$requisitionItem->qty) }}" onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">
                                                </div>
                                            </td>
                                            @endif
                                            <td>
                                                <div class="input-group input-group-md mb-3 d-">
                                                    <input type="number" @if($modifiedName) readonly name="old_unit_price[]" value="{{ old('unit_price', $requisitionItem->unit_price) }}" @else name="unit_price[]" value="{{ old('unit_price', $requisitionItem->unit_price) }}" @endif  min="0" step="any" id="unit_price_{{$key}}" class="form-control requisition-unit_price text-right" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required {{($requisition->status==1)?'readonly':''}} onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">
                                                </div>
                                            </td>
                                            <td class="total-amount text-right">{{ $requisitionItem->unit_price*$requisitionItem->qty }}</td>
                                            <td>
                                                @if($requisition->status !=1)
                                                <a href="javascript:void(0);" id="remove_{{$key}}" class="remove_button btn btn-danger btn-sm" style="margin-right:17px;" title="Remove" >
                                                    <i class="las la-trash"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="{{ auth()->user()->hasPermissionTo('department-requisition-edit') && $requisition->author_id != auth()->user()->id ? 6 : 5 }}" class="text-right"><strong>Total Budgeted Amount:</strong></td>
                                            <td class="grand-total-amount text-right">0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                @if(auth()->user()->id == $requisition->author_id)
                                     @if($requisition->status !=1)
                                    <a href="javascript:void(0);" style="margin-right:27px;" class="add_button btn btn-sm btn-primary pull-right" title="Add More Product">
                                        <i class="las la-plus"></i>
                                    </a>
                                    @endif
                                @endif
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1 font-weight-bold"><label for="cost_centre_id"><strong>Cost Centre: <span class="text-danger">*</span></strong></label>
                                        {!! $errors->has('cost_centre_id')? '<span class="text-danger text-capitalize">'.
                                        $errors->first('cost_centre_id').'</span>':'' !!}</p>
                                        <div class="form-group form-group-lg mb-3 d-">
                                            <select name="cost_centre_id" id="cost_centre_id" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1 font-weight-bold">
                                            <label for="edit_file"><strong>Attachment:</strong></label>
                                            @if(!empty($requisition->attachment) && file_exists(public_path($requisition->attachment)))
                                                <a class="text-primary" href="{{ url($requisition->attachment) }}" target="_blank">View Existing Attachment</a>
                                            @endif 
                                            {!! $errors->has('edit_file')? '<span class="text-danger text-capitalize">'. $errors->first('edit_file').'</span>':'' !!}
                                        </p>
                                        <div class="form-group form-group-lg mb-3 d-">
                                            <input type="file" name="edit_file" id="edit_file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <p class="mb-1 font-weight-bold"><label for="explanations"><strong>Explanations:</strong><strong class="text-danger">*</strong></label> {!!
                                    $errors->has('explanations')? '<span class="text-danger text-capitalize">'.
                                    $errors->first('explanations').'</span>':'' !!}</p>
                                <div class="form-group form-group-lg mb-3 d-">
                                    {{-- <select name="explanations[]" id="explanations" multiple class="form-control">
                                        @if(isset($explanations[0]))
                                        @foreach($explanations as $key => $explanation)
                                        <option {{ in_array($explanation->explanation, json_decode(!empty($requisition->explanations) ? $requisition->explanations : '', true)) ? 'selected' : '' }}>{{ $explanation->explanation }}</option>
                                        @endforeach
                                        @endif
                                    </select> --}}
                                    <textarea rows="3" name="explanations" id="explanations" class="form-control rounded word-restrictions" aria-label="Large" aria-describedby="inputGroup-sizing-sm" data-input="recommended" required>{!! old('explanations', $requisition->explanations) !!}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <p class="mb-1 font-weight-bold"><label for="remarks"><strong>Notes:</strong></label> {!! $errors->has('remarks')? '<span class="text-danger text-capitalize">'. $errors->first('remarks').'</span>':'' !!}</p>
                                <div class="form-group form-group-lg mb-3 d-">
                                    <textarea rows="3" name="remarks" id="remarks" class="form-control rounded word-restrictions" aria-label="Large" aria-describedby="inputGroup-sizing-sm" data-input="recommended">{!! old('remarks',$requisition->remarks) !!}</textarea>
                                </div>

                                <input type="hidden" name="status" value="{{$requisition->status}}">

                                @if($modifiedName)
                                <input type="hidden" name="approval_qty" value="true">
                                <input type="hidden" name="project_id" value="{{$requisition->project_id}}">
                                <input type="hidden" name="deliverable_id" value="{{$requisition->deliverable_id}}">
                                @else
                                <input type="hidden" name="approval_qty" value="false">
                                @endif
                                <input type="hidden" name="hr_unit_id" value="{{$requisition->hr_unit_id}}">
                                <input type="hidden" name="author_id" value="{{$requisition->author_id}}">
                                <input type="hidden" name="created_by" value="{{$requisition->created_by}}">
                            </div>

                            
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                   <i class="la la-arrow-down"></i> Notes History
                                </button>
                                
                            </div>
                            <div class="col-6">
                                <button type="button" onclick="updateRequisition()" class="btn btn-primary rounded pull-right update-button"><i class="la la-plus"></i> {{ __('Update Requisition') }}</button>
                            </div>

                        </div>
                    </div>
                </form>

            </div>

            <div class="panel-body">
                <div class="collapse" id="collapseExample">
                  <div class="row">
                    @foreach($requisition->requisitionNoteLogs as $key => $log)
                    <div class="col-md-6 {{ in_array($log->type, ['department-head']) ? 'offset-md-6' : '' }}">
                        <div class="panel">
                            <div class="panel-body">
                                <p>{{ $log->notes }}</p>
                                <br>
                                <small>{{$log->createdBy->name}}&nbsp;&nbsp;|&nbsp;&nbsp;{{ ucwords(implode(' ', explode('-', $log->type))) }}&nbsp;&nbsp;|&nbsp;&nbsp;{{ date('Y-m-d g:i a', strtotime($log->created_at))}}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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

    var selectedProductIds = ["{{ implode(",", $oldProductIds) }}"];

    function changeSelectedProductIds() {
        selectedProductIds = [];
        $('.product').each(function () {
            selectedProductIds.push($(this).val());
        })
    }

    $(document).ready(function(){
        // getSubCategories();

        $.each($('.subcategory'), function(index, val) {
            $(this).val($(this).attr('data-selected')).select2();
        });

        $.each($('.requisition-products'), function(index, val) {
            $(this).val($(this).attr('data-selected-product')).select2();
        });

        var maxField = 500;
        var addButton = $('.add_button');
        var x = parseInt("{{ $requisition->items->count()+1 }}"); 
        var wrapper = $('.field_wrapper');
        $(addButton).click(function(){
            x++;

            var fieldHTML = '<tr>\n' +
            '                                            <td>\n' +
            '                                                    <div class="input-group input-group-md mb-3 d-">\n' +
            '                                                        <select name="sub_category_id[]" id="subCategoryId_'+x+'" class="form-control subcategory" onchange="getProduct($(this))"></select>\n' +
            '                                                    </div>\n' +
            '\n' +
            '                                                </td>'+

            '                                            <td>\n' +
            '\n' +
            '                                                <div class="input-group input-group-md mb-3 d-">\n' +
            '                                                    <select name="product_id[]" id="product_'+x+'" class="form-control select2 product requisition-products" required onchange="getAttributes($(this))" data-item-id="0" data-serial="'+(x-1)+'">\n' +
            '                                                        <option value="{{ null }}">{{ __("--Select Product--") }}</option>\n' +
            '                                                    </select>\n' +
            '                                                </div>\n' +
            '\n' +
            '                                            <td class="product-attributes-'+(x-1)+'"></td>' +
            '                                            <td>\n' +
            '                                                <div class="input-group input-group-md mb-3 d-">\n' +
            '                                                    <input type="number" name="qty[]" min="1" max="99999999" id="qty_'+x+'" onKeyPress="if(this.value.length==6) return false;" class="form-control requisition-qty text-right" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ old("qty") }}" onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">\n' +
            '                                                </div>\n' +
            '                                            </td>\n'+
            '@if($modifiedName)\n' +
            '<td></td>\n'+
            '@endif\n' +
            '                                            <td>\n' +
            '                                                <div class="input-group input-group-md mb-3 d-">\n' +
            '                                                    <input type="number" name="unit_price[]" min="0" value="0" step="any" id="unit_price_'+x+'" class="form-control requisition-unit_price text-right" aria-label="Large" aria-describedby="inputGroup-sizing-sm" required value="{{ old("unit_price") }}" onchange="calculateTotal($(this))" onkeyup="calculateTotal($(this))">\n' +
            '                                                </div>\n' +
            '                                            </td>\n'+
            '<td class="total-amount text-right">0.00</td>'+
            '                                            <td>\n' +
            '                                                <a href="javascript:void(0);" id="remove_'+x+'" class="remove_button btn btn-sm btn-danger" title="Remove" >\n' +
            '                                                    <i class="las la-trash"></i>\n' +
            '                                                </a>\n' +
            '                                            </td>\n' +
            '\n' +
            '                                        </tr>';

            $(wrapper).append(fieldHTML);
            $('#subCategoryId_'+x, wrapper).select2();
            $('#product_'+x, wrapper).select2();
            generateSubCategories($('#subCategoryId_'+x, wrapper))
            // getProduct($('#subCategoryId_'+x, wrapper));
        });


            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                x--;

                var incrementNumber = $(this).attr('id').split("_")[1];
                var productVal=$('#product_'+incrementNumber).val()

                const index = selectedProductIds.indexOf(productVal);
                if (index > -1) {
                    selectedProductIds.splice(index, 1);
                }
                $(this).parent('td').parent('tr').remove();
                
            });


        });

        function getSubCategories() {
            $.each($('.subcategory'), function(index, val) {
                generateSubCategories($(this));
            });
        }

        function generateSubCategories(element) {
            var category = $('#category_id').find(':selected');
            var ids = (category.attr('data-sub-category-ids') ? category.attr('data-sub-category-ids').split(',') : []);
            var names = (category.attr('data-sub-category-names') ? category.attr('data-sub-category-names').split(',') : []);
            var codes = (category.attr('data-sub-category-codes') ? category.attr('data-sub-category-codes').split(',') : []);

            var value = element.val();
            var subCategories = '<option value="">Select Subcategory</option>';
            $.each(ids, function(index, val) {
                if(val > 0){
                    subCategories += '<option value="'+(val)+'" '+(val == value ? 'selected' : '')+'>'+(names[index])+' ('+(codes[index])+')</option>';
                }
            });

            element.html(subCategories).change();
        }
    </script>

    <script>
        $(document).ready(function() {
            var wrapper = $('.field_wrapper');
            $(wrapper).on('change','.product', function (e) {
                changeSelectedProductIds();
                var incrementNumber = $(this).attr('id').split("_")[1];
                $(this).parent().parent().parent().find('.subcategory').val(parseInt($(this).find(':selected').attr('data-sub-category-id'))).select2();
            });

            var form = $('#editRequisitionForm');
            var updateButton = $('.update-button');
            var updateButtonContent = updateButton.html();
            form.submit(function(event) {
                event.preventDefault();
                
                updateButton.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');
                // saveNewButton.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please wait...');

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
                        updateButton.prop('disabled', false).html(updateButtonContent);
                    }
                })
                .fail(function(response) {
                    $.each(response.responseJSON.errors, function(index, val) {
                        toastr.error(val[0]);
                    });

                    updateButton.prop('disabled', false).html(updateButtonContent);
                });
            });
        });

        function getProduct(element){
            var incrementNumber = element.attr('id').split("_")[1];

            changeSelectedProductIds();

            var subcategory_id = $('#subCategoryId_' + incrementNumber).val();
            var selected_category = $('#product_' + incrementNumber).attr('data-selected-sub-category');
            var selected_product = $('#product_' + incrementNumber).attr('data-selected-product');

            if (subcategory_id.length === 0) {
                subcategory_id = 0;
            }

            $('#product_' + incrementNumber).html('<option value="">Please Wait...</option>');
            $.ajax({
                url: '{{url(request()->route()->getPrefix()."requisition/load-category-wise-product")}}/'+subcategory_id+'?parent_id='+$('#category_id').val()+'&products_id='+selectedProductIds+'&selected='+selected_product,
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                $('#product_' + incrementNumber).html(response).change();
                getAttributes($('#product_' + incrementNumber));
            });
        }


        (function ($){
            "use script";
            $("#project_id").on('change', (e)=> {
                let project = e.target.value;
                $("#deliverable_id").empty();
                if(project) {
                    $.ajax({
                        type: 'get',
                        url: `${e.target.getAttribute("data-url")}/${project}`,
                        success: (data) => {
                            $("#deliverable_id").empty().append(data)
                        },
                    })
                }
            });
        })(jQuery);

        function updateRequisition() {
            var error_count = 0;
            if($('#explanations').val() == ""){
                error_count++;
                toastr.error("Please write explanations.");
            }

            if($('#category_id').find(':selected').val() == ""){
                error_count++;
                toastr.error("Please Choose Category");
            }

            $.each($('.requisition-products'), function(index, val) {
                if($(this).val() == "" || parseInt($(this).val()) <= 0){
                    error_count++;
                    toastr.error("Please choose Product");
                }
            });

            $.each($('.requisition-qty'), function(index, val) {
                if($(this).val() == "" || parseInt($(this).val()) <= 0){
                    error_count++;
                    toastr.error("Please write Product Quantity");
                }
            });

            if(error_count == 0){
                $('#editRequisitionForm').submit();
            }
        }

        function getAttributes(element) {
            var product_id = parseInt(element.find(':selected').val());
            var item_id = parseInt(element.attr('data-item-id'));
            if(product_id > 0){
                $.ajax({
                    url: "{{ url('pms/requisition/requisition/create') }}?get-attributes&product_id="+product_id+"&item_id="+item_id+"&serial="+element.attr('data-serial'),
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

        getCostCentres();
        function getCostCentres() {
            $('#cost_centre_id').html('<option value="{{ null }}">Please wait...</option>');
            $.ajax({
                url: "{{ url('pms/requisition/requisition/create') }}?get-cost-centres&hr_unit_id="+$('#hr_unit_id').val(),
                type: 'GET',
                data: {},
            })
            .done(function(response) {
                $('#cost_centre_id').html(response).select2().val('{{ $requisition->cost_centre_id }}').trigger("change");
            });
        }
    </script>
    @endsection
