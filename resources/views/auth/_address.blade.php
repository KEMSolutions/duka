
<div class="title">
    {{ $address->id > 0 ? $address->line1 .' / '. $address->postcode : Lang::get('boukem.add_address') }}
</div>

<div class="content">

    {{-- Address line 1 --}}
    <div class="field">
        <label>@lang('boukem.address_1')</label>
        <input
            type="text"
            name="addresses[{{ $address->id }}][line1]"
            value="{{ $address->line1 }}"
            placeholder="@lang('boukem.address_1')">
    </div>

    {{-- Address line 2 --}}
    <div class="field">
        <label>@lang('boukem.address_2')</label>
        <input
            type="text"
            name="addresses[{{ $address->id }}][line2]"
            value="{{ $address->line2 }}"
            placeholder="@lang('boukem.address_2')">
    </div>

    {{-- Country and province --}}
    <div class="field">
        <label>@lang('boukem.country') &amp; @lang('boukem.province_state_reg')</label>
        <div class="fields">
            <div class="eight wide field">
                <select name="addresses[{{ $address->id }}][country]">
                    @foreach (\Utilities::getCountryList() as $code => $name)
                        <option value="{{ $code }}" {{ $code == $address->country ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="eight wide field">
                <select name="addresses[{{ $address->id }}][province]" disabled>

                </select>
            </div>
        </div>
    </div>

    {{-- City and postal code --}}
    <div class="field">
        <label>@lang('boukem.city') &amp; @lang('boukem.postal_code')</label>
        <div class="fields">
            <div class="ten wide field">
                <input
                    type="text"
                    name="addresses[{{ $address->id }}][city]"
                    value="{{ $address->city }}"
                    placeholder="@lang('boukem.city')">
            </div>

            <div class="six wide field">
                <input
                    type="tel"
                    name="addresses[{{ $address->id }}][postcode]"
                    value="{{ $user->postcode }}"
                    placeholder="@lang('boukem.postal_code')">
            </div>
        </div>
    </div>

    <input type="hidden" name="addresses[{{ $address->id }}][id]" value="{{ $address->id > 0 ? $address->id : 'new' }}">
</div>
