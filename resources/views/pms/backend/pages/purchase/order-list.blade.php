@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
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
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{__($title)}}</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info p-4">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
@include('yajra.js')
<script>
     function poActions(element){
        var content = element.html();
        element.html('<i class="las la-spinner la-spin"></i>').prop('disabled', true);
        $.confirm({
            title: "Confirm!",
            content: "<hr>Are you confirm to <strong>"+element.attr('title')+"</strong> ?",
            buttons: {
                yes: {
                    text: '<i class="la la-check"></i>&nbsp;Confirm',
                    btnClass: 'btn-blue',
                    action: function(){
                        $.ajax({
                            url: element.attr('data-src'),
                            type: 'GET',
                            dataType: 'json',
                            data: {},
                        })
                        .done(function(response) {
                            if(response.success){
                                toastr.success(response.message);
                                reloadDatatable();
                            }else{
                                toastr.error(response.message);
                                element.html(content).prop('disabled', false);
                            }
                        })
                        .fail(function(response) {
                            var errors = '<ul class="pl-2">';
                            $.each(response.responseJSON.errors, function(index, val) {
                                errors += "<li class='text-white'>"+val[0]+"</li>";
                            });
                            errors += '</ul>';
                            toastr.error(errors);

                            element.html(content).prop('disabled', false);
                        });
                    }
                },
                no: {
                    text: '<i class="la la-times"></i>&nbsp;Close',
                    btnClass: 'btn-dark',
                    action: function(){
                        element.html(content).prop('disabled', false);
                    }
                }
            }
        });
     }
</script>
@endsection