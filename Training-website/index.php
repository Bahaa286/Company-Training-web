<?php include "parts/_header.php" ?>

<?php $_SESSION['previous_location'] = 'index.php' ?>

<aside>
    <h2>Aside</h2>
    <p>
        The aside will have information related to the current page or ads.
    </p>
</aside>
<main>

    <?php if(isset($_SESSION['user_id'])):?>
        <?php
        if(strcmp(getUserTypeFromUserID($_SESSION['user_id']), "student") == 0 ){ // user is a student
            if(isset($_SESSION['student_id'])){ // if the student has informations details
                $companiesAndStatuses = getCompaniesOfferFromStudentID($_SESSION['student_id']);

                echo "<strong>Company offers:</strong>";

                if(empty($companiesAndStatuses)){ // if there are no offers
                    echo "<p>there is no offered companies</p>";
                    echo "<a href=\"student.php\">go to student page</a>";
                }else{ // if there are offeres
                    echo "<ul>";
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
                    echo "</ul>";
                }



            }else{
                echo "<p>You have no student details</p>";
                echo "<a href=\"student.php\" >add details</a>";
            }

        }elseif(strcmp(getUserTypeFromUserID($_SESSION['user_id']), "company") == 0){// user is company
            if(isset($_SESSION['company_id'])){
                header("location:company.php");
            }else{
                $id = $_SESSION['user_id'];
                header("location:add-company.php?add_company_id=$id");
            }
        }    
            
            
        ?>



    <?php else:?>

        <h2>Login</h2>

        <form method="post" action="process.php" id="loginForm">
            <label>Username</label>
            <input type="text" id="username" name="username"/>
            <br/>
            <label>Password</label>
            <input type="password" id="password" name="password"/>
            <br/>
            <input type="submit" id="login" value="login"/>
        </form>

        <?php if(isset($_GET['error'])):?> <!-- if there is an invalid username or password -->
            <p class="error" > <?php $error = $_GET['error']; echo "$error";?> </p>
        <?php endif?>   

    <?php endif;?>

    

     
    
</main>
<?php include "parts/_footer.php" ?>

