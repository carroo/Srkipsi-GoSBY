<!-- Input Summary -->
<div class="p-6 bg-gray-50 border-b border-gray-200">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Data Input Kriteria</h3>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
        <!-- Rating Weight -->
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-gray-900 text-sm">⭐ Rating</span>
                <span class="text-xl font-black text-yellow-600">{{ number_format($sawCalculation['input']['weights']['rating'] * 100, 0) }}%</span>
            </div>
        </div>

        <!-- Price Weight -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-gray-900 text-sm">💰 Harga</span>
                <span class="text-xl font-black text-green-600">{{ number_format($sawCalculation['input']['weights']['price'] * 100, 0) }}%</span>
            </div>
        </div>

        <!-- Facility Weight -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-gray-900 text-sm">🏢 Fasilitas</span>
                <span class="text-xl font-black text-purple-600">{{ number_format($sawCalculation['input']['weights']['facility'] * 100, 0) }}%</span>
            </div>
        </div>

        <!-- Distance Weight -->
        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-lg p-3">
            <div class="flex items-center justify-between">
                <span class="font-semibold text-gray-900 text-sm">📍 Jarak</span>
                <span class="text-xl font-black text-blue-600">{{ number_format($sawCalculation['input']['weights']['distance'] * 100, 0) }}%</span>
            </div>
        </div>

        <!-- Category Weight -->
        <div class="md:col-span-2 bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-3">
            <div class="flex items-center justify-between mb-2">
                <span class="font-semibold text-gray-900 text-sm">🏷️ Kategori</span>
                <span class="text-xl font-black text-indigo-600">{{ number_format($sawCalculation['input']['weights']['category_total'] * 100, 0) }}%</span>
            </div>
            @if(!empty($sawCalculation['input']['selected_categories']))
                <div class="flex flex-wrap gap-1">
                    @foreach($sawCalculation['input']['selected_categories'] as $catId => $weight)
                        @php
                            $category = $categories->firstWhere('id', $catId);
                        @endphp
                        @if($category)
                            <span class="bg-indigo-600 text-white px-2 py-0.5 rounded text-xs font-bold">
                                {{ $category->name }} ({{ number_format($weight * 100, 0) }}%)
                            </span>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Min/Max Values -->
<div class="p-6 bg-white border-b border-gray-200">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Nilai Min & Max (Untuk Normalisasi)</h3>
    <div class="grid md:grid-cols-4 gap-3">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <h4 class="text-xs font-bold text-gray-600 mb-2">RATING</h4>
            <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">{{ number_format($sawCalculation['minMaxValues']['max']['rating'], 2) }}</span></p>
            <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">{{ number_format($sawCalculation['minMaxValues']['min']['rating'], 2) }}</span></p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <h4 class="text-xs font-bold text-gray-600 mb-2">HARGA</h4>
            <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">Rp {{ number_format($sawCalculation['minMaxValues']['max']['price'], 0, ',', '.') }}</span></p>
            <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">Rp {{ number_format($sawCalculation['minMaxValues']['min']['price'], 0, ',', '.') }}</span></p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <h4 class="text-xs font-bold text-gray-600 mb-2">FASILITAS</h4>
            <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">{{ $sawCalculation['minMaxValues']['max']['facility'] }}</span></p>
            <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">{{ $sawCalculation['minMaxValues']['min']['facility'] }}</span></p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
            <h4 class="text-xs font-bold text-gray-600 mb-2">JARAK</h4>
            <p class="text-sm"><span class="font-semibold">Max:</span> <span class="text-green-600 font-bold">{{ number_format($sawCalculation['minMaxValues']['max']['distance'], 2) }} km</span></p>
            <p class="text-sm"><span class="font-semibold">Min:</span> <span class="text-red-600 font-bold">{{ number_format($sawCalculation['minMaxValues']['min']['distance'], 2) }} km</span></p>
        </div>
    </div>
</div>

<!-- Calculation Table -->
<div class="p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Tabel Perhitungan SAW - Semua Wisata</h3>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div style="max-height: 500px; overflow-y: auto; overflow-x: auto;">
            <table class="min-w-full divide-y divide-gray-200 text-xs">
                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white sticky top-0">
                    <tr>
                        <th rowspan="3" class="px-2 py-2 text-center font-black uppercase border-r border-purple-400 sticky left-0 bg-purple-600 z-20" style="min-width: 50px;">Rank</th>
                        <th rowspan="3" class="px-3 py-2 text-left font-black uppercase border-r border-purple-400 sticky left-12 bg-purple-600 z-20" style="min-width: 150px;">Nama Wisata</th>

                        <th colspan="{{ 4 + count($sawCalculation['input']['selected_categories']) }}" class="px-2 py-1 text-center font-black uppercase border-r border-purple-400 bg-yellow-500 text-gray-900">Nilai Raw</th>
                        <th colspan="{{ 4 + count($sawCalculation['input']['selected_categories']) }}" class="px-2 py-1 text-center font-black uppercase border-r border-purple-400 bg-green-500 text-white">Ternormalisasi</th>
                        <th rowspan="3" class="px-3 py-2 text-center font-black uppercase bg-purple-700" style="min-width: 90px;">Skor SAW</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="px-1 py-1 text-center font-bold bg-yellow-400 text-gray-900 border-r border-yellow-300 text-xs">Kriteria</th>
                        @if(count($sawCalculation['input']['selected_categories']) > 0)
                            <th colspan="{{ count($sawCalculation['input']['selected_categories']) }}" class="px-1 py-1 text-center font-bold bg-yellow-400 text-gray-900 border-r border-purple-400 text-xs">Kategori</th>
                        @endif
                        <th colspan="4" class="px-1 py-1 text-center font-bold bg-green-400 text-gray-900 border-r border-green-300 text-xs">Kriteria</th>
                        @if(count($sawCalculation['input']['selected_categories']) > 0)
                            <th colspan="{{ count($sawCalculation['input']['selected_categories']) }}" class="px-1 py-1 text-center font-bold bg-green-400 text-gray-900 border-r border-purple-400 text-xs">Kategori</th>
                        @endif
                    </tr>
                    <tr>
                        <th class="px-2 py-1 text-center font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 60px;">Rating</th>
                        <th class="px-2 py-1 text-center font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 70px;">Harga</th>
                        <th class="px-2 py-1 text-center font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 60px;">Fasilitas</th>
                        <th class="px-2 py-1 text-center font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 60px;">Jarak</th>

                        @foreach($sawCalculation['input']['selected_categories'] as $catId => $weight)
                            @php
                                $category = $categories->firstWhere('id', $catId);
                                $shortName = $category ? (strlen($category->name) > 5 ? substr($category->name, 0, 5) : $category->name) : 'C' . $catId;
                            @endphp
                            <th class="px-1 py-1 text-center font-semibold bg-yellow-300 text-gray-900 border-r border-yellow-200" style="min-width: 50px;" title="{{ $category ? $category->name : '' }}">{{ $shortName }}</th>
                        @endforeach

                        <th class="px-2 py-1 text-center font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 60px;">R</th>
                        <th class="px-2 py-1 text-center font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 60px;">H</th>
                        <th class="px-2 py-1 text-center font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 60px;">F</th>
                        <th class="px-2 py-1 text-center font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 60px;">J</th>

                        @foreach($sawCalculation['input']['selected_categories'] as $catId => $weight)
                            @php
                                $category = $categories->firstWhere('id', $catId);
                                $shortName = $category ? (strlen($category->name) > 5 ? substr($category->name, 0, 5) : $category->name) : 'C' . $catId;
                            @endphp
                            <th class="px-1 py-1 text-center font-semibold bg-green-300 text-gray-900 border-r border-green-200" style="min-width: 50px;" title="{{ $category ? $category->name : '' }}">{{ $shortName }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($sawCalculation['results'] as $index => $result)
                        <tr class="hover:bg-purple-50 {{ $index < 5 ? 'bg-purple-50 font-semibold' : '' }}">
                            <td class="px-2 py-2 text-center border-r border-gray-200 sticky left-0 {{ $index < 5 ? 'bg-purple-50' : 'bg-white' }} z-10">
                                @if($index < 3)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $index == 0 ? 'bg-yellow-400' : ($index == 1 ? 'bg-gray-300' : 'bg-orange-400') }} text-gray-900 font-black text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                @else
                                    <span class="font-bold text-xs">{{ $index + 1 }}</span>
                                @endif
                            </td>

                            <td class="px-3 py-2 border-r border-gray-200 sticky left-12 {{ $index < 5 ? 'bg-purple-50' : 'bg-white' }} z-10">
                                <span class="text-xs font-semibold">{{ $result['tourism_name'] }}</span>
                            </td>

                            <!-- Raw Values -->
                            <td class="px-2 py-2 text-center bg-yellow-50 border-r border-gray-200">{{ number_format($result['raw_data']['rating'], 2) }}</td>
                            <td class="px-2 py-2 text-center bg-yellow-50 border-r border-gray-200">{{ number_format($result['raw_data']['price'], 0) }}</td>
                            <td class="px-2 py-2 text-center bg-yellow-50 border-r border-gray-200">{{ $result['raw_data']['facility'] }}</td>
                            <td class="px-2 py-2 text-center bg-yellow-50 border-r border-gray-200">{{ number_format($result['raw_data']['distance'], 2) }}</td>

                            @foreach($sawCalculation['input']['selected_categories'] as $catId => $weight)
                                <td class="px-2 py-2 text-center bg-yellow-50 border-r border-gray-200">
                                    @if(isset($result['raw_data']['categories'][$catId]) && $result['raw_data']['categories'][$catId] == 1)
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-red-600">✗</span>
                                    @endif
                                </td>
                            @endforeach

                            <!-- Normalized Values -->
                            <td class="px-2 py-2 text-center bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['rating'], 4) }}</td>
                            <td class="px-2 py-2 text-center bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['price'], 4) }}</td>
                            <td class="px-2 py-2 text-center bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['facility'], 4) }}</td>
                            <td class="px-2 py-2 text-center bg-green-50 border-r border-gray-200 font-mono">{{ number_format($result['normalized']['distance'], 4) }}</td>

                            @foreach($sawCalculation['input']['selected_categories'] as $catId => $weight)
                                <td class="px-2 py-2 text-center bg-green-50 border-r border-gray-200 font-mono">
                                    {{ number_format($result['normalized']['categories'][$catId] ?? 0, 4) }}
                                </td>
                            @endforeach

                            <!-- SAW Score -->
                            <td class="px-3 py-2 text-center bg-purple-50">
                                <span class="text-purple-700 font-black text-sm">{{ number_format($result['saw_score'], 4) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
