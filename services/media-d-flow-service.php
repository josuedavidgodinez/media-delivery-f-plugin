<?php
$path = __DIR__ . '/..';
require_once $path . "/services/pdf-service.php";
require_once $path . "/services/drop-box-service.php";
require_once $path . "/services/send-mail-service.php";

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
        //$travelFee = $_POST['travel-fee'];
        $travelFeeAmount = $_POST['travel-fee-amount'];
        error_log('travelFeeAmount: ' . $travelFeeAmount);

        $startEndTimes = $_POST['start-end-times'];
        //$teamMembers = $_POST['team-members'];
        $who = $_POST['who'];
        error_log('who: ' . $who);
        $experience = $_POST['experience'];
        $comments = $_POST['comments'];

        $parent_folder_name = 'File Requests';
        $folder_name = '/' . $parent_folder_name . '/' . $clientNames . " " . $weddingSession . " RAW " . $mediaDelivered;
        $resp_create_fR = createFileRequest($folder_name);

        if (is_null($resp_create_fR)) {
            $resp_flow->message = "Error for create file REQUEST";
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

        $audio_video_fee = $audioamount + $droneamount;
        error_log($audio_video_fee);

        $total = totalCalculations($weddingSessionHours);
        error_log($total);
        error_log('travelFeeAmount: ' . $travelFeeAmount);

        $invoice_server_resp = generateInvoicePDF(Constants::$brandName_MDF, $yourName, $payment, $clientNames, $weddingSession, $mediaDelivered, $weddingSessionDate, $weddingSessionHours, $audio_video_fee, $travelFeeAmount, $total, $rawMedia, $specificShots, $specialRequests, $videographerSong, $requestedAudio, $experience, $comments, $startEndTimes);


        if (is_null($invoice_server_resp->archivoPDF)) {
            $resp_flow->message = "Error for create PDF file";
            return wp_send_json_error($resp_flow);
        }


        $status = uploadInvoiceFileRequest($invoice_server_resp->archivoPDF, $folder_name . "/" . $invoice_server_resp->nombreArchivo);
        if ($status != 0) {
            $resp_flow->message = "Error for upload Invoice file";
            return wp_send_json_error($resp_flow);
        }


        $namefile = $_FILES['media-backup']['name'];
        $extension = pathinfo($namefile, PATHINFO_EXTENSION);
        $namefiledrpbox = "$yourName-$mediaDelivered-$weddingSessionDate-backup.$extension";
        $statusBackUp = uploadInvoiceFileRequest($mediaBackup, $folder_name . "/" . $namefiledrpbox);
        if ($statusBackUp != 0) {
            $resp_flow->message = "Error for upload backup file";
            return wp_send_json_error($resp_flow);
        }

        $wp_mail_result = wp_SendMail_MDF($yourMail, 'Media delivery link', "We're attaching your link to upload the event files: " . $resp_create_fR->url);
        if (!$wp_mail_result) {
            $resp_flow->message = "Error for send mail";
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

function totalCalculations($hours)
{
    return $hours * Constants::$LeadRate_MDF;

}

?>