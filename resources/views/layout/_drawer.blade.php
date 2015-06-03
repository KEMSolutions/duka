<aside id="cart-container" style="margin-right: 0">
    <div class="cart">
        <header class="cart-header">
            <i class="fa fa-angle-left fa-2x" id="back"></i>
            <h2>{{ Lang::get("boukem.cart") }}</h2>
        </header>

        <div id="cart-items">
            <ul class="cart-items-list">

            </ul>
        </div>

        <div class="cart-footer">
            <div class="cart-price order-summary">
                <div class="cart-subtotal">
                    <dl class="calculation">
                        <dt>{{ Lang::get("boukem.subtotal") }}</dt>
                        <dd id="subtotal">$85.00</dd>
                    </dl>

                    <div class="price-estimate">
                        <p>{{ Lang::get("boukem.get_estimate_label") }} </p>
                        <select name="country" id="country" class="form-control one-half pull-left">
                            {{--Populated from js/data/country-list--}}
                        </select>

                        <input type="text" name="postalcode" value="" placeholder="A1A 1A1" id="postcode" class="form-control one-half">

                        <button type="button" class=" one-half center-block btn btn-info" id="getEstimate">{{ Lang::get("boukem.calculate") }}</button>
                    </div>

                    <dl class="calculation  hidden">
                        <dt>{{ Lang::get("boukem.shipping") }}</dt>
                        <dd id="shipping">FREE</dd>
                    </dl>

                    <dl class="calculation hidden">
                        <dt>{{ Lang::get("boukem.taxes") }}</dt>
                        <dd id="taxes">$7.66</dd>
                    </dl>

                </div>
                <div class="cart-total hidden"><dl class="calculation total">
                        <dt>{{ Lang::get("boukem.total") }}</dt>
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
