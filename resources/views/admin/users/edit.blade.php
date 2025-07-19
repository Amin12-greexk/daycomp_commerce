@extends('layouts.admin')

@section('content-header', 'Edit User')

@section('content')
    <div class="p-6 mt-8 bg-white rounded-md shadow-md">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700" for="name">Full Name</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                    @error('name')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="text-gray-700" for="email">Email Address</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                    @error('email')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="text-gray-700" for="password">New Password</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="password" name="password" id="password" placeholder="Leave blank to keep current password">
                    @error('password')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="text-gray-700" for="password_confirmation">Confirm New Password</label>
                    <input
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="password" name="password_confirmation" id="password_confirmation">
                </div>
                <div>
                    <label class="text-gray-700" for="role">Role</label>
                    <select
                        class="block w-full mt-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="role" id="role" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                        <option value="{{ \App\Models\User::ROLE_CUSTOMER }}" {{ old('role', $user->role) == \App\Models\User::ROLE_CUSTOMER ? 'selected' : '' }}>Customer</option>
                        <option value="{{ \App\Models\User::ROLE_ADMIN }}" {{ old('role', $user->role) == \App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>Admin</option>
                    </select>
                    @if(auth()->id() === $user->id)
                        <small class="text-gray-500">You cannot change your own role.</small>
                    @endif
                    @error('role')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 py-2 mr-2 font-bold text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 font-bold text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Update User</button>
            </div>
        </form>
    </div>
@endsection