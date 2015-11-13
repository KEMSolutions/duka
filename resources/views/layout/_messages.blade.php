
@if ($errors->any() || $messages = Session::get('messages'))
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
                        Please note that a similar email will be sent to your email address
                        that you entered during the checkout process.
                    </h4>

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
