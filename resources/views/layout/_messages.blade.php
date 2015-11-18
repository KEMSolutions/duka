<?php
$latest_order_details = Session::pull('latest_order_details');
if ($latest_order_details) {
    $status = Orders::details($latest_order_details->id, $latest_order_details->verification);
}
?>

{{-- Display a dimmer with the latest order details. --}}
@if (isset($status) && $status->status === "paid")
    <div class="ui page dimmer congratulate-dimmer">
            <div class="content">
                <div class="center">

                    <button class="ui right floated inverted icon button close-dimmer"
                            style="margin: 0.5rem">
                        <i class="remove icon"></i>
                    </button>

                    <h1 class="ui centered aligned header">
                        <img class="ui tiny image" src="{{ Store::logo() }}" alt="{{ Store::logo() }}"/>
                        <br/>
                        <div class="content">
                            @lang("boukem.payment_successful")
                        </div>
                    </h1>

                    <h4 class="ui header">
                        @lang("boukem.summary_below")
                        <br/>
                        @lang("boukem.summary_copy")
                    </h4>

                    <table class="ui collapsing padded inverted table" style="margin: 0 auto">
                        <tbody>
                        <tr>
                            <td>@lang("boukem.order")</td>
                            <td>#{{ $latest_order_details->id }}</td>
                        </tr>

                        <tr>
                            <td>@lang("boukem.shipping_address")</td>
                            <td>
                                {{ $latest_order_details->shipping_address->line1 }}

                                @if(!is_null($latest_order_details->shipping_address->line2))
                                    {{ $latest_order_details->shipping_address->line2 }}
                                @endif

                                <br/>
                                {{ $latest_order_details->shipping_address->postcode }}
                                <br/>
                                {{ $latest_order_details->shipping_address->city }},
                                {{ $latest_order_details->shipping_address->province }},
                                {{ $latest_order_details->shipping_address->country }}
                                <br/>
                                {{ $latest_order_details->shipping_address->name }}
                            </td>
                        </tr>
                        <tr>
                            <td>@lang("boukem.billing_address")</td>
                            <td>
                                {{ $latest_order_details->billing_address->line1 }}

                                @if(!is_null($latest_order_details->billing_address->line2))
                                    {{ $latest_order_details->billing_address->line2 }}
                                @endif

                                <br/>
                                {{ $latest_order_details->billing_address->postcode }}
                                <br/>
                                {{ $latest_order_details->billing_address->city }},
                                {{ $latest_order_details->billing_address->province }},
                                {{ $latest_order_details->billing_address->country }}
                                <br/>
                                {{ $latest_order_details->billing_address->name }}
                            </td>
                        </tr>

                        <tr>
                            <td>@lang("boukem.subtotal")</td>
                            <td>${{ number_format($latest_order_details->payment_details->subtotal, 2, '.', '') }}</td>
                        </tr>

                        <tr>
                            <td>@lang("boukem.taxes")</td>
                            <td>${{ number_format($latest_order_details->payment_details->taxes, 2, '.', '') }}</td>
                        </tr>

                        <tr>
                            <td>@lang("boukem.total")</td>
                            <td>${{ number_format($latest_order_details->payment_details->total, 2, '.', '') }}</td>
                        </tr>
                        </tbody>
                    </table>


                    @if(Request::cookie('unregistered_user'))
                        <hr/>
                        <h3 class="ui header">@lang("boukem.create_account")</h3>
                        <h4 class="ui header">@lang("boukem.simple_easy")</h4>

                        <a href="{{ route('auth.register') . '?email=' . $latest_order_details->customer->email . '&name=' . $latest_order_details->customer->name }}">
                            <button class="ui inverted green button">
                                @lang("boukem.click_here")
                            </button>
                        </a>

                    @endif
                </div>
            </div>
        </div>
@endif

{{-- Error messages are stored in $error automatically by Laravel. --}}
@if($errors->any())
    <div class="ui page dimmer congratulate-dimmer">
        <div class="content">
            <div class="center">
                <h1 class="ui centered aligned header">
                    <img class="ui tiny image" src="{{ Store::logo() }}" alt="{{ Store::logo() }}"/>
                    <br/>

                    <div class="content">
                        @lang("boukem.error_occurred")
                    </div>
                </h1>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
@endif

