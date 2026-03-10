<?php
// app/Traits/GoogleMapsTrait.php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait GoogleMapsTrait
{
    /**
     * Extraer latitud y longitud de un enlace de Google Maps
     * Soporta tanto URLs largas como cortas (goo.gl) y el formato /search/
     */
    public function extraerCoordenadasDeGoogleMaps($url)
    {
        if (empty($url)) {
            return [
                'lat' => null,
                'lng' => null,
                'url_procesada' => $url
            ];
        }

        Log::info('Procesando URL de Google Maps:', ['url' => $url]);

        $lat = null;
        $lng = null;
        $urlProcesada = $url;

        try {
            // CASO 1: URL larga de Google Maps con @ (formato: @lat,lng)
            if (strpos($url, '@') !== false) {
                preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches);
                if (count($matches) >= 3) {
                    $lat = floatval($matches[1]);
                    $lng = floatval($matches[2]);
                    Log::info('Coordenadas extraídas de URL larga:', ['lat' => $lat, 'lng' => $lng]);
                }
            }
            
            // CASO 2: URL con /search/ (nuevo formato que viste)
            elseif (strpos($url, '/search/') !== false) {
                // Buscar patrón: /search/-11.959946,+-77.060246
                preg_match('/\/search\/(-?\d+\.\d+),\+?(-?\d+\.\d+)/', $url, $matches);
                if (count($matches) >= 3) {
                    $lat = floatval($matches[1]);
                    $lng = floatval($matches[2]);
                    Log::info('Coordenadas extraídas de URL /search/:', ['lat' => $lat, 'lng' => $lng]);
                }
                
                // Si no funciona, intentar con el formato donde los números están en la URL
                if ($lat === null || $lng === null) {
                    preg_match('/search\/([+-]?\d+\.\d+),\+?([+-]?\d+\.\d+)/', $url, $matches);
                    if (count($matches) >= 3) {
                        $lat = floatval($matches[1]);
                        $lng = floatval($matches[2]);
                        Log::info('Coordenadas extraídas de URL search formato alternativo:', ['lat' => $lat, 'lng' => $lng]);
                    }
                }
            }
            
            // CASO 3: URL con parámetros ll (lat,lng)
            elseif (strpos($url, 'll=') !== false) {
                preg_match('/ll=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches);
                if (count($matches) >= 3) {
                    $lat = floatval($matches[1]);
                    $lng = floatval($matches[2]);
                    Log::info('Coordenadas extraídas de parámetro ll:', ['lat' => $lat, 'lng' => $lng]);
                }
            }
            
            // CASO 4: URL con query params q=lat,lng
            elseif (strpos($url, 'q=') !== false) {
                preg_match('/q=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches);
                if (count($matches) >= 3) {
                    $lat = floatval($matches[1]);
                    $lng = floatval($matches[2]);
                    Log::info('Coordenadas extraídas de parámetro q:', ['lat' => $lat, 'lng' => $lng]);
                }
            }
            
            // CASO 5: URL con place/ (otro formato común)
            elseif (strpos($url, '/place/') !== false) {
                preg_match('/place\/.*\/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches);
                if (count($matches) >= 3) {
                    $lat = floatval($matches[1]);
                    $lng = floatval($matches[2]);
                    Log::info('Coordenadas extraídas de URL place:', ['lat' => $lat, 'lng' => $lng]);
                }
            }
            
            // CASO 6: URL corta de goo.gl (necesita resolución)
            elseif (strpos($url, 'goo.gl') !== false || strpos($url, 'maps.app.goo.gl') !== false) {
                Log::info('URL corta detectada, intentando resolver...');
                $resolvedUrl = $this->resolverUrlCorta($url);
                if ($resolvedUrl && $resolvedUrl !== $url) {
                    Log::info('URL resuelta:', ['resolved' => $resolvedUrl]);
                    // Intentar extraer coordenadas de la URL resuelta
                    $coordenadas = $this->extraerCoordenadasDeGoogleMaps($resolvedUrl);
                    $lat = $coordenadas['lat'];
                    $lng = $coordenadas['lng'];
                    $urlProcesada = $resolvedUrl; // Guardar la URL resuelta
                    
                    // Si aún no hay coordenadas, intentar extraer directamente del formato /search/
                    if ($lat === null || $lng === null) {
                        // Intentar extraer manualmente del ejemplo que tienes
                        if (preg_match('/search\/([+-]?\d+\.\d+),\+?([+-]?\d+\.\d+)/', $resolvedUrl, $matches)) {
                            $lat = floatval($matches[1]);
                            $lng = floatval($matches[2]);
                            Log::info('Coordenadas extraídas manualmente de URL resuelta:', ['lat' => $lat, 'lng' => $lng]);
                        }
                    }
                }
            }

            // MÉTODO DE RESPALDO: Extraer números directamente si todo lo demás falla
            if ($lat === null || $lng === null) {
                // Buscar cualquier patrón de coordenadas en la URL
                preg_match_all('/(-?\d+\.\d+)/', $url, $matches);
                if (isset($matches[0]) && count($matches[0]) >= 2) {
                    // Intentar encontrar el par que tenga sentido (generalmente los primeros dos números grandes)
                    foreach ($matches[0] as $key => $value) {
                        if (abs(floatval($value)) > 180) { // La longitud no debería ser > 180
                            unset($matches[0][$key]);
                        }
                    }
                    $matches[0] = array_values($matches[0]);
                    
                    if (count($matches[0]) >= 2) {
                        $lat = floatval($matches[0][0]);
                        $lng = floatval($matches[0][1]);
                        Log::info('Coordenadas extraídas por método de respaldo:', ['lat' => $lat, 'lng' => $lng]);
                    }
                }
            }

            if ($lat === null || $lng === null) {
                Log::warning('No se pudieron extraer coordenadas de la URL:', ['url' => $url]);
            }

        } catch (\Exception $e) {
            Log::error('Error extrayendo coordenadas de Google Maps:', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }

        return [
            'lat' => $lat,
            'lng' => $lng,
            'url_procesada' => $urlProcesada
        ];
    }

    /**
     * Resolver URLs cortas de goo.gl para obtener la URL real
     */
    private function resolverUrlCorta($url)
    {
        try {
            // Inicializar cURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            $response = curl_exec($ch);
            $redirectUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);
            
            if ($redirectUrl && $redirectUrl !== $url) {
                return $redirectUrl;
            }
            
            // Si cURL no funciona, intentar con get_headers
            $headers = @get_headers($url, true);
            if ($headers && isset($headers['Location'])) {
                $location = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
                if ($location && strpos($location, 'http') === 0) {
                    return $location;
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error resolviendo URL corta:', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
        }
        
        return $url; // Devolver la original si no se pudo resolver
    }

    /**
     * Generar URL de Google Maps a partir de lat/lng
     */
    public function generarGoogleMapsUrl($lat, $lng)
    {
        if ($lat && $lng) {
            return "https://www.google.com/maps?q={$lat},{$lng}";
        }
        return null;
    }
    
    /**
     * Método de utilidad para debug: mostrar todos los matches de regex
     */
    private function debugRegex($url, $pattern)
    {
        preg_match($pattern, $url, $matches);
        Log::debug('Regex debug:', [
            'pattern' => $pattern,
            'matches' => $matches
        ]);
        return $matches;
    }
}