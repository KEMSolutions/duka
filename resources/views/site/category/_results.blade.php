<div class="ui padded grid">
    <div class="ui row four stackable doubling link cards">
        @foreach($products as $product )
            @if($product)
                {!! view("product._card", ["product"=>$product])->render() !!}
            @endif
        @endforeach
    </div>
</div>

{{--Pagination --}}
<div class="row text-center">
    @if ($paginator)
        {!! $paginator->render() !!}
    @endif
</div>
