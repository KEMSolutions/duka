{{-- 401: Unauthorized --}}

@extends('errors.template')
@section('status', 401)
@section('details')
    @lang("boukem.401")
@endsection

