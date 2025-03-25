# Geolocation Service

Este projeto contém serviços para encontrar a concessionária mais próxima usando coordenadas geográficas.

## Instalação

1. Clone o repositório:
```sh
    git clone https://github.com/seu-usuario/geo-location-service.git
    cd geo-location-service
```

2. Instale as dependências do Laravel (se aplicável):
```sh
    composer install
```

3. Adicione a chave da API do Google Maps no .env:
```ini
    GOOGLE_MAPS_API_KEY=sua_chave_aqui
```

4. Configure a chave no arquivo config/services.php:
```php
    return [
        'google_maps' => [
            'api_key' => env('GOOGLE_MAPS_API_KEY'),
        ],
    ];
```

## Uso

Para usar o serviço de geolocalização:
```php
$geocodingService = new GeocodingService();
$nearest = $geocodingService->findNearestAddress($latitude, $longitude);
```

## Licença

Este projeto está sob a licença MIT.
