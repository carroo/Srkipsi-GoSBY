<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - GoSBY Admin</title>

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables CSS (Custom styling) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Custom DataTables styling */
        .dataTables_wrapper {
            font-size: 0.875rem;
        }

        table.dataTable thead th {
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 18px;
        }

        table.dataTable tbody td {
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        table.dataTable tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Card animations */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar transition */
        .sidebar-link {
            transition: all 0.2s ease;
        }

        /* Custom button styles */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #63408a 100%);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: true }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarOpen ? 'ml-0' : '-ml-64'">
            <!-- Header -->
            @include('admin.partials.header')

            <!-- Page Content -->
            <main class="flex-1 p-6 lg:p-8">
                <!-- Breadcrumb -->
                @if(isset($breadcrumbs))
                <nav class="mb-6 text-sm">
                    <ol class="flex items-center space-x-2 text-gray-500">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600"><i class="fas fa-home"></i></a></li>
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="flex items-center">
                                <i class="fas fa-chevron-right text-xs mx-2"></i>
                                @if(isset($breadcrumb['url']))
                                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-blue-600">{{ $breadcrumb['title'] }}</a>
                                @else
                                    <span class="text-gray-700">{{ $breadcrumb['title'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            @include('admin.partials.footer')
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vite JS -->
    @vite('resources/js/app.js')

    <!-- Global Scripts -->
    <script>
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // SweetAlert2 Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Make Toast globally available
        window.Toast = Toast;

        // Confirm delete function
        window.confirmDelete = function(form) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        };

        // Success message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        // Error message
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        // Validation errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '<ul class="text-left">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
