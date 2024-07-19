@extends('emails.layout')

@section('title', 'Welcome to StoryFlame')

@section('content')
    <p>Hello, Welcome to StoryFlame,</p>
    <p>Enter the your verification token to continue: <strong>{{ $email_token }}</strong></p>
@endsection
