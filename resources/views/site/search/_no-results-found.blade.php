
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
                                    <h2>Sorry, no results were found.</h2>
                                    <h1 class="font-lg">
                                        <i class="fa fa-meh-o fa-4x"></i>
                                    </h1>

                                    <p>
                                        Try to rephrase your request. You can search by product name,
                                        category or brand (eg. glucosamine or children's vitamins.).
                                    </p>

                                    <p>
                                        Still no luck? No problem! Call us at
                                        <a href="tel:1-844-276-3434 ext. 8">1-844-276-3434 ext. 8</a>
                                        to order over the phone.
                                    </p>

                                    <span class="clearfix"></span>
                                    <form class="form-inline" method="get" action="{{ route('search') }}">
                                        <div class="input-group">
                                            <input name="q" class="form-control" value="" placeholder="Product name, brand or condition" type="text">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fa fa-search"></i>
                                                    Search
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