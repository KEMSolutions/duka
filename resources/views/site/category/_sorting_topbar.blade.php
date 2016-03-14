<div class="row sorting-topbar">
    <div class="ui horizontal list full-width">
        <div class="item">
            <span>
              <div class="ui inline dropdown top right pointing button labeled" id="sort-by-box">
                  @lang("boukem.sort_by")
                  <div class="text">
                      <span style="padding-left: 1rem">
                          <i class="linkify icon"></i>
                          @lang("boukem.relevance")
                      </span>
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

        <div class="item buttons">
            <span>
                <div class="ui inline dropdown top right pointing button labeled" id="items-per-page-box">
                    @lang("boukem.items_per_page")
                    <div class="text">
                        <span style="padding-left: 1rem">8</span>
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
                    </div>
                </div>
            </span>
        </div>
    </div>
</div>
