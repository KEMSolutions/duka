<footer class="color-four">
    <div class="container">

        <div class="row">

            {{-- TODO : B2B yet to be done--}}
            @if( true === true)
                <div class="col-md-4">
                    <div class="col">
                        <h4>{{ Lang::get("boukem.information") }}</h4>
                        <ul>
                            {{-- TODO : implement real href instead of placeholders--}}
                            <li><a href="{{ url("/about") }}"> {{ Lang::get("boukem.about") }}</a></li>
                            <li><a href="{{ url("/privacy") }}"> {{ Lang::get("boukem.privacy_policy") }}</a></li>
                            <li><a href="{{ url("/terms") }}"> {{ Lang::get("boukem.terms") }}</a></li>
                            <li><a href="{{ url("/shipping") }}"> {{ Lang::get("boukem.shipping_methods") }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="col">
                        <h4>{{ Lang::get("boukem.shortcuts") }}</h4>
                        <ul>
                            <li><a href="catalogue.html">{{ Lang::get("boukem.home") }}</a></li>
                            {{--TODO : display category links ($category_links_html)--}}
                        </ul>
                    </div>
                </div>
            @endif

            <div class="col-md-4">
                <div class="col">
                    <h4>{{ Lang::get("boukem.customer_service") }}</h4>
                    <ul>
                        {{-- TODO : implement real href instead of placeholders --}}
                        <li><a href="{{ url("/contacts") }}"> {{ Lang::get("boukem.contact_us") }}</a></li>
                        <li><a href="{{ url("/returns") }}"> {{ Lang::get("boukem.returns") }}</a></li>
                        <li><a href="{{ url("/auth/login") }}"> {{ Lang::get("boukem.account") }}</a></li>

                        @if (Auth::guest())
                            <li><a href="{{ url('/auth/register') }}"> {{ Lang::get("boukem.sign_up") }}</a></li>
                        @else
                            <li><a href="{{ url('/auth/logout') }}"> {{ Lang::get("boukem.sign_out") }}</a></li>
                        @endif

                    </ul>
                </div>
            </div>

        </div>


        <hr />
        <div class="row">
            <div class="col-lg-9 copyright">
                &copy; <?php echo date("Y"); ?>, {{ Lang::get("boukem.copyrights") }}.
            </div>
            <div class="col-lg-3 footer-logo text-right">
                <img src="{{url("/") . "/img/kem_signature.png"}}" alt="Powered by KEM">
            </div>
        </div>
    </div>
</footer><!-- JavaScript -->