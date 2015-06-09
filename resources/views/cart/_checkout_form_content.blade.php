<section class="cart-checkout">
    <div class="col-md-5 color-two cart-checkout-content">

        <div id="cart-items" style="height: auto !important";>
            <ul class="cart-items-list">
            </ul>
        </div>

        <div class="cart-content-giftcard">

            <div class="input-group">
                <input id="couponField" type="text" class="form-control input-sm" placeholder="{{ \Illuminate\Support\Facades\Lang::get("boukem.gift_card") }}">
			      <span class="input-group-btn">
			        <button class="btn btn-sm btn-default" id="redeemCouponButton" type="button">{{ \Illuminate\Support\Facades\Lang::get("boukem.apply") }}</button>
			      </span>
            </div><!-- /input-group -->
        </div><!-- cart-content-giftcard -->

        <hr>
        <div class="cart-content-agreement">
            <p>{{ \Illuminate\Support\Facades\Lang::get("boukem.conditions") }}</p>
        </div>
    </div>

    @include("cart._checkout_form_process")


</section>