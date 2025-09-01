<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div
        class="flex items-center justify-between flex-column flex-wrap md:flex-row space-y-4 md:space-y-0 px-2 py-4 bg-white dark:bg-gray-900">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Email Templates</h2>

        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                placeholder="Search templates...">
        </div>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-6 py-3">Template Name</th>
                <th class="px-6 py-3">Content Path</th>
                <th class="px-6 py-3">Preview (JSON)</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($emailTemplates as $template)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $template->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $template->content_path }}
                    </td>
                    <td class="px-6 py-4 max-w-sm">
                        <pre class="text-xs whitespace-pre-wrap overflow-auto max-h-40 bg-gray-100 dark:bg-gray-700 p-2 rounded">
                            {{ json_encode($template->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                        </pre>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ url('/admin/email-builder') }}#template/{{ $template->id }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No templates found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
