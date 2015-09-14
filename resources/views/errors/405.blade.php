{{-- 405: Method Not Allowed --}}

@extends('errors.template')
@section('status', 405)
@section('details')
    @lang("boukem.405")
@endsection

