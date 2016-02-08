<div class="ui right vertical menu wide sidebar cart-drawer">
    <header class="cart-header">

        <table class="ui very basic table cart-header-table">
            <tbody>
            <tr>
                <td class="five wide center aligned">
                    <button class="ui button mini close-cart">
                        @lang("boukem.close")
                    </button>
                </td>
                <td class="six wide center aligned">
                    <h2 class="white">
                        @lang("boukem.cart")
                    </h2>
                </td>
                <td class="five wide center aligned">
                    <a href="{{ url("/cart") }}">
                        <button class="ui button mini color-one">
                            @lang("boukem.check_out")
                        </button>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

    </header>

    <div class="item" id="empty-cart">
        <h4 class="text-center">
            {{ Lang::get("boukem.empty_cart") }}
        </h4>
    </div>


    {{-- Populated in cartSliderContainer. --}}
    <div class="cart-items-list">

    </div>

    <hr/>

    <div class="cart-content-agreement text-center">
        <p>
            @lang('boukem.taxes_delivery')
        </p>
    </div>
</div>
