<div class="ui right vertical menu wide sidebar cart-drawer">
    <header class="cart-header">
        <h2>{{ Lang::get("boukem.cart") }}</h2>
    </header>

    <div class="item hidden" id="cart-items">
        <h4 class="text-center empty-cart">
        {{ Lang::get("boukem.empty_cart") }}
        </h4>

        <div class="ui divided items cart-items-list">
        </div>
    </div>

    <div class="item animated fadeInUp" data-product="6505-07580231" data-quantity="1">
        <div class="ui tiny left floated image">
            <img src="https://img.kem.guru/product/30/70/110/7425-en-cla-and-green-tea-90-caps?modes=fit">
        </div>

        <div class="middle aligned content" style="padding-left: 1.7531%">
            <h4 class="ui header">
                CLA and Green Tea - 90 caps
            </h4>
            <div class="meta">
                <span class="price" data-price="30.59">$30.59</span>
                <i class="remove icon large pull-right close-button"></i>
            </div>
            <div class="content cart-content">
                <span style="padding: 0 1.7531% 0 0">Quantity</span>
                <div class="ui input one-quarter">
                    <input type="number" class="quantity" min="1" step="1" value="1" name="products[6505-07580231][quantity]">
                    <input type="hidden" name="products[6505-07580231][id]" value="6505-07580231">
                </div>
            </div>
        </div>
    </div>


    <div class="item animated fadeInUp" data-product="6505-07580231" data-quantity="1">
        <div class="ui tiny left floated image">
            <img src="https://img.kem.guru/product/30/70/110/7425-en-cla-and-green-tea-90-caps?modes=fit">
        </div>

        <div class="middle aligned content" style="padding-left: 1.7531%">
            <h4 class="ui header">
                CLA and Green Tea - 90 caps
            </h4>
            <div class="meta">
                <span class="price" data-price="30.59">$30.59</span>
                <i class="remove icon large pull-right close-button"></i>
            </div>
            <div class="content cart-content">
                <span style="padding: 0 1.7531% 0 0">Quantity</span>
                <div class="ui input one-quarter">
                    <input type="number" class="quantity" min="1" step="1" value="1" name="products[6505-07580231][quantity]">
                    <input type="hidden" name="products[6505-07580231][id]" value="6505-07580231">
                </div>
            </div>
        </div>
    </div>

</div>




{{--<aside id="cart-container" style="margin-right: 0">--}}
    {{--<div class="cart">--}}
        {{--<header class="cart-header">--}}
            {{--<button class="pull-left no-border" id="back">&times;</button>--}}
            {{--<h2>{{ Lang::get("boukem.cart") }}</h2>--}}
        {{--</header>--}}

        {{--<div id="cart-items">--}}
            {{--<h4 class="text-center empty-cart invisible">--}}
                {{--{{ Lang::get("boukem.empty_cart") }}--}}
            {{--</h4>--}}

            {{--<div class="ui divided items cart-items-list">--}}
            {{--</div>--}}

        {{--</div>--}}

        {{--<div class="cart-footer">--}}
            {{--<div class="cart-price order-summary">--}}
                {{--<div class="cart-subtotal">--}}
                    {{--<dl class="calculation">--}}
                        {{--<dt>{{ Lang::get("boukem.subtotal") }}</dt>--}}
                        {{--<dd id="subtotal"></dd>--}}
                    {{--</dl>--}}

                    {{--<div class="price-estimate">--}}
                        {{--<p>{{ Lang::get("boukem.get_estimate_label") }} </p>--}}
                        {{--<select name="country" class="form-control one-half pull-left" id="country">--}}
                        {{--</select>--}}
                        {{--<span>--}}
                            {{--<input type="text" name="postalcode" value="{{ Customers::getDefaultAddress()->postcode }}" placeholder="A1A 1A1" id="postcode" class="form-control one-half">--}}
                        {{--</span>--}}

                        {{--<button type="button" class=" one-half center-block btn btn-two-inverted getEstimate" style="margin-top: 0.5rem;">{{ Lang::get("boukem.estimate") }}</button>--}}

                    {{--</div>--}}

                    {{--<div class="price-estimate-update">--}}
                        {{--<button type="button" class=" one-half center-block btn btn-two-inverted getEstimate">{{ Lang::get("boukem.update") }}</button>--}}
                        {{--<p class="text-center text-uppercase">--}}
                            {{--{{ Lang::get("boukem.or") . " " }}--}}
                            {{--<button class="btn-link text-uppercase changeLocation">{{ Lang::get("boukem.change_location") }}</button>--}}
                        {{--</p>--}}
                    {{--</div>--}}

                    {{--<dl class="calculation  invisible">--}}
                        {{--<dt>{{ Lang::get("boukem.shipping") }}</dt>--}}
                        {{--<dd id="shipping"></dd>--}}
                    {{--</dl>--}}

                    {{--<dl class="calculation invisible">--}}
                        {{--<dt>{{ Lang::get("boukem.taxes") }}</dt>--}}
                        {{--<dd id="taxes"></dd>--}}
                    {{--</dl>--}}

                {{--</div>--}}
                {{--<div class="cart-total invisible"><dl class="calculation total">--}}
                        {{--<dt>{{ Lang::get("boukem.total") }}</dt>--}}
                        {{--<dd></dd>--}}
                    {{--</dl></div>--}}
            {{--</div>--}}
            {{--<div class="cart-actions">--}}
                {{--<a class="checkout color-one"--}}
                   {{--id="checkout"--}}
                   {{--href="{{ url("/cart") }}">--}}
                    {{--<span class="cart-action-text">{{ Lang::get("boukem.check_out") }}</span>--}}
                {{--</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

{{--</aside>--}}
