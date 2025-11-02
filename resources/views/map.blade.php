<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EcoLink - Mapa</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
</head>
<body>
    <h1>Mapa de Pontos de Coleta</h1>
    <div id="map" style="width:100%; height:500px;"></div>
    <button id="load-nearby" style="margin-top:10px;">🔄 Carregar locais próximos</button>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    const map = L.map('map').setView([-25.383, -51.467], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    // Carregar pontos do banco
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

    let userLat, userLng;

    // Pegar localização
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            userLat = pos.coords.latitude;
            userLng = pos.coords.longitude;
            map.setView([userLat, userLng], 13);
            L.marker([userLat, userLng]).addTo(map).bindPopup("Você está aqui!").openPopup();
        }, err => {
            console.error(err);
            alert("Erro ao obter localização.");
        });
    }

    // Quando clicar no botão
    document.getElementById('load-nearby').addEventListener('click', () => {
        if (!userLat || !userLng) {
            alert("Localização ainda não carregada!");
            return;
        }

        fetch(`/api/nearby/${userLat}/${userLng}/1500`)
            .then(res => res.json())
            .then(data => {
                if (!data.results || data.results.length === 0) {
                    alert("Nenhum local encontrado perto de você.");
                    return;
                }

                data.results.forEach(place => {
                    const loc = place.geometry.location;
                    L.marker([loc.lat, loc.lng])
                        .addTo(map)
                        .bindPopup(`<b>${place.name}</b><br>${place.vicinity || 'Sem endereço'}`);
                });
            })
            .catch(err => console.error("Erro ao buscar locais próximos:", err));
    });
    </script>
</body>
</html>
