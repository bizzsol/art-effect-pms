<ul class="pl-3">
    <li>Category: <strong>{{ isset($product->category->name)?$product->category->name:'' }}</strong></li>
    <li>Product Name: <strong>{{ $product->name }}</strong></li>
    <li>Attributes: {!! selectedProductAttributes($product->id, $attributeOptions) !!}</li>
</ul>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 70%">Warehouse</th>
            <th style="width: 30%" class="text-right">Stock</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inventoryDetails as $key => $values)
        <tr>
            <td style="width: 70%">{{isset($values->relWarehouse->name)?$values->relWarehouse->name:''}}</td>
            <td style="width: 30%" class="text-right">{{$values->qty}}</td>
        </tr>
        @endforeach

        <tr>
            <td style="width: 70%"><strong>Total:</strong></td>
            <td style="width: 30%" class="text-right">
                <strong>{{ $inventoryDetails->sum('qty') }}</strong>
            </td>
        </tr>
    </tbody>
</table>