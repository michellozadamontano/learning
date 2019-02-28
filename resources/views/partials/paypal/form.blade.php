<form action="{{ route('subscriptions.redirect') }}" method="GET">
    @csrf
    <input type="hidden" name="type" value="{{ $product['type'] }}" />
    <hr />
    <button class="btn btn-success">Subscribirme</button>
</form>