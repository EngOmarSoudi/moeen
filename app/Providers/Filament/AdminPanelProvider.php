<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#B8860B', // Dark Golden Rod
                'secondary' => '#D4A574',
                'gray' => Color::Zinc,
            ])
            ->brandName('MOEAN System')
            ->font('Outfit')
            ->favicon('/favicon.ico')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->spa()
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => Blade::render('@include("filament.components.language-switch")')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                    <script>
                        document.addEventListener("alpine:init", () => {
                            Alpine.data("tripLocationPicker", () => ({
                                map: null,
                                marker: null,
                                latElement: null,
                                lngElement: null,
                                locationType: null,
                                latField: null,
                                lngField: null,
                                nameField: null,
                                
                                initPicker(type, latField, lngField, nameField) {
                                    this.locationType = type;
                                    this.latField = latField;
                                    this.lngField = lngField;
                                    this.nameField = nameField;
                                    
                                    console.log("Init picker:", type, { latField, lngField });
                                    
                                    // Find coordinate fields
                                    const form = this.$el.closest("form");
                                    if (form) {
                                        // Try multiple selectors (removing invalid wire:model selector)
                                        const latSelectors = [
                                            `input[name="data[${latField}]"]`,
                                            `input[id*="${latField}"]`,
                                            `.coordinate-field[id*="${latField}"]`,
                                            `input[type="text"][id*="${latField}"]`
                                        ];
                                        
                                        const lngSelectors = [
                                            `input[name="data[${lngField}]"]`,
                                            `input[id*="${lngField}"]`,
                                            `.coordinate-field[id*="${lngField}"]`,
                                            `input[type="text"][id*="${lngField}"]`
                                        ];
                                        
                                        // Try each selector until we find the field
                                        for (const selector of latSelectors) {
                                            this.latElement = form.querySelector(selector);
                                            if (this.latElement) {
                                                console.log(`✓ Found lat field with selector: ${selector}`, this.latElement);
                                                break;
                                            }
                                        }
                                        
                                        for (const selector of lngSelectors) {
                                            this.lngElement = form.querySelector(selector);
                                            if (this.lngElement) {
                                                console.log(`✓ Found lng field with selector: ${selector}`, this.lngElement);
                                                break;
                                            }
                                        }
                                        
                                        console.log("Fields found:", { 
                                            lat: !!this.latElement, 
                                            lng: !!this.lngElement,
                                            latElement: this.latElement,
                                            lngElement: this.lngElement
                                        });
                                        
                                        // If not found, try to find ALL inputs to debug
                                        if (!this.latElement || !this.lngElement) {
                                            console.error("Hidden fields not found! Searching all inputs...");
                                            const allInputs = form.querySelectorAll("input");
                                            const relevantInputs = Array.from(allInputs).filter(i => 
                                                i.name?.includes(latField) || i.name?.includes(lngField) ||
                                                i.id?.includes(latField) || i.id?.includes(lngField)
                                            );
                                            console.log(`Relevant inputs for ${type}:`, relevantInputs.map(i => ({
                                                tag: i.tagName,
                                                type: i.type,
                                                name: i.name,
                                                id: i.id,
                                                class: i.className,
                                                value: i.value
                                            })));
                                        }
                                    } else {
                                        console.error("Form not found!");
                                    }
                                    
                                    // Wait for Leaflet
                                    const checkLeaflet = setInterval(() => {
                                        if (typeof L !== "undefined") {
                                            clearInterval(checkLeaflet);
                                            this.initMap();
                                        }
                                    }, 100);
                                    setTimeout(() => clearInterval(checkLeaflet), 10000);
                                },
                                
                                initMap() {
                                    const container = this.$refs.mapContainer;
                                    if (!container || container._leaflet_id) return;
                                    
                                    const lat = this.latElement?.value ? parseFloat(this.latElement.value) : 24.7136;
                                    const lng = this.lngElement?.value ? parseFloat(this.lngElement.value) : 46.6753;
                                    
                                    this.map = L.map(container).setView([lat, lng], 12);
                                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                                        attribution: "&copy; OpenStreetMap",
                                        maxZoom: 19
                                    }).addTo(this.map);
                                    
                                    if (this.latElement?.value) {
                                        this.addMarker(lat, lng);
                                        this.updateDisplay(lat, lng);
                                    }
                                    
                                    this.map.on("click", (e) => {
                                        this.selectLocation(e.latlng.lat, e.latlng.lng);
                                    });
                                    
                                    setTimeout(() => this.map.invalidateSize(), 300);
                                },
                                
                                selectLocation(lat, lng) {
                                    console.log("Location selected:", this.locationType, lat, lng);
                                    
                                    if (this.latElement) {
                                        const oldValue = this.latElement.value;
                                        this.latElement.value = lat.toFixed(8);
                                        console.log(`${this.locationType} lat updated:`, oldValue, "->", this.latElement.value);
                                        console.log(`${this.locationType} lat field:`, {
                                            name: this.latElement.name,
                                            id: this.latElement.id,
                                            tagName: this.latElement.tagName,
                                            type: this.latElement.type
                                        });
                                        this.latElement.dispatchEvent(new Event("input", { bubbles: true }));
                                        this.latElement.dispatchEvent(new Event("change", { bubbles: true }));
                                    } else {
                                        console.error(`${this.locationType} latElement is NULL! Cannot update coordinate.`);
                                    }
                                    
                                    if (this.lngElement) {
                                        const oldValue = this.lngElement.value;
                                        this.lngElement.value = lng.toFixed(8);
                                        console.log(`${this.locationType} lng updated:`, oldValue, "->", this.lngElement.value);
                                        console.log(`${this.locationType} lng field:`, {
                                            name: this.lngElement.name,
                                            id: this.lngElement.id,
                                            tagName: this.lngElement.tagName,
                                            type: this.lngElement.type
                                        });
                                        this.lngElement.dispatchEvent(new Event("input", { bubbles: true }));
                                        this.lngElement.dispatchEvent(new Event("change", { bubbles: true }));
                                    } else {
                                        console.error(`${this.locationType} lngElement is NULL! Cannot update coordinate.`);
                                    }
                                    
                                    this.updateDisplay(lat, lng);
                                    this.addMarker(lat, lng);
                                    this.reverseGeocode(lat, lng);
                                    this.map.setView([lat, lng], 15);
                                },
                                
                                updateDisplay(lat, lng) {
                                    if (this.$refs.latDisplay) this.$refs.latDisplay.textContent = lat.toFixed(6);
                                    if (this.$refs.lngDisplay) this.$refs.lngDisplay.textContent = lng.toFixed(6);
                                },
                                
                                addMarker(lat, lng) {
                                    if (this.marker) this.map.removeLayer(this.marker);
                                    
                                    const color = this.locationType === "origin" ? "#22c55e" : "#ef4444";
                                    const icon = L.divIcon({
                                        html: `<div style="width:24px;height:24px;background:${color};border:3px solid white;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>`,
                                        iconSize: [24, 24],
                                        iconAnchor: [12, 12]
                                    });
                                    
                                    this.marker = L.marker([lat, lng], { icon, draggable: true })
                                        .addTo(this.map)
                                        .on("dragend", () => {
                                            const pos = this.marker.getLatLng();
                                            this.selectLocation(pos.lat, pos.lng);
                                        });
                                },
                                
                                reverseGeocode(lat, lng) {
                                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                                        .then(r => r.json())
                                        .then(d => {
                                            if (d.display_name && this.$refs.nameInput) {
                                                this.$refs.nameInput.value = d.display_name.split(",").slice(0, 3).join(", ");
                                                this.$refs.nameInput.dispatchEvent(new Event("input", { bubbles: true }));
                                                this.$refs.nameInput.dispatchEvent(new Event("change", { bubbles: true }));
                                            }
                                        })
                                        .catch(err => console.error("Geocode error:", err));
                                }
                            }));
                        });
                    </script>
                '),
            );
    }
}
