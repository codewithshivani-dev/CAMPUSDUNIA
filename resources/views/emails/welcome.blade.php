@extends('emails.layouts.app')

@section('title', 'Welcome')

@section('content')
<p>Hello {{ $name }},</p>

<p>Welcome to {{ config('app.name') }} 🎉</p>

<p>We are glad to have you onboard.</p>
@endsection
