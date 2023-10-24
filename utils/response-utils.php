<?php

/**
 * Verify response status
 */
function OkCreateResponse_MDF( $response)
{
   $statusCode = wp_remote_retrieve_response_code($response);
   if ($statusCode != 200 && $statusCode != 201) {
      return false;
   }
   return true;
}

function getBody_MDF($response)  {
   if(OkCreateResponse($response)){
      $bodyResp = wp_remote_retrieve_body($response);
      error_log($bodyResp);
      $data = json_decode($bodyResp);
      return $data;
   }else {
      return null;
   }
}

