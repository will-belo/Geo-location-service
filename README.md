# Geolocation Service

Este projeto contém serviços para encontrar a loja mais próxima usando coordenadas geográficas.

## 📦 Instalação

via Composer:
```bash
    composer require will-belo/geo-location-service
```

via git:
1. Clone o repositório:
```sh
    git clone https://github.com/seu-usuario/geo-location-service.git
    cd geo-location-service
```

2. Instale as dependências do Laravel (se aplicável):
```sh
    composer install
```

## ⚙️ Configuração

1. Adicione as configurações no ```.env```
Adicione as seguintes variáveis ao seu arquivo ```.env```
```env
    GEOLOCATION_RADIUS=6371
    GEOLOCATION_CACHE_DURATION=60
    GEOLOCATION_GOOGLE_API_KEY=<YOUR_GoogleAPIKey_HERE>
    GEOLOCATION_ADDRESS_MODEL=App\Models\Address # Altere para o seu modelo de endereço, se necessário
```

2. Configure sua model de endereço
O pacote pode ser usado com qualquer model, contanto que ela tenha as seguintes características:
✅ Colunas ```latitude``` e ```longitude``` (em sua tabela de endereços)
✅ Um relacionamento ```relatedEntity()``` que aponta para a entidade que deseja associar ao endereço (ex. ```Store```, ```Shop```, etc.).
```php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class MyAddress extends Model
    {
        protected $table = 'addresses'; // Nome da tabela

        // Relacionamento com a entidade associada, como uma loja
        public function relatedEntity()
        {
            return $this->hasOne(\App\Models\Store::class); // Modelo que você deseja associar
        }
    }
```
3. Configuração de Cache
Certifique-se de que a configuração de cache esteja corretamente definida no arquivo ```.env``` e em ```config/cache.php```.

## 🚀 Uso

1. Encontrando a entidade mais próxima
Agora você pode usar o serviço de geolocalização para encontrar a entidade (por exemplo, uma loja) mais próxima de uma latitude e longitude específicas.
Exemplo de uso:
```php
    use GeoLocationService\Services\GeocodingService;

    $geoService = new GeocodingService();
    $latitude = -23.550520; // Exemplo de latitude
    $longitude = -46.633308; // Exemplo de longitude

    $nearestEntity = $geoService->findNearestAddress($latitude, $longitude);

    if ($nearestEntity) {
        echo "Entidade mais próxima: " . $nearestEntity->relatedEntity->name;
    } else {
        echo "Nenhuma entidade encontrada próxima.";
    }
```

2. Utilizando a API de Geocodificação (Google)
Se você configurou a chave da API do Google Maps, você também pode usar o serviço para obter as coordenadas de um endereço:
```php
    use GeoLocationService\Services\GeoCodingAPI;

    $geoAPI = new GeoCodingAPI();
    $address = "São Paulo, Brasil";

    $coordinates = $geoAPI->getCoordinates($address);

    if ($coordinates) {
        echo "Latitude: " . $coordinates['lat'];
        echo "Longitude: " . $coordinates['lng'];
    } else {
        echo "Endereço não encontrado.";
}
```

## Licença

Este projeto está sob a licença MIT.


![MIT License](https://img.shields.io/badge/License-MIT-green.svg) ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)