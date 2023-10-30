jQuery(document).ready(function ($) {

    $("#media-delivery-form").submit(function (e) {
        try {
            e.preventDefault();
            // Obtener los datos del formulario
            const formData = new FormData(this);
            formData.append("action", "media_delivery_submit");
            console.log(formData);
            $.ajax({
                url: script_var.ajaxurl, // Ruta al archivo PHP que procesará los datos
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        $("#media-delivery-form :input").each(function () {
                            if (this.type != "submit" && this.type != "button") {
                                $(this).val("");
                            }
                        });
                        alert('The form to create the file upload link was successful.');
                        // You can use SweetAlert to show an error message:
                    } else {
                        const {data} = response;
                        if(data){
                            const {message} = data;
                            if (message) {
                                alert(message);
                            }else{
                                alert('There was a problem while processing the form.');
                            }
                        }else{
                            alert('There was a problem while processing the form.');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    alert('There was a problem while processing the form.');

                }
            });
        } catch (error) {
            alert('There was a problem while processing the form.');
        }

    });

    $(".audio-settings").hide();
    $("#travel-fee-amount-container").hide();
    $("#who-container").hide();
    // Listen for changes in the audio-vows-speeches select

    $("#team-members").change(function () {
        var selectedOption = $(this).val();
        if (selectedOption === "no") {
            $("#who-container").hide();
        } else {
            $("#who-container").show();
        }
    });
    $("#travel-fee").change(function () {
        var selectedOption = $(this).val();
        if (selectedOption === "no") {
            $("#travel-fee-amount-container").hide();
        } else {
            $("#travel-fee-amount-container").show();
        }
    });

    $("#audio-vows-speeches").change(function () {
        var selectedOption = $(this).val();

        if (selectedOption === "no") {
            $(".audio-settings").hide();
        } else {
            $(".audio-settings").show();
        }
    });
    // Listen for changes in the media-delivered select
    $("#media-delivered").change(function () {
        let selectedOption = $(this).val();

        console.log(selectedOption);

        if (selectedOption == "Photos") {
            // If "Photos" is selected, hide videographer settings
            $(".videographer-settings").hide();
        }

        if (selectedOption == "Video") {
            // If "Video" is selected, show videographer settings
            $(".videographer-settings").show();
        }
    });
});