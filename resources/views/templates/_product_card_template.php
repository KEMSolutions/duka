<script type="x-template" id="product-card-template">

    <div class="card">
        <a class="ui fluid image" href="@{{ route }}">
            <template v-if="firstFormatReducedPrice && firstFormatRebatePercent">
                <div class="ui orange ribbon label">
                    - @{{ firstFormatRebatePercent }}
                </div>
            </template>

            <img :src="image">
        </a>

        <div class="content">
            <div class="header">
                <a href="@{{ route }}">
                    @{{ name }}
                </a>
            </div>

            <div class="meta">
                <a href="@{{ brandSlug }}" v-if="brandSlug && brandName">@{{ brandName }}</a>
            </div>

            <div class="description">
                @{{ description }}
            </div>
        </div>

        {{-- In the case there is only one format, we take the first format and display the
        appropriate values here.
        --}}
        <template v-if="formatNumber === 1">
            <div class="ui bottom attached button color-one buybutton" v-if="!products[0].discontinued"
                 data-product="@{{ productId }}-@{{ products[0].id }}"
                 data-price="@{{ firstFormatPrice }}"
                 data-thumbnail="@{{ thumbnail }}"
                 data-thumbnail_lg="@{{ thumbnailLg }}"
                 data-name="@{{ name }}-@{{ products[0].name }}"
                 data-description="@{{ description }}"
                 data-link="@{{ route }}"
                 data-quantity="1"
                >

                <div class="meta text-center white"
                     style="font-size: 11px;
                            margin-bottom: 0.3rem"
                     v-if="products[0].name != null"   >
                    @{{ products[0].name }}
                </div>

                <i class="add to cart icon"></i>
                <span v-if="products[0].reduced_price != null">
                    $ @{{ products[0].reduced_price.price }}
                </span>
                <span v-else>
                    $ @{{ products[0].price }}
                </span>
            </div>

            {{-- If the product is discontinued. --}}
            <div class="ui bottom attached button color-one discontinued" v-else>
                <div class="meta text-center white"
                     style="font-size: 11px;
                            margin-bottom: 0.3rem"
                     v-if="products[0].name != null"   >
                    @{{ products[0].name }}
                </div>

                <i class="add to cart icon"></i>
                <span v-if="products[0].reduced_price != null">
                    $ @{{ products[0].reduced_price.price }}
                </span>
                <span v-else>
                    $ @{{ products[0].price }}
                </span>
            </div>
        </template>

        {{--
        Multiple formats.
        --}}
        <template v-else>
            <div class="extra content">
                <span>Format</span>

                <select name="product-format" class="product-format" v-model="productFormat">
                    <option v-for="format in products"
                            :value="{ name: format.name,
                                      price: parseFloat(format.price).toFixed(2),
                                      reduced_price: format.reduced_price ? parseFloat(format.reduced_price.price).toFixed(2) : null,
                                      product: productId + '-' + format.id,
                                      format: format.name
                                      }"
                            selected>


                        <template v-if="format.reduced_price === null">
                            <span>
                                @{{ format.name }} - CAD $ @{{ parseFloat(format.price).toFixed(2) }}
                            </span>
                        </template>
                        <template v-else>
                             <span>
                                @{{ format.name }} - CAD $ @{{ parseFloat(format.reduced_price.price).toFixed(2) }}
                            </span>
                        </template>

                    </option>
                </select>
            </div>


            {{-- We display by default the first format information. --}}
            <div class="ui bottom attached button color-one buybutton" v-if="!products[0].discontinued"
                 data-product="@{{ productFormat.product }}"
                 data-price="@{{ productFormat.reduced_price ? productFormat.reduced_price : productFormat.price }}"
                 data-thumbnail="@{{ thumbnail }}"
                 data-thumbnail_lg="@{{ thumbnailLg }}"
                 data-name="@{{ name }}-@{{ productFormat.name }}"
                 data-description="@{{ description }}"
                 data-link="@{{ route }}"
                 data-quantity="1"
                >

                <div class="meta text-center white"
                     style="font-size: 11px;
                            margin-bottom: 0.3rem"
                     v-if="productFormat.name != null"   >
                    @{{ productFormat.name }}
                </div>

                <i class="add to cart icon"></i>
                <span v-if="productFormat.reduced_price != null">
                    $ @{{ productFormat.reduced_price }}
                </span>
                <span v-else>
                    $ @{{ productFormat.price }}
                </span>
            </div>

            {{-- If the product is discontinued. --}}
            <div class="ui bottom attached button color-one discontinued" v-else>
                <div class="meta text-center white"
                     style="font-size: 11px;
                            margin-bottom: 0.3rem"
                     v-if="productFormat.name != null"   >
                    @{{ productFormat.name }}
                </div>

                <i class="add to cart icon"></i>
                <span v-if="productFormat.reduced_price != null">
                    $ @{{ productFormat.reduced_price}}
                </span>
                <span v-else>
                    $ @{{ productFormat.price }}
                </span>
            </div>

        </template>

    </div>
</script>

