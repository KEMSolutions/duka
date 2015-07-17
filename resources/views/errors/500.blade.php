{{-- 500: Internal Server Error --}}

@extends('errors.template')
@section('status', 500)
@section('details')
    <div>Internal Server Error.</div>
@endsection

