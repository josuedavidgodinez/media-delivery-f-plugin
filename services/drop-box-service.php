<?php
$path = __DIR__ . '/..';
require_once $path . "/vendor/autoload.php";
require_once $path . "/api/api-calls.php";
require_once $path . "/env/constant-env-v.php";

use Kunnu\Dropbox\Dropbox;
use Kunnu\Dropbox\DropboxApp;

function uploadInvoiceFileRequest($invoice_path, $dropbox_path)
{
    try {
        $app = new DropboxApp(
            Constants::$appKey_MDF,
            Constants::$appSecrect_MDF,
            getNoExpToken_MDF() // Debes obtener este token mediante el flujo de autenticación de Dropbox
        );
        $dropbox = new Dropbox($app);
        error_log('invoice_path: '.$invoice_path);
        error_log('dropbox_path: '.$dropbox_path);

        $dropbox->upload($invoice_path, $dropbox_path, ['autorename' => true]);
        return 0;
    } catch (Exception $e) {
        error_log('Exception: ' . ($e));
        return -1;
    }
}

function createFileRequest($folder_name)
{
    $body = new stdClass();
    $body->title = $folder_name;
    $body->destination = $folder_name;
    $bodystr = json_encode($body);

    $response = wp_madePostAPI_MDF('file_requests/create', $bodystr, true, array(), 'https://api.dropboxapi.com/2/');
    error_log(json_encode($response));
    if (!is_null($response)) {
        return getBody_MDF($response);
    } else {
        return null;
    }
}

function accessToken()
{
    $accessToken_MDF = get_option('accessToken_MDF');
    $TokenExpireDate = get_option('TokenExpireDate');
    $fecha_ingresada = DateTime::createFromFormat('Y-m-d H:i:s', $TokenExpireDate);
    $fecha_actual_utc = new DateTime('now', new DateTimeZone('UTC'));
    if ($fecha_ingresada > $fecha_actual_utc) {
        $_SESSION['token'] = $accessToken_MDF;
    } elseif ($fecha_ingresada < $fecha_actual_utc) {
        $response = getRefreshToken(get_option('appKey_MDF'), get_option('appSecrect_MDF'), null, null, true, get_option('refreshToken_MDF'));
        if (is_null($response->message)) {
            $accessToken_MDF = $response->data->access_token;
            $expires_in = $response->data->expires_in;
            $fecha_actual = new DateTime('now', new DateTimeZone('UTC'));
            $fecha_actual->add(new DateInterval("PT{$expires_in}S"));
            update_option('accessToken_MDF', $accessToken_MDF);
            update_option('TokenExpireDate', $fecha_actual->format('Y-m-d H:i:s'));
            $_SESSION['token'] = $accessToken_MDF;
        }
    }
}

function getRefreshToken($app_key, $app_secret, $access_token, $redirect_uri, $refresh = false, $refresh_token = null)
{
    // URL del servidor al que deseas enviar la solicitud POST

    $url = 'https://api.dropboxapi.com/oauth2/token';

    $data = [];
    if ($refresh) {
        // Datos a enviar en la solicitud POST
        $data = array(
            'client_id' => $app_key,
            'client_secret' => $app_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token
        );

    } else {
        // Datos a enviar en la solicitud POST
        $data = array(
            'client_id' => $app_key,
            'client_secret' => $app_secret,
            'grant_type' => 'authorization_code',
            'code' => $access_token,
            'redirect_uri' => $redirect_uri
        );

    }
    error_log(json_encode($data));

    // Configura la solicitud POST
    $args = array(
        'body' => http_build_query($data),
        'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
        'method' => 'POST',
    );

    $response = wp_remote_request($url, $args);

    $resp = new stdClass();
    if (is_wp_error($response)) {
        $resp->data = null;
        $resp->message = "There was an error doing the authentication in dropbox";
        return $resp;
    } else {
        if (!OkCreateResponse_MDF($response)) {
            $resp->data = null;
            $resp->message = "There was an error doing the authentication in dropbox";
            return $resp;
        }

        $response_body_str = wp_remote_retrieve_body($response);
        $response_body = json_decode($response_body_str);

        if (property_exists($response_body, 'error')) {
            $resp->data = null;
            $resp->message = $response_body->error;
            return $resp;
        }
        error_log($response_body_str);
        $resp->data = $response_body;
        $resp->message = null;
        return $resp;
    }

}








?>