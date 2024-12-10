<?php declare(strict_types=1);

namespace EduSharingApiClient;

use ilProxySettings;  // Klasse für Proxy-Einstellungen nutzen

class DefaultCurlHandler extends CurlHandler
{
    /**
     * Function handleCurlRequest
     *
     * @param string $url
     * @param array $curlOptions
     * @return CurlResult
     */
    public function handleCurlRequest(string $url, array $curlOptions): CurlResult {
        $curl = curl_init($url);

        // Proxy-Einstellungen laden
        $proxy = ilProxySettings::_getInstance();
        if ($proxy->isActive()) {
            // Proxy-Tunnel aktivieren
            $curlOptions[CURLOPT_HTTPPROXYTUNNEL] = true;

            if (!empty($proxy->getHost())) {
                $curlOptions[CURLOPT_PROXY] = $proxy->getHost();
            }

            if (!empty($proxy->getPort())) {
                $curlOptions[CURLOPT_PROXYPORT] = $proxy->getPort();
            }
        }

        curl_setopt_array($curl, $curlOptions);

        $content = curl_exec($curl);
        $error   = curl_errno($curl);
        $info    = curl_getinfo($curl);
        curl_close($curl);

        return new CurlResult(!is_string($content) ? '' : $content, $error, $info);
    }
}


//fau