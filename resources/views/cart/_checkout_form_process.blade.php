<div class="col-md-7 cart-content-checkout-process">
    <form method="post" id="cart_form" class="{{ base64_decode($_COOKIE["quantityCart"]) == "0" ? "hidden" : "" }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <span class="badge pull-right">1</span>
                {{ Lang::get("boukem.taxes_delivery") }}
            </div>
            <div class="panel-body">

                {{-- COUNTRIES --}}
                <div class="form-group">
                    <label for="country">{{ Lang::get("boukem.country") }}</label>
                    <select name="country" id="country" class="form-control">
                        {{--Populated from js/data/country-list--}}
                    </select>
                </div>

                {{-- CA PROVINCE / US STATES / MEX STATES--}}
                <div class="form-group">
                    <label for="province">{{ Lang::get("boukem.province_state_reg") }}</label>
                    <select name="province" id="province" class="form-control">
                        <optgroup data-country="CA" label="{{ Lang::get("boukem.ca_province") }}"></optgroup>
                        <optgroup data-country="US" label="{{ Lang::get("boukem.us_states") }}"></optgroup>
                        <optgroup data-country="MX" label="{{ Lang::get("boukem.mex_states") }}"></optgroup>
                    </select>
                </div>


                <div class="form-group">
                    <label for="country" class="control-label">{{ Lang::get("boukem.postal_code") }}</label>
                    <input type="text" name="postalcode" value="" placeholder="A1A 1A1" id="postcode" class="form-control">
                </div>

                {{--TODO: Display only if user is a guest--}}
                    <div class="form-group">
                        <span class="hidden label label-info pull-right" id="why_email" data-toggle="tooltip" data-placement="left" data-trigger="click" title="{{ Lang::get("boukem.keep_email") }}">{{ Lang::get("boukem.why") }}</span>
                        <label for="customer_email" class="control-label">{{ Lang::get("boukem.email_address") }}</label>
                        <input type="email" name="email" id="customer_email" class="form-control" value="">

                    </div>

                <button class="btn btn-three pull-right btn-lg" id="estimateButton">{{ Lang::get("boukem.continue") }}</button>
            </div> <!-- panel-body -->
        </div>  <!-- first panel -->

        <div class="panel panel-default hidden" id="estimate">
        </div><!-- second panel -->

        <div class="panel panel-default hidden" id="payment">

            <div class="panel-heading"><span class="badge pull-right">3</span>{{ Lang::get("boukem.secure_payment") }}</div>

            <table class="table" id="finalPrice">

                <tr id="rebate_row" class="hidden">
                    <td class="text-right" id="rebate_name"></td>
                    <td id="rebate_value"></td>
                </tr>

                <tr>
                    <td width="75%" class="text-right">{{ Lang::get("boukem.subtotal") }}</td>
                    <td id="price_subtotal">0.00</td>
                </tr>
                <tr>
                    <td class="text-right">{{ Lang::get("boukem.shipping_methods") }}</td>
                    <td id="price_transport">0.00</td>
                </tr>
                <tr>
                    <td class="text-right">{{ Lang::get("boukem.taxes") }}</td>
                    <td id="price_taxes">0.00</td>
                </tr>
                <tr>
                    <td class="text-right"><h4>{{ Lang::get("boukem.total") }}</h4></td>
                    <td><h4 id="price_total">0.00</h4></td>
                </tr>

            </table>

            <div class="panel-body text-right">
                <button class="btn btn-three btn-lg" id="checkoutButton">{{ Lang::get("boukem.checkout") }}</button><br>
                <small class="hidden-xs">
                    <i class="fa fa-lock"></i> {{ Lang::get("boukem.secure_payment") }}
                    <br>
                    <i class="fa fa-cc-paypal"><span class="sr-only">Paypal</span></i>
                    <i class="fa fa-cc-visa"><span class="sr-only">Visa</span></i>
                    <i class="fa fa-cc-mastercard"><span class="sr-only">Mastercard</span></i>
                    <i class="fa fa-cc-amex"><span class="sr-only">American Express</span></i>
                    <i class="fa fa-cc-discover"><span class="sr-only">Discover</span></i>
                </small>

            </div><!-- panel-body -->

        </div><!-- third panel -->
    </form>
</div>