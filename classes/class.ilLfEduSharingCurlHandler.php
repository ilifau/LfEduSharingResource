<?php

use EduSharingApiClient\CurlHandler;
use EduSharingApiClient\CurlResult;

use ilProxySettings; // Proxy-Settings einbinden

class ilLfEduSharingCurlHandler extends CurlHandler {

    /**
     * Function handleCurlRequest
     *
     * @param string $url
     * @param array $curlOptions
     * @return CurlResult
     */
    public function handleCurlRequest(string $url, array $curlOptions): CurlResult
    {
        $curl = curl_init($url);

        // Proxy-Einstellungen laden
        $proxy = ilProxySettings::_getInstance();
        if ($proxy->isActive()) {
            // Hier nicht als Array-Eintrag, sondern einzeln zuweisen
            $curlOptions[CURLOPT_HTTPPROXYTUNNEL] = 1;
            if (!empty($proxy->getHost())) {
                $curlOptions[CURLOPT_PROXY] = $proxy->getHost();
            }
            if (!empty($proxy->getPort())) {
                $curlOptions[CURLOPT_PROXYPORT] = $proxy->getPort();
            }
        }

        curl_setopt_array($curl, $curlOptions);
        $content = curl_exec($curl);
        $error = curl_errno($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        $curlResult = new CurlResult(!is_string($content) ? '' : $content, $error, $info);
        return $curlResult;
    }

    // fau 
}
