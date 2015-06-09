<div class="col-md-7 cart-content-checkout-process">
    <form method="post" id="cart_form" class="{{ base64_decode($_COOKIE["quantityCart"]) == "0" ? "hidden" : "" }}" autocomplete="on">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <span class="badge pull-right">1</span>
                {{ Lang::get("boukem.taxes_delivery") }}
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="form-group one-half">
                        <label for="shippingFirstname" class="form-label" >{{ Lang::get("boukem.first_name") }}</label>
                        <input type="text" name="shippingFirstname" id="shippingFirstname" class="form-control" required/>
                    </div>

                    <div class="form-group one-half">
                        <label for="shippingLastname" class="form-label" >{{ Lang::get("boukem.last_name") }}</label>
                        <input type="text" name="shippingLastname" id="shippingLastname" class="form-control" required/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="shippingAddress1" class="form-label">{{ Lang::get("boukem.address_1") }}</label>
                    <input type="text" name="shippingAddress1" id="shippingAddress1" class="form-control" required/>
                </div>
                <div class="form-group">
                    <label for="shippingAddress2" class="form-label">{{ Lang::get("boukem.address_2") }} ( {{ Lang::get("boukem.optional") }} )</label>
                    <input type="text" name="shippingAddress2" id="shippingAddress2" class="form-control"/>
                </div>

                <div class="form-group">
                    <label for="billingAddress" class="form-label">{{ Lang::get("boukem.billing_address") }}</label>
                    <input type="text" name="billingAddress" id="billingAddress" class="form-control" required/>
                </div>

                <div class="row">
                    <div class="form-group one-half">
                        <label for="country">{{ Lang::get("boukem.country") }}</label>
                        <select name="country" class="country form-control">
                            {{--Populated from js/data/country-list--}}
                        </select>
                    </div>

                    <div class="form-group one-half">
                        <label for="province">{{ Lang::get("boukem.province_state_reg") }}</label>
                        <select name="province" id="province" class="form-control">
                            <optgroup data-country="CA" label="{{ Lang::get("boukem.ca_province") }}"></optgroup>
                            <optgroup data-country="US" label="{{ Lang::get("boukem.us_states") }}"></optgroup>
                            <optgroup data-country="MX" label="{{ Lang::get("boukem.mex_states") }}"></optgroup>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="form-group one-half">
                        <label for="shippingCity" class="form-label">{{ Lang::get("boukem.city") }}</label>
                        <input type="text" name="shippingCity" id="shippingCity" class="form-control" required/>
                    </div>

                    <div class="form-group one-half">
                        <label for="postalcode" class="control-label">{{ Lang::get("boukem.postal_code") }}</label>
                        <input type="text" name="postalcode" value="" placeholder="A1A 1A1" id="postcode" class="form-control" required>
                    </div>
                </div>


                <div class="row">
                    <div class="form-group one-half">
                        <span class="hidden label label-info pull-right" id="why_email" data-toggle="tooltip" data-placement="left" data-trigger="click" title="{{ Lang::get("boukem.keep_email") }}">{{ Lang::get("boukem.why") }}</span>
                        <label for="customer_email" class="control-label">{{ Lang::get("boukem.email_address") }}</label>
                        <input type="email" name="email" id="customer_email" class="form-control" value="" required>
                    </div>

                    <div class="form-group one-half">
                        <label for="shippingTel" class="form-label">{{ Lang::get("boukem.phone") }}</label>
                        <input type="tel" name="shippingTel" id="shippingTel" class="form-control" required/>
                    </div>
                </div>


                <button class="btn btn-three pull-right btn-lg" id="estimateButton">{{ Lang::get("boukem.continue") }}</button>
            </div> <!-- panel-body -->
        </div>  <!-- first panel -->

        <div class="panel panel-default hidden" id="estimate">
            <div class="panel-heading">
                <span class="badge pull-right">2</span>{{ Lang::get("boukem.shipping_methods") }}
            </div>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{ Lang::get("boukem.service_name") }}</th>
                    <th>{{ Lang::get("boukem.estimated_transit_time") }}</th>
                    <th>{{ Lang::get("boukem.expected_delivery") }}</th>
                    <th>{{ Lang::get("boukem.cost") }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
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