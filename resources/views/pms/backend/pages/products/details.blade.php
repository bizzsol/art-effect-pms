<table class="table table-bordered table-striped">
	<tbody>
		<tr>
			<td style="width: 20%">SKU</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->sku }}</td>
		</tr>
		<tr>
			<td style="width: 20%">Name</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->name }}</td>
		</tr>
		<tr>
			<td style="width: 20%">Category</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->category->name }}</td>
		</tr>
		<tr>
			<td style="width: 20%">UOM</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->productUnit->unit_name }}</td>
		</tr>
		<tr>
			<td style="width: 20%">Unit Price:</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->unit_price }}</td>
		</tr>
		<tr>
			<td style="width: 20%">Sales Price:</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->sales_price }}</td>
		</tr>
		<tr>
			<td style="width: 20%">TAX:</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->tax }}%</td>
		</tr>
		<tr>
			<td style="width: 20%">VAT:</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->vat }}%</td>
		</tr>
		<tr>
			<td style="width: 20%">Product Attributes:</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ getProductAttributes($product->id) }}</td>
		</tr>
		<tr>
			<td style="width: 20%">Suppliers</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->suppliers->pluck('name')->implode(', ') }}</td>
		</tr>
		<tr>
			<td style="width: 20%">Added By</td>
			<td style="width: 5%;" class="text-center"><strong>:</strong></td>
			<td>{{ $product->creator->name }}</td>
		</tr>
	</tbody>
</table>