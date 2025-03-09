@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .col-form-label{
        font-size: 14px;
        font-weight: 600;
    }
</style>
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
                <li><a href="#">PMS</a></li>
                <li class="active">{{__($title)}}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2" style="padding-bottom: 0px !important">
                <div class="row">
                    <div class="col-md-9">
                        <form action="{{ route('pms.product-management.attribute-options.index') }}" method="get" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-md mb-1 d-">
                                        <select name="attribute_id" id="attribute_id" class="form-control rounded" onchange="$(this).parent().parent().parent().parent().submit()">
                                            <option value="0">Choose Attributes</option>
                                            @if(isset($attributes[0]))
                                            @foreach($attributes as $key => $attribute)
                                            <option value="{{ $attribute->id }}" {{ request()->get('attribute_id') == $attribute->id ? 'selected' : '' }}>[{{ $attribute->code }}] {{ $attribute->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if(request()->has('attribute_id') && request()->get('attribute_id') > 0)
                                    @can('attribute-edit')

                                    <a class="text-primary pl-1" onclick="openModal('Edit Attribute', '{{ url('/pms/product-management/attributes/'.request()->get('attribute_id').'/edit') }}');" style="font-size: 12px"><i class="la la-edit"></i>&nbsp;Edit</a>
                                    @endcan
                                    @can('attribute-delete')
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <a class="text-danger deleteBtn" data-reload="1" data-src="{{ route('pms.product-management.attributes.destroy', request()->get('attribute_id')) }}" style="font-size: 12px"><i class="la la-trash"></i>&nbsp;Delete</a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    @endcan
                                    @endif
                                    @can('attribute-create')
                                    <a class="text-success" onclick="openModal('New Attribute', '{{ url('/pms/product-management/attributes/create') }}');" style="font-size: 12px"><i class="la la-plus"></i>&nbsp;New Attribute</a>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        @can('attribute-option-create')
                        <a class="btn btn-sm btn-success pull-right ml-2" href="{{ url('pms/product-management/attribute-options/create?attribute_id='.request()->get('attribute_id')) }}" style="float: right"><i class="la la-plus"></i>&nbsp;New Attribute Option</a>
                        @endcan
                    </div>
                </div>
            </div>
            @if(request()->has('attribute_id'))
            <div class="panel panel-info mt-2 p-2">
                @include('yajra.datatable')
               
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-lg">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script>
    (function ($) {
        "use script";
        const showAlert = (status, error) => {
            swal({
                icon: status,
                text: error,
                dangerMode: true,
                buttons: {
                    cancel: false,
                    confirm: {
                        text: "OK",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value)form.reset();
            });
        };

        $('.deleteBtn').on('click', function () {
            var reload = $(this).attr('data-reload');
            swal({
                title: "{{__('Are you sure?')}}",
                text: "{{__('Once you delete, You can not recover this data and related files.')}}",
                icon: "warning",
                dangerMode: true,
                buttons: {
                    cancel: true,
                    confirm: {
                        text: "Delete",
                        value: true,
                        visible: true,
                        closeModal: true
                    },
                },
            }).then((value) => {
                if(value){
                    var button = $(this);
                    $.ajax({
                        type: 'DELETE',
                        url: $(this).attr('data-src'),
                        dataType: 'json',
                        success:function (response) {
                            if(response.success){
                                swal({
                                    icon: 'success',
                                    text: response.message,
                                    button: false
                                });

                                setTimeout(()=>{
                                    if(reload == 1){
                                        window.open("{{ url('pms/product-management/attribute-options') }}", "_parent");
                                    }else{
                                        swal.close();
                                    }
                                }, 1500);

                                button.parent().parent().remove();
                            }else{
                                showAlert('error', response.message);
                                return;
                            }
                        },
                    });
                }
            });
        });
    })(jQuery)

    function openModal(title, link) {
        var modal = $('#modal');
        modal.modal('toggle');
        modal.find('.modal-title').html(title);
        modal.find('.modal-body').html('<h5 class="text-center">Please Wait...</h5>');
        $.ajax({
            url: link,
            type: 'GET',
            data: {},
        })
        .done(function(response) {
            modal.find('.modal-body').html(response);
        });
    }
</script>
@endsection