@extends("app")

@section("content")


<section class="ui padded centered container">

<h3 class="ui top attached block center aligned header" style="background-color:#{{ Store::info()->colors->color_two }};color:#fff;border-color: #{{ Store::info()->colors->color_two }};">
    @lang("boukem.promoted_title")
</h3>
<div class="ui attached segment" style="border-color: #{{ Store::info()->colors->color_two }};">
    <div class="ui row four stackable doubling link cards">
    @forelse($promoted as $product)
        {{--  Present all the promoted products. You can tweak what product appear here from KEM's Admin Panel > Customize > Promoted --}}
        {!! view("product._card", ["product"=>$product])->render() !!}
        
    @empty
        {{-- When no products are promoted, just fetch 4 random products and present them inside of cards. --}}
        
            @foreach(Products::random(4) as $product)
                {!! view("product._card", ["product"=>$product])->render() !!}
            @endforeach
        

    @endforelse
    </div>
</div>


        
</section>
@endsection