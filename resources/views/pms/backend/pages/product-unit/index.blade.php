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
              <li class="active">{{__($title)}} List</li>
              <li class="top-nav-btn">
                <a href="javascript:void(0)" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" title="Add Unit" id="addProductUnitBtn"> <i class="las la-plus">Add</i></a>
            </li>
        </ul>

    </div>

    <div class="page-content">
        <div class="">
            <div class="panel panel-info">
                <div class="panel-body">
                    @include('yajra.datatable')
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="productUnitAddModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productUnitAddModalLabel">Add New Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" data-src="{{ route('pms.product-management.product-unit.store') }}">
                @csrf
                @method('post')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4">
                            <p class="mb-1 font-weight-bold"><label for="unit_name">{{ __('Unit Name') }}:</label> {!! $errors->has('unit_name')? '<span class="text-danger text-capitalize">'. $errors->first('unit_name').'</span>':'' !!}</p>
                            <div class="input-group input-group-md mb-3 d-">
                                <input type="text" name="unit_name" id="unit_name" class="form-control rounded" aria-label="Large" placeholder="{{__('Unit Name')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('unit_name') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 font-weight-bold"><label for="unit_code">{{ __('Unit Code') }}:</label> {!! $errors->has('unit_code')? '<span class="text-danger text-capitalize">'. $errors->first('unit_code').'</span>':'' !!}</p>
                            <div class="input-group input-group-md mb-3 d-">
                                <input type="text" name="unit_code" id="unit_code" class="form-control rounded" readonly aria-label="Large" placeholder="{{__('Unit Code')}}" aria-describedby="inputGroup-sizing-sm" required value="{{ old('unit_code')?old('unit_code'):$unit_code }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 font-weight-bold"><label for="status">{{ __('Status') }}:</label> 
                                {!! $errors->has('status')? '<span class="text-danger text-capitalize">'. $errors->first('status').'</span>':'' !!}</p>
                                <div class="select-search-group  input-group input-group-md mb-3 d-">
                                   {!! Form::Select('status',$status,request()->old('status'),['id'=>'status', 'class'=>'form-control selectheighttype select2']) !!}
                               </div>
                           </div>
                       </div>
                       <div class="col-md-12 pl-0 pr-0">
                            <p class="mb-1 font-weight-bold"><label for="round_policy">Round Policy:</label> 
                                {!! $errors->has('round_policy')? '<span class="text-danger text-capitalize">'. $errors->first('round_policy').'</span>':'' !!}</p>
                                <div class="select-search-group  input-group input-group-md mb-3 d-">
                                    <select name="round_policy" id="round_policy" class="form-control">
                                       @foreach(roundPolicies() as $key => $policy)
                                       <option value="{{ $key }}">{{ $policy['title'] }} | {{ $policy['description'] }}</option>
                                       @endforeach
                                    </select>
                               </div>
                           </div>
                       </div>
                       <div class="col-md-12 mb-5 text-right">
                            <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-danger rounded" id="productFormSubmit">{{ __('Save') }}</button>
                       </div>
                   </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page-script')
@include('yajra.js')
<script>
    $('.select2').select2();
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
                    // if(value) form.reset();
        });
    };

    const modalShow = () => {
        $('#productUnitAddModal').modal('show');
        let url = $('#productUnitAddModal').find('form').attr('data-src');
        $('#productUnitAddModal').find('form').attr('action', url);
    };

    $('#addProductUnitBtn').on('click', function () {
        $('#productUnitAddModal').find('form')[0].reset();
        $('#productUnitAddModal').find('#productUnitAddModalLabel').html(`Add new Unit`);
        modalShow()
    });
    
    function editBtn(element){
        $.ajax({
            type: 'get',
            url: element.attr('data-src'),
            success:function (data) {
                if(!data.status){
                    showAlert('error', data.info);
                    return;
                }
                $('#productUnitAddModal').find('form')[0].reset();
                $('#productUnitAddModal').find('form').attr('data-src', data.info.src);
                $('#productUnitAddModal').find('form').find('input[name="_method"]').val(data.info.req_type);

                $('#productUnitAddModal').find('form').find('input[name="unit_name"]').val(data.info.unit_name);
                $('#productUnitAddModal').find('form').find('input[name="unit_code"]').val(data.info.unit_code);
                $('#productUnitAddModal').find('form').find('#round_policy').val(data.info.round_policy).change();

                if(data.info.status){
                    $('#status').select2('val',[data.info.status]);
                }
                modalShow()
            }
        })
    }

    function deleteBtn(element){
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
                $.ajax({
                    type: 'DELETE',
                    url: element.attr('data-src'),
                    success:function (data) {
                        if(data){
                            showAlert('error', data);
                            return;
                        }
                        swal({
                            icon: 'success',
                            text: 'Data deleted successfully',
                            button: false
                        });
                        setTimeout(()=>{
                            swal.close();
                        }, 1500);
                    },
                });
                element.parent().parent().remove();
            }
        });
    }


    function unitConversions(element) {
        $.dialog({
            title: 'Unit Conversion Matrix',
            content: "url:{{ url('pms/product-management/product-unit') }}/"+element.attr('data-unit-id')+'/unit-conversions',
            animation: 'scale',
            columnClass: 'large',
            closeAnimation: 'scale',
            backgroundDismiss: true,
        });
    }
</script>
@endsection
