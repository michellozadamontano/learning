<form action="{{ route('subscriptions.epay_redirect') }}" method="POST">
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