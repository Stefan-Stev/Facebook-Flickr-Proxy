<?php
/**
 * generateSignature
 * @param        $httpVerb
 * @param        $requestUrl
 * @param        $parameters
 * @param string $tokenSecret
 *
 * @return string
 */
function generateSignature($httpVerb, $requestUrl, $parameters, $tokenSecret='89ea5e5795194ddd'){
    //$tokenSecret='';
    global $oauth_consumer_secret;
    ksort($parameters);
    $queryStr = http_build_query($parameters);
    $baseStr = $httpVerb . '&' . urlencode($requestUrl) . '&' . urlencode($queryStr);
    $key = $oauth_consumer_secret . '&' . $tokenSecret;
    $signature = hash_hmac('sha1', $baseStr, $key, true);
    $signature = urlencode(base64_encode($signature));
    return $signature;
}

/**
 * Get authorization header
 * @return array
 */
function getRequestHeaders(){
    global $oauth_consumer_key;
    global $oauth_callback;
    global $requestTokenURL;
    $queryParameters = [
        'oauth_nonce' => crc32(time()),
        'oauth_timestamp' => time(),
        'oauth_consumer_key' => $oauth_consumer_key,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_version' => '1.0',
        'oauth_callback' => $oauth_callback,
    ];
    // var_dump($queryParameters);exit;
    $queryParameters['oauth_signature'] = generateSignature('POST', $requestTokenURL, $queryParameters);
    $queryParameters['oauth_token'] = 'aeraae';

    //urldecode the oauth_callback url(cause http_build_query() will urlencode it)
    $queryStr = urldecode(http_build_query($queryParameters));

    //replace "&" with ","，and add double quote at the right side of the "=" and the left side of the ","，
    //doing this is because we need to add double quote to the value of the query string, because this string
    //will use as authorization header, not as query string.
    $authorization = str_replace('&', '", ', str_replace('=', '="', $queryStr)) . '"';
    $authorization = 'Authorization: OAuth realm="", ' . $authorization;
    return [$authorization];
}

/**
 * getRequestToken
 * @return array
 */
function getRequestToken(){
    $headers = getRequestHeaders();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt ($ch, CURLOPT_URL, 'https://up.flickr.com/services/upload/');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo'=>'E:\xampp\tmp\poza.jpg'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //in case you need a proxy
    //curl_setopt ($ch, CURLOPT_PROXY, 'http://127.0.0.1:1087');
    $returnStr = curl_exec($ch);
    if(curl_errno($ch)){
        echo 'Error: ' . curl_error($ch);
    }
    return $returnStr;
    close($ch);
}

$requestTokenURL = 'https://up.flickr.com/services/upload/';

$oauth_consumer_key = '740c27a168ed8f2fcacf1fff3ccf0051';
$oauth_consumer_secret = '198f5303c69bbbc0';
$oauth_callback = 'http://localhost/DPZ/test.php';

$tokens = getRequestToken();
var_dump($tokens);