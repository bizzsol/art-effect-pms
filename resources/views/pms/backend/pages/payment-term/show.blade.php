
<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title editPaymentModal">{{$title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        {!! Form::open(array('route' => ['pms.payment-terms.update',$paymentTerm->id],'id'=>'paymentTermsFormEdit','class'=>'form-horizontal','method'=>'PUT','role'=>'form')) !!}

        <div class="modal-body">

            <div class="form-group row">
                <label for="term" class="control-label col-md-12"><strong>Payment Term <span class="text-danger">&nbsp;*</span></strong></label>
                <div class="col-md-12">
                    {!! Form::text('term' ,old('term',$paymentTerm->term),[ 'required'=>true,'class'=>'form-control rounded']) !!}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="percentage"><strong>Payment Percentage <span class="text-danger">&nbsp;*</span></strong></label>
                    <input id="percentage" required class="form-control rounded" name="percentage" type="number" min="1" max="100" value="{{ old('percentage',$paymentTerm->percentage) }}">
                </div>
                <div class="col-md-6">
                    <label for="days"><strong>Day Duration <span class="text-danger">&nbsp;*</span></strong></label>
                    <input id="days" required class="form-control rounded" name="days" type="number" min="1" max="9999" value="{{ old('days', $paymentTerm->days) }}">
                </div>
            </div>

            {{--<div class="form-group row">--}}
                {{--<label for="remarks" class="control-label col-md-12">Notes:</label>--}}
                {{--<div class="col-md-12">--}}
                    {{--{!! Form::textarea('remarks' ,old('remarks',$paymentTerm->remarks),['rows' => 2, 'required'=>false,'class'=>'form-control rounded']) !!}--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="type"><strong>Type</strong></label>
                    {!! Form::select('type',$type,$paymentTerm->type,['id'=>'type', 'required'=>true,'class'=>'form-control rounded','style'=>'width:100%']) !!}
                </div>
                <div class="col-md-6">
                    <label for="status"><strong>Status</strong></label>
                    {!! Form::select('status',$status,$paymentTerm->status,['required'=>true,'class'=>'form-control rounded','style'=>'width:100%']) !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-sm btn-primary text-white rounded">{{ __('Update') }}</button>
        </div>
        {!! Form::close(); !!}
    </div>
</div>
