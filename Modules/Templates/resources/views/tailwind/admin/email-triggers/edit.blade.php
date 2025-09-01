@extends('templates::tailwind.layouts.master')
@section('title', 'Email Triggers')

@section('content')
    <div
        class="flex gap-4 p-4 my-6 mx-4 bg-white rounded-lg shadow  md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">

        <span class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Edit Email Trigger
            @error('file')
                <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </span>
    </div>
    <div class="p-4 my-6 mx-4 bg-white rounded-lg shadow  md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">
        <livewire:templates::trigger.edit-email-trigger :trigger="$trigger" />
    </div>
@endsection
