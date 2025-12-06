@extends('layout')

@section('title', 'Hasil Itinerary')

@section('content')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        .table-wrapper {
            overflow-x: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in-up">
                <h1 class="text-4xl md:text-5xl font-black mb-3">
                    <svg class="w-12 h-12 inline-block mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    Hasil Itinerary Perjalanan
                </h1>
                <p class="text-xl text-indigo-100">Rencana perjalanan optimal Anda</p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Alert Messages -->
            @if(isset($alerts) && count($alerts) > 0)
                @foreach($alerts as $alert)
                    <div class="mb-6 animate-fade-in-up">
                        @if($alert['type'] === 'info')
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-lg font-bold text-blue-900">{{ $alert['title'] }}</h3>
                                        <p class="mt-1 text-blue-800">{{ $alert['message'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($alert['type'] === 'success')
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-lg font-bold text-green-900">{{ $alert['title'] }}</h3>
                                        <p class="mt-1 text-green-800">{{ $alert['message'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($alert['type'] === 'warning')
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-lg font-bold text-yellow-900">{{ $alert['title'] }}</h3>
                                        <p class="mt-1 text-yellow-800">{{ $alert['message'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
            
            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 animate-fade-in-up">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Ringkasan
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-indigo-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $settings['duration_days'] }}</div>
                        <div class="text-sm text-gray-600 mt-1">Hari</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ number_format($route['total_distance'] / 1000, 2) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Jarak (km)</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ gmdate('H:i', $route['total_duration']) }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total Durasi</div>
                    </div>
                    <div class="bg-pink-50 rounded-lg p-4 text-center">
                        <div class="text-3xl font-bold text-pink-600">
                            {{ collect($route['days'])->sum(function($day) { return count($day['locations']); }) }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Destinasi</div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <div class="bg-gray-100 rounded-lg px-4 py-2">
                        <span class="text-sm text-gray-600">Waktu: </span>
                        <span class="font-semibold text-gray-900">{{ $settings['start_time'] }} - {{ $settings['end_time'] }}</span>
                    </div>
                    <div class="bg-gray-100 rounded-lg px-4 py-2">
                        <span class="text-sm text-gray-600">Titik Awal: </span>
                        <span class="font-semibold text-gray-900">{{ $startLocation['name'] }}</span>
                    </div>
                    <div class="bg-gray-100 rounded-lg px-4 py-2">
                        <span class="text-sm text-gray-600">Titik Akhir: </span>
                        <span class="font-semibold text-gray-900">{{ $endLocation['name'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Programming Steps Visualization -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4 cursor-pointer" onclick="toggleDPSteps()">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        Visualisasi Algoritma Dynamic Programming
                        <span class="ml-2 text-sm text-gray-500">(Klik untuk toggle)</span>
                    </h3>
                    <svg id="dpToggleIcon" class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div id="dpStepsContent" style="display: none;">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-400 p-4 mb-4 rounded">
                        <p class="text-sm text-indigo-900 font-semibold mb-2">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Tentang Algoritma Bitmask Dynamic Programming untuk TSP
                        </p>
                        <p class="text-sm text-indigo-800 ml-7 mb-2">
                            Algoritma ini menggunakan <strong>Bitmask Dynamic Programming</strong> untuk menyelesaikan Traveling Salesman Problem:
                        </p>
                        <ul class="text-sm text-indigo-800 ml-7 space-y-1 list-disc list-inside">
                            <li><strong>State:</strong> dp[mask][i] = jarak minimum mengunjungi lokasi dalam mask, berakhir di i</li>
                            <li><strong>Bitmask:</strong> Representasi biner untuk tracking lokasi yang sudah dikunjungi</li>
                            <li><strong>Transition:</strong> Coba semua kemungkinan lokasi berikutnya, pilih yang optimal</li>
                            <li><strong>Backtracking:</strong> Rekonstruksi path optimal dari tabel DP</li>
                            <li><strong>Complexity:</strong> O(2^n × n²) waktu, O(2^n × n) space</li>
                        </ul>
                    </div>

                    <!-- Timeline of DP Steps -->
                    <div class="relative">
                        @foreach ($dpSteps as $step)
                            @php
                                $stepColors = [
                                    'INITIALIZATION' => ['bg' => 'bg-blue-100', 'border' => 'border-blue-400', 'text' => 'text-blue-800', 'icon' => 'bg-blue-500'],
                                    'LOCATION_PREPARATION' => ['bg' => 'bg-green-100', 'border' => 'border-green-400', 'text' => 'text-green-800', 'icon' => 'bg-green-500'],
                                    'DP_TABLE_INIT' => ['bg' => 'bg-purple-100', 'border' => 'border-purple-400', 'text' => 'text-purple-800', 'icon' => 'bg-purple-500'],
                                    'DP_BASE_CASE' => ['bg' => 'bg-indigo-100', 'border' => 'border-indigo-400', 'text' => 'text-indigo-800', 'icon' => 'bg-indigo-500'],
                                    'DP_TRANSITION' => ['bg' => 'bg-yellow-100', 'border' => 'border-yellow-400', 'text' => 'text-yellow-800', 'icon' => 'bg-yellow-500'],
                                    'DP_COMPLETE' => ['bg' => 'bg-teal-100', 'border' => 'border-teal-400', 'text' => 'text-teal-800', 'icon' => 'bg-teal-500'],
                                    'FIND_OPTIMAL_ENDING' => ['bg' => 'bg-orange-100', 'border' => 'border-orange-400', 'text' => 'text-orange-800', 'icon' => 'bg-orange-500'],
                                    'BACKTRACK_PATH' => ['bg' => 'bg-pink-100', 'border' => 'border-pink-400', 'text' => 'text-pink-800', 'icon' => 'bg-pink-500'],
                                    'SPLIT_INTO_DAYS' => ['bg' => 'bg-cyan-100', 'border' => 'border-cyan-400', 'text' => 'text-cyan-800', 'icon' => 'bg-cyan-500'],
                                    'DAY_SPLIT' => ['bg' => 'bg-amber-100', 'border' => 'border-amber-400', 'text' => 'text-amber-800', 'icon' => 'bg-amber-500'],
                                    'ADD_TO_DAY' => ['bg' => 'bg-lime-100', 'border' => 'border-lime-400', 'text' => 'text-lime-800', 'icon' => 'bg-lime-500'],
                                    'FINAL_RESULT' => ['bg' => 'bg-purple-100', 'border' => 'border-purple-400', 'text' => 'text-purple-800', 'icon' => 'bg-purple-500'],
                                ];
                                
                                $stepType = explode('_', $step['step'], 2)[0] ?? 'INITIALIZATION';
                                if (str_contains($step['step'], 'DP_')) $stepType = $step['step'];
                                if (str_contains($step['step'], 'FIND_')) $stepType = 'FIND_OPTIMAL_ENDING';
                                if (str_contains($step['step'], 'BACKTRACK')) $stepType = 'BACKTRACK_PATH';
                                if (str_contains($step['step'], 'SPLIT')) $stepType = 'SPLIT_INTO_DAYS';
                                if (str_contains($step['step'], '_SPLIT')) $stepType = 'DAY_SPLIT';
                                if (str_contains($step['step'], 'ADD_TO')) $stepType = 'ADD_TO_DAY';
                                
                                $colors = $stepColors[$stepType] ?? ['bg' => 'bg-gray-100', 'border' => 'border-gray-400', 'text' => 'text-gray-800', 'icon' => 'bg-gray-500'];
                            @endphp

                            <div class="mb-4 flex items-start">
                                <!-- Timeline dot -->
                                <div class="flex-shrink-0 w-10 h-10 {{ $colors['icon'] }} rounded-full flex items-center justify-center text-white font-bold shadow-lg z-10">
                                    {{ $loop->iteration }}
                                </div>

                                <!-- Timeline line -->
                                @if (!$loop->last)
                                    <div class="absolute left-5 mt-10 w-0.5 bg-gray-300" style="height: calc(100% - 2.5rem);"></div>
                                @endif

                                <!-- Step content -->
                                <div class="ml-4 flex-1">
                                    <div class="border-l-4 {{ $colors['border'] }} {{ $colors['bg'] }} rounded-r-lg p-4 shadow-md">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-bold {{ $colors['text'] }} text-lg">
                                                {{ $step['step'] }}
                                            </h4>
                                            <span class="text-xs {{ $colors['text'] }} opacity-75">Step #{{ $loop->iteration }}</span>
                                        </div>
                                        <p class="{{ $colors['text'] }} text-sm mb-3">{{ $step['description'] }}</p>
                                        
                                        @if (isset($step['data']) && !empty($step['data']))
                                            <div class="bg-white bg-opacity-60 rounded p-3">
                                                @if ($step['step'] === 'DP_TRANSITION')
                                                    <!-- Special rendering for DP transition -->
                                                    <dl class="grid grid-cols-2 gap-2 text-xs">
                                                        @foreach ($step['data'] as $key => $value)
                                                            @if (!is_array($value))
                                                                <dt class="text-gray-600 font-semibold">{{ ucwords(str_replace('_', ' ', $key)) }}:</dt>
                                                                <dd class="text-gray-900">{{ $value }}</dd>
                                                            @elseif ($key === 'visited_before')
                                                                <dt class="text-gray-600 font-semibold col-span-2">Visited:</dt>
                                                                <dd class="text-gray-900 col-span-2">{{ implode(', ', $value) }}</dd>
                                                            @endif
                                                        @endforeach
                                                    </dl>
                                                @elseif ($step['step'] === 'FIND_OPTIMAL_ENDING' && isset($step['data']['candidates']))
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full text-xs">
                                                            <thead class="bg-gray-200">
                                                                <tr>
                                                                    <th class="px-2 py-1 text-left">Lokasi</th>
                                                                    <th class="px-2 py-1 text-left">Total Distance</th>
                                                                    <th class="px-2 py-1 text-center">Best?</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($step['data']['candidates'] as $candidate)
                                                                    <tr class="{{ $candidate['is_best'] ? 'bg-green-100 font-bold' : '' }}">
                                                                        <td class="px-2 py-1 border-t">{{ $candidate['name'] }}</td>
                                                                        <td class="px-2 py-1 border-t">{{ number_format($candidate['total_distance'] / 1000, 2) }} km</td>
                                                                        <td class="px-2 py-1 border-t text-center">
                                                                            @if ($candidate['is_best'])
                                                                                <span class="text-green-600 text-lg">★</span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @elseif ($step['step'] === 'DP_BASE_CASE' && isset($step['data']['distances']))
                                                    <div class="space-y-1">
                                                        <strong class="text-xs text-gray-700">Jarak dari {{ $step['data']['from'] }}:</strong>
                                                        <ul class="text-xs text-gray-600 list-disc list-inside">
                                                            @foreach ($step['data']['distances'] as $dist)
                                                                <li class="{{ $dist['feasible'] ? 'text-green-700' : 'text-red-700' }}">
                                                                    Ke {{ $dist['to_name'] }}: {{ number_format($dist['distance'] / 1000, 2) }} km ({{ $dist['duration_minutes'] }} menit)
                                                                    @if (!$dist['feasible'])
                                                                        - <span class="font-semibold">{{ $dist['reason'] }}</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @elseif ($step['step'] === 'LOCATION_PREPARATION' && isset($step['data']['locations']))
                                                    <div class="space-y-1">
                                                        <strong class="text-xs text-gray-700">Daftar Lokasi (n={{ $step['data']['n'] }}):</strong>
                                                        <ul class="text-xs text-gray-600 list-disc list-inside">
                                                            @foreach ($step['data']['locations'] as $loc)
                                                                <li>Index {{ $loc['index'] }}: {{ $loc['name'] }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @elseif ($step['step'] === 'BACKTRACK_PATH' && isset($step['data']['path_names']))
                                                    <div class="space-y-1">
                                                        <strong class="text-xs text-gray-700">Urutan Optimal:</strong>
                                                        <div class="text-sm text-gray-900 font-semibold">
                                                            {{ implode(' → ', $step['data']['path_names']) }}
                                                        </div>
                                                    </div>
                                                @elseif ($step['step'] === 'FINAL_RESULT' && isset($step['data']['optimal_path']))
                                                    <div class="space-y-2">
                                                        <dl class="grid grid-cols-2 gap-2 text-xs">
                                                            @foreach ($step['data'] as $key => $value)
                                                                @if ($key !== 'optimal_path')
                                                                    <dt class="text-gray-600 font-semibold">{{ ucwords(str_replace('_', ' ', $key)) }}:</dt>
                                                                    <dd class="text-gray-900">{{ $value }}</dd>
                                                                @endif
                                                            @endforeach
                                                        </dl>
                                                        <div class="pt-2 border-t">
                                                            <strong class="text-xs text-gray-700">Rute Optimal:</strong>
                                                            <div class="text-sm text-indigo-900 font-bold mt-1">
                                                                {{ implode(' → ', $step['data']['optimal_path']) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Default rendering for other steps -->
                                                    <dl class="grid grid-cols-2 gap-2 text-xs">
                                                        @foreach ($step['data'] as $key => $value)
                                                            @if (!is_array($value))
                                                                <dt class="text-gray-600 font-semibold">{{ ucwords(str_replace('_', ' ', $key)) }}:</dt>
                                                                <dd class="text-gray-900">{{ $value }}</dd>
                                                            @endif
                                                        @endforeach
                                                    </dl>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Itinerary by Day -->
            @foreach ($route['days'] as $dayData)
                @php
                    $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $dayOfWeek = isset($dayData['day_of_week']) ? $dayNames[$dayData['day_of_week']] : '';
                @endphp
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="bg-indigo-600 text-white rounded-full w-10 h-10 flex items-center justify-center mr-3">
                                {{ $dayData['day'] }}
                            </span>
                            <div>
                                <div>Hari {{ $dayData['day'] }}</div>
                                @if($dayOfWeek)
                                    <div class="text-sm font-normal text-gray-500">{{ $dayOfWeek }}</div>
                                @endif
                            </div>
                        </h3>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Jarak: <span class="font-bold text-indigo-600">{{ number_format($dayData['total_distance'] / 1000, 2) }} km</span></div>
                            <div class="text-sm text-gray-600">Durasi: <span class="font-bold text-purple-600">{{ gmdate('H:i', $dayData['total_duration']) }}</span></div>
                        </div>
                    </div>

                    @if (empty($dayData['locations']))
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                            <p class="text-yellow-800">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak ada destinasi yang dapat dikunjungi pada hari ini
                            </p>
                        </div>
                    @else
                        <div class="table-wrapper rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-indigo-600 to-purple-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Destinasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Lokasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Jarak dari Sebelumnya</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Waktu Tempuh</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Waktu Kunjungan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if ($dayData['day'] === 1 && $dayData['start_location'])
                                        <tr class="bg-green-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="flex items-center justify-center w-8 h-8 bg-green-500 text-white rounded-full font-bold">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-green-800">🚩 TITIK AWAL</div>
                                                <div class="text-sm text-green-600">{{ $startLocation['name'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ number_format($startLocation['latitude'], 6) }}, {{ number_format($startLocation['longitude'], 6) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">-</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">-</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">{{ $settings['start_time'] }}</td>
                                        </tr>
                                    @endif

                                    @php
                                        $currentTime = strtotime($settings['start_time']);
                                    @endphp

                                    @foreach ($dayData['locations'] as $location)
                                        @php
                                            $travelMinutes = $location['travel_minutes'];
                                            $arrivalTime = $location['arrival_time'] ?? date('H:i', $currentTime + ($travelMinutes * 60));
                                            $currentTime += $travelMinutes * 60;
                                            $visitDuration = 60; // 60 minutes
                                            $currentTime += $visitDuration * 60;
                                            $departureTime = date('H:i', $currentTime);
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full font-bold">
                                                    {{ $loop->iteration }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-gray-900">{{ $location['tourism']['name'] }}</div>
                                                @if (isset($location['tourism']['description']))
                                                    <div class="text-sm text-gray-500">{{ Str::limit($location['tourism']['description'], 50) }}</div>
                                                @endif
                                                
                                                <!-- Open Status Badge -->
                                                @if (isset($location['is_open']))
                                                    @if ($location['is_open'])
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Buka
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            {{ $location['open_status'] }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ number_format($location['tourism']['latitude'], 6) }}, {{ number_format($location['tourism']['longitude'], 6) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                                    </svg>
                                                    {{ number_format($location['distance_from_previous'] / 1000, 2) }} km
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $travelMinutes }} menit
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <div class="font-semibold text-gray-900">{{ $arrivalTime }} - {{ $departureTime }}</div>
                                                <div class="text-xs text-gray-500">(60 menit)</div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($dayData['day'] === $settings['duration_days'] && !empty($dayData['locations']))
                                        <tr class="bg-red-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="flex items-center justify-center w-8 h-8 bg-red-500 text-white rounded-full font-bold">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-red-800">🏁 TITIK AKHIR</div>
                                                <div class="text-sm text-red-600">{{ $endLocation['name'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">
                                                {{ number_format($endLocation['latitude'], 6) }}, {{ number_format($endLocation['longitude'], 6) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if (isset($dayData['return_distance']))
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                                        {{ number_format($dayData['return_distance'] / 1000, 2) }} km
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if (isset($dayData['return_duration']))
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                                        {{ ceil($dayData['return_duration'] / 60) }} menit
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @if (isset($dayData['return_duration']))
                                                    {{ date('H:i', $currentTime + ceil($dayData['return_duration'] / 60) * 60) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Distance Matrix Section (Collapsible) -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4 cursor-pointer" onclick="toggleDistanceMatrix()">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-7 h-7 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Matriks Jarak (Distance Matrix)
                        <span class="ml-2 text-sm text-gray-500">(Klik untuk toggle)</span>
                    </h3>
                    <svg id="matrixToggleIcon" class="w-6 h-6 text-gray-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <div id="distanceMatrixContent" style="display: none;">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded">
                        <p class="text-sm text-blue-800">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            Matriks ini menunjukkan jarak (dalam km) dan durasi (dalam menit) antar semua lokasi. 
                            Label <span class="px-2 py-1 bg-green-200 text-green-800 rounded font-semibold text-xs">✓</span> menandakan data dari cache database.
                        </p>
                    </div>

                    <div class="table-wrapper rounded-lg overflow-auto max-h-96">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gradient-to-r from-gray-700 to-gray-600 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-white uppercase tracking-wider sticky left-0 bg-gray-700 z-10">
                                        Dari / Ke
                                    </th>
                                    @php
                                        $allLocationIds = array_keys($distanceMatrix);
                                        $locationNames = [];
                                        $locationNames[$startLocation['id']] = '🚩 ' . Str::limit($startLocation['name'], 15);
                                        foreach($route['days'] as $day) {
                                            foreach($day['locations'] as $loc) {
                                                $locationNames[$loc['tourism']['id']] = Str::limit($loc['tourism']['name'], 15);
                                            }
                                        }
                                        $locationNames[$endLocation['id']] = '🏁 ' . Str::limit($endLocation['name'], 15);
                                    @endphp
                                    @foreach ($allLocationIds as $locId)
                                        <th class="px-4 py-3 text-center text-xs font-bold text-white uppercase tracking-wider whitespace-nowrap">
                                            {{ $locationNames[$locId] ?? 'Loc-' . $locId }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($allLocationIds as $fromId)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-semibold text-gray-900 whitespace-nowrap sticky left-0 bg-white border-r-2 border-gray-200">
                                            {{ $locationNames[$fromId] ?? 'Loc-' . $fromId }}
                                        </td>
                                        @foreach ($allLocationIds as $toId)
                                            @php
                                                $distData = $distanceMatrix[$fromId][$toId] ?? null;
                                            @endphp
                                            <td class="px-4 py-3 text-center whitespace-nowrap {{ $fromId === $toId ? 'bg-gray-100' : '' }}">
                                                @if ($distData)
                                                    @if ($fromId === $toId)
                                                        <span class="text-gray-400">-</span>
                                                    @else
                                                        <div class="text-xs">
                                                            <div class="font-semibold text-blue-600">
                                                                {{ number_format($distData['distance'] / 1000, 2) }} km
                                                            </div>
                                                            <div class="text-gray-500">
                                                                {{ ceil($distData['duration'] / 60) }} min
                                                            </div>
                                                            @if (isset($distData['cached']) && $distData['cached'])
                                                                <div class="text-green-600 font-bold text-xs">✓</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-300">N/A</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center mt-8">
                <a href="{{ route('itinerary.create') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Buat Ulang
                </a>
                <button onclick="window.print()"
                    class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print / PDF
                </button>
            </div>

        </div>
    </section>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    <script>
        function toggleDistanceMatrix() {
            const content = document.getElementById('distanceMatrixContent');
            const icon = document.getElementById('matrixToggleIcon');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        function toggleDPSteps() {
            const content = document.getElementById('dpStepsContent');
            const icon = document.getElementById('dpToggleIcon');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection
