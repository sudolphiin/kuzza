<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MyBidhaaApiService
{
    private Client $client;

    public function __construct(?Client $client = null)
    {
        $baseUri = rtrim(config('services.mybidhaa.base_url', 'https://mybidhaa.com/api/v1'), '/');
        $this->client = $client ?: new Client([
            'base_uri' => $baseUri . '/',
            'timeout'  => 10,
        ]);
    }

    /**
     * Search products using the public Products API.
     *
     * @param  array{keyword?:string,category_id?:int,per_page?:int,page?:int}  $params
     * @return array{data:array,meta:array}
     */
    public function searchProducts(array $params = []): array
    {
        $query = [
            'keyword'   => Arr::get($params, 'keyword'),
            'category_id' => Arr::get($params, 'category_id'),
            'per_page'  => Arr::get($params, 'per_page', 20),
            'page'      => Arr::get($params, 'page', 1),
        ];

        // Normalise: remove null/empty values
        $query = array_filter($query, static fn ($v) => $v !== null && $v !== '');

        $cacheKey = 'mybidhaa.products.' . md5(json_encode($query));
        $ttl = (int) config('services.mybidhaa.cache_ttl', 60);

        return Cache::remember($cacheKey, $ttl, function () use ($query) {
            try {
                $response = $this->client->get('ecommerce/products', [
                    'query'   => $query,
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]);

                $json = json_decode((string) $response->getBody(), true);

                return [
                    'data' => $this->mapProducts(Arr::get($json, 'data', [])),
                    'meta' => Arr::get($json, 'meta', []),
                ];
            } catch (\Throwable $e) {
                Log::error('MyBidhaa products API failed', [
                    'message' => $e->getMessage(),
                ]);

                return [
                    'data' => [],
                    'meta' => [],
                ];
            }
        });
    }

    /**
     * Fetch all product categories from the MyBidhaa API.
     *
     * This is used to power the Categories dropdown on the
     * "Assign items to student / parents" page so that admins
     * can quickly switch between groups such as "Special needs".
     *
     * @return array<int,array{id:int|string|null,name:string|null}>
     */
    public function categories(): array
    {
        $cacheKey = 'mybidhaa.categories.all';
        $ttl = (int) config('services.mybidhaa.cache_ttl', 300);

        return Cache::remember($cacheKey, $ttl, function () {
            try {
                $response = $this->client->get('ecommerce/product-categories', [
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ]);

                $json = json_decode((string) $response->getBody(), true);
                $categories = Arr::get($json, 'data', []);

                if (!is_array($categories)) {
                    return [];
                }

                return array_map(static function (array $category): array {
                    return [
                        'id' => Arr::get($category, 'id'),
                        'name' => Arr::get($category, 'name'),
                    ];
                }, $categories);
            } catch (\Throwable $e) {
                Log::error('MyBidhaa categories API failed', [
                    'message' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    /**
     * Map raw API product objects into the shape expected by the admin UI.
     *
     * @param  array<int,array>  $products
     * @return array<int,array>
     */
    private function mapProducts(array $products): array
    {
        return array_map(static function (array $p): array {
            // Prefer slug as stable external ID for links.
            $slug = Arr::get($p, 'slug');

            return [
                'id'          => $slug ?: Arr::get($p, 'id'),
                'name'        => Arr::get($p, 'name'),
                'category'    => Arr::get($p, 'store.name'), // fallback until dedicated categories are wired
                'price'       => (float) Arr::get($p, 'price', 0),
                'description' => Arr::get($p, 'description'),
                'image_url'   => Arr::get($p, 'image_url'),
                'currency'    => 'KES',
                'product_url' => Arr::get($p, 'url'),
                'raw'         => $p,
            ];
        }, $products);
    }
}

