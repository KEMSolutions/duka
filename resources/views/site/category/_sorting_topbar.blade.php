<div class="row sorting-topbar horizontally-padded">
    <div class="ui horizontal list full-width">
        <div class="item">
            <span>
              <div class="ui inline dropdown dropdown-select top left pointing button labeled tiny" id="sort-by-box">
                  Sort by:
                  <div class="text">
                      <i class="linkify icon"></i>
                      Relevance
                  </div>
                  <i class="dropdown icon"></i>
                  <div class="menu sort-by">
                      <div class="item" data-sort="-relevance">
                          <i class="linkify icon"></i>
                          Relevance
                      </div>
                      <div class="item" data-sort="price">
                          <i class="dollar icon"></i>
                          Price: Low to High
                      </div>
                      <div class="item" data-sort="-price">
                          <i class="dollar icon"></i>
                          Price: High to Low
                      </div>
                  </div>
              </div>
            </span>
        </div>

        <div class="item">
            <span>
                <div class="ui inline dropdown dropdown-select top right pointing button labeled tiny" id="items-per-page-box">
                    Items per page:
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

        <div class="item">
                <span>
              Layout:
              <ul class="list-unstyled list-inline inline-block" id="category-result-layout">
                  <li>
                      <button class="ui labeled icon button tiny" id="grid-layout">
                          <i class="fa fa-th-large icon"></i>
                          Grid
                      </button>
                  </li>

                  <li>
                      <button class="ui labeled icon button tiny" id="list-layout">
                          <i class="fa fa-bars icon"></i>
                          List
                      </button>
                  </li>
              </ul>
            </span>
        </div>
    </div>
</div>
