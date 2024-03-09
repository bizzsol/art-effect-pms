<div style="width: 35px;" class="text-center">
	@if($paymentDetails['payment-bill-amount']-($paymentDetails['payment-payment-paid']) > 0)
		<input type="checkbox" name="payment_id[]" class="payment-checkboxes" value="{{$payment->id}}" checked style="transform: scale(1.5, 1.5)">
	@endif
</div>