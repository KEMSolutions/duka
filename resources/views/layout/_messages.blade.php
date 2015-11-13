
@if ($errors->any() || $latest_order_details = Session::get('latest_order_details'))
        <div class="ui page dimmer congratulate-dimmer">
            <div class="content">
                <div class="center">
                    <h1 class="ui centered aligned header">
                        <img class="ui tiny image" src="{{ Store::logo() }}" alt="{{ Store::logo() }}"/>
                        <br/>
                        <div class="content">
                            Thank you for your order!
                        </div>
                    </h1>

                    <h4 class="ui header">
                        Below is a summary of your order.
                        <br/>
                        Please note that this summary will also be sent to the email address
                        that you entered during the checkout process.
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
                        <h3 class="ui header">Create an account to keep track of your purchases!</h3>
                        <h4 class="ui header">It is simple and easy.</h4>

                        <a href="{{ route('auth.register') . '?email=' . $latest_order_details->customer->email . '&name=' . $latest_order_details->customer->name }}">
                            <button class="ui inverted green button">
                                Click here
                            </button>
                        </a>


                    @endif
                </div>
            </div>
        </div>



    {{--<section>--}}

        {{-- Error messages are stored in $error automatically by Laravel. --}}
        {{--@if ($errors->any())--}}
        {{--<div class="ui error message">--}}
        {{--<i class="close icon"></i>--}}
        {{--<div class="header">--}}
            {{--@lang("boukem.error_occurred")--}}
        {{--</div>--}}
        {{--<ul class="list">--}}
        {{--@foreach ($errors->all() as $error)--}}
                    {{--<li>{!! $error !!}</li>--}}
            {{--@endforeach--}}
        {{--</ul>--}}
        {{--</div>--}}
            {{----}}
        {{--@endif--}}

        {{-- General messages are pushed to the $messages variable in the session by the application. --}}
        {{--@if ($messages = Session::pull('messages'))--}}

        {{--<div class="ui info message payment_successful">--}}
            {{--<i class="close icon"></i>--}}
            {{--<div class="header">--}}
                {{--@lang("boukem.message")--}}
            {{--</div>--}}
        {{--<ul class="list">--}}
        {{--@foreach ($messages as $msg)--}}
            {{--{!! $msg !!}--}}
        {{--@endforeach--}}
        {{--</ul>--}}
        {{--</div>--}}


            {{----}}
        {{--@endif--}}

    {{--</section>--}}
@endif
