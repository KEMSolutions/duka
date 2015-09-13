{{-- 400: Bad Request --}}

@extends('errors.template')
@section('status', 400)
@section('details')
    @lang("boukem.400")
@endsection

