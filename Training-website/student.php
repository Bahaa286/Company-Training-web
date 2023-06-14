<?php include "parts/_header.php" ?>

<?php $_SESSION['previous_location'] = 'student.php' ?>

<aside>
    <h2>Similar Students</h2>
    <p>
        A student or 2 students with same major
    </p>
</aside>

<main>


    <?php
    $studentInfo = array();
    if(isset($_GET['student_id'])){
        $studentInfo = getStudentInfoFromSID($_GET['student_id']);
    }elseif(isset($_SESSION['student_id'])){
        $studentInfo = getStudentInfoFromSID($_SESSION['student_id']);
    }
    ?>
    
    <?php if(!empty($studentInfo)):?>
    
        <h2><?php $name = $studentInfo['student_name']; echo "$name";?></h2>

        <img src="<?php $photo_path = $studentInfo['photo_path']; echo "$photo_path";?>" alt="student image"/>
        <dl>

            <dt><strong>City:</strong></dt>
            <dd> <?php echo $studentInfo['city_name']; ?> </dd>

            <dt><strong>Email:</strong></dt>
            <dd><a href="mailto:<?php echo $studentInfo['email']; ?>"><?php echo $studentInfo['email']; ?></a></dd>

            <dt><strong>Universty:</strong></dt>
            <dd><a href="tel:<?php $tel = $studentInfo['tel']; echo "$tel";?>" ><?php $tel = $studentInfo['tel']; echo "$tel";?></a></dd>

            <dt><strong>Projects:</strong></dt>
            <dd><?php $projects = $studentInfo['projects']; echo "$projects";?></dd>

            <dt><strong>Interests:</strong></dt>
            <dd><?php $interests = $studentInfo['interests']; echo "$interests";?></dd>

            <?php

            if(isset($_SESSION['student_id'])){ // check if the user is a student 

                $companiesAndStatuses = array(); // companies and status 
                $add_data = TRUE; // is there is data to be appended in the description list
                if(isset($_GET['student_id'])){ 
                    if($_GET['student_id'] == $_SESSION['student_id']){ // information of student are for the same user
                        $companiesAndStatuses = getCompaniesOfferFromStudentID($_SESSION['student_id']);
                    }else{ // information of studet are not for the same user
                        $add_data = FALSE; // dont append date to description list
                    }
                }else{ // page directly open after login 
                    $companiesAndStatuses = getCompaniesOfferFromStudentID($_SESSION['student_id']);
                }

                if($add_data){
                    echo "<dt><strong>Company offers:</strong></dt>";

                    if(empty($companiesAndStatuses)){
                        echo " <dd> there is no offered companies </dd> ";
                    }else{
                        echo "<dd><ul>";
                        foreach( $companiesAndStatuses as $row){
                            $offer_date = $row['offer_date'];
                            $company_name = $row['company_name'];
                            $status = $row['status'];
                            $student_id = $_SESSION['student_id'];
                            $company_id = getCompanyIdFromCompanyName($company_name);

                            if(strcmp($status, "sent") == 0){
                                echo "  <li>\n 
                                \tcompany name: $company_name. status: $status. offer date: $offer_date.\n
                                \t<a href=\"process.php?application_status=accept&student_id=$student_id&company_id=$company_id&action=accept_application_training\" > accept </a>\n 
                                \t<a href=\"process.php?application_status=reject&student_id=$student_id&company_id=$company_id&action=reject_application_training\" > reject </a>\n
                                </li>\n";
                            }else{
                                echo "  <li>\n 
                                \tcompany name: $company_name. status: $status. offer date: $offer_date.\n
                                </li>\n";
                            }
                        }
                        echo "</ul></dd>";
                    }
                }
            }
            
            ?>      

        </dl>  
        
        <div>

            <a href="students.php">back to students list</a>

            <?php if(isset($_GET['student_id'])): ?> <!-- if the user click on his name from students table -->
                <?php if( isset($_SESSION['student_id']) && ( $_GET['student_id'] == $_SESSION['student_id'] ) ):?>
                    <a href="add-student.php?edit_Student_ID=<?php $ID = $_SESSION['student_id']; echo "$ID";?>" >Edit</a>
                <?php endif;?>   
            <?php else: ?> <!-- if there is a user logged in his account -->
                <?php if(isset($_SESSION['student_id'])): ?>  
                    <a href="add-student.php?edit_Student_ID=<?php $ID = $_SESSION['student_id']; echo "$ID";?>" >Edit</a>
                <?php endif; ?>    
            <?php endif; ?>

            <!-- if the user type is a company -->
            <?php
            if(strcmp(getUserTypeFromUserID($_SESSION['user_id']), 'company') == 0){

                if(isset($_SESSION['company_id'])){
                    
                    if(isCompanyOfferStudentBefore($_GET['student_id'], $_SESSION['company_id'])){
                        echo " <a> Offered </a> ";
                    }else{
                        $SID = $_GET['student_id'];
                        $CID = $_SESSION['company_id'];
                        echo "<a href=\"process.php?student_id=$SID&company_id=$CID&action=apply_an_application\"  >offer A Training</a>";
                    }
                }
            }
            
            ?>   

        </div>
    <?php else:?>
        <?php
            $user_id = $_SESSION['user_id'];
            header("location:add-student.php?user_id=$user_id");    
        ?>
    <?php endif;?>    
    
</main>
<?php include "parts/_footer.php" ?>

