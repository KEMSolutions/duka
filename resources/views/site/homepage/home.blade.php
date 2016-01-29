@extends("app")

@section("content")


<section class="ui padded centered container">


<h2 class="ui center aligned icon header" style="color:#{{ Store::info()->colors->color_two }}">
  <i class="star icon"></i>
  <div class="content">
    @lang("boukem.promoted_title")
  </div>
</h2>
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
        
</section>
@endsection