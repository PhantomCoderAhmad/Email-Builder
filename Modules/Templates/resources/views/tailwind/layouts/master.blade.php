<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://www.Email Builder.com/index/assets/images/fav.jpg">
    <title>@yield('title') - Email Builder Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.css" rel="stylesheet" /> --}}
    <!-- Include Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

    @livewireStyles
    @vite(\Nwidart\Modules\Module::getAssets()) 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    @stack('css')
</head>

<body class="bg-white dark:bg-gray-900">
    
    @include('templates::tailwind.partials.preloader')
    {{-- Navbar Component --}}
    @include('templates::tailwind.partials.navbar')
    {{-- Navbar Component Ends here --}}
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        {{-- Sidebar --}}
        @include('templates::tailwind.partials.sidebar')
        {{-- Sidebar Ends here --}}
        {{-- Main Content --}}
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 lg:mr-0 dark:bg-gray-900">
            <main>
                @yield('content')
            </main>
            {{-- Footer --}}
            @include('templates::tailwind.partials.footer')
            {{-- Footer Ends here --}}
        </div>
    </div>
    @stack('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- Include Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (
            localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                console.log(event[0]);
                if (event.type === 'success') {
                    toastr.success(event.message, event.title);
                } else if (event.type === 'error') {
                    toastr.error(event.message, event.title);
                } else if (event.type === 'warning') {
                    toastr.warning(event.message, event.title);
                } else {
                    toastr.info(event.message, event.title);
                }
            });
        });
        //Handle error or success messages from laravel sessions
        @if (session('success'))
            toastr.success("{{ session('success') }}", "Success");
        @elseif (session('error'))
            toastr.error("{{ session('error') }}", "Error");
        @endif
       
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Write something...',
                tabsize: 2,
                height: 100,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
    @livewireScripts
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Livewire) {
            console.log("✅ Livewire is ready:", window.Livewire);

            // Safely wait for the DOM AND Livewire component to be fully booted
            Livewire.all().forEach(component => {
                console.log("Livewire component:", component);

                // Use wire:ignore-safe for this selector or target manually
                setTimeout(() => {
                    $('#templateContentEditor').summernote({
                        height: 300,
                        callbacks: {
                            onChange: function(contents) {
                                component.set('templateContent', contents);
                            }
                        }
                    });
                }, 200); // Slight delay to ensure DOM hydration
            });
        }
    });
</script>



</body>

</html>