@extends('layout')

@section('title', 'Home - Skripsi')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Selamat Datang</h2>
            <p class="text-gray-600 mb-4">
                Ini adalah aplikasi skripsi yang dikembangkan dengan Laravel dan Tailwind CSS.
            </p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Mulai
            </button>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Informasi</h2>
            <p class="text-gray-600">
                Layout ini sudah menggunakan Tailwind CSS dan siap untuk dikembangkan sesuai kebutuhan project.
            </p>
        </div>
    </div>
@endsection
