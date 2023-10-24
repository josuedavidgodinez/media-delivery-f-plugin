<?php

class Constants
{
   public static $appKey_MDF      = MY_APP_KEY;
   public static $appSecrect_MDF  = MY_APP_SECRET;
   public static $refreshToken_MDF = MY_REFRESH_TOKEN;
   public static $brandName_MDF     = MY_BRAND_NAME;
   public static $LeadRate_MDF      = MY_LEAD_RATE;
   public static $SecondRate_MDF    = MY_SECOND_RATE;
   public static $AudioCost_MDF     = MY_AUDIO_COST;
   public static $DroneCost_MDF     = MY_DRONE_COST;

   public static $api_endpoint = "https://api.box.com/";

   // Resto del código de la clase
}

define('MY_APP_KEY', get_option('appKey_MDF'));
define('MY_APP_SECRET', get_option('appSecrect_MDF'));
define('MY_REFRESH_TOKEN', get_option('refreshToken_MDF'));

define('MY_BRAND_NAME', get_option('brandName_MDF'));
define('MY_LEAD_RATE', get_option('LeadRate_MDF'));
define('MY_SECOND_RATE', get_option('SecondRate_MDF'));
define('MY_AUDIO_COST', get_option('AudioCost_MDF'));
define('MY_DRONE_COST', get_option('DroneCost_MDF'));

?>