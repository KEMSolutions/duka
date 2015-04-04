{{--TODO : Include back link --}}

<header>
    <nav class="navbar navbar-default navbar-white" id="navbar">
        <div class="container">

            <div class="navbar-header">
                <button type="button" id="cmdSearchCollapse" class="navbar-toggle">
                    <i class="fa fa-search icon-search"></i>
                </button>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">{{ Lang::get("boukem.home") }}</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{url("/")}}">
                    <img src="{{url("/") . "/img/logo.png"}}" class="img-responsive" alt="">
                </a>
            </div>

            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/') }}">Home</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden-xs">
                        <a href="#" class="" id="cmdSearch"><i class="fa fa-search"></i></a>
                    </li>

                    <li>
                        {{-- TODO : Put alternative link for B2B--}}
                        <a href="{{ url("/") }}"> {{ Lang::get("boukem.home") }} </a>
                    </li>

                    {{--TODO: display next categories? --}}

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get("boukem.contact") }}</a>
                        <ul class="dropdown-menu">
                            <li>
                                {{--TODO : afficher le poste de la boutique Yii::app()->params['outbound_api_user']; --}}
                                <a href="tel:18442763434" class="sign-up.html"><i class="fa fa-phone fa-fw"></i> 1-844-276-3434 </a>
                            </li>
                            <li>
                                {{--TODO : afficher le courriel de la boutique Yii::app()->params['adminEmail']; --}}
                                <a href="mailto:#" class="sign-up.html"><i class="fa fa-envelope fa-fw"></i>Temporary Email </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        {{--TODO : update the cart_badge with the right amount of products in cart--}}
                        <a href="{{ url("/cart/index") }}"><i class="fa fa-shopping-cart icon-cart"></i> {{ Lang::get("boukem.cart") }}<span class="badge" id="cart_badge">0</span></a>
                    </li>

                    @if (Auth::guest())
                        <li><a href="{{ url('/auth/login') }}">Login</a></li>
                        <li><a href="{{ url('/auth/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>--}}
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </nav>
</header>