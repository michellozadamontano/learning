<form action="{{ route('subscriptions.redirect') }}" method="GET">
    @csrf
    <input
        class="form-control"
        name="coupon"
        placeholder="{{ __("¿Tienes un cupón?") }}"
    />
    <input type="hidden" name="type" value="{{ $product['type'] }}" />
    <hr />
    <button class="btn btn-success">Subscribirme</button>
</form>