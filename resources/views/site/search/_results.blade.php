
    @if (isset($results->tags) && count($results->tags))
    <div class="ui container">
    <h5 class="ui top attached header">
  @lang("boukem.handpick")
</h5>
<div class="ui attached piled segment">
  <div class="ui four stackable cards">
    @foreach($results->tags[0]->products as $product)
                    {!! view("product._card", ["product"=>$product])->render() !!}
                @endforeach
    </div>
</div>
</div>
    @endif

<div class="ui stackable grid layout-toggle-container grid-layout">

    <div class="ui container equal width grid vertically padded">
        <div class="row">
            <div class="column" style="padding-top: 2rem">
                <h3 class="ui header">@lang('boukem.results_for', ["term" => $query])</h3>
            </div>
        </div>

        <div class="full-width">
            <div class="ui four stackable doubling link cards">
                @foreach($results->organic_results as $product)
                    {!! view("product._card", ["product"=>$product])->render() !!}
                @endforeach
            </div>
        </div>
    </div>

    <div class="row" style="text-align: center; display: block">
        {!! $paginator->render() !!}
    </div>

</div>

