<?php
$path = __DIR__ . '/..';
require_once $path . "/services/pdf-service.php";
require_once $path . "/services/drop-box-service.php";
require_once $path . "/services/send-mail-service.php";
require_once $path . "/services/file-service.php";

require_once $path . "/env/constant-env-v.php";


function media_delivery_flow()
{
    $resp_flow = new stdClass();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('entro a POST');
        $yourName = $_POST['your-name'];
        $yourMail = $_POST['your-mail'];
        $payment = $_POST['payment'];
        $clientNames = $_POST['client-names'];
        $weddingSession = $_POST['wedding-session'];
        $weddingSessionDate = $_POST['wedding-session-date'];
        $mediaDelivered = $_POST['media-delivered'];
        $rawMedia = $_POST['raw-media'];
        $specificShots = $_POST['specific-shots'];
        $specialRequests = $_POST['special-requests'];
        $videographerSong = $_POST['videographer-song'];
        $audioVowsSpeeches = $_POST['audio-vows-speeches'];
        $mediaBackup = $_FILES['media-backup']['tmp_name'];
        $weddingSessionHours = $_POST['wedding-session-hours'];
        $requestedAudio = $_POST['requested-audio'];
        $drone = $_POST['drone'];
        $role = $_POST['role'];

        $travelFeeAmount = 0;
        if(isset($_POST['travel-fee-amount'])){
            $travelFeeAmount = $_POST['travel-fee-amount'];
        }
        error_log('travelFeeAmount: ' . $travelFeeAmount);

        $startEndTimes = $_POST['start-end-times'];
        //$teamMembers = $_POST['team-members'];
        $who = $_POST['who'];
        error_log('who: ' . $who);
        $experience = $_POST['experience'];
        $comments = $_POST['comments'];

        $parent_folder_name = 'File Requests';
        $folder_name = '/' . $parent_folder_name . '/' . $clientNames . " " . $weddingSession . " RAW " . $mediaDelivered . " - " . $yourName;
        $resp_create_fR = createFileRequest($folder_name);

        if (is_null($resp_create_fR)) {
            $resp_flow->message = "An error was generated when creating your dropbox link for the files request.";
            return wp_send_json_error($resp_flow);
        }


        error_log(Constants::$AudioCost_MDF);
        $audioamount = 0;
        if ($audioVowsSpeeches == 'yes') {
            $audioamount = Constants::$AudioCost_MDF;
        }

        $droneamount = 0;
        if ($drone == 'yes') {
            $droneamount = Constants::$DroneCost_MDF;
        }



        $totalHours = totalCalculations($weddingSessionHours,$role);
        $total = $totalHours + $droneamount + $audioamount + $travelFeeAmount;
        error_log($total);
        error_log('total: ' . $total);

        $invoice_server_resp = generateInvoicePDF(Constants::$brandName_MDF, $yourName, $payment, $clientNames, $weddingSession, $mediaDelivered, $weddingSessionDate, $weddingSessionHours, $audioamount, $travelFeeAmount, $total, $rawMedia, $specificShots, $specialRequests, $videographerSong, $requestedAudio, $experience, $comments, $startEndTimes,$droneamount);


        if (is_null($invoice_server_resp->archivoPDF)) {
            $resp_flow->message = "An error was generated when creating the PDF file of the invoice.";
            return wp_send_json_error($resp_flow);
        }


        $status = uploadInvoiceFileRequest($invoice_server_resp->archivoPDF, $folder_name . "/" . $invoice_server_resp->nombreArchivo);
        if ($status != 0) {
            $resp_flow->message = "An error was generated when uploading the invoice file to the dropbox server.";
            return wp_send_json_error($resp_flow);
        }

        deleteFile($invoice_server_resp->archivoPDF);

        $namefile = $_FILES['media-backup']['name'];
        $extension = pathinfo($namefile, PATHINFO_EXTENSION);
        $namefiledrpbox = "$yourName-$mediaDelivered-$weddingSessionDate-backup.$extension";
        $statusBackUp = uploadInvoiceFileRequest($mediaBackup, $folder_name . "/" . $namefiledrpbox);
        if ($statusBackUp != 0) {
            $resp_flow->message = "An error was generated when uploading the backup screenshot to the dropbox server.";
            return wp_send_json_error($resp_flow);
        }

        $wp_mail_result = wp_SendMail_MDF($yourMail, 'Media delivery link', "We're attaching your link to upload the event files: " . $resp_create_fR->url);
        if (!$wp_mail_result) {
            $resp_flow->message = "An error was generated when sending the mail with your link.";
            return wp_send_json_error($resp_flow);
        }

        $resp_flow->message = null;
        wp_send_json_success($resp_flow);
        //wp_send_json_error($resp_flow);

    } else {
        $resp_flow->message = "You're not allowed to execute this code";
        return wp_send_json_error($resp_flow);
    }


}

function totalCalculations($hours,$role)
{
    if($role == 'lp' || $role == 'lv'){
        return $hours * Constants::$LeadRate_MDF;
    }else if($role == 'sp' || $role == 'sv'){
        return $hours * Constants::$SecondRate_MDF;
    }else{
        return 0;
    }

}

?>