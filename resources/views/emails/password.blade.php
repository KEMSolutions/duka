@lang('passwords.greeting', ['name' => $customer->name])


@lang('passwords.follow_token')


{{ route('auth.reset.token', ['token' => $token]) }}
