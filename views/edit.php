<style>
    <?php require_once plugin_dir_path( dirname( __FILE__ ) ).'css/admin-page.css'; ?>
</style>

<?php

    use Nowshad\NhrIacaabCertificateValidator\Database;

    $id = intval($_GET['post_id']);

    // to show a cancellable admin notice
    function notify(string $msg, string $type, bool $is_dismissible=true){
        $dismissible = ($is_dismissible==true) ? "is-dismissible":''; 
        $notice_board = "<div style='margin-left: 2px;margin-right: 10px;' class='notice notice-$type $dismissible'>".
                            "<p>$msg</p>".
                        "</div>";
        echo $notice_board;
    }


    if(isset($_POST['update_certificate'])){

        $organization = trim($_POST['organization_name']);
        $accreditation_number = trim($_POST['accreditation_number']);
        $program = trim($_POST['program']);
        $country = trim($_POST['country']);
        $comment = trim($_POST['comment']);

        $issueYear = intval(trim($_POST['issue_year']));
        $issueMonth = intval(trim($_POST['issue_month']));
        $issueDay = intval(trim($_POST['issue_day']));

        $expiryYear = intval(trim($_POST['expiry_year']));
        $expiryMonth = intval(trim($_POST['expiry_month']));
        $expiryDay = intval(trim($_POST['expiry_day']));

        $issueDate = "$issueYear-$issueMonth-$issueDay";
        $expiryDate = "$expiryYear-$expiryMonth-$expiryDay";
        
        if(
            empty($organization) || 
            empty($accreditation_number) || 
            empty($program) || 
            empty($country) || 
            empty($issueYear) || 
            empty($issueMonth) || 
            empty($issueDay) || 
            empty($expiryYear) || 
            empty($expiryMonth) || 
            empty($expiryDay)
        ){
            notify("Please fill all the fields!", "warning", true);
        }else{
            
            $pdf = isset($_FILES['pdf']) ? $_FILES['pdf']:false;

            $response = Database::update($id, $organization, $accreditation_number, $program, $country, $pdf, $issueDate, $expiryDate, $comment);

            if($response == 'record-exists'){
                notify("Record already exists!", 'warning', true);
            }else if($response == 'success'){
                notify("Certificate updated successfully!", "success", true);
            }else{
                echo $response;
                notify("Something went wrong, please try again!", "warning", true);
            }

        }

    }


    // fetching the certificate by id
    $certificate = Database::getOneCertificateByID($id);
    
    $issDate = explode('-', $certificate['ISSUE_DATE']);
    $expDate = explode('-', $certificate['EXPIRY_DATE']);
    
    $issYear = $issDate[0];
    $issMonth = $issDate[1];
    $issDay = $issDate[2];

    $expYear = $expDate[0];
    $expMonth = $expDate[1];
    $expDay = $expDate[2];


?>

<h1>Update Certificate</h1>

<form method="post" enctype="multipart/form-data">
    
    <label>Organization Name :</label>
    <input value="<?php echo $certificate['ORGANIZATION_NAME']; ?>" type="text" name="organization_name" placeholder="Organization name" required/>
    
    <label>Accreditation Number : </label>
    <input value="<?php echo $certificate['ACCREDITATION_NUMBER']; ?>" type="text" name="accreditation_number" placeholder="Accreditation Number" required/>
    
    <label>Program : </label>
    <input value="<?php echo $certificate['PROGRAM']; ?>" type="text" name="program" placeholder="Program" required/>
    
    <label>Country : </label>
    <input value="<?php echo $certificate['COUNTRY']; ?>" type="text" name="country" placeholder="Country" required/>
    
    <label>PDF : </label>
    <input style="padding: 5px;" type="file" name="pdf" placeholder="PDF of the certificate" />

    <label>Issue Date : </label>
    <div class="inline-date-inputs">
        <input value="<?php echo $issYear; ?>" placeholder="Year" type="number" name="issue_year" minlength="4" maxlength="4" required>
        <input value="<?php echo $issMonth; ?>" placeholder="Month" type="number" name="issue_month" minlength="1" maxlength="2" min="1" max="12" required>
        <input value="<?php echo $issDay; ?>" placeholder="Day" type="number" name="issue_day" minlength="1" maxlength="1" min="1" max="31" required>
    </div>
    
    <label>Expiry Date : </label>
    <div class="inline-date-inputs">
        <input value="<?php echo $expYear; ?>" placeholder="Year" type="number" name="expiry_year" minlength="4" maxlength="4" required>
        <input value="<?php echo $expMonth; ?>" placeholder="Month" type="number" name="expiry_month" minlength="1" maxlength="2" min="1" max="12" required>
        <input value="<?php echo $expDay; ?>" placeholder="Day" type="number" name="expiry_day" minlength="1" maxlength="1" min="1" max="31" required>
    </div>

    <label>Comment : </label>
    <textarea name="comment" cols="30" rows="5" placeholder="Write a comment for this certificate holder"><?php echo $certificate['COMMENT']; ?></textarea>

    <input class="btn btn-primary" type="submit" value="Update" name="update_certificate">

</form>



