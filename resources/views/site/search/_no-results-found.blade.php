<div class="ui fluid container color-one" style="
    padding: 5%;
    border-top: 1px solid #fff;
    margin-bottom: -14px;"
        >

    <h2 class="ui center aligned icon header">
        <i class="frown icon"></i>
        <div class="content">
            @lang("boukem.no_result")
        </div>
    </h2>

    <h4 class="ui center aligned header">
        @lang("boukem.no_result_suggestion")
    </h4>

    @if (Store::info()->support->phone)
        <h5 class="ui center aligned header">
            {!! Lang::get('boukem.no_result_assistance', ["number"=>'<a href="tel:' . Store::info()->support->phone->number . '">' . Store::info()->support->phone->vanity . '</a>']) !!}
        </h5>
    @endif
</div>


