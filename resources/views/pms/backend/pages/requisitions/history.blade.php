@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .list-unstyled .ratings {
        display: none;
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

            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info">
                 <div class="panel-body">
                    <h4 class="pb-1">Requisition List</h4>
                    <table class="table table-striped table-bordered table-head " cellspacing="0" width="100%" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th width="2%">{{__('SL')}}</th>
                                <th>{{__('Reference No')}}</th>
                                <th>{{__('Requisition Date')}}</th>
                                <th>{{__('Product Category')}}</th>
                                <th>{{__('Requisition By')}}</th>
                                <th>{{__('Unit')}}</th>
                                <th>{{__('Attachment')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($requisition)
                            <tr>
                                <td width="5%" class="text-center">1</td>
                                <td>
                                    <a href="javascript:void(0)" data-src="{{route('pms.requisition.list.view.show',$requisition->id)}}" class="btn btn-link requisition m-1 rounded showRequistionDetails" onclick="requistionDetails($(this))">{{$requisition->reference_no }}</a>
                                </td>
                                <td>
                                    {{date('Y-m-d',strtotime($requisition->requisition_date))}}
                                </td>
                                <td>
                                    {{ isset($requisition->items[0]->product->category->category->name)?$requisition->items[0]->product->category->category->name:'' }}
                                </td>

                                <td>{{isset($requisition->relUsersList->name)? $requisition->relUsersList->name :''}}</td>
                                <td class="text-center">{{isset($requisition->relUsersList->employee->unit->hr_unit_short_name)?$requisition->relUsersList->employee->unit->hr_unit_short_name:''}}</td>

                                <td class="text-center">
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Department-Head') || auth()->user()->hasRole('Purchase-Department'))
                                    @if(!empty($requisition->attachment) && file_exists(public_path($requisition->attachment)))
                                    <a href="{{url($requisition->attachment)}}" target="_blank" class="btn btn-xs btn-primary"><i class="las la-paperclip"></i>Attachment</a>
                                    @endif
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($requisition->status==0)
                                    <span class="btn btn-sm btn-warning">Pending</span>
                                    @elseif($requisition->status==1)
                                    <span class="btn btn-sm btn-success">Approved</span>
                                    @elseif($requisition->status==2)
                                    <span class="btn btn-sm btn-danger">Halt</span>
                                    @elseif($requisition->status==3)
                                    <span class="btn btn-sm btn-warning">Draft</span>
                                    @endif
                                </td>
                            </tr>

                            @endif
                        </tbody>
                    </table>
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
                <h4 class="modal-title">Quotations Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="tableData">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endsection
@section('page-script')
<script>

</script>
@endsection