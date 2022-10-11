<div id="nhr-certificate-validator-form">
    
    <div class="heading">
        <h1>VERIFICATION</h1>
    </div>

    <?php

        use Nowshad\NhrIacaabCertificateValidator\Database;

        $foundNHRCertificate = false;

        if(isset($_GET['check_certificate'])){

            $organization = trim($_GET['organization_name']);
            $accreditation_number = trim($_GET['accreditation_number']);
            $country = trim($_GET['country']);
            
            if(empty($organization) && empty($accreditation_number)){

                $twoFieldEmpty = true;
            
            }else{

                $certificate = Database::searchOneCertificate($organization, $accreditation_number, $country);
                
                if($certificate && $certificate['COUNTRY'] == $country){

                    $foundNHRCertificate = true;
                    $expDateString = strtotime($certificate['EXPIRY_DATE']);
                    $currentDateString = strtotime(date('Y-m-d'));
                    $certificate['STATUS'] = ($expDateString < $currentDateString) ? 'INACTIVE':'ACTIVE';

                }else{

                    $foundNHRCertificate = false;

                }

            }

        }

    ?>
    
    <form id="nhr-certificate-validator" action="#nhr-certificate-validator-form" method="get">
        <?php if(isset($_GET['page_id'])):  ?><input type="hidden" name="page_id" value="<?php echo $_GET['page_id']; ?>" /><?php endif; ?>
        <input value="<?php echo isset($organization) ? $organization:''; ?>" type="text" name="organization_name" id="organization_name" placeholder="Certification Body Name" />
        <input value="<?php echo isset($accreditation_number) ? $accreditation_number:''; ?>" type="text" name="accreditation_number" id="accreditation_number" placeholder="Accreditation Number" />
        <input value="<?php echo isset($country) ? $country:''; ?>" type="text" name="country" id="country" placeholder="Country" required />
        <button type="submit" name="check_certificate" id="check_submit_btn">Search <i class="icon-angle-right"></i></button>
    </form>

</div>

<?php if(isset($_GET['check_certificate'])){ ?>
    
    <div id="nhr-cv-results-table-container">
        <table id="nhr-cv-results-table">
            
            <tbody>

                <?php if($foundNHRCertificate){ ?>

                    <tr>
                        <th>Organization Name</th>
                        <th>Accreditation Number</th>
                        <th>Program</th>
                        <th>Status</th>
                        <th>Expiry Date</th>
                    </tr>
                    <tr>
                        <td><?php echo strtoupper($certificate['ORGANIZATION_NAME']); ?></td>
                        <td><?php echo strtoupper($certificate['ACCREDITATION_NUMBER']); ?></td>
                        <td><?php echo strtoupper($certificate['PROGRAM']); ?></td>
                        <td><?php echo strtoupper((!empty($certificate['COMMENT'])) ? 'INACTIVE':$certificate['STATUS']); ?></td>
                        <td><?php echo strtoupper(Database::beautifyDateStr( $certificate['EXPIRY_DATE'] )); ?></td>
                    </tr>

                    <?php if(!empty($certificate['COMMENT'])){ ?>
                        <tr>
                            <th>Comment</th>
                            <td><?php echo $certificate['COMMENT']; ?></td>
                        </tr>
                        <tr>
                            <td class="notice" colspan="6" style="color: #e87713;">
                                This certificate is Inactive. Please contact AL WAHID GLOBAL HALAL CERTIFICATION.
                            </td>
                        </tr>
                    <?php }else if($certificate['STATUS'] == 'ACTIVE') { ?>
                        <tr>
                            <td class="notice" colspan="6" style="color: green;">
                                <a href="<?php echo plugin_dir_url(dirname(__FILE__)) . 'assets/certificates/' . $certificate['PDF']; ?>" download>DOWNLOAD CERTIFICATE</a>
                            </td>
                        </tr>
                    <?php }else{ ?>
                        <tr>
                            <td class="notice" colspan="6" style="color: #e87713;">
                                This certificate is Inactive. Please contact IACA.
                            </td>
                        </tr>
                    <?php } ?>

                <?php }else{ ?>
                    <tr>
                        <td class="notice" colspan="6" style="color: #800000;">
                            <?php if(!isset($twoFieldEmpty)){
                                echo "Sorry! This certificate is not valid!";
                            }else if(isset($twoFieldEmpty) && $twoFieldEmpty === true){
                                echo "Please atleast enter the 'Certificate Body Name'!";
                            } ?>
                        </td>
                    </tr>
                <?php } ?>
                
            </tbody>

        </table>
    </div>

<?php } ?>