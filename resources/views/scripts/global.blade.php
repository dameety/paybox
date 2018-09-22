<script>
    window.Paybox = {
        symbol: '{{ config('paybox.currency.symbol') }}',
        stripeKey: '{{ config('paybox.stripePublicKey') }}',
    }
</script>