@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container">
    <h3>Send Notification</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('notifications.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="receiver_ids">Send To</label>
            <select name="receiver_ids[]" class="form-control" multiple required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple users</small>
        </div>


        <div class="form-group mt-2">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group mt-2">
            <label>Message</label>
            <textarea name="message" rows="4" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Send</button>
    </form>
</div>
@endsection
