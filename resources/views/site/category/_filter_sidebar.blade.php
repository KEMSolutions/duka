<div class="three wide column" id="refine-search-container">
    <div class="row">
        <h3 class="ui header center aligned">{{ Lang::get('boukem.results_found', ['total' => $total]) }}</h3>
    </div>

    <div class="row padded">
        <nav>
            <ul class="nav list-unstyled">
                {{--Only show brands filter on category page --}}
                @if (!$isBrand)
                    <li>
                        <div class="refine-search-component">
                            <h5 class="ui header">@lang('boukem.brands')</h5>
                            <div class="ui list" id="refine-by-brand">
                                @foreach(Brands::getAllBrands() as $brand)
                                    <div class="item">
                                        <label>
                                            <input type="checkbox" class="item" data-name="{{ $brand->name }}" data-filter="{{ $brand->id }}" data-type="brands"/> {{ $brand->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endif

                {{--Price filter --}}
                <li>
                    <div class="refine-search-component">
                        <h5 class="ui header">@lang("boukem.price_range")</h5>
                        <div class="center-block">
                            <div class="ui labeled input refine-search-input">
                                <div class="ui label">
                                    $
                                </div>
                                <input type="number" placeholder="Min" min="0" id="min-price">
                            </div>

                            <div class="ui labeled input refine-search-input">
                                <div class="ui label">
                                    $
                                </div>
                                <input type="number" placeholder="Max" min="0" id="max-price">
                            </div>

                            <button class="ui button center-block" id="price-update">
                                @lang("boukem.update")
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
