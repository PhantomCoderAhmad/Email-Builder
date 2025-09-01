@extends('templates::tailwind.layouts.master')
@section('title', 'Media Gallery')

@section('content')
    <div
        class="flex gap-4 p-4 my-6 mx-4 bg-white rounded-lg shadow  md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">

        <span class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Media Gallery
            @error('file')
                <p class="text-red-600 text-xs mt-1 font-semibold">{{ $message }}</p>
            @enderror
        </span>
        <button data-modal-target="add-media-modal" data-modal-toggle="add-media-modal"
            class=" text-white max-w-64  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Add Media
        </button>
    </div>
    <div class="p-4 my-6 mx-4 bg-white rounded-lg shadow  md:items-center md:justify-between md:p-6 xl:p-8 dark:bg-gray-800">
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 ">
            @foreach ($mediaLibrary as $media)
                @php
                    $inputId = 'media-url-' . $media->id;
                    $buttonId = 'copy-button-' . $media->id;
                    $mediaUrl = Storage::disk('media_library_public')->url($media->path);
                @endphp
                <div class="relative">
                    <img class="max-w-full object-cover h-52 rounded-lg" src="{{ $mediaUrl }}" alt="Image">
                    <div class="absolute top-2 right-2 flex gap-2 bg-gray-300 dark:bg-gray-700 rounded-lg px-4 py-2">
                        <button onclick="confirmDelete('{{ route('admin.media.destroy', $media->id) }}')"
                            title="Delete image" data-modal-target="confirm-delete-modal"
                            data-modal-toggle="confirm-delete-modal"
                            class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-xs px-2 py-1 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                            Delete
                        </button>
                        <a target="_blank" href="{{ $mediaUrl }}" title="View Image in new Tab"
                            class="text-white text-xs bg-gray-100 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg px-2 py-1 text-center dark:bg-gray-500 dark:hover:bg-gray-600 dark:focus:ring-gray-800">
                            <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2"
                                    d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </div>

                    <div class="w-full mt-1">
                        <div class="relative">
                            <label for="{{ $inputId }}" class="sr-only">Media Url</label>
                            <input id="{{ $inputId }}" type="text"
                                class="col-span-6 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-2.5 py-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{ Storage::disk('media_library_public')->url($media->path) }}" readonly>
                            <button id="{{ $buttonId }}" data-copy-target="{{ $inputId }}"
                                class="absolute end-2.5 top-1/2 -translate-y-1/2 text-gray-900 dark:text-gray-400 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 rounded-lg py-2 px-2.5 inline-flex items-center justify-center bg-white border-gray-200 border">
                                <span class="inline-flex items-center">
                                    <svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 18 20">
                                        <path
                                            d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                    </svg>
                                    <span class="text-xs font-semibold">Copy</span>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
        <!-- Pagination Links -->
        <nav aria-label="Page navigation example" class="flex items-center justify-center mt-4">
            <ul class="inline-flex -space-x-px text-sm">
                @if ($mediaLibrary->onFirstPage())
                    <li>
                        <span
                            class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">Previous</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $mediaLibrary->previousPageUrl() }}"
                            class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                @endif

                @foreach ($mediaLibrary->links()->elements[0] as $page => $url)
                    @if ($page == $mediaLibrary->currentPage())
                        <li>
                            <span
                                class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                @if ($mediaLibrary->hasMorePages())
                    <li>
                        <a href="{{ $mediaLibrary->nextPageUrl() }}"
                            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                @else
                    <li>
                        <span
                            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">Next</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>



    {{-- Modal to add image --}}

    <div id="add-media-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Add media
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="add-media-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="{{ route('admin.media.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">

                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input">Upload file</label>
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                aria-describedby="file_input_help" id="file_input" type="file" name="file">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="file_input_help">
                                Jpeg, Png, Jpg, Gif, Svg - Max (2MB).</p>


                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Add new media
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal to confirm deletion --}}

    <div id="confirm-delete-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Confirm Deletion
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="confirm-delete-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-300">Are you sure you want to delete this media?</p>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg border border-gray-200 text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 dark:hover:border-gray-600 dark:focus:ring-gray-600"
                            data-modal-toggle="confirm-delete-modal">Cancel</button>
                        <form id="delete-form" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-sm px-5 py-2.5 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(action) {
            const deleteForm = document.getElementById('delete-form');
            deleteForm.action = action;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-copy-target]').forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetId = button.getAttribute('data-copy-target');
                    const inputElement = document.getElementById(targetId);

                    if (inputElement) {
                        var tempElement = document.createElement('textarea');
                        tempElement.value = inputElement.value;
                        document.body.appendChild(tempElement);

                        // Select the text in the textarea
                        tempElement.select();

                        // Copy the selected text
                        document.execCommand('copy');

                        // Remove the temporary textarea
                        document.body.removeChild(tempElement);
                        console.log('Copied:', inputElement.value);
                        button.innerHTML =
                            '<svg class="w-3 h-3 text-blue-700 dark:text-blue-500 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/></svg><span class="text-xs font-semibold text-blue-700 dark:text-blue-500">Copied</span>';
                        setTimeout(function() {
                            button.innerHTML =
                                '<span class="inline-flex items-center"><svg class="w-3 h-3 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20"><path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/></svg><span class="text-xs font-semibold">Copy</span></span>';
                        }, 2000);
                    }
                });
            });
        });
    </script>
@endsection
