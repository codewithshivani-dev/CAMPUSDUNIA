@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<div class="container">
    <h3>My Notifications</h3>
    @foreach($notifications as $note)
        <div class="card mb-2 {{ $note->is_read ? '' : 'border-warning' }}">
            <div class="card-body">
                <h5>{{ $note->title }}</h5>
                <p>{{ $note->message }}</p>
                <small>From: {{ $note->sender->name }}</small>
                @if(!$note->is_read)
                    <a href="{{ route('notifications.read', $note->id) }}" class="btn btn-sm btn-success float-end">Mark as Read</a>
                @endif
            </div>
        </div>
    @endforeach

    {{ $notifications->links() }}
</div>
@endsection
