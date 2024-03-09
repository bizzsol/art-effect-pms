<div style="overflow: hidden;">
    <hr>
<form action="{{ url('pms/product-management/product-unit/'.$unit->id.'/unit-conversions') }}" method="post" accept-charset="utf-8" id="unit-conversion-form">
@csrf
<h5 class="text-center">Unit Conversion Rates for <strong>{{ $unit->unit_name }}</strong></h5>
<hr>
<div class="row">
    @if(isset($units[0]))
    @foreach($units as $u)
    <div class="col-md-3 mb-2">
        <div class="form-group">
            <label for="unit-{{ $u->id  }}"><strong>{{ $u->unit_name }}</strong></label>
            <input type="number" step="any" name="units[{{ $u->id }}]" id="unit-{{ $u->id }}" value="{{ $unit->matrixes->where('conversion_unit_id', $u->id)->count() > 0 ? $unit->matrixes->where('conversion_unit_id', $u->id)->first()->conversion_rate : 0 }}" class="form-control">
        </div>
    </div>
    @endforeach
    @endif

    <div class="col-md-12 text-center">
        <button class="btn btn-success btn-md unit-conversion-button" type="submit"><i class="la la-check"></i>&nbsp;Update Unit Conversions</button>
    </div>
</div>
</form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var form = $('#unit-conversion-form');
        var button = $('.unit-conversion-button');
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