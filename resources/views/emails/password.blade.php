Hello {{ $customer->name }}

@lang('passwords.follow_token_link') {{ route('auth.reset.token', ['token' => $token]) }}
