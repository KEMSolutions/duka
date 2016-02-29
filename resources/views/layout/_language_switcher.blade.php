<div class="ui button">
    <div class="ui simple dropdown item">
        {{ strtoupper(Localization::getCurrentLocale()) }}
        <div class="menu">
           @forelse ($alternatives as $alternative)

           @empty
            @foreach (Store::info()->locales as $locale)
                @if ($locale->language !== Localization::getCurrentLocale())
                    <a class="item" rel="alternate" hreflang="{{ $locale->language }}" href="{{Localization::getLocalizedURL($locale->language, "/") }}">
                        {{ $locale->language_name }}
                    </a>
                @endif
            @endforeach
           @endforelse
        </div>
    </div>
</div>