 {{--Error messages are stored in $error automatically by Laravel. --}}
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

