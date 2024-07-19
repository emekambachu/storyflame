@extends('emails.layout')

@section('title', 'Welcome to Flame')

@section('content')
    <p>{{ $name ? 'Dear '.$name : 'Hello' }},</p>
    <p>{{ $token }}</p>
@endsection
