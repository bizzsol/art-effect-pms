<div style="overflow: hidden;">
<hr>
<form action="{{ url('pms/product-management/product/'.$product->id.'/update-suppliers') }}" method="post" accept-charset="utf-8" id="update-suppliers-form">
@csrf
<h5 class="text-center">
    Update Suppliers for
    <br>
    <strong>{{ $product->name }} {{ getProductAttributesFaster($product) }}</strong>
</h5>
<hr>
<div class="row" style="padding-bottom: 200px;">
    <div class="col-md-12">
        <p class="mb-1 font-weight-bold"><label for="supplier">{{ __('Supplier') }}</label> {!! $errors->has('supplier')? '<span class="text-danger text-capitalize">'. $errors->first('supplier').'</span>':'' !!}</p>
        <div class="select-search-group input-group input-group-md mb-3 d-">
            <select name="supplier[]" id="supplier" class="form-control rounded select2" multiple style="width: 100%">
                @if(isset($suppliers[0]))
                @foreach($suppliers as $key => $supplier)
                <option value="{{ $supplier->id }}" {{ in_array($supplier->id, $existedSuppliers) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-12 text-center">
        <button class="btn btn-success btn-md update-suppliers-button" type="submit"><i class="la la-check"></i>&nbsp;Update Suppliers</button>
    </div>
</div>
</form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").each(function() {
            $(this).select2({
              dropdownParent: $(this).parent()
            });
        });

        var form = $('#update-suppliers-form');
        var button = $('.update-suppliers-button');
        var content = button.html();

        form.submit(function(event) {
            event.preventDefault();

            button.prop('disabled', true).html('<i class="las la-spinner la-spin"></i>&nbsp;Please Wait...');
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                dataType: 'json',
                data: form.serializeArray(),
            })
            .done(function(response) {
                if(response.success){
                  toastr.success(response.message);
                }else{
                  toastr.error(response.message);
                }

                button.prop('disabled', false).html(content);
            });
        });
    });
</script>