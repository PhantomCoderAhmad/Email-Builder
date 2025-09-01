<!DOCTYPE html>
<html lang="en">
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
    @livewireStyles
    @vite(\Nwidart\Modules\Module::getAssets()) 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    @stack('css')
    @inertiaHead
</head>
<body>
    @inertia
    @stack('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script></script>
    <script>
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
        document.addEventListener('livewire:load', function() {
            console.log('Livewire loaded!');
        });

    </script>
    @livewireScripts    
    
</body>
</html>