<div class="three wide column" id="refine-search-container">
    <div class="row">
        <h3 class="ui header text-center">{{ Lang::get('boukem.results_found', ['total' => $total]) }}</h3>
    </div>

    <div class="row padded">
        <nav class="hidden-print hidden-xs hidden-sm">
            <ul class="nav list-unstyled">

                {{--Only display category filter on brand page --}}
                @if ($isBrand)
                    <li>
                        <div class="refine-search-component">
                            <h5 class="refine-search-title">@lang('boukem.categories')</h5>
                            <ul class="list-unstyled absolute-scrollable" id="refine-by-category">

                                @foreach(Categories::getAllCategories() as $category)
                                    <li>
                                        <label>
                                            <input type="checkbox" class="item" data-filter="{{ $category->id }}"/> {{ $category->name }}
                                        </label>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </li>
                @endif

                {{--Only show brands filter on category page --}}
                @if (!$isBrand)
                    <li>
                        <div class="refine-search-component">
                            <h5 class="refine-search-title">@lang('boukem.brands')</h5>
                            <ul class="list-unstyled absolute-scrollable" id="refine-by-brand">

                                @foreach(Brands::getAllBrands() as $brand)
                                    <li>
                                        <label>
                                            <input type="checkbox" class="item" data-filter="{{ $brand->id }}"/> {{ $brand->name }}
                                        </label>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </li>
                @endif

                {{--Price filter --}}
                <li>
                    <div class="refine-search-component">
                        <h5 class="refine-search-title">Gamme de prix</h5>
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
                                Update
                            </button>
                        </div>
                    </div>
                </li>

            </ul>
        </nav>
    </div>
</div>
