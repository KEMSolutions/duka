<div class="row sorting-topbar horizontally-padded">
    <div class="ui horizontal list full-width">
        <div class="item">
            <span>
              <div class="ui inline dropdown dropdown-select top left pointing button labeled tiny" id="sort-by-box">
                  @lang("boukem.sort_by")
                  <div class="text">
                      <i class="linkify icon"></i>
                      @lang("boukem.relevance")
                  </div>
                  <i class="dropdown icon"></i>
                  <div class="menu sort-by">
                      <div class="item" data-sort="-relevance" data-value="-relevance">
                          <i class="linkify icon"></i>
                          @lang("boukem.relevance")
                      </div>
                      <div class="item" data-sort="price" data-value="price">
                          <i class="dollar icon"></i>
                          @lang("boukem.price_low_to_high")
                      </div>
                      <div class="item" data-sort="-price" data-value="-price">
                          <i class="dollar icon"></i>
                          @lang("boukem.price_high_to_low")
                      </div>
                  </div>
              </div>
            </span>
        </div>

        <div class="item">
            <span>
                <div class="ui inline dropdown dropdown-select top right pointing button labeled tiny" id="items-per-page-box">
                    @lang("boukem.items_per_page")
                    <div class="text">
                        8
                    </div>
                    <i class="dropdown icon"></i>
                    <div class="menu items-per-page">
                        <div class="item" data-sort="8">
                            8
                        </div>
                        <div class="item" data-sort="24">
                            24
                        </div>
                        <div class="item" data-sort="40">
                            40
                        </div>
                        <div class="item" data-sort="80">
                            80
                        </div>
                    </div>
                </div>
            </span>
        </div>

        <div class="item pull-right">
            {{-- Grid layout is set by default. --}}
                <span>
              @lang("boukem.switch_layout")
                    <button class="ui labeled icon button tiny" id="category-layout-switcher">
                        <i class="list layout icon"></i>
                        @lang("boukem.list")
                    </button>
            </span>
        </div>
    </div>
</div>
