<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
        }
    }

    function showPosition(position) {
        let lat = position.coords.latitude;
        let long = position.coords.longitude;
        
        // --- PERBAIKAN: Masukkan angka ke input hidden agar bisa dikirim ke database ---
        if(document.getElementById('lat')) document.getElementById('lat').value = lat;
        if(document.getElementById('long')) document.getElementById('long').value = long;
        
        // Kirim ke Controller untuk cek radius [cite: 28, 52]
        fetch(`/check-location?lat=${lat}&long=${long}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('status-lokasi').innerText = data.message;
                
                // Jika lokasi valid, aktifkan tombol [cite: 29, 30]
                if(data.status === 'valid') {
                    const btn = document.getElementById('btn-absen');
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btn.classList.add('hover:scale-105', 'transition-transform');
                }
            });
    }
    
    window.onload = getLocation;
</script>