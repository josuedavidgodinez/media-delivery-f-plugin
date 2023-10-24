<?php

$path = __DIR__ . '/..';

require_once $path . "/utils/token-utils.php";
require_once $path . "/utils/response-utils.php";
require_once $path . "/env/constant-env-v.php";


/**
 * Create POST to the API endpoint
 */
function wp_madePostAPI_MDF(string $apiRoute, $body, bool $secured, array $Qparams = array(),$endpoint)
{
   $apiRequest= commonApiRequest_MDF($apiRoute,$secured,$body , $Qparams,$endpoint,false);
   error_log(json_encode($apiRequest));

   if(is_null($apiRequest)){
      return null;
   }else {
      $response = wp_remote_post($apiRequest['apiendpoint'], $apiRequest['apiargs']);
      return $response;
   }

}

/**
 * Create PUT to the API endpoint
 */
function wp_madePutAPI_MDF(string $apiRoute, $body, bool $secured, array $Qparams = array(),$endpoint)
{
   $apiRequest= commonApiRequest_MDF($apiRoute,$secured,$body , $Qparams,$endpoint,true);
   error_log(json_encode($apiRequest));

   if(is_null($apiRequest)){
      return null;
   }else {
      $response = wp_remote_request($apiRequest['apiendpoint'], $apiRequest['apiargs']);
      return $response;
   }

}



/**
 * Create POST to the API endpoint
 */
function wp_madeGetAPI_MDF(string $apiRoute,bool $secured , array $Qparams = array(),$endpoint)
{
   $apiRequest= commonApiRequest_MDF($apiRoute,$secured,null,$Qparams,$endpoint,false);
   error_log(json_encode($apiRequest));
   if(is_null($apiRequest)){
      return null;
   }else {
      $response = wp_remote_get($apiRequest['apiendpoint'], $apiRequest['apiargs']);
      error_log(json_encode($response));

      return $response;
   }

}

/**
 * Common API request params
 */
function commonApiRequest_MDF(string $apiRoute, bool $secured, $body ,array $Qparams = array(),$endpoint,$put){

   $params="";
   $index=0;
   foreach ($Qparams as $key => $value) {
      if($index==0){
         $params .= '?'.$key.'='.urlencode( $value);
      }else {
         $params .= '&'.$key.'='.urlencode( $value);      # code...
      }
      $index++;
    }
   $headers = array(
      'Content-Type' => 'application/json'
   );
   if($secured){
      $token=getNoExpToken_MDF();
      if(is_null($token)){
         return null;
      }
      $headers['Authorization'] = 'Bearer '.$token;
   }

   $api_endpoint = $endpoint . $apiRoute . $params;
   $api_args = array(
      'headers' => $headers,
      'timeout' => 20
   );
   if (!is_null($body)){
      $api_args['body'] = $body;
   }

   if($put){
      $api_args['method'] = 'PUT';
   }

   return array(
      'apiargs' => $api_args,
      'apiendpoint' => $api_endpoint
   );
}