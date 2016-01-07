<div class="ui inverted page dimmer cart-dimmer">
    <button class="ui btn close-cart-dimmer one-half pull-left no-margin">@lang("boukem.close_cart")</button>

    <a href="{{ url("/cart") }}">
        <button class="ui btn btn-one btn-one-inverted close-cart-dimmer one-half pull-right no-margin">
            @lang("boukem.checkout")
        </button>
    </a>

    <hr/>

    <div class="ui container">
        <div class="content">
            <div class="ui divided items cart-items-list dimmered"></div>
        </div>
    </div>
</div>