<aside id="cart-container" style="margin-right: 0">
    <div class="cart">
        <header class="cart-header">
            <i class="fa fa-angle-left fa-2x" id="back"></i>
            <h2>Your cart</h2>
        </header>

        <div id="cart-items">
            <ul class="cart-items-list">

            </ul>
        </div>

        <div class="cart-footer">
            <div class="cart-price order-summary">
                <div class="cart-subtotal">
                    <dl class="calculation">
                        <dt>Subtotal</dt>
                        <dd>$85.00</dd>
                    </dl>

                    <dl class="calculation">
                        <dt>Taxes</dt>
                        <dd>$7.66</dd>
                    </dl>

                    <dl class="calculation">
                        <dt>Shipping</dt>
                        <dd>FREE</dd>
                    </dl>

                </div>
                <div class="cart-total"><dl class="calculation total">
                        <dt>Total</dt>
                        <dd>$92.66</dd>
                    </dl></div>
            </div>
            <div class="cart-actions">
                <a class="checkout" id="checkout" href="{{ url("/dev/cart") }}">
                    <span class="cart-action-text">{{ Lang::get("boukem.check_out") }}</span>
                </a>
            </div>
        </div>
    </div>

</aside>
