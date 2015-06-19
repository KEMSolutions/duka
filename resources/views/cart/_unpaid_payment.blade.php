<div class="container overlay fullScreen" id="cancelledOrder">
    <div class="jumbotron vertical-align color-one" >

        <div class="text-center">
            <h2>{{ Lang::get("boukem.pending_order", ["command" => json_decode($_COOKIE["_unpaid_orders"])->id]) }}</h2>
            <h4>{{ Lang::get("boukem.what_to_do") }}</h4>

            <br/>

            <a href="{{ route("api.orders.pay", ["id" => json_decode($_COOKIE["_unpaid_orders"])->id]) }}">
                <button class="btn btn-success" id="payOrder">{{ Lang::get("boukem.pay_now") }}</button>
            </a>
            <button class="btn btn-danger" id="cancelOrder">{{ Lang::get("boukem.cancel_order") }}</button>
        </div>

    </div>
</div>