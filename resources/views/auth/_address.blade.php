<?php

// Set the index for this address.
$index = (isset($address->id) && $address->id > 0) ? $address->id : 'new';
?>

<h4 class="ui dividing header">@lang('boukem.address')</h4>

{{-- Address line 1 --}}
<div class="field">
    <label>@lang('boukem.address_1')</label>
    <input type="text" name="addresses[{{ $index }}][line1]" placeholder="">
</div>

{{-- Address line 2 --}}
<div class="field">
    <label>@lang('boukem.address_2')</label>
    <input type="text" name="addresses[{{ $index }}][line2]" placeholder="">
</div>

{{-- Country and province --}}
<div class="field">
    <label>@lang('boukem.country') &amp; @lang('boukem.province_state_reg')</label>
    <div class="fields">
        <div class="eight wide field">
            <select name="addresses[{{ $index }}][country]" disabled>

            </select>
        </div>

        <div class="eight wide field">
            <select name="addresses[{{ $index }}][province]" disabled>

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
                name="addresses[{{ $index }}][city]"
                value="{{ $address->city }}"
                placeholder="">
        </div>

        <div class="six wide field">
            <input
                type="tel"
                name="addresses[{{ $index }}][postcode]"
                value="{{ $user->postcode }}"
                placeholder="">
        </div>
    </div>
</div>
