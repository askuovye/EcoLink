<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-black tracking-tight">
                🌱 EcoLink - Mapa de Pontos de Descarte
            </h2>

            <button id="buscar-locais"
                class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-black font-semibold rounded-lg shadow transition-transform transform hover:scale-105 duration-200">
                🔍 Buscar Locais Próximos
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-white min-h-screen text-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 p-6">
                <p id="status-message" class="text-center text-sm text-gray-600 mb-4">Veja os pontos de coleta próximos de você</p>

                <div id="alert" class="hidden mb-4"></div>

                <div id="map" class="w-full rounded-md border border-gray-200" style="height:640px;"></div>
            </div>
        </div>
    </div>

    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const alertEl = document.getElementById('alert');
            const statusEl = document.getElementById('status-message');

            function showAlert(message, type = 'error') {
                alertEl.className = 'mb-4 p-3 rounded-md text-center';
                if (type === 'error') {
                    alertEl.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
                } else if (type === 'success') {
                    alertEl.classList.add('bg-green-100', 'text-green-800', 'border', 'border-green-200');
                } else {
                    alertEl.classList.add('bg-blue-50', 'text-blue-800', 'border', 'border-blue-100');
                }
                alertEl.innerText = message;
                alertEl.classList.remove('hidden');
            }

            if (typeof L === 'undefined') {
                showAlert("Leaflet não carregou — verifique se a CDN está sendo bloqueada por uma extensão (AdBlock / Brave Shields). Abra em modo anônimo ou desative as extensões temporariamente.", 'error');
                console.error('Leaflet não definido (L is undefined).');
                return;
            }

            try {
                const map = L.map('map', { zoomControl: true }).setView([-25.3902, -51.4627], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const userIcon = L.icon({
                    iconUrl: 'https://cdn-icons-png.flaticon.com/512/64/64113.png',
                    iconSize: [34, 34],
                    iconAnchor: [17, 34]
                });

                statusEl.innerHTML = 'Carregando pontos...';
                let points;
                try {
                    const res = await fetch('/api/points');
                    if (!res.ok) throw new Error('Falha ao obter pontos do servidor');
                    points = await res.json();
                } catch (err) {
                    console.error(err);
                    showAlert('Erro ao carregar pontos do servidor. Veja o console para detalhes.', 'error');
                    statusEl.innerText = 'Erro ao carregar pontos.';
                    points = [];
                }

                if (points && points.length) {
                    const iconsByType = {
                        vidro: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/5003/5003587.png", // ícone vidro
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                        papel: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/60/60492.png", // ícone papel
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                        plastico: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/7951/7951976.png",
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                        metal: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/605/605897.png",
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                        quimico: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/9772/9772675.png",
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                        eletronico: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/5703/5703931.png",
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                        default: L.icon({
                            iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png", // genérico
                            iconSize: [32, 32],
                            iconAnchor: [16, 32],
                            popupAnchor: [0, -30],
                        }),
                    };

                    points.forEach((p, idx) => {
                        const lat = parseFloat(p.latitude);
                        const lng = parseFloat(p.longitude);
                        if (!isFinite(lat) || !isFinite(lng)) return;

                        const type = (p.type || "").toLowerCase();
                        const icon = iconsByType[type] || iconsByType.default;

                        const verifiedBadge = p.verified
                            ? '<span class="text-green-600 font-semibold">✔️ Aprovado</span>'
                            : '<span class="text-gray-500">⏳ Pendente</span>';

                        const title = p.verified
                            ? `⭐ <strong>${escapeHtml(p.name)}</strong>`
                            : `<strong>${escapeHtml(p.name)}</strong>`;

                        const popupHtml = `
                            <div style="text-align:center;">
                                ${title}<br>
                                <small class="text-gray-600">${escapeHtml(p.address)}</small><br>
                                <small>🕒 ${escapeHtml(p.operating_hours || "Horário não informado")}</small><br>
                                <small>Tipo: <strong>${escapeHtml(p.type || "Descarte geral")}</strong></small><br>
                                ${verifiedBadge}
                            </div>
                        `;

                        const marker = L.marker([lat, lng], { icon }).addTo(map);
                        marker.bindPopup(popupHtml);

                        if (marker._icon) {
                            marker._icon.style.opacity = 0;
                            marker._icon.style.transform = 'scale(0.85)';
                            setTimeout(() => {
                                marker._icon.style.transition = 'all 300ms ease';
                                marker._icon.style.opacity = 1;
                                marker._icon.style.transform = 'scale(1)';
                            }, idx * 80);
                        }
                    });

                    statusEl.innerText = `${points.length} pontos carregados.`;
                } else {
                    statusEl.innerText = 'Nenhum ponto encontrado no banco.';
                }

                let userMarker = null;
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition((position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map)
                            .bindPopup('📍 Você está aqui').openPopup();
                        map.setView([lat, lng], 13);
                    }, (err) => {
                        console.warn('Geolocation erro', err);
                        statusEl.innerText = statusEl.innerText + ' (Localização indisponível)';
                    }, {timeout: 8000});
                } else {
                    statusEl.innerText = statusEl.innerText + ' (Geolocation não suportado)';
                }

                document.getElementById('buscar-locais').addEventListener('click', async () => {
                    if (!userMarker) {
                        alert("Localização ainda não carregada. Aguarde alguns segundos e tente novamente.");
                        return;
                    }

                    const pos = userMarker.getLatLng();
                    const apiKey = "<?php echo e(env('GOOGLE_PLACES_API_KEY')); ?>";
                    const radius = 7000;

                    const keywords = [
                        'reciclagem', 'reciclar', 'ponto de coleta', 'ponto de reciclagem',
                        'coleta seletiva', 'centro de reciclagem', 'lixo eletrônico',
                        'pilhas', 'baterias', 'eletrônico', 'ewaste', 'recycling', 'recycle'
                    ];
                    const keywordParam = encodeURIComponent(keywords.join('|'));

                    const placesUrl = `https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=${pos.lat},${pos.lng}&radius=${radius}&type=establishment&keyword=${keywordParam}&language=pt-BR&key=${apiKey}`;

                    try {
                        const proxyRes = await fetch(`/api/proxy?url=${encodeURIComponent(placesUrl)}`);
                        if (!proxyRes.ok) throw new Error('Erro no proxy');
                        const placesData = await proxyRes.json();

                        if (!placesData.results || !placesData.results.length) {
                            alert('Nenhum local de descarte encontrado por perto 😕');
                            return;
                        }

                        const filtered = placesData.results.filter(place => {
                            if (place.types && place.types.includes('recycling_center')) return true;

                            const text = ((place.name||'') + ' ' + (place.vicinity||'') + ' ' + (place.types||[]).join(' ')).toLowerCase();

                            for (const k of keywords) {
                                if (text.includes(k.toLowerCase())) return true;
                            }

                            return false;
                        });
                        
                        if (filtered.length === 0) {
                            const fallback = (placesData.results || []).filter(p => {
                                const t = ((p.name||'') + ' ' + (p.vicinity||'')).toLowerCase();
                                return t.includes('recycl') || t.includes('recicl');
                            });
                            if (fallback.length) {
                                showAlert(`${fallback.length} locais possivelmente relevantes encontrados (fallback).`, 'info');
                            }
                            resultsToShow = fallback.length ? fallback : filtered;
                        } else {
                            resultsToShow = filtered;
                        }

                        if (!resultsToShow || resultsToShow.length === 0) {
                            alert('Nenhum local de descarte específico encontrado — tente aumentar o raio ou pesquisar manualmente.');
                            return;
                        }

                        const maxShow = 12;
                        resultsToShow.slice(0, maxShow).forEach((place, i) => {
                            if (!place.geometry) return;

                            const lat =
                                typeof place.geometry.location.lat === "function"
                                    ? place.geometry.location.lat()
                                    : place.geometry.location.lat;
                            const lng =
                                typeof place.geometry.location.lng === "function"
                                    ? place.geometry.location.lng()
                                    : place.geometry.location.lng;

                            if (!lat || !lng) return;

                            const name = place.name || "Local de descarte";
                            const address = place.vicinity || place.formatted_address || "Endereço não disponível";

                            const m = L.marker([lat, lng], {
                                icon: L.icon({
                                    iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
                                    iconSize: [32, 32],
                                    iconAnchor: [16, 32]
                                })
                            }).addTo(map).bindPopup(
                                `<strong>${escapeHtml(name)}</strong><br><small>${escapeHtml(address)}</small>`
                            );

                            if (m._icon) {
                                m._icon.style.opacity = 0;
                                m._icon.style.transform = "scale(0.8)";
                                setTimeout(() => {
                                    m._icon.style.transition = "all 0.4s ease";
                                    m._icon.style.opacity = 1;
                                    m._icon.style.transform = "scale(1)";
                                }, 80 * i);
                            }
                        });


                } catch (error) {
                    console.error('Erro ao buscar locais:', error);
                    showAlert('Erro ao buscar locais. Verifique a chave da API e o proxy.', 'error');
                }
            });

                function escapeHtml(text) {
                    if (!text) return '';
                    return text
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }

                setTimeout(() => map.invalidateSize(), 300);
            } catch (err) {
                console.error('Erro na inicialização do mapa:', err);
                showAlert('Erro ao inicializar o mapa. Veja o console para detalhes.', 'error');
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\yikuu\Documents\EcoLinkProject(fudido)\ecolink\resources\views/map.blade.php ENDPATH**/ ?>