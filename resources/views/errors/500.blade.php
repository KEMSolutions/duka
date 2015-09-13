{{-- 500: Internal Server Error --}}

@extends('errors.template')
@section('status', 500)
@section('details')
    @lang("boukem.500")
@endsection

