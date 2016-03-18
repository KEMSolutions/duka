<div class="ui container grid vertically padded">
    <div class="row">
        <div class="column">
            <div class="ui breadcrumb">
                @if(Request::route()->getName() == "category")
                    <a>@lang("boukem.category")</a>
                @else
                    <a>@lang("boukem.brands")</a>
                @endif

                <i class="right chevron icon divider"></i>
                <a class="active section" href="/{{ $locale }}/cat/{{ $name }}">{{ $name }}</a>
            </div>
        </div>
    </div>
</div>
