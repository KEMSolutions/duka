<div class="col-md-2" id="refine-search-container">
    <div class="row">
        <h4 class="text-center">{{ Lang::get('boukem.results_found', ['total' => $total]) }}</h4>
    </div>

    <div class="row padding-5">
        <nav class="hidden-print hidden-xs hidden-sm">
        <ul class="nav list-unstyled">
            <li>
                <div class="refine-search-component">
                    <h5 class="refine-search-title">{{ Lang::get("boukem.categories") }}</h5>
                    <ul class="list-unstyled absolute-scrollable" id="refine-by-category">

                        @foreach(Categories::getAllCategories() as $category)
                            <li>
                                <label>
                                    <input type="checkbox" data-filter="{{ $category->id }}"/> {{ $category->name }}
                                </label>
                            </li>
                        @endforeach

                        </ul>
                    </div>
                </li>

                <li>
                    <div class="refine-search-component">
                        <h5 class="refine-search-title">Marques</h5>
                        <ul class="list-unstyled absolute-scrollable">
                            @foreach(Brands::getAllBrands() as $category)
                                @if($category)
                                    <li>
                                        <label>
                                            <input type="checkbox" data-filter="{{ $category->id }}"/> {{ $category->name }}
                                        </label>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </li>

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

                        <button class="ui button large center-block" id="price-update">
                            Update
                        </button>

                    </div>


                </div>


            </li>
        </ul>
    </nav>
    </div>
</div>