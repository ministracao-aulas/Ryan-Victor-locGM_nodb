<?php include __DIR__ . '/partials/header.php'; ?>
<h2>Mapa de Guajará-Mirim</h2>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<div id="map" style="height:70vh;border-radius:16px;"></div>
<script>
const map = L.map('map').setView([-10.783, -65.338], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
const places = <?php echo json_encode(places()); ?>;
const focus = <?php echo isset($_GET['focus']) ? intval($_GET['focus']) : 'null'; ?>;
let focusMarker = null;
places.forEach(p => {
  const m = L.marker([p.lat, p.lng]).addTo(map);
  m.bindPopup(`<b>${p.name}</b><br>${p.type.toUpperCase()}<br>Nota: ${p.rating}`);
  if (focus && p.id===focus) focusMarker = m;
});
if (focusMarker){ map.setView(focusMarker.getLatLng(), 17); focusMarker.openPopup(); }
</script>
<?php include __DIR__ . '/partials/footer.php'; ?>
