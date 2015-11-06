
@if ($errors->any() || $messages = Session::get('messages'))
    <section>

        {{-- Error messages are stored in $error automatically by Laravel. --}}
        @if ($errors->any())
        <div class="ui error message">
        <i class="close icon"></i>
        <div class="header">
            @lang("boukem.error_occurred")
        </div>
        <ul class="list">
        @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
            @endforeach
        </ul>
        </div>
            
        @endif

        {{-- General messages are pushed to the $messages variable in the session by the application. --}}
        @if ($messages = Session::pull('messages'))

        <div class="ui info message payment_successful">
            <i class="close icon"></i>
            <div class="header">
                @lang("boukem.message")
            </div>
        <ul class="list">
        @foreach ($messages as $msg)
            {!! $msg !!}
        @endforeach
        </ul>
        </div>


            
        @endif

    </section>
@endif
