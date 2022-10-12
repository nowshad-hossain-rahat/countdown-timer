<style>
    <?php require_once plugin_dir_path(dirname(__FILE__)) . 'css/admin-page.css'; ?>
</style>

<?php

use Nhrdev\CountdownTimer\DbHandler;

// to show a cancellable admin notice
function notify(string $msg, string $type, bool $is_dismissible = true)
{
    $dismissible = ($is_dismissible == true) ? "is-dismissible" : '';
    $notice_board = "<div style='margin-left: 2px;margin-right: 10px;' class='notice notice-$type $dismissible'>" .
        "<p>$msg</p>" .
        "</div>";
    echo $notice_board;
}


if (isset($_POST['add_new_timer'])) {

    $timer_name = trim($_POST['timer_name']);
    $countdown_till = trim($_POST['countdown_till']);
    $bg_type = trim($_POST['bg_type']);
    $bg_image = trim($_POST['bg_image']);
    $bg_color = trim($_POST['bg_color']);

    if (
        empty($timer_name) ||
        empty($countdown_till) ||
        empty($bg_type)
    ) {
        notify("Please fill all the fields!", "warning", true);
    } else {

        if ($bg_type === "image" && empty($bg_image)) {
            notify("Background image is required!", "warning");
        } else if ($bg_type === "color" && empty($bg_color)) {
            notify("Background color is required!", "warning");
        } else {

            $response = DbHandler::addNewTimer($timer_name, $countdown_till, $bg_type, $bg_image, $bg_color);

            if ($response == 'success') {
                notify("Timer added successfully!", "success", true);
            } else if ($response == 'exists') {
                notify("Timer already exists!", 'warning', true);
            } else {
                notify("Something went wrong, please try again!", "warning", true);
            }
        }
    }
}


?>

<h1>Add New Timer</h1>
<hr>

<form method="post" class="nhr-add-new-timer-form">

    <label>Timer name :</label>
    <input value="" type="text" name="timer_name" placeholder="Timer name" required />

    <label>Countdown till : </label>
    <input type="date" name="countdown_till" placeholder="Countdown till" required />

    <label>Background type : </label>
    <select id="bg_type" name="bg_type" required>
        <option value="">Select type</option>
        <option value="transparent">Transparent</option>
        <option value="image">Image</option>
        <option value="color">Color</option>
    </select>

    <label style="display: none;" id="bg_image_label">Background image : </label>
    <input style="display: none;" id="bg_image" type="url" name="bg_image" placeholder="Enter image URL" />

    <label style="display: none;" id="bg_color_label">Background color : </label>
    <input style="display: none;" type="color" name="bg_color" id="bg_color">

    <input class="btn btn-primary" type="submit" value="Add Timer" name="add_new_timer">

</form>

<script>
    jQuery(document).ready(() => {

        jQuery("#bg_type").val("")

        jQuery("#bg_type").change((evt) => {
            let bg_type = evt.target.value
            if (bg_type === "image") {

                jQuery("#bg_image_label, #bg_image")
                    .attr("required", true).css({
                        display: "block"
                    })

                jQuery("#bg_color_label, #bg_color")
                    .removeAttr("required").css({
                        display: "none"
                    })

            } else if (bg_type === "color") {

                jQuery("#bg_image_label, #bg_image")
                    .removeAttr("required").css({
                        display: "none"
                    })

                jQuery("#bg_color_label, #bg_color")
                    .attr("required", true).css({
                        display: "block"
                    })

            } else {

                jQuery("#bg_image_label, #bg_image, #bg_color_label, #bg_color")
                    .removeAttr("required").css({
                        display: "none"
                    })

            }
        })

    })
</script>
