<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @if(isset($attributes[0]))
            @foreach($attributes  as $attribute)
            <div class="icheck-primary d-inline">
                <input type="checkbox" id="attribute-{{ $product->id }}-{{ $attribute->id }}" name="product_attributes[{{ $product->id }}][]" value="{{ $attribute->id }}" onchange="showAttributeoptions($(this))" class="attributes" {{ in_array($attribute->id, $selectedAttributes) ? 'checked' : '' }}>
                <label for="attribute-{{ $product->id }}-{{ $attribute->id }}" class="text-primary mb-1">
                  {{ $attribute->name }}&nbsp;&nbsp;&nbsp;
                </label>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    @if(isset($attributes[0]))
    @foreach($attributes  as $attribute)
    @php
        $options = $attribute->options->whereIn('id', $attribute->categories->where('category_id', $product->category_id)->count() > 0 ? (!empty($attribute->categories->where('category_id', $product->category_id)->first()->options) && isset(json_decode($attribute->categories->where('category_id', $product->category_id)->first()->options, true)[0]) ? json_decode($attribute->categories->where('category_id', $product->category_id)->first()->options, true) : []) : []);
    @endphp
    <div class="col-md-3 attribute-option-div attribute-option-div-{{ $attribute->id }}" @if(!in_array($attribute->id, $selectedAttributes)) style="display: none;" @endif>
        <div class="form-group">
            <label><strong>{{ $attribute->name }}</strong></label>
            <select class="form-control attribute-select2-{{ $product->id }}" name="attribute_options[{{ $product->id }}][{{ $attribute->id }}]">
                @if($options->count() > 0)
                @foreach($options as $option)
                <option value="{{ $option->id }}" {{ isset($item->attributes[0]) && $item->attributes->where('attribute_option_id', $option->id)->count() > 0 ? 'selected' : '' }}>{{ $option->name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    @endforeach
    @endif
</div>

<script type="text/javascript">
    $('.attribute-select2-{{ $product->id }}').select2({
        tags: true
    });
</script>