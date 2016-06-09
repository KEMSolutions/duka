<div class="ui stackable padded grid" style="margin-top: 2rem;">

    <div class="ten wide column" style="font-family: Lato,'Helvetica Neue',Arial,Helvetica,sans-serif">

        <div class="contactInformation">
            <div class="ui segment shippingInformation">
                <h4 class="ui dividing header">@lang("boukem.shipping_info")</h4>
                <div class="field">
                    <label>@lang("boukem.name")</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="shipping_address[firstname]" id="shippingFirstname" class="firstname" placeholder=@lang("boukem.first_name")>
                        </div>
                        <div class="field">
                            <input type="text" name="shipping_address[lastname]" id="shippingLastname" class="lastname" placeholder=@lang("boukem.last_name")>
                        </div>
                    </div>
                </div>

                {{-- Hidden name field... --}}
                <div class="field disabled hidden">
                    <label>@lang("boukem.name")</label>
                    <input type="text" name="shipping_address[name]" id="shippingName" class="name" value="{{ Customers::getDefaultAddress()->name }}" disabled/>
                </div>

                <div class="field">
                    <label>@lang("boukem.shipping_address")</label>
                    <input type="text" name="shipping_address[line1]" id="shippingAddress1" class="address1" placeholder=@lang("boukem.address_1") value="{{ Customers::getDefaultAddress()->line1 }}" >
                </div>

                <div class="field">
                    <input type="text" name="shipping_address[line2]" id="shippingAddress2" class="address2" placeholder="@lang("boukem.address_2") (@lang("boukem.optional"))" value="{{ Customers::getDefaultAddress()->line2 }}">
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>@lang("boukem.country")</label>
                        <select name="shipping_address[country]" id="shippingCountry">
                            @include("checkout._country_list")
                        </select>
                    </div>

                    <div class="field">
                        <label>@lang("boukem.province_state_reg")</label>
                        <select name="shipping_address[province]" id="shippingProvince">
                            @include("checkout._province_state_reg")
                        </select>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>@lang("boukem.city")</label>
                        <input type="text" placeholder=@lang("boukem.city") name="shipping_address[city]" id="shippingCity" class="city" value="{{ Customers::getDefaultAddress()->city }}"/>
                    </div>

                    <div class="field shippingPostcode">
                        <label>@lang("boukem.postal_code")</label>
                        <input type="text" placeholder="A1A 1A1" name="shipping_address[postcode]" id="shippingPostcode" value="{{ Customers::getDefaultAddress()->postcode }}"/>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field popup" data-content="@lang("boukem.keep_email")" data-variation="inverted">
                        <label>@lang("boukem.email_address")</label>
                        <input type="email" placeholder="you@you.com" name="email" id="customer_email" value="{{ Customers::getDefault()->email }}"/>
                    </div>

                    <div class="field">
                        <label>@lang("boukem.phone")</label>
                        <input type="tel" placeholder="(xxx) xxx xxx" name="shipping_address[phone]" id="customer_phone" value="{{ Customers::getDefaultAddress()->phone }}"/>
                    </div>
                </div>

                <div class="ui checkbox billing-checkbox">
                    <input
                            type="checkbox"
                            tabindex="0"
                            class="hidden"
                            checked
                            name="use_shipping_address"
                            id="checkboxSuccess"
                    />
                    <label style="color: green">@lang("boukem.ship_billing")</label>
                </div>
            </div>


            <div class="ui segment hidden billingInformation">
                <h4 class="ui dividing header">@lang("boukem.billing_info")</h4>

                <div class="field">
                    <label>@lang("boukem.name")</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="billing_address[firstname]" id="billingFirstname" placeholder=@lang("boukem.first_name")>
                        </div>
                        <div class="field">
                            <input type="text" name="billing_address[lastname]" id="billingLastname" placeholder=@lang("boukem.last_name")>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>@lang("boukem.billing_address")</label>
                    <input type="text" name="billing_address[line1]" id="billingAddress1" placeholder=@lang("boukem.address_1")>
                </div>

                <div class="field">
                    <input type="text" name="billing_address[line2]" id="billingAddress2" placeholder="@lang("boukem.address_2") (@lang("boukem.optional"))">
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>@lang("boukem.country")</label>
                        <select name="billing_address[country]" id="billingCountry">
                            @include("checkout._country_list")
                        </select>
                    </div>

                    <div class="field">
                        <label>@lang("boukem.province_state_reg")</label>
                        <select name="billing_address[province]" id="billingProvince">
                            @include("checkout._province_state_reg")
                        </select>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>@lang("boukem.city")</label>
                        <input type="text" placeholder=@lang("boukem.city") name="billing_address[city]" id="billingCity"/>
                    </div>

                    <div class="field">
                        <label>@lang("boukem.postal_code")</label>
                        <input type="text" placeholder="A1A 1A1" name="billing_address[postcode]" id="billingPostcode"/>
                    </div>
                </div>

            </div>

            @if(Products::suggested() != null)
                <h4 class="ui sub header">@lang("boukem.suggested_product")</h4>
                <div class="ui divider"></div>
                <div class="ui items">
                    <div class="ui two column grid">
                        @foreach(Products::suggested() as $suggested)
                        @if ($suggested && isset($suggested->formats[0]) && isset($suggested->images[0]))
                            <div class="ui column">
                                <div class="item">
                                    <img class="left floated mini circular ui image" src="{{ str_replace(['{width}', '{height}', '{mode}'], ['80', '80', 'fit'], $suggested->images[0]->url )}}">
                                    <div class="content">
                                        <div class="header">
                                            {{ $suggested->localization->name . " " . $suggested->formats[0]->name}}
                                        </div>
                                        <div class="meta">
                                            <span class="price" style="color:rgba(0,0,0,.6)">$ {{ money_format('%i', $suggested->formats[0]->price) }}</span>
                                            <div class="ui buttons right floated">
                                                <div class="ui basic green button buybutton_impulse"
                                                     data-product="{{ $suggested->id }}-{{ $suggested->formats[0]->id }}"
                                                     data-price="{{ $suggested->formats[0]->price }}"
                                                     data-thumbnail="{{ str_replace(['{width}', '{height}', '{mode}'], ['80', '80', 'fit'], $suggested->images[0]->url) }}"
                                                     data-thumbnail_lg="{{ str_replace(['{width}', '{height}', '{mode}'], ['80', '80', 'fit'], $suggested->images[0]->url) }}"
                                                     data-name="{{ $suggested->localization->name }}-{{ $suggested->formats[0]->name }}"
                                                     data-description="{{ (strlen($suggested->localization->short_description) > 200) ? substr($suggested->localization->short_description,0,200).'…' : $suggested->localization->short_description }}"
                                                     data-link="{{ route('product', ['slug' => $suggested->slug]) }}"
                                                     data-quantity="1">
                                                    @lang("boukem.buy")
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="ui divider"></div>

            <button class="ui right labeled green icon button right floated shipment-trigger submit">
                <i class="right arrow icon"></i>
                @lang("boukem.next")
            </button>
        </div>


        <div class="shippingMethod hidden">

            <div class="ui basic segment">
                <h4 class="ui dividing header">@lang("boukem.shipping_methods")</h4>
            </div>

            <div class="ui basic segment loadable-segment loading">

                <table class="ui padded table unstackable shippingMethod-table">
                    <thead>
                    <tr>
                        <th>@lang("boukem.service_name")</th>
                        <th>@lang("boukem.expected_delivery")</th>
                        <th>@lang("boukem.cost")</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody id="shippingMethod-table-tbody">
                    {{-- Populated with checkoutContainer.fetchEstimate(data) --}}
                    </tbody>
                </table>
            </div>
        </div>


        <div class="priceInformation hidden">
            <div class="ui basic segment">
                <h4 class="ui dividing header">@lang("boukem.total_price")</h4>
            </div>

            <div class="ui basic segment loadable-segment loading">

                <table class="ui padded celled table priceInformation-table">

                    <tbody id="priceInformation-table-tbody">
                    <tr>
                        <td>@lang("boukem.subtotal")</td>
                        <td class="center aligned" id="price_subtotal"></td>
                    </tr>

                    <tr>
                        <td>@lang("boukem.shipping_methods")</td>
                        <td class="center aligned" id="price_transport"></td>
                    </tr>

                    <tr>
                        <td>@lang("boukem.taxes")</td>
                        <td class="center aligned" id="price_taxes"></td>
                    </tr>

                    <tr>
                        <td><h3>@lang("boukem.total")</h3></td>
                        <td class="center aligned"><h3 id="price_total"></h3></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="ui basic segment">
                <div class="ui checkbox">
                    <input id="marketing_email_optin" type="checkbox" name="marketing_email_optin">
                    <label for="marketing_email_optin">@lang("boukem.marketing_email_optin")</label>
                </div>
            </div>

            <div class="ui basic segment">
                <button class="ui inverted red icon button left floated back-contact-info">
                    <i class="left arrow icon"></i>
                    @lang("boukem.back_contact_info")
                </button>

                <button class="ui right labeled green icon button right floated next-payment-process">
                    <i class="right arrow icon"></i>
                    @lang("boukem.proceed_checkout")
                </button>
            </div>




        </div>
    </div>

    <div class="six wide column">
        <div class="ui divided items cart-items-list">
        </div>

        <hr>
        <div class="cart-content-agreement">
            <p>@lang("boukem.conditions")</p>
        </div>
    </div>
</div>
