// Leaflet Map Variables
let map = null;
let currentMarker = null;
let selectedLat = null;
let selectedLng = null;
let currentLocationType = null; // 'start', 'end', or 'accommodation'

document.addEventListener('DOMContentLoaded', function() {
    console.log('Itinerary Map Script Loaded');
    
    // Initialize Leaflet Map
    initializeMap();

    // Open Map Modal Buttons
    const startMapBtn = document.getElementById('openStartMapBtn');
    const endMapBtn = document.getElementById('openEndMapBtn');
    const accommodationMapBtn = document.getElementById('openAccommodationMapBtn');
    
    console.log('Start Map Button:', startMapBtn);
    console.log('End Map Button:', endMapBtn);
    console.log('Accommodation Map Button:', accommodationMapBtn);

    startMapBtn?.addEventListener('click', function() {
        console.log('Opening Start Map Modal');
        openMapModal('start', 'Pilih Titik Awal');
    });

    endMapBtn?.addEventListener('click', function() {
        console.log('Opening End Map Modal');
        openMapModal('end', 'Pilih Titik Akhir');
    });

    accommodationMapBtn?.addEventListener('click', function() {
        console.log('Opening Accommodation Map Modal');
        openMapModal('accommodation', 'Pilih Lokasi Penginapan');
    });

    // Close Modal Buttons
    document.getElementById('closeMapModal')?.addEventListener('click', closeMapModal);
    document.getElementById('cancelMapSelection')?.addEventListener('click', closeMapModal);

    // Confirm Selection
    document.getElementById('confirmMapSelection')?.addEventListener('click', confirmLocationSelection);

    // GPS Detection in Modal
    document.getElementById('detectGPSInModal')?.addEventListener('click', detectGPSInModal);

    // Close modal when clicking overlay
    document.getElementById('mapModal')?.addEventListener('click', function(e) {
        if (e.target.id === 'mapModal') {
            closeMapModal();
        }
    });

    // Handle start destination select change
    document.getElementById('startDestinationId')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('startLocationName').value = selectedOption.dataset.name;
            document.getElementById('startLatitude').value = selectedOption.dataset.lat;
            document.getElementById('startLongitude').value = selectedOption.dataset.lng;
        }
    });

    // Handle end destination select change
    document.getElementById('endDestinationId')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('endLocationName').value = selectedOption.dataset.name;
            document.getElementById('endLatitude').value = selectedOption.dataset.lat;
            document.getElementById('endLongitude').value = selectedOption.dataset.lng;
        }
    });

    // Handle duration days change - show/hide accommodation section
    document.getElementById('durationDays')?.addEventListener('change', function() {
        toggleAccommodationSection(parseInt(this.value));
    });

    // Check on load
    toggleAccommodationSection(parseInt(document.getElementById('durationDays').value));
});

// Initialize Leaflet Map
function initializeMap() {
    // Default center: Surabaya
    map = L.map('map').setView([-7.2575, 112.7521], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Click event on map
    map.on('click', function(e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });
}

// Open Map Modal
function openMapModal(locationType, title) {
    console.log('openMapModal called with:', locationType, title);
    
    currentLocationType = locationType;
    const modalElement = document.getElementById('mapModal');
    const modalTitle = document.getElementById('mapModalTitle');
    
    console.log('Modal Element:', modalElement);
    console.log('Modal Title Element:', modalTitle);
    
    if (modalTitle) {
        modalTitle.textContent = title;
    }
    
    if (modalElement) {
        modalElement.classList.add('active');
        console.log('Modal classes:', modalElement.className);
    }
    
    // Get existing coordinates based on location type
    let existingLat = null;
    let existingLng = null;
    
    if (locationType === 'start') {
        existingLat = document.getElementById('startLatitude').value;
        existingLng = document.getElementById('startLongitude').value;
    } else if (locationType === 'end') {
        existingLat = document.getElementById('endLatitude').value;
        existingLng = document.getElementById('endLongitude').value;
    } else if (locationType === 'accommodation') {
        existingLat = document.getElementById('accommodationLatitude').value;
        existingLng = document.getElementById('accommodationLongitude').value;
    }
    
    // Remove old marker
    if (currentMarker) {
        map.removeLayer(currentMarker);
        currentMarker = null;
    }
    
    // If there are existing coordinates, show them
    if (existingLat && existingLng && !isNaN(existingLat) && !isNaN(existingLng)) {
        selectedLat = parseFloat(existingLat).toFixed(6);
        selectedLng = parseFloat(existingLng).toFixed(6);
        
        // Set marker at existing position
        currentMarker = L.marker([selectedLat, selectedLng]).addTo(map);
        
        // Show selected location info
        document.getElementById('selectedLat').textContent = selectedLat;
        document.getElementById('selectedLng').textContent = selectedLng;
        document.getElementById('selectedLocationInfo').style.display = 'block';
        
        // Center map on existing marker
        map.setView([selectedLat, selectedLng], 15);
    } else {
        // Reset selection if no existing coordinates
        selectedLat = null;
        selectedLng = null;
        document.getElementById('selectedLocationInfo').style.display = 'none';
        
        // Refresh map size and try to get current location
        setTimeout(() => {
            map.invalidateSize();
            
            // Try to get current location for initial view
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        map.setView([position.coords.latitude, position.coords.longitude], 13);
                    },
                    function() {
                        // If failed, keep default view (Surabaya)
                        map.setView([-7.2575, 112.7521], 13);
                    }
                );
            }
        }, 100);
    }

    // Refresh map size
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

// Close Map Modal
function closeMapModal() {
    document.getElementById('mapModal').classList.remove('active');
}

// Set Marker on Map
function setMarker(lat, lng) {
    // Remove previous marker
    if (currentMarker) {
        map.removeLayer(currentMarker);
    }

    // Add new marker
    currentMarker = L.marker([lat, lng]).addTo(map);
    
    // Store coordinates
    selectedLat = lat.toFixed(6);
    selectedLng = lng.toFixed(6);

    // Show selected location info
    document.getElementById('selectedLat').textContent = selectedLat;
    document.getElementById('selectedLng').textContent = selectedLng;
    document.getElementById('selectedLocationInfo').style.display = 'block';

    // Pan to marker
    map.panTo([lat, lng]);
}

// Detect GPS in Modal
function detectGPSInModal() {
    if (!navigator.geolocation) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Geolocation tidak didukung oleh browser Anda.'
        });
        return;
    }

    const button = document.getElementById('detectGPSInModal');
    const originalHTML = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mendeteksi...
    `;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Set marker and center map
            setMarker(lat, lng);
            map.setView([lat, lng], 15);

            button.disabled = false;
            button.innerHTML = originalHTML;

            // Show success notification
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Lokasi GPS Anda berhasil terdeteksi',
                timer: 2000,
                showConfirmButton: false
            });
        },
        function(error) {
            button.disabled = false;
            button.innerHTML = originalHTML;

            let errorMessage = 'Gagal mendapatkan lokasi. ';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Izin akses lokasi ditolak.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Informasi lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Waktu permintaan lokasi habis.';
                    break;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: errorMessage
            });
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

// Confirm Location Selection
function confirmLocationSelection() {
    if (!selectedLat || !selectedLng) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: 'Silakan pilih lokasi di peta terlebih dahulu'
        });
        return;
    }

    let latInput, lngInput, nameInput;

    if (currentLocationType === 'start') {
        latInput = document.getElementById('startLatitude');
        lngInput = document.getElementById('startLongitude');
        nameInput = document.getElementById('startLocationName');
    } else if (currentLocationType === 'end') {
        latInput = document.getElementById('endLatitude');
        lngInput = document.getElementById('endLongitude');
        nameInput = document.getElementById('endLocationName');
    } else if (currentLocationType === 'accommodation') {
        latInput = document.getElementById('accommodationLatitude');
        lngInput = document.getElementById('accommodationLongitude');
        nameInput = document.getElementById('accommodationName');
    }

    // Set values
    latInput.value = selectedLat;
    lngInput.value = selectedLng;

    if (!nameInput.value) {
        nameInput.value = `Lokasi (${selectedLat}, ${selectedLng})`;
    }

    // Close modal
    closeMapModal();

    // Show success
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Lokasi berhasil dipilih',
        timer: 1500,
        showConfirmButton: false
    });
}

// Toggle accommodation section based on duration
function toggleAccommodationSection(days) {
    const accommodationSection = document.getElementById('accommodationSection');
    const accommodationName = document.getElementById('accommodationName');
    const accommodationLat = document.getElementById('accommodationLatitude');
    const accommodationLng = document.getElementById('accommodationLongitude');

    if (days > 1) {
        accommodationSection.style.display = 'block';
        // Make accommodation fields required
        accommodationName?.setAttribute('required', 'required');
        accommodationLat?.setAttribute('required', 'required');
        accommodationLng?.setAttribute('required', 'required');
    } else {
        accommodationSection.style.display = 'none';
        // Remove required attribute
        accommodationName?.removeAttribute('required');
        accommodationLat?.removeAttribute('required');
        accommodationLng?.removeAttribute('required');
    }
}

// Toggle start location type
function toggleStartLocationType() {
    const locationType = document.querySelector('input[name="start_location_type"]:checked').value;
    const destinationSelect = document.getElementById('startDestinationSelect');
    const customLocation = document.getElementById('startCustomLocation');
    
    if (locationType === 'destination') {
        destinationSelect.style.display = 'block';
        customLocation.style.display = 'none';
        
        // Make custom fields not required
        document.getElementById('startLocationName').removeAttribute('required');
        document.getElementById('startLatitude').removeAttribute('required');
        document.getElementById('startLongitude').removeAttribute('required');
        
        // Make destination select required
        document.getElementById('startDestinationId').setAttribute('required', 'required');
    } else {
        destinationSelect.style.display = 'none';
        customLocation.style.display = 'block';
        
        // Make custom fields required
        document.getElementById('startLocationName').setAttribute('required', 'required');
        document.getElementById('startLatitude').setAttribute('required', 'required');
        document.getElementById('startLongitude').setAttribute('required', 'required');
        
        // Make destination select not required
        document.getElementById('startDestinationId').removeAttribute('required');
    }
}

// Toggle end location type
function toggleEndLocationType() {
    const locationType = document.querySelector('input[name="end_location_type"]:checked').value;
    const destinationSelect = document.getElementById('endDestinationSelect');
    const customLocation = document.getElementById('endCustomLocation');
    
    if (locationType === 'destination') {
        destinationSelect.style.display = 'block';
        customLocation.style.display = 'none';
        
        // Make custom fields not required
        document.getElementById('endLocationName').removeAttribute('required');
        document.getElementById('endLatitude').removeAttribute('required');
        document.getElementById('endLongitude').removeAttribute('required');
        
        // Make destination select required
        document.getElementById('endDestinationId').setAttribute('required', 'required');
    } else {
        destinationSelect.style.display = 'none';
        customLocation.style.display = 'block';
        
        // Make custom fields required
        document.getElementById('endLocationName').setAttribute('required', 'required');
        document.getElementById('endLatitude').setAttribute('required', 'required');
        document.getElementById('endLongitude').setAttribute('required', 'required');
        
        // Make destination select not required
        document.getElementById('endDestinationId').removeAttribute('required');
    }
}
