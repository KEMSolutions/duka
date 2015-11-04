<aside id="cart-container" style="margin-right: 0">
    <div class="cart">
        <header class="cart-header">
            <button class="pull-left no-border" id="back">&times;</button>
            <h2>{{ Lang::get("boukem.cart") }}</h2>
        </header>

        <div id="cart-items">
            <h4 class="text-center empty-cart hidden">
                {{ Lang::get("boukem.empty_cart") }}
            </h4>

            <div class="ui divided items cart-items-list">
            </div>

        </div>

        <div class="cart-footer">
            <div class="cart-price order-summary">
                <div class="cart-subtotal">
                    <dl class="calculation">
                        <dt>{{ Lang::get("boukem.subtotal") }}</dt>
                        <dd id="subtotal"></dd>
                    </dl>

                    <div class="price-estimate">
                        <p>{{ Lang::get("boukem.get_estimate_label") }} </p>
                        <select name="country" class="form-control one-half pull-left" id="country">
                        </select>
                        <span>
                            <input type="text" name="postalcode" value="{{ Customers::getDefaultAddress()->postcode }}" placeholder="A1A 1A1" id="postcode" class="form-control one-half">
                        </span>

                        <button type="button" class=" one-half center-block ui button btn-two getEstimate" style="margin-top: 0.5rem;">{{ Lang::get("boukem.estimate") }}</button>

                    </div>

                    <div class="price-estimate-update">
                        <button type="button" class=" one-half center-block ui button btn-two getEstimate">{{ Lang::get("boukem.update") }}</button>
                        <p class="text-center text-uppercase">
                            {{ Lang::get("boukem.or") . " " }}
                            <button class="btn-link text-uppercase changeLocation">{{ Lang::get("boukem.change_location") }}</button>
                        </p>
                    </div>

                    <dl class="calculation  hidden">
                        <dt>{{ Lang::get("boukem.shipping") }}</dt>
                        <dd id="shipping"></dd>
                    </dl>

                    <dl class="calculation hidden">
                        <dt>{{ Lang::get("boukem.taxes") }}</dt>
                        <dd id="taxes"></dd>
                    </dl>

                </div>
                <div class="cart-total hidden"><dl class="calculation total">
                        <dt>{{ Lang::get("boukem.total") }}</dt>
                        <dd></dd>
                    </dl></div>
            </div>
            <div class="cart-actions">
                <a class="checkout color-one"
                   id="checkout"
                   href="{{ url("/cart") }}">
                    <span class="cart-action-text">{{ Lang::get("boukem.check_out") }}</span>
                </a>
            </div>
        </div>
    </div>

</aside>
