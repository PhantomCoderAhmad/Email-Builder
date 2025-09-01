@extends('templates::tailwind.layouts.master')
@section('title', 'Email Triggers')

@section('content')
    <div
        class="flex gap-4 p-4 my-6 mx-4 bg-white rounded-lg shadow  md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">

        <span class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Email Triggers
            @error('file')
                <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </span>
        <button data-modal-target="add-email-trigger-modal" data-modal-toggle="add-email-trigger-modal"
            class=" text-white max-w-64  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Add Email Trigger
        </button>
    </div>
    <div class="p-4 my-6 mx-4 bg-white rounded-lg shadow  md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">
        <livewire:templates::trigger.email-trigger-list />
    </div>
@endsection
