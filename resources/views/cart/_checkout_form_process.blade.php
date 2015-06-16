<div class="col-md-7 cart-content-checkout-process" data-chosen-width="">
    <form method="post" action="{{ route('api.orders')  }}" id="cart_form" class="{{ base64_decode($_COOKIE["quantityCart"]) == "0" ? "hidden" : "" }}" autocomplete="on">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading">
                <span class="badge pull-right">1</span>
                {{ Lang::get("boukem.shipping_address") }}
            </div>
            <div class="panel-body">

                <div class="row">
                    <div class="form-group one-half">
                        <label for="shipping_address[firstname]" class="control-label" >{{ Lang::get("boukem.first_name") }}</label>
                        <input type="text" name="shipping_address[firstname]" id="shippingFirstname" class="form-control firstname" required/>
                    </div>

                    <div class="form-group one-half">
                        <label for="shipping_address[lastname]" class="control-label" >{{ Lang::get("boukem.last_name") }}</label>
                        <input type="text" name="shipping_address[lastname]" id="shippingLastname" class="form-control lastname" required/>
                    </div>
                </div>

                <div class="form-group">
                    <label for="shipping_address[line1]" class="control-label">{{ Lang::get("boukem.address_1") }}</label>
                    <input type="text" name="shipping_address[line1]" id="shippingAddress1" class="form-control address1" required/>
                </div>
                <div class="form-group">
                    <label for="shipping_address[line2]" class="control-label">{{ Lang::get("boukem.address_2") }} ( {{ Lang::get("boukem.optional") }} )</label>
                    <input type="text" name="shipping_address[line2]" id="shippingAddress2" class="form-control address2"/>
                </div>

                <div class="row">
                    <div class="form-group one-half">
                        <label for="shipping_address[country]">{{ Lang::get("boukem.country") }}</label>
                        <select name="shipping_address[country]" class="form-control country" id="shippingCountry">
                            {{--Populated from js/data/country-list--}}
                        </select>
                    </div>

                    <div class="form-group one-half">
                        <label for="shipping_address[province]">{{ Lang::get("boukem.province_state_reg") }}</label>
                        <select name="shipping_address[province]" class="form-control province" id="shippingProvince">
                            <optgroup data-country="CA" label="{{ Lang::get("boukem.ca_province") }}"></optgroup>
                            <optgroup data-country="US" label="{{ Lang::get("boukem.us_states") }}"></optgroup>
                            <optgroup data-country="MX" label="{{ Lang::get("boukem.mex_states") }}"></optgroup>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="form-group one-half">
                        <label for="shipping_address[city]" class="control-label">{{ Lang::get("boukem.city") }}</label>
                        <input type="text" name="shipping_address[city]" id="shippingCity" class="form-control city" required/>
                    </div>

                    <div class="form-group one-half">
                        <label for="shipping_address[postcode]" class="control-label">{{ Lang::get("boukem.postal_code") }}</label>
                        <input type="text" name="shipping_address[postcode]" value="" placeholder="A1A 1A1" id="shippingPostcode" class="form-control postcode" required>
                    </div>
                </div>


                <div class="row">
                    <div class="form-group one-half">
                        <span class="hidden label label-info pull-right" id="why_email" data-toggle="tooltip" data-placement="left" data-trigger="click" title="{{ Lang::get("boukem.keep_email") }}">{{ Lang::get("boukem.why") }}</span>
                        <label for="email" class="control-label">{{ Lang::get("boukem.email_address") }}</label>
                        <input type="email" name="email" id="customer_email" class="form-control" value="" required>
                    </div>

                    <div class="form-group one-half">
                        <label for="shipping_address[phone]" class="control-label">{{ Lang::get("boukem.phone") }}</label>
                        <input type="tel" name="shipping_address[phone]" id="customer_phone" class="form-control " required/>
                    </div>
                </div>

                <div class="checkbox has-success form-group">
                    <label>
                        <input type="checkbox" name="use_shipping_address" checked="checked" id="checkboxSuccess" class="billing-checkbox"> {{ Lang::get("boukem.ship_billing") }}
                    </label>
                </div>

                <div class="form-billing panel panel-default hidden">
                    <div class="panel-heading">
                        {{ Lang::get("boukem.billing_address") }}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group one-half">
                                <label for="billing_address[firstname]" class="control-label" >{{ Lang::get("boukem.first_name") }}</label>
                                <input type="text" name="billing_address[firstname]" id="billingFirstname" class="form-control firstname" required/>
                            </div>

                            <div class="form-group one-half">
                                <label for="billing_address[lastname]" class="control-label" >{{ Lang::get("boukem.last_name") }}</label>
                                <input type="text" name="billing_address[lastname]" id="billingLastname" class="form-control lastname" required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="billingAddress1" class="control-label">{{ Lang::get("boukem.address_1") }}</label>
                            <input type="text" name="billing_address[line1]" id="billingAddress1" class="form-control address1" required/>
                        </div>
                        <div class="form-group">
                            <label for="billingAddress2" class="control-label">{{ Lang::get("boukem.address_2") }} ( {{ Lang::get("boukem.optional") }} )</label>
                            <input type="text" name="billing_address[line2]" id="billingAddress2" class="form-control address2"/>
                        </div>

                        <div class="row">
                            <div class="form-group one-half">
                                <label for="billing_address[country]">{{ Lang::get("boukem.country") }}</label>
                                <select name="billing_address[country]" class="form-control country" id="billingCountry">
                                    {{--Populated from js/data/country-list--}}
                                </select>
                            </div>

                            <div class="form-group one-half">
                                <label for="billing_address[province]">{{ Lang::get("boukem.province_state_reg") }}</label>
                                <select name="billing_address[province]" class="form-control province" id="billingProvince">
                                    <optgroup data-country="CA" label="{{ Lang::get("boukem.ca_province") }}"></optgroup>
                                    <optgroup data-country="US" label="{{ Lang::get("boukem.us_states") }}"></optgroup>
                                    <optgroup data-country="MX" label="{{ Lang::get("boukem.mex_states") }}"></optgroup>
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group one-half">
                                <label for="billing_address[city]" class="control-label">{{ Lang::get("boukem.city") }}</label>
                                <input type="text" name="billing_address[city]" id="billingCity" class="form-control city" required/>
                            </div>

                            <div class="form-group one-half">
                                <label for="billing_address[postcode]" class="control-label">{{ Lang::get("boukem.postal_code") }}</label>
                                <input type="text" name="billing_address[postcode]" value="" placeholder="A1A 1A1" id="billingPostcode" class="form-control postcode" required>
                            </div>
                        </div>
                    </div>
                </div><!-- billing panel -->


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