<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>EcoLink - Mapa de Pontos de Descarte</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        #map {
            width: 100%;
            height: 500px;
            margin-top: 15px;
            border-radius: 10px;
        }
        button {
            margin: 10px;
            padding: 8px 16px;
            background-color: #3c8c3c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2e6d2e;
        }
    </style>
</head>
<body>
    <h1>🌱 EcoLink - Pontos de Descarte</h1>
    <p>Veja os pontos de coleta próximos de você</p>

    <button id="buscar-locais">🔍 Buscar Locais Próximos</button>

    <div id="map"></div>

    <script>
        const map = L.map('map').setView([-25.3902, -51.4627], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        fetch('/api/points')
        .then(res => res.json())
        .then(points => {
            points.forEach(p => {
                L.marker([p.latitude, p.longitude])
                    .addTo(map)
                    .bindPopup(`<b>${p.name}</b><br>${p.address}`);
            });
        })
        .catch(err => console.error("Erro ao carregar banco:", err));

        let userMarker = null;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                userMarker = L.marker([lat, lng]).addTo(map)
                    .bindPopup("📍 Você está aqui")
                    .openPopup();

                map.setView([lat, lng], 14);
            }, () => {
                alert("Não foi possível obter sua localização 😕");
            });
        }

        fetch('/api/points')
            .then(res => res.json())
            .then(data => {
                data.forEach(p => {
                    L.marker([p.latitude, p.longitude])
                        .addTo(map)
                        .bindPopup(`<b>${p.name}</b><br>${p.address}`);
                });
            })
            .catch(err => console.error('Erro ao carregar pontos do banco:', err));

        document.getElementById('buscar-locais').addEventListener('click', async () => {
            if (!userMarker) {
                alert("Localização ainda não carregada. Aguarde um momento.");
                return;
            }

            const pos = userMarker.getLatLng();
            const apiKey = "{{ env('GOOGLE_PLACES_API_KEY') }}";
            const radius = 2000; 
            const type = "recycling_center";

            const url = `https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=${pos.lat},${pos.lng}&radius=${radius}&type=${type}&key=${apiKey}`;

            try {
                const response = await fetch(`/api/proxy?url=${encodeURIComponent(url)}`);
                const data = await response.json();

                if (!data.results || data.results.length === 0) {
                    alert("Nenhum local de descarte encontrado por perto 😕");
                    return;
                }

                data.results.forEach(place => {
                    if (place.geometry) {
                        const { lat, lng } = place.geometry.location;
                        const name = place.name || "Local de descarte";
                        const address = place.vicinity || "Endereço não disponível";

                        L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(`<b>${name}</b><br>${address}`);
                    }
                });

            } catch (error) {
                console.error('Erro ao buscar locais:', error);
                alert('Erro ao buscar locais. Verifique a chave da API.');
            }
        });
    </script>
</body>
</html>
