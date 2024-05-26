<hr class="mt-0 mb-0">
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 3.5%">SL</th>
            <th style="width: 20%">Approver</th>
            <th style="width: 55%">Logs</th>
            <th style="width: 7.5%">Reponse</th>
            <th style="width: 14%">DateTime</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($approvals[0]))
        @foreach($approvals as $key => $approval)
        @php
            $quotations = !empty($approval->logs) ? json_decode($approval->logs) : false;
        @endphp
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $approval->user->name }}</td>
            <td>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10%">SL</th>
                            <th style="width: 45%">Supplier</th>
                            <th style="width: 25%">Reference</th>
                            <th style="width: 20%">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotations as $q => $quotation)
                        <tr>
                            <td>{{ $q+1 }}</td>
                            <td>{{ $suppliers->where('id', $quotation->supplier_id)->first()->name }}</td>
                            <td class="text-center">{{ $quotation->reference_no }}</td>
                            <td class="text-center">{{ collect($quotation->rel_quotation_items)->where('product_id', $product_id)->first()->approved_qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td>{{ ucwords($approval->response) }}</td>
            <td>{{ date('Y-m-d g:i a', strtotime($approval->updated_at)) }}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>