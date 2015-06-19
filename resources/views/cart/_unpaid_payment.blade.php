<div class="container overlay fullScreen" id="cancelledOrder">
    <div class="jumbotron vertical-align color-one" >

        <div class="text-center">
            <h2>Votre commande n'a pas été finalisée. </h2>
            <h4>Que souhaitez vous faire?</h4>

            <a href="{{ route("api.orders.pay", ["id" => json_decode($_COOKIE["_unpaid_orders"])->id]) }}">
                <button class="btn btn-success" id="payOrder">Payer cette commande</button>
            </a>
            <button class="btn btn-danger" id="cancelOrder">Annuler cette commande</button>
        </div>

    </div>
</div>