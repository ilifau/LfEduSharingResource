<?php


use EduSharingApiClient\CurlHandler;
use EduSharingApiClient\CurlResult;
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

        $proxy = ilProxySettings::_getInstance();
        if ($proxy->isActive()) {
            $curlOptions[] = [
                CURLOPT_HTTPPROXYTUNNEL => 1,
                CURLOPT_PROXY => $proxy->getHost(),
                CURLOPT_PROXYPORT => $proxy->getPort()
            ];
        }
        curl_setopt_array($curl, $curlOptions);
        $content = curl_exec($curl);
        $error = curl_errno($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        $curlResult = new CurlResult(!is_string($content) ? '' : $content, $error, $info);
        return $curlResult;
    }

//    /**
//     * Function handleCurlRequest
//     *
//     * Method name does not comply with moodle code style
//     * in order to ensure compatibility with edu-sharing api library
//     *
//     * @param string $url
//     * @param array $curlOptions
//     * @return CurlResult
//     */
//    public function handleCurlRequest(string $url, array $curlOptions): CurlResult {
//        global $CFG;
//        $curl         = new ilCurlConnection($url);
//
//        $params       = [];
//        $options      = [];
//        $allconstants = null;
//        foreach ($curlOptions as $key => $value) {
//            if (is_int($key)) {
//                if ($allconstants === null) {
//                    $allconstants = get_defined_constants(true)['curl'];
//                }
//                $key = array_search($key, $allconstants, true);
//                if ($key === false) {
//                    continue;
//                }
//            }
//            if ($key === 'CURLOPT_HTTPHEADER') {
////                $curl->header = $value;
//                $curl->setOpt(CURLOPT_HEADER, $value);
//            } elseif ($key === 'CURLOPT_POSTFIELDS') {
//                $params = $value;
//            } elseif ($key === 'CURLOPT_POST' && $value === 1) {
//                $this->method = static::METHOD_POST;
//            } elseif ($key === 'CURLOPT_CUSTOMREQUEST' && $value === 'DELETE') {
//                $this->method = static::METHOD_DELETE;
//            } else {
//                $options[$key] = $value;
//            }
//        }
//        if ($this->method === static::METHOD_POST) {
//            //$result = $curl->post($url, $params, $options);
//            $curl->setOpt(CURLOPT_POST, 1); //added
//            $curl->setOpt(CURLOPT_POSTFIELDS,http_build_query($params));
//        } elseif ($this->method === static::METHOD_PUT) {
////            $result = $curl->put($url, $params, $options);
//            $curl->setOpt(CURLOPT_PUT, true);
//            $curl->setOpt(CURLOPT_POSTFIELDS,http_build_query($params));
//        } elseif ($this->method === static::METHOD_DELETE) {
////            $result = $curl->delete($url, $params, $options);
//            $curl->setOpt(CURLOPT_CUSTOMREQUEST, 'DELETE');
//        } else {
////            $result = $curl->get($url, $params, $options);
//            $curl->setOpt(CURLOPT_HTTPGET, 1);
//            if (sizeof($params)) {
//                $url = $url .
//                    (strpos($url, "?") === false ? "?" : "") .
//                    http_build_query($params);
//            }
//            $curl = new ilCurlConnection($url);
//        }
//
//        $proxy = ilProxySettings::_getInstance();
//        $curl->init($proxy->isActive());
//
//        $result = $curl->exec();
//
//
//        if ($curl->errno !== 0 && is_array($curl->info)) {
//            $curl->info['message'] = $curl->error;
//        }
//        $this->method = self::METHOD_GET;
//        return new CurlResult($result, $curl->errno, is_array($curl->info) ? $curl->info : ['message' => $curl->error]);
//    }


}