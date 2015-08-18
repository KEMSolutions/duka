<footer class="color-four">
    <div class="container">

        <div class="row">

            {{-- Contract pages --}}
            <div class="col-md-4">
                <div class="col">
                    <h4>{{ Lang::get("boukem.information") }}</h4>
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
            </div>

            {{-- Quick links --}}
            <div class="col-md-4">
                <div class="col">
                    <h4>{{ Lang::get("boukem.shortcuts") }}</h4>
                    <ul>
                        <li>
                            <a href="{{ route('home') }}">
                                {{ Lang::get("boukem.home") }}
                            </a>
                        </li>
                        {{--TODO : display category links ($category_links_html)--}}
                    </ul>
                </div>
            </div>

            {{-- User links --}}
            <div class="col-md-4">
                <div class="col">
                    <h4>{{ Lang::get("boukem.customer_service") }}</h4>
                    <ul>

                        @if (Auth::guest())

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

        </div>

        {{-- Copyright & Powered By --}}
        <hr />
        <div class="row">
            <div class="col-lg-9 copyright">
                &copy; <?php echo date("Y"); ?>, {{ Lang::get("boukem.copyrights") }}.
            </div>
            <div class="col-lg-3 footer-logo text-right">
                <img src="{{ asset('/img/kem_signature.png') }}" alt="Powered by KEM">
            </div>
        </div>
    </div>
</footer>
