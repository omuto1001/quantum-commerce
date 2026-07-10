{{-- resources/views/auth/pending-approval.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-8 max-w-lg text-center mx-auto">
    @if ($user->isPending())
        <h2 class="text-xl font-bold text-yellow-600">⏳ Application Under Review</h2>
        <p class="mt-2 text-gray-600">
            Thanks for registering, {{ $user->name }}. An admin needs to review your
            {{ $user->roleLabel() }} application before you can start using your dashboard.
        </p>
    @elseif ($user->isRejected())
        <h2 class="text-xl font-bold text-red-600">❌ Application Rejected</h2>
        <p class="mt-2 text-gray-600">
            Unfortunately your application was not approved. Please contact support for more information.
        </p>
    @endif
</div>
@endsection