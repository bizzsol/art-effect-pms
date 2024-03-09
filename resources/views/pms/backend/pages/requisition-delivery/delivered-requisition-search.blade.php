<table class="table table-striped table-bordered table-head datatable-exportable" id="dataTable" data-table-name="Delivered Requisition" border="1">
    <thead>
        <tr class="text-center">
           <th width="2%">SL No</th>
           <th>Requisition No</th>
           <th>Requisition Date</th>
           <th>Delivered Ref. No</th>
           <th>Delivered Date</th>
           <th>Category</th>
           <th>SubCategory</th>
           <th>Product</th>
           <th>Delivered Qty</th>
           <th class="text-center">Action</th>
       </tr>
   </thead>
   <tbody>
       @if(isset($deliveredRequisition))
       @foreach($deliveredRequisition as $key=>$values)
       <tr>
        <td class="text-center" width="5%">{{($deliveredRequisition->currentpage()-1) * $deliveredRequisition->perpage() + $key + 1 }}</td>
        <td><a href="javascript:void(0)"
            data-src="{{route('pms.requisition.list.view.show',$values->relRequisitionDelivery->relRequisition->id)}}"  class="btn btn-link requisition m-1 rounded showRequistionDetails">{{ isset($values->relRequisitionDelivery->relRequisition->reference_no)?$values->relRequisitionDelivery->relRequisition->reference_no:'' }}</a></td>

            <td>{{ isset($values->relRequisitionDelivery->relRequisition->requisition_date)? date('d-m-Y',strtotime($values->relRequisitionDelivery->relRequisition->requisition_date)):'' }}</td>

            <td>
                {{isset($values->relRequisitionDelivery->reference_no)?$values->relRequisitionDelivery->reference_no:''}}
            </td>

            <td>{{ isset($values->relRequisitionDelivery->delivery_date)? date('d-m-Y',strtotime($values->relRequisitionDelivery->delivery_date)):'' }}</td>
            <td>
                {{ isset($values->product->category->category->name)?$values->product->category->category->name:'' }}
            </td>

            <td>
                {{ isset($values->product->category->name)?$values->product->category->name:'' }}
            </td>
            <td>{{ isset($values->product->name)?$values->product->name:'' }} {{ isset($values->product->id)? getProductAttributes($values->product->id):'' }}</td>
            <td class="text-center">{{ number_format($values->delivery_qty,0)}}</td>

            <td class="text-center" id="action{{$values->id}}">
                @if($values->status=='pending' ||$values->status=='delivered')
                <div class="btn-group">
                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                        <span id="statusName{{$values->id}}">
                            {{ ucfirst($values->status)}} 

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                              <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                          </svg>
                      </span>
                  </button>
                  <ul class="dropdown-menu">
                     <li id="hideBtn{{$values->id}}"><a href="javascript:void(0)" class="deliveredAcknowledge" data-id="{{$values->id}}" title="Acknowledged"><i class="la la-check"></i> {{ __('Acknowledged')}}</a>
                     </li>
                 </ul>
             </div>
             @else
             Acknowledged
             @endif
         </td>
     </tr>
     @endforeach
     @endif
 </tbody>

</table>
<div class="la-1x text-center">
    @if(count($deliveredRequisition)>0)
    <ul class="searchPagination">
        {{$deliveredRequisition->links()}}
    </ul>
    @endif
</div>