# Map Visualization Feature

## 📍 Overview
Fitur visualisasi peta interaktif untuk menampilkan rute itinerary menggunakan Leaflet.js dan OpenRouteService API.

## 🚀 Features Implemented

### 1. **Route Geometry API Integration**
- Menggunakan OpenRouteService Directions API untuk mendapatkan geometry rute
- Endpoint: `POST https://api.openrouteservice.org/v2/directions/driving-car`
- Request body berisi array koordinat dalam format `[longitude, latitude]`

### 2. **Controller Method: `getRouteGeometry()`**
Location: `app/Http/Controllers/ItineraryController.php`

**Fungsi:**
- Mengambil koordinat dari start point dan semua destinations dalam urutan optimal
- Mengirim request ke OpenRouteService API
- Mendapatkan geometry data untuk rute yang sebenarnya (bukan garis lurus)
- **Decode encoded polyline** menggunakan Google's Polyline Algorithm
- Menyimpan data ke session untuk ditampilkan di result page

**Response Structure:**
```php
[
    'type' => 'success', // or 'fallback'
    'geometry' => [
        'type' => 'LineString',
        'coordinates' => [[lng, lat], ...] // Decoded dari polyline
    ],
    'coordinates' => [[lng, lat], ...], // Waypoints
    'summary' => [
        'distance' => 1370.7, // meters
        'duration' => 292.4   // seconds
    ]
]
```

### 2.1 **Polyline Decoder: `decodePolyline()`**
Location: `app/Http/Controllers/ItineraryController.php`

**Fungsi:**
- Decode encoded polyline string dari OpenRouteService API
- Algorithm: Google's Encoded Polyline Algorithm Format
- Input: String seperti `"ghrlHir~s@?BIC{ELgDo@aBa@}@I?sB..."`
- Output: Array of `[longitude, latitude]` coordinates
- Precision: 5 decimal places (1e5)

**Algorithm Steps:**
1. Iterate through encoded string
2. Decode latitude delta using variable-length encoding
3. Decode longitude delta
4. Add deltas to previous values
5. Divide by 1e5 to get actual coordinates
6. Return as GeoJSON-compatible array

### 3. **Leaflet Map Visualization**
Location: `resources/views/itinerary/result.blade.php`

**Features:**
- **Interactive Map**: Zoom, pan, dan interaksi penuh
- **Custom Markers**:
  - 🚩 **Start Point**: Icon hijau dengan arrow
  - 📍 **Destinations**: Icon biru dengan nomor urutan
- **Route Line**: 
  - Biru solid jika menggunakan geometry dari API
  - Merah dash jika fallback (garis lurus)
- **Popup Information**:
  - Nama destinasi
  - Koordinat lat/long
  - Jarak dari lokasi sebelumnya
  - Waktu perjalanan
  - Link ke Google Maps

### 4. **Map Styling**
- Menggunakan OpenStreetMap tiles
- Custom CSS untuk popup dan markers
- Responsive design
- Auto-fit bounds untuk menampilkan semua markers

## 📦 Dependencies

### Frontend:
```html
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

### Backend:
- PHP cURL untuk API calls
- OpenRouteService API Key (sudah dikonfigurasi)
- Polyline decoder untuk geometry string

## 🔧 API Configuration

**OpenRouteService API Key:**
```
eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjIyMTZlOWViNmQwYjQ1MTRhODE5NDJlNzM2MDFjNTI1IiwiaCI6Im11cm11cjY0In0
```

**API Headers:**
```
Content-Type: application/json; charset=utf-8
Accept: application/json, application/geo+json
Authorization: {API_KEY}
```

**API Response Structure:**
```json
{
  "bbox": [minLng, minLat, maxLng, maxLat],
  "routes": [
    {
      "summary": {
        "distance": 1370.7,
        "duration": 292.4
      },
      "segments": [...],
      "bbox": [...],
      "geometry": "encoded_polyline_string",
      "way_points": [0, 17, 24]
    }
  ],
  "metadata": {...}
}
```

**Important Notes:**
- API returns **`routes`** array, NOT `features`
- Geometry is an **encoded polyline string** (needs decoding)
- Format follows Google's Encoded Polyline Algorithm
- Coordinates in geometry: `[longitude, latitude]`

## 📊 Data Flow

1. **Generate Itinerary** → TSP Algorithm menghitung rute optimal
2. **Get Route Geometry** → Request ke OpenRouteService dengan koordinat berurutan
3. **Store in Session** → Simpan geometry data bersama itinerary data
4. **Display Result** → Render Leaflet map dengan markers dan route line

## 🎨 Visual Components

### Map Container
```html
<div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300"></div>
```

### Marker Icons
- **Start**: Green circle with arrow icon
- **Destinations**: Blue circles with numbers

### Route Line
- **With API**: Blue solid line (actual driving route)
- **Fallback**: Red dashed line (straight lines)

## 🔄 Fallback Mechanism

Jika API gagal atau tidak tersedia:
1. System akan log warning
2. Menggunakan straight lines antar koordinat
3. Route tetap ditampilkan dengan garis merah putus-putus
4. Semua marker tetap berfungsi normal

## 🧪 Testing

### Test Scenarios:
1. ✅ Itinerary dengan 2-3 destinasi
2. ✅ Itinerary dengan banyak destinasi (5+)
3. ✅ Start point dari cart (tourism)
4. ✅ Start point custom location
5. ✅ API timeout/failure handling

## 📱 Responsive Design

- Desktop: Map height 500px
- Tablet: Responsive dengan auto-fit
- Mobile: Full width, scroll untuk detail

## 🔐 Security Notes

- API key hardcoded (consider moving to .env)
- HTTPS untuk semua external requests
- Input validation untuk koordinat
- XSS protection pada popup content

## 🚀 Future Enhancements

- [ ] Cache geometry data di database
- [ ] Multiple route options (fastest, shortest, etc)
- [ ] Turn-by-turn directions
- [ ] Export to GPX/KML
- [ ] Offline map tiles
- [ ] Real-time traffic data

## 📞 Support

Jika ada masalah dengan map visualization:
1. Check browser console untuk error
2. Verify API key masih valid
3. Check network tab untuk API responses
4. Pastikan Leaflet library loaded correctly
