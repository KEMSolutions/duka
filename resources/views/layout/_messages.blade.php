
@if ($errors->any() || $messages = Session::get('messages'))
    <section>

        {{-- Error messages are stored in $error automatically by Laravel. --}}
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    {!! $error !!}
                </div>
            @endforeach
        @endif

        {{-- General messages are pushed to the $messages variable in the session by the application. --}}
        @if ($messages = Session::pull('messages'))
            @foreach ($messages as $msg)
                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    <span class="sr-only">Message:</span>
                    {!! $msg !!}
                </div>
            @endforeach
        @endif

    </section>
@endif
