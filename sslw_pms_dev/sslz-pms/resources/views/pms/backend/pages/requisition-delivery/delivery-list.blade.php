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
          </li>
      </ul><!-- /.breadcrumb -->
  </div>

  <div class="page-content">
    <div class="">
       <div class="panel panel-info">
          <div class="panel-body">

             <table class="table table-striped table-bordered table-head datatable-exportable" data-table-name="{{ $title }}" cellspacing="0" width="100%" id="dataTable">
                <thead>
                    <tr class="text-center">
                       <th width="5%" class="text-center">{{__('SL')}}</th>
                       <th>{{__('Requisition Date')}}</th>
                       <th>{{__('Requisition By')}}</th>
                       <th>{{__('Delivered Date')}}</th>
                       <th>{{__('Delivered Reference No')}}</th>
                       <th>{{__('Delivered Qty')}}</th>
                       <th>{{__('Delivered By')}}</th>
                   </tr>
               </thead>
               <tbody>

                   @forelse($requisitionDeliveries as $key=> $value)
                   <tr id="row{{$value->id}}">
                     <td class="text-center">{{$key+1}}</td>
                     <td>{{ isset($value->relRequisition->requisition_date)? date('d-m-Y', strtotime($value->relRequisition->requisition_date)):''}}</td>

                     <td>
                        <a href="javascript:void(0)" onclick="openModal({{$value->relRequisition->id}})"  class="btn btn-link">
                            {{isset($value->relRequisition->relUsersList->name)?$value->relRequisition->relUsersList->name:''}}  ({{ isset($value->relRequisition->requisitionItems)? $value->relRequisition->requisitionItems->sum('qty'):0}} Qty)
                        </a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($value->delivery_date))}} </td>
                    <td>
                        <a href="javascript:void(0)" onclick="openDeliveryModal({{$value->id}})" ta-toggle="tooltip" title="Click here to view details"  class="btn btn-link">
                            {{$value->reference_no}}
                        </a>
                    </td>
                    <td class="text-center"> {{$value->relDeliveryItems->sum('delivery_qty')}}</td>
                    <td> {{$value->relDeliveryBy->name}}</td>
                </tr>
                @empty
                @endforelse
            </tbody>
            <tfoot>
               <ul>{{$requisitionDeliveries->links()}}</ul>
           </tfoot>
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
        <div class="modal-header">
           <h4 class="modal-title">Requisition Details</h4>
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
<div class="modal" id="deliveryDetailModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delivery Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="detailTable">
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
    function openModal(requisitionId) {
        $('#tableData').load('{{URL::to(Request()->route()->getPrefix()."/store-inventory-compare")}}/'+requisitionId);
        $('#requisitionDetailModal').modal('show');
    }
    function openDeliveryModal(requisitionDeliveryId) {
        $('#detailTable').load('{{URL::to(Request()->route()->getPrefix()."/requisition-delivered-detail")}}/'+requisitionDeliveryId);
        $('#deliveryDetailModal .modal-title').html(`Delivery Product with Qty`);
        $('#deliveryDetailModal').modal('show');
    }
</script>
@endsection