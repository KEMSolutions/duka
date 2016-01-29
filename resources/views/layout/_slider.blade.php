<div class="ui right vertical menu wide sidebar cart-drawer">
    <header class="cart-header">
        <h2>{{ Lang::get("boukem.cart") }}</h2>
    </header>

    <div class="item" id="cart-items">
        <h4 class="text-center empty-cart">
        {{ Lang::get("boukem.empty_cart") }}
        </h4>
    </div>


    {{-- Populated in cartSliderContainer. --}}
    <div class="cart-items-list">

    </div>


    <a class="item color-one"
    id="checkout"
    href="{{ url("/cart") }}">
        <span class="cart-action-text">{{ Lang::get("boukem.check_out") }}</span>
    </a>
</div>
