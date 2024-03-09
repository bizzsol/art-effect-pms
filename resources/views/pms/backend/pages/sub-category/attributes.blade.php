@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)

@section('main-content')
<style type="text/css" media="screen">
    .select2-container{
        width:  100% !important;
    }
</style>
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
            <div class="panel panel-info">
                <div class="panel-body mb-5 pb-5">
                    <form action="{{ url('pms/product-management/sub-category/'.$subcategory->id.'/update-attributes') }}" method="post" accept-charset="utf-8">
                    @csrf
                        <h5><strong>{{ $subcategory->name }} Attributes</strong></h5>
                        <hr>
                        <div class="form-group">
                            <label for="attributes">
                                <strong>Attributes:</strong>
                            </label>
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="productAttributes[]" id="attributes" class="form-control rounded select2" multiple data-placeholder="Choose Attributes" onchange="updateAttributes()">
                                    @if(isset($attributes[0]))
                                    @foreach($attributes as $key => $attribute)
                                        <option value="{{ $attribute->id }}" {{ in_array($attribute->id, isset($categoryAttributes[0]) ? $categoryAttributes : []) ? 'selected' : '' }}>{{ $attribute->name }}</attribute>
                                    @endforeach
                                    @endif
                                </select>
                            </div> 
                        </div>
                        <div class="form-group row">
                        @php
                            $categoryAttributes = \App\Models\PmsModels\CategoryAttribute::where([
                                'category_id' => $subcategory->id,
                            ])->get();
                        @endphp
                        @if(isset($attributes[0]))
                        @foreach($attributes as $key => $attribute)
                        @php
                            $categoryAttribute = $categoryAttributes->where('attribute_id', $attribute->id)->first();

                            $options = (isset($categoryAttribute->id) && !empty($categoryAttribute->options) ? json_decode($categoryAttribute->options, true) : []); 
                        @endphp
                            <div class="col-md-6 mb-3 attributes attribute-{{ $attribute->id }}" style="display: none">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-body p-0" style="border: 1px solid #ccc;padding: 5px 10px 5px 10px !important">
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <label class="mb-1" for="attribute-{{ $attribute->id }}">
                                                            <strong>{{ $attribute->name }}:</strong>
                                                        </label>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <select name="attributeOptions[{{ $attribute->id }}][]" id="attribute-{{ $attribute->id }}" class="form-control rounded select2" multiple>
                                                                @if(isset($attribute->options[0]))
                                                                @foreach($attribute->options as $key => $option)
                                                                    <option value="{{ $option->id }}" {{ isset($options[0]) && in_array($option->id, $options) ? 'selected' : '' }}>{{ $option->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div> 
                                                    </div>
                                                    <div class="col-md-3 pl-0">
                                                        <label class="mb-1" for="attribute-serial-{{ $attribute->id }}">
                                                            <strong>Serial:</strong>
                                                        </label>
                                                        <div class="input-group input-group-md mb-3 d-">
                                                            <input type="number" name="attributeSerials[{{ $attribute->id }}]" id="attribute-serial{{ $attribute->id }}" value="{{ isset($categoryAttribute->serial) ? $categoryAttribute->serial : 1 }}" class="form-control text-right" min="1">
                                                        </div> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endif
                        </div>
                        <button type="submit" class="btn btn-md btn-success"><i class="la la-edit"></i>&nbsp;Update Sub Category Attributes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('page-script')
<script>
    updateAttributes();
    function updateAttributes(){
        var attributes = $("#attributes :selected").map(function(i, el) {
            return $(el).val();
        }).get();

        $('.attributes').hide();
        $.each(attributes, function(index, attribute) {
            $('.attribute-'+attribute).show();
            $('.attribute-serial-'+attribute).show();

            $('.select2').select2({width: '100%'});
        });
    }
</script>
@endsection
