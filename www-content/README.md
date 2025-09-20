# locGM (PHP puro, sem banco)
Roda no navegador e usa **sessão** para guardar tudo (ideal para apresentação).

## Como rodar (VS Code)
1) Abra um terminal na pasta do projeto e rode:
```
php -S localhost:8000
```
2) Acesse http://localhost:8000 no navegador.

## Páginas
- **index.php**: login (visitante/empresa por e-mail)
- **home.php**: locais por categoria com estrelas
- **map.php**: mapa de Guajará-Mirim (Leaflet + OSM)
- **feed.php**: feed de promoções (empresa publica)
- **chat.php**: chat entre visitantes e empresas
- **profile.php**: campos de perfil
- **empresa.php**: CRUD de locais (nome, tipo, lat/lng, nota, endereço)
- **emergencias.php**: telefones úteis
