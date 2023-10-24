<?php
// contenido_pagina.php

// Puedes agregar cualquier código PHP que necesites aquí

?>

<!DOCTYPE html>
<html>

<head>
    <title>Media Delivery Form</title>

</head>

<body>
    <div class="container">
        <form action="#" method="post" id="media-delivery-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-2">
                        <label class="form-label" for="your-name">What is your name?</label>
                        <input type="text" class="form-control" id="your-name" name="your-name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="your-mail">What is your mail?</label>
                        <input type="text" class="form-control" id="your-mail" name="your-mail" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for="payment">Could you provide to us your Melio or Bank Details 
(Account number & ACH)?</label>
                        <input type="text" class="form-control" id="payment" name="payment" required>
                    </div>


                    
                    <div class="form-group mb-2">
                        <label class="form-label" for="client-names">Who are the clients (Bride and Groom)?</label>
                        <input type="text" class="form-control" id="client-names" name="client-names" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="wedding-session">Was this a wedding or photo session?</label>
                        <select class="form-select" id="wedding-session" name="wedding-session" required>
                            <option value="Wedding">Wedding</option>
                            <option value="Session">Session</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="wedding-session-date">What was the event date?</label>
                        <input type="date" class="form-control" id="wedding-session-date" name="wedding-session-date"
                            required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="media-delivered">What type of media are you delivering?</label>
                        <select class="form-select" id="media-delivered" name="media-delivered" required>
                            <option value="Video">Video</option>
                            <option value="Photos">Photos</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="raw-media">How many RAW media files are you uploading?</label>
                        <input type="number" class="form-control" id="raw-media" name="raw-media" required>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="specific-shots">Please list by FILE NAME any specific shots or
                            footage that should be
                            included in the final media. Otherwise, put N/A.</label>
                        <textarea class="form-control" id="specific-shots" name="specific-shots" rows="4"
                            required></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="special-requests">Do you have any special requests or notes for
                            the editor?</label>
                        <textarea class="form-control" id="special-requests" name="special-requests"
                            rows="4"></textarea>
                    </div>

                    <div class="form-group mb-2 videographer-settings">
                        <label class="form-label" for="videographer-song">Did the client request any specific songs? If
                            not, put N/A
                        </label>
                        <input type="text" class="form-control" id="videographer-song" name="videographer-song">
                    </div>
                    <div class="form-group mb-2 videographer-settings">
                        <label class="form-label" for="audio-vows-speeches">Does the package include Audio of
                            vows/speeches?</label>
                        <select class="form-select" id="audio-vows-speeches" name="audio-vows-speeches" required>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>


                </div>
                <div class="col-md-6">


                    <div class="form-group mb-2">
                        <label class="form-label" for="media-backup">Upload a screenshot of Media Backup
                            (Image).</label>
                        <input type="file" class="form-control-file" id="media-backup" name="media-backup" accept="image/*">
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="wedding-session-hours">What was the package coverage
                            duration?</label>
                        <input type="number" class="form-control" id="wedding-session-hours" name="wedding-session-hours"
                            required>
                    </div>
                    <div class="form-group mb-2 videographer-settings audio-settings">
                        <label class="form-label " for="requested-audio">Audio- Which segment of audio did the client
                            request be included?</label>
                        <input type="text" class="form-control" id="requested-audio" name="requested-audio">
                    </div>

                    <div class="form-group mb-2 videographer-settings">
                        <label class="form-label" for="drone">Does the package include drone coverage?</label>
                        <select class="form-select" id="drone" name="drone" required>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label" for="travel-fee">Is there a travel fee?</label>
                                <select class="form-select" id="travel-fee" name="travel-fee" required>
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                            <div class="col-6" id="travel-fee-amount-container">
                                <label class="form-label" for="travel-fee-amount">Whats is the amount?</label>
                                <input type="number"    class="form-control" id="travel-fee-amount" name="travel-fee-amount">
                            </div>
                        </div>

                    </div>



                    <div class="form-group mb-2">
                        <label class="form-label" for="start-end-times">What were the start and end times of the
                            wedding/session (e.g., 2:00pm to 8:00pm)?</label>
                        <input type="text" class="form-control" id="start-end-times" name="start-end-times" required>
                    </div>

                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label" for="team-members">Did you work with anyone else? </label>
                                <select class="form-select" id="team-members" name="team-members" required>
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>
                            <div class="col-6" id="who-container">
                                <label class="form-label" for="who">Who?</label>
                                <input type="text" class="form-control" id="who" name="who" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="experience">How was your experience? Did you work well together,
                            and would you like to be scheduled together more often?</label>
                        <textarea class="form-control" id="experience" name="experience" rows="4" required></textarea>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label" for="comments">Do you have any comments about this booking?</label>
                        <textarea class="form-control" id="comments" name="comments" rows="4"></textarea>
                    </div>


                    <div class="form-group mb-2">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
