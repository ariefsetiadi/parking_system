@extends('layouts.master')

@push('css')
@endpush

@section('content')
    <h5>Selamat Datang {{ Auth::user()->fullname }}</h5>
@endsection

@push('js')
@endpush
