<script>
    {{-- Include all language strings into a javascript object, based on the current locale. --}}
    {{-- We're basically including the language file, which itself contains an array of strings. --}}
    var Localization = {!! json_encode(include base_path('resources/lang/'. Localization::getCurrentLocale() .'/boukem.php')) !!};
    {{-- Include all necessary API endpoints into a javascript object. --}}
    var ApiEndpoints = {!! json_encode([
                'estimate'  => route('api.estimate'),
            'placeOrder'=> route('api.orders'),
            'orders'    => [
        'pay'   => route('api.orders.pay', ['id' => ':id', 'verification' => ':verification']),
    'view'  => route('api.orders.view', ['id' => ':id', 'verification' => ':verification'])
    ]
    ]) !!};
</script>