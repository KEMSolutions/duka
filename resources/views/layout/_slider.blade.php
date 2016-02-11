<div class="ui right vertical menu wide sidebar cart-drawer">
    <header class="cart-header">

        <div class="vertical-align">
            <h3 class="ui header white inline-block horizontal-align"
                style="padding: 14px 0 0 40px;"
                    >

                @lang("boukem.cart")

            </h3>


            <button class="circular tiny ui icon button close-cart pull-right inverted black"
                    style="margin: 7px 40px"
                    >
                <i class="icon remove"></i>
            </button>
        </div>

    </header>

    <div class="item ">
        <ul class="no-margin">
            <li>
                @lang('boukem.taxes_delivery')
            </li>

            <br/>

            <li>
                @lang('boukem.price_currency', ["currency" => "$ CAD"])
            </li>
        </ul>
    </div>



    <div class="item" id="empty-cart">
        <h4 class="text-center">
            {{ Lang::get("boukem.empty_cart") }}
        </h4>
    </div>



    <div class="item no-padding">
        <a href="{{ url("/cart") }}">
            <button class="ui button big fluid color-one no-border-radius">
                @lang("boukem.check_out")
            </button>
        </a>
    </div>


    {{-- Populated in cartSliderContainer. --}}
    <div class="cart-items-list">

    </div>

</div>
