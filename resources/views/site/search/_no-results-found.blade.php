
{{-- This code was copied over from Boukem 1. --}}
<section class="slice color-one">
    <div class="w-section inverse">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="aside-feature">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="text-center">
                                    <h2>@lang("boukem.no_result")</h2>
                                    <h1 class="font-lg">
                                        <i class="fa fa-meh-o fa-4x"></i>
                                    </h1>

                                    <p>
                                        @lang("boukem.no_result_suggestion")
                                    </p>

@if (Store::info()->support->phone)
                                    <p>
                                        {!! Lang::get('boukem.no_result_assistance'); !!}
                                        <a href="tel:{{ Store::info()->support->phone->number }}">{{ Store::info()->support->phone->number }}</a>
                                        
                                    </p>
@endif
                                    <span class="clearfix"></span>
                                    <form class="form-inline" method="get" action="{{ route('search') }}">
                                        <div class="input-group">
                                            <input name="q" class="form-control" value="" placeholder="@lang('boukem.product_name_brand_condition')" type="text">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    @lang("boukem.search")
                                                </button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>