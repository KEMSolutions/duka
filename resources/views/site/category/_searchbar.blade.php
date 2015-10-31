<div class="col-md-2 hidden-print hidden-xs hidden-sm" id="refine-search-container">
    <h4>@lang("boukem.refine_by")</h4>
    <hr/>

    <nav>
        <ul class="nav list-unstyled">
            <li>
                <div class="refine-search-component">
                    <h5 class="refine-search-title">@lang("boukem.subcategory")</h5>
                    <ul class="list-unstyled absolute-scrollable">
                        <!--<li>
                            <label><input type="checkbox"/> Tisanes thérapeutiques</label>
                        </li>
                        <li>
                            <label><input type="checkbox"/> Fatigue</label>
                        </li>
                        <li>
                            <label><input type="checkbox"/> Stress</label>
                        </li>-->
                        
                    </ul>
                </div>
            </li>

            <li>
                <div class="refine-search-component">
                    <h5 class="refine-search-title">@lang("boukem.brand")</h5>
                    <ul class="list-unstyled absolute-scrollable">
                        <!--<li>
                            <label><input type="checkbox"/> Lorna Vanderhaeghe</label>
                        </li>
                        -->
                    </ul>
                </div>
            </li>

            <li>
                <div class="refine-search-component">
                    <h5 class="refine-search-title">@lang("boukem.price_range")</h5>
                                <span>
                                    @lang("boukem.min") $
                                    <input type="number" min="0" max="100" placeholder="0"/>
                                    @lang("boukem.max") $
                                    <input type="number" min="0" max="100" placeholder="100"/>
                                </span>
                </div>


            </li>
        </ul>
    </nav>
</div>
