# Live Tracking Map - Quick Implementation

Minimal implementation of a real-time tracking map using Leaflet.js. Copy and paste to implement in any project.

---

## 🔒 Authentication & Access Control

**Important Notes:**

- **Drivers** are represented as **users** in the system
- **Dashboard Login** is restricted to **Agents** and **Administrators** only
- Drivers authenticate through the existing user authentication system
- Dashboard access is controlled by role-based permissions (RBAC)
- Only users with `agent` or `admin` roles can access the web dashboard
- Driver users are for API/mobile authentication only

**Implementation:**
```php
// Middleware to protect dashboard routes
Route::middleware(['auth', 'role:admin,agent'])->group(function () {
    Route::get('/drivers/live-map', fn() => view('drivers.live-map'))->name('admin.drivers.live_map');
    // ... other dashboard routes
});
```

---

## 🚀 Quick Start (3 Files)

### 1️⃣ API Controller (Returns locations as JSON)

**Create:** `app/Http/Controllers/Api/DriverGeoController.php`

```
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

/**
 * Returns a list of drivers with geographic coordinates for map display.
 * Supports:
 * - last_lat / last_lng fields
 * - last_gps as text "lat,lng"
 * - last_gps as JSON {"lat":..,"lng":..}
 */
class DriverGeoController extends Controller
{
    public function index()
    {
        $rows = DB::table('drivers')->get();

        $data = $rows->map(function ($d) {
            [$lat, $lng] = $this->extractLatLng($d);

            return [
                'id'          => $d->id,
                'name'        => $d->name ?? null,
                'phone'       => $d->phone ?? null,
                'status'      => $d->status ?? 'offline',
                'rating'      => isset($d->rating) ? (float)$d->rating : null,
                'lat'         => $lat,
                'lng'         => $lng,
                'battery'     => isset($d->last_battery) ? (int)$d->last_battery : null,
                'online'      => isset($d->last_seen_at) && now()->subMinutes(5)->lte($d->last_seen_at),
                'low_battery' => isset($d->last_battery) && $d->last_battery < 15,
                'last_seen_at'=> isset($d->last_seen_at) ? (string)$d->last_seen_at : null,
                'app_version' => $d->app_version ?? null,
                'city'        => $d->city ?? null,
            ];
        })->filter(fn($d) => $d['lat'] !== null && $d['lng'] !== null)->values();

        return response()->json(['data' => $data]);
    }

    private function extractLatLng($d): array
    {
        // 1) Direct fields
        if (isset($d->last_lat, $d->last_lng) && is_numeric($d->last_lat) && is_numeric($d->last_lng)) {
            return [(float)$d->last_lat, (float)$d->last_lng];
        }

        // 2) last_gps = "lat,lng"
        if (isset($d->last_gps) && is_string($d->last_gps) && str_contains($d->last_gps, ',')) {
            [$a, $b] = array_map('trim', explode(',', $d->last_gps, 2));
            if (is_numeric($a) && is_numeric($b)) return [(float)$a, (float)$b];
        }

        // 3) last_gps JSON {"lat":..,"lng":..}
        if (isset($d->last_gps) && is_string($d->last_gps)) {
            $json = json_decode($d->last_gps, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($json['lat'],$json['lng'])) {
                return [ (float)$json['lat'], (float)$json['lng'] ];
            }
        }

        return [null, null];
    }
}
```

---

### 2️⃣ Add API Route

**In:** `routes/web.php` or `routes/api.php`

```
use App\Http\Controllers\Api\DriverGeoController;

// Add this route
Route::get('/api/drivers/geo', [DriverGeoController::class, 'index'])->name('api.drivers.geo');
```

---

### 3️⃣ Map View (Complete HTML + JavaScript)

**Create:** `resources/views/drivers/live-map.blade.php`

```
{{-- resources/views/drivers/live-map.blade.php --}}
@extends('layouts.app')
@section('title','Live Drivers Map')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
  :root {
    --map-height: 660px;
  }
  body { direction: rtl; }
  
  .drivers-map-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
    margin-bottom: 20px;
  }
  
  .drivers-map-card h3 {
    margin: 0 0 10px;
    color: #0f172a;
    font-size: 1.25rem;
    font-weight: 700;
  }
  
  #drivers-map {
    height: var(--map-height);
    width: 100%;
    min-height: 400px;
    border-radius: 14px;
    position: relative;
    overflow: hidden;
  }
  
  /* Fix tile clipping */
  .leaflet-container img.leaflet-tile { max-width: none !important; }
  .leaflet-container .leaflet-marker-icon,
  .leaflet-container .leaflet-marker-shadow { max-width: none !important; }
  
  .legend {
    background: #fff;
    border-radius: 10px;
    padding: .75rem 1rem;
    margin-top: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
  }
  
  .legend .dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-inline-end: 4px;
  }
  
  .legend .dot.idle { background: #22c55e; }
  .legend .dot.busy { background: #ef4444; }
  .legend .dot.offline { background: #9ca3af; }
  
  .legend-item {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-inline-end: 16px;
  }
  
  .status-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-inline-start: 4px;
  }
  
  .status-badge.idle {
    background: #dcfce7;
    color: #166534;
  }
  
  .status-badge.busy {
    background: #fee2e2;
    color: #991b1b;
  }
  
  .status-badge.offline {
    background: #f3f4f6;
    color: #6b7280;
  }
  
  .battery-low {
    color: #dc2626;
    font-weight: 600;
  }
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="mb-1">Live Drivers Map</h3>
      <p class="text-muted small mb-0">Auto-refresh every 15 seconds</p>
    </div>
    <div class="d-flex gap-2">
      <button id="btnRefresh" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-arrow-clockwise"></i> Refresh
      </button>
      <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.drivers.index') }}">
        <i class="bi bi-arrow-right"></i> Back
      </a>
    </div>
  </div>

  <div class="drivers-map-card">
    <h3>Live Drivers Map</h3>
    <div id="drivers-map" aria-label="Drivers Map"></div>
    
    <div class="legend" aria-hidden="true">
      <div class="legend-item">
        <span class="dot idle"></span>
        <span>Available</span>
      </div>
      <div class="legend-item">
        <span class="dot busy"></span>
        <span>Busy</span>
      </div>
      <div class="legend-item">
        <span class="dot offline"></span>
        <span>Offline</span>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
  // Map configuration
  const DEFAULT_CENTER = [21.4858, 39.1925]; // Change to your default location
  const DEFAULT_ZOOM = 11;
  const API_ENDPOINT = '/api/drivers/geo';
  const REFRESH_INTERVAL = 15000; // 15 seconds

  // Initialize map
  const map = L.map('drivers-map', {
    zoomSnap: 0.5,
    minZoom: 4,
    maxZoom: 19
  }).setView(DEFAULT_CENTER, DEFAULT_ZOOM);

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
  }).addTo(map);

  // Add scale control
  L.control.scale({ imperial: false }).addTo(map);

  // Fix map size
  const fixSize = () => map.invalidateSize(true);
  window.addEventListener('load', fixSize);
  window.addEventListener('resize', fixSize);
  setTimeout(fixSize, 100);

  // Store markers
  let markers = new Map();
  let driversData = [];

  // Status colors
  const statusColors = {
    'available': '#22c55e',
    'idle': '#22c55e',
    'busy': '#ef4444',
    'offline': '#9ca3af',
    'suspended': '#dc2626'
  };

  function getStatusColor(status) {
    const normalized = String(status || 'offline').toLowerCase();
    return statusColors[normalized] || '#9ca3af';
  }

  function translateStatus(status) {
    const normalized = String(status || 'offline').toLowerCase();
    const translations = {
      'available': 'Available',
      'idle': 'Available',
      'busy': 'Busy',
      'offline': 'Offline',
      'suspended': 'Suspended'
    };
    return translations[normalized] || 'Offline';
  }

  function createPopupContent(driver) {
    const statusClass = String(driver.status || 'offline').toLowerCase();
    const batteryClass = (driver.battery && driver.battery < 15) ? 'battery-low' : '';
    
    let html = `
      <div class="popup-content">
        <div class="popup-title">${driver.name || 'Driver'}</div>
        <div class="popup-info">
          <div><strong>Status:</strong> <span class="status-badge ${statusClass}">${translateStatus(driver.status)}</span></div>
    `;
    
    if (driver.phone) {
      html += `<div><strong>Phone:</strong> ${driver.phone}</div>`;
    }
    
    if (driver.battery !== null && driver.battery !== undefined) {
      html += `<div class="${batteryClass}"><strong>Battery:</strong> ${driver.battery}%</div>`;
    }
    
    if (driver.city) {
      html += `<div><strong>City:</strong> ${driver.city}</div>`;
    }
    
    if (driver.rating) {
      html += `<div><strong>Rating:</strong> ${driver.rating} ⭐</div>`;
    }
    
    if (driver.last_seen_at) {
      const lastSeen = new Date(driver.last_seen_at).toLocaleString();
      html += `<div><strong>Last Seen:</strong> ${lastSeen}</div>`;
    }
    
    html += `</div></div>`;
    return html;
  }

  function drawOrUpdateMarker(driver) {
    const { id, lat, lng, status } = driver;
    
    if (!lat || !lng) return;

    const color = getStatusColor(status);
    const key = `driver_${id}`;

    if (markers.has(key)) {
      const marker = markers.get(key);
      marker.setLatLng([lat, lng]);
      marker.setStyle({
        color: color,
        fillColor: color
      });
      marker.setPopupContent(createPopupContent(driver));
    } else {
      const marker = L.circleMarker([lat, lng], {
        radius: 10,
        color: '#fff',
        weight: 3,
        fillColor: color,
        fillOpacity: 0.9
      }).addTo(map);

      marker.bindPopup(createPopupContent(driver));
      markers.set(key, marker);
    }
  }

  function removeStaleMarkers(currentDriverIds) {
    const currentKeys = currentDriverIds.map(id => `driver_${id}`);
    
    markers.forEach((marker, key) => {
      if (!currentKeys.includes(key)) {
        map.removeLayer(marker);
        markers.delete(key);
      }
    });
  }

  async function loadDrivers() {
    try {
      const response = await fetch(API_ENDPOINT);
      const json = await response.json();
      
      driversData = json.data || [];
      
      console.log(`Loaded ${driversData.length} drivers`);
      
      driversData.forEach(drawOrUpdateMarker);
      
      const currentIds = driversData.map(d => d.id);
      removeStaleMarkers(currentIds);
      
      if (!loadDrivers._fitted && driversData.length > 0) {
        fitMapToBounds();
        loadDrivers._fitted = true;
      }
      
    } catch (error) {
      console.error('Error loading drivers:', error);
    }
  }

  function fitMapToBounds() {
    const latlngs = [];
    markers.forEach(marker => {
      latlngs.push(marker.getLatLng());
    });
    
    if (latlngs.length > 0) {
      const bounds = L.latLngBounds(latlngs);
      map.fitBounds(bounds, {
        padding: [30, 30],
        maxZoom: DEFAULT_ZOOM
      });
    }
  }

  document.getElementById('btnRefresh')?.addEventListener('click', () => {
    loadDrivers();
  });

  document.addEventListener('DOMContentLoaded', () => {
    loadDrivers();
    setInterval(loadDrivers, REFRESH_INTERVAL);
  });
</script>
@endpush
```

---

## ⚙️ Configuration

### Web Route

**Add to:** `routes/web.php`

```
// Add within your drivers route group
Route::prefix('drivers')->as('admin.drivers.')->middleware('auth')->group(function () {
    // ... existing routes ...
    
    Route::get('/live-map', fn() => view('drivers.live-map'))->name('live_map');
});
```

---

### Database Schema

**Minimum required columns in your tracking table:**

```
-- Minimum required columns
id BIGINT PRIMARY KEY
name VARCHAR(255)
status ENUM('available', 'idle', 'busy', 'offline', 'suspended')
last_lat DECIMAL(10,7) NULLABLE
last_lng DECIMAL(10,7) NULLABLE

-- Optional but recommended
phone VARCHAR(50) NULLABLE
rating DECIMAL(3,2) NULLABLE
last_battery TINYINT NULLABLE
last_seen_at TIMESTAMP NULLABLE
city VARCHAR(100) NULLABLE
app_version VARCHAR(50) NULLABLE
```

**OR** use a single `last_gps` field:

```
last_gps VARCHAR(255) NULLABLE -- Can store "lat,lng" or JSON {"lat":x,"lng":y}
```

---

## 🎯 Quick Customization

**Change these variables in the JavaScript section:**

```
// Map center location
const DEFAULT_CENTER = [YOUR_LAT, YOUR_LNG]; // e.g., [40.7128, -74.0060] for NYC

// Auto-refresh interval
const REFRESH_INTERVAL = 15000; // milliseconds (15 seconds)

// Status colors
const statusColors = {
    'available': '#22c55e',  // Green
    'busy': '#ef4444',       // Red  
    'offline': '#9ca3af'     // Gray
};
```

---

## 🔗 Adding Navigation Link

Add to your sidebar or navigation:

```
<a href="{{ route('admin.drivers.live_map') }}" class="nav-link">
    <i class="bi bi-map"></i>
    <span>Live Map</span>
</a>
```

---

## 🧪 Test It

```bash
# Test API
curl http://localhost:8000/api/drivers/geo

# Access map in browser
http://localhost:8000/drivers/live-map
```

---

## 🤖 AI Prompt (Copy & paste to AI editor)

```
Implement a live tracking map feature:

1. API endpoint /api/drivers/geo returning JSON with lat/lng coordinates
2. Leaflet.js map with auto-refresh every 15 seconds  
3. Color-coded markers: green (available), red (busy), gray (offline)
4. Popups showing: name, status, phone, battery
5. Auto-fit bounds to show all markers

Database needs: id, name, status, last_lat, last_lng, phone, battery, last_seen_at

Authentication:
- Drivers are users in the system
- Dashboard access restricted to agents and admins only (role-based)
- Use middleware: ['auth', 'role:admin,agent'] for dashboard routes
- Drivers authenticate via API/mobile (Sanctum) not web login

Use code from LIVE_MAP_IMPLEMENTATION_GUIDE.md
```
