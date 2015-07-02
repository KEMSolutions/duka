<div class="w-section inverse blog-grid">
    <div class="container">
        <div class="row">

            {{--TODO: Featured products (limited to 3 on desktop, slide them on mobile)--}}
            {{--TODO: Blur the background of header (get the right dimension)--}}
            {{--TODO: Implement breadcrumbs--}}
            {{--TODO: search functions (refining results)--}}
            {{--TODO: Pagination--}}


            <div class="col-md-2" id="refine-search-container">
                <h4>Refine By</h4>
                <hr/>

                <nav class="hidden-print hidden-xs hidden-sm">
                    <ul class="nav list-unstyled">
                        <li>
                            <div class="refine-search-component">
                                <h5 class="refine-search-title">Sous-catégories</h5>
                                <ul class="list-unstyled absolute-scrollable">
                                    <li>
                                        <label><input type="checkbox"/> Tisanes thérapeutiques</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Fatigue</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Stress</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <div class="refine-search-component">
                                <h5 class="refine-search-title">Marques</h5>
                                <ul class="list-unstyled absolute-scrollable">
                                    <li>
                                        <label><input type="checkbox"/> Lorna Vanderhaeghe</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                    <li>
                                        <label><input type="checkbox"/> Folie</label>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <div class="refine-search-component">
                                <h5 class="refine-search-title">Gamme de prix</h5>
                                <span>
                                    Min $
                                    <input type="number" min="0" max="100" placeholder="0"/>
                                    Max $
                                    <input type="number" min="0" max="100" placeholder="100"/>
                                </span>
                            </div>


                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-md-10">
                @include(
                    'product.layout._product_card_dense', [
                        'showTag' => false,
                        'locale' => Localization::getCurrentLocale(),
                        'products' => $products
                ])
            </div>
        </div>

         {{--Pagination --}}
        <div class="row text-center">
            {!! $paginator->render() !!}
        </div>
    </div>
</div>