@if(isset($products[0]))
    @php
        $total = 0;
    @endphp
    @foreach($products as $key => $product)
        @php
            $qty = $inventoryDetails->where('product_id', $product->id)->sum('qty');
            $total += $qty;
        @endphp
        <tr>
            <td class="text-center">{{ $key+1 }}</td>
            <td>{{ isset($product->category->name)?$product->category->name:'' }}</td>
            <td><a onclick="showWarehouseStocks('{{ $product->id }}')" class="text-primary">{{ $product->name }}</a></td>
            <td>{!! selectedProductAttributes($product->id, $attributeOptions) !!}</td>
            <td class="text-center">{{ $qty }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4" class="text-right"><strong>Total:</strong></td>
        <td class="text-center"><strong>{{ $total }}</strong></td>
    </tr>
@else
    <tr>
        <td colspan="5" class="text-center"><h4>No Product Found for these attributes!</h4></td>
    </tr>
@endif