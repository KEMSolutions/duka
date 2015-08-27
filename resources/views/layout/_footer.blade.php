<footer class="color-four">
    <div class="ui container stackable vertically padded grid">
        <div class="row">

            {{-- Contract pages --}}
            <div class="five wide column">
                <h2 class="ui header white">
                    @lang("boukem.information")
                </h2>

                <ul>
                    @foreach (Store::contracts() as $contract)
                        <li>
                            <a href="{{ route('contract', ['slug' => $contract->slug]) }}">
                                {{ $contract->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>


            {{-- Quick links --}}
            <div class="five wide column">
                <h2 class="ui header white">
                    @lang("boukem.shortcuts")
                </h2>

                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            {{ Lang::get("boukem.home") }}
                        </a>
                    </li>
                </ul>
            </div>


            {{--User links--}}
            <div class="five wide column">
                <h2 class="ui header white">
                    @lang("boukem.customer_service")
                </h2>

                <ul>
                    @if(Auth::guest())
                        <li>
                            <a href="{{ route('auth.login') }}">
                                {{ Lang::get("boukem.account") }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auth.register')  }}">
                                {{ Lang::get("boukem.sign_up") }}
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('auth.account') }}">
                                {{ Lang::get("boukem.account") }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('auth.logout') }}">
                                @lang("boukem.log_out")
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="ui divider"></div>

        {{-- Copyright & Powered By --}}
        <div class="row">
            <div class="left floated three wide column white">
                &copy; <?php echo date("Y"); ?>, {{ Lang::get("boukem.copyrights") }}.
            </div>

            <div class="right floated three wide column white">
                <img src="{{ asset('/img/kem_signature.png') }}" alt="Powered by KEM">
            </div>
        </div>
    </div>
</footer>
