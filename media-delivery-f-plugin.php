<?php
/*
Plugin Name: Media Delivery Form Plugin
Plugin URI:  
Description: Plugin for Media Delivery Form
Version:     1.0
Author:      Josue Godinez
Author URI:  
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
require_once "services/media-d-flow-service.php";
require_once "services/drop-box-service.php";
function media_delivery_f_plugin_settings()
{
    add_menu_page(
        "media-delivery-f-plugin",
        'Media Delivery Form Plugin',
        'administrator',
        "media-delivery-f-plugin",
        'displayAdminDashboardMediaDF',
        'dashicons-admin-media',
        20
    );
    // Add more settings fields as needed
}






function displayAdminDashboardMediaDF()
{


    $settings = array(
        'appKey_MDF' => 'App Key',
        'appSecrect_MDF' => 'App Secret',
        'brandName_MDF' => 'Brand Name',
        'LeadRate_MDF' => 'Lead photographer or Lead videographer Rate',
        'SecondRate_MDF' => '2nd photographer or 2nd videographer Rate',
        'AudioCost_MDF' => 'Cost of audio of vows speeches',
        'DroneCost_MDF' => 'Cost of drone'
    );

    if (isset($_POST['media_delivery_opt_submit'])) {
        $refreshToken_MDF = get_option('refreshToken_MDF');
        if ($refreshToken_MDF == false) {
            $appKey_MDF = stripslashes(sanitize_text_field($_POST['appKey_MDF']));
            update_option('appKey_MDF', $appKey_MDF);
            $appSecrect_MDF = stripslashes(sanitize_text_field($_POST['appSecrect_MDF']));
            update_option('appSecrect_MDF', $appSecrect_MDF);
            $current_url = rtrim(home_url(add_query_arg(array(), $_SERVER['REQUEST_URI'])), '/');
            $destinty = 'https://www.dropbox.com/oauth2/authorize';
            $parametros = array(
                'client_id' => $appKey_MDF,
                'response_type' => 'code',
                'token_access_type' => 'offline',
                'redirect_uri' => $current_url,
            );
            $url_destino = $destinty . '?' . http_build_query($parametros);

            echo '<script type="text/javascript">
                        window.location.href = "' . ($url_destino) . '";
                    </script>';
        } else {
            foreach ($settings as $key => $label) {
                $value = stripslashes(sanitize_text_field($_POST[$key]));
                update_option($key, $value);
            }
            echo '<div class="updated"><p>Settings saved!</p></div>';
        }
    }

    //get auth code
    if (isset($_GET['code'])) {
        $refreshToken_MDF = get_option('refreshToken_MDF');
        error_log('refreshToken_MDF: '.$refreshToken_MDF);
        if ($refreshToken_MDF == false) {
            $accessToken_MDF = $_GET['code'];
            update_option('accessToken_MDF', $accessToken_MDF);
            $current_url_without_params = remove_query_arg(
                array(
                    'code'
                ), home_url(add_query_arg(array(), $_SERVER['REQUEST_URI'])));
            $response = getRefreshToken(get_option('appKey_MDF'), get_option('appSecrect_MDF'), $accessToken_MDF, $current_url_without_params);
            if (is_null($response->message)) {
                $refreshToken_MDF = $response->data->refresh_token;
                $accessToken_MDF = $response->data->access_token;
                $expires_in = $response->data->expires_in;
                $fecha_actual = new DateTime('now', new DateTimeZone('UTC'));
                $fecha_actual->add(new DateInterval("PT{$expires_in}S"));
                update_option('refreshToken_MDF', $refreshToken_MDF);
                update_option('accessToken_MDF', $accessToken_MDF);
                update_option('TokenExpireDate', $fecha_actual->format('Y-m-d H:i:s'));

                echo '<div class="updated"><p>Authorization succesful!</p></div>';

            } else {
                echo '<div class="updated"><p>There was a problem with your authorizat</p></div>';

            }
        }

    }

    $savedSettings = array();
    foreach ($settings as $key => $label) {
        $savedSettings[$key] = get_option($key);
    }

    $refreshToken_MDF = get_option('refreshToken_MDF');
    if ($refreshToken_MDF == false) {
        echo '<div class="wrap">
        <h1>Leads Plugin Settings</h1>
        <!-- Intro text here -->

        <form method="post" action="">
            <h3>Plugin Settings</h3>
            <table class="table-form" id="brandsettings">';
        echo ' <tr>
                            <th style="text-align: left;"><label for="appKey_MDF">App Key:</label></th>
                            <td><input type="text" name="appKey_MDF" id="appKey_MDF"
                                    value="">
                                <br><br>

                              </td>
                              <th style="text-align: left;"><label for="appKey_MDF">App Secret:</label></th>
                             <td><input type="text" name="appSecrect_MDF" id="appSecrect_MDF"
                                    value="">
                                <br><br>

                              </td>
                              <ul>
                              <li>
                                  <label>Get your App key and secret in this link</label>
                                  <a href="https://www.dropbox.com/developers/apps?_tk=pilot_lp&_ad=topbar4&_camp=myapps">Apps Dashboard</a>
                              </li>
                              <li>
                                  <label> Then click the Media delivery form App</label>
                              </li>
                              <li>
                                  <label> Then copy the app key and secret</label>
                              </li>
                          </ul>
                        </tr>';

        echo '</table>
    <input type="submit" name="media_delivery_opt_submit" class="button-primary" value="Authorize App">
     </form>
    </div>';
    } else {
        echo '<div class="wrap">
        <h1>Leads Plugin Settings</h1>
        <!-- Intro text here -->

        <form method="post" action="">
            <h3>Plugin Settings</h3>
            <table class="table-form" id="brandsettings">';
        foreach ($settings as $key => $label):
            echo ' <tr>
                            <th style="text-align: left;"><label for="' . esc_attr($key) . '">' . esc_html($label) . ':</label></th>
                            <td><input type="text" name="' . esc_attr($key) . '" id="' . esc_attr($key) . '"
                                    value="' . esc_attr($savedSettings[$key]) . '">
                                <br><br>
                            </td>
                        </tr>';

        endforeach;
        echo '</table>
    <input type="submit" name="media_delivery_opt_submit" class="button-primary" value="Save Settings">
     </form>
    </div>';
    }


}



function handlemedia_delivery_submit()
{
    error_log('Entro a handle');
    media_delivery_flow();
}

function create_form_page()
{
    // Define el título y contenido de la página
    $post_title = 'Media Delivery Form';
    $post_content = file_get_contents(__DIR__ . '/pages/media-delivery-form-content.php');

    // Define el slug de la página
    $post_name = 'media-delivery-form';

    // Crea la página
    $page = array(
        'post_title' => $post_title,
        'post_content' => $post_content,
        'post_name' => $post_name,
        // Aquí se establece el slug
        'post_status' => 'publish',
        'post_type' => 'page',
    );

    // Inserta la página en la base de datos
    wp_insert_post($page);
}

function resetearoptions()
{
    delete_option('appKey_MDF');
    delete_option('appSecrect_MDF');
    delete_option('accessToken_MDF');
    delete_option('refreshToken_MDF');
    delete_option('TokenExpireDate');
    delete_option('brandName_MDF');
    delete_option('LeadRate_MDF');
    delete_option('SecondRate_MDF');
    delete_option('AudioCost_MDF');
    delete_option('DroneCost_MDF');

}

function delete_form_page()
{
    $page_name = 'media-delivery-form';
    $page = get_page_by_path($page_name);
    if ($page) {
        wp_delete_post($page->ID, true);
    }
}


register_activation_hook(__FILE__, 'create_form_page');
register_activation_hook(__FILE__, 'resetearoptions');
register_deactivation_hook(__FILE__, 'delete_form_page');


function media_DF_enqueue_styles()
{
    $is_required_page = is_page('media-delivery-form');
    if ($is_required_page) {
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css');

        
        
    }
}
add_action('wp_enqueue_scripts', 'media_DF_enqueue_styles', 100);


function media_DF_enqueue_scripts()
{
    $is_required_page = is_page('media-delivery-form');
    if ($is_required_page) {
        wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.5.1.slim.min.js', array(), '3.5.1', true);
        wp_enqueue_script('media-d-scripts', plugins_url('/js/scripts.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script('popper-scripts', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"', array('jquery'), '1.0', true);
        wp_enqueue_script('bootstrap-scripts', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js', array('jquery'), '1.0', true);
        wp_localize_script('media-d-scripts', 'script_var', ['ajaxurl' => admin_url('admin-ajax.php')]);
        wp_enqueue_script('sweetalert2-js', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js', array('jquery'), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'media_DF_enqueue_scripts');

add_action('wp_ajax_nopriv_media_delivery_submit', 'handlemedia_delivery_submit');
add_action('wp_ajax_media_delivery_submit', 'handlemedia_delivery_submit');

// Hook into the admin menu
add_action('admin_menu', 'media_delivery_f_plugin_settings');
?>