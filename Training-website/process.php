<?php include "parts/_header.php" ?>

<?php
#login
if(isset($_POST['username']) && isset($_POST['password'])){

    if(chickUserLogin($_POST['username'], $_POST['password'])){ // if there is a user with this username and password

        updateUserLastHit($_POST['username']); // update the last hit to now date

        if(strcmp(getUserType($_POST['username']), 'student')==0){ // if user type is student

            session_start();

            $userInfo = getUserInfo($_POST['username']);

            $_SESSION['user_id'] = $userInfo['id'];
            $_SESSION['desplay_name'] = $userInfo['desplay_name'];
            $_SESSION['username'] = $userInfo['username'];

            if(isStudentHasId( $_SESSION['user_id'] )){
                $name = getStudentName($_POST['username']);
                $studentInfo = getStudentInfo($_POST['username']);
                $_SESSION['student_id'] = $studentInfo['student_id'];
            }

            header("location: index.php");
        }elseif(strcmp(getUserType($_POST['username']), 'company')==0){ // if user type is company

            session_start();

            $userInfo = getUserInfo($_POST['username']);

            $_SESSION['user_id'] = $userInfo['id'];
            $_SESSION['desplay_name'] = $userInfo['desplay_name'];
            $_SESSION['username'] = $userInfo['username'];

            if(isCompanyHasId($_SESSION['user_id'])){
                $companyInfo = getCompanyInfoFromUserId($_SESSION['user_id']);
                $_SESSION['company_id'] = $companyInfo['company_id'];
            }
            header("location: index.php");
        }
        
    }else{
        header("location: index.php?error=invalid username or password");
    }
}
?>

<?php 
#search for students
if(isset($_GET['studentMajor']) && isset($_GET['city'])){

    if(isMajorExist($_GET['studentMajor'])){ // major exist
        $_SESSION['allStudentInfo'] =  getStudentInfoFromcityAndMajor($_GET['city'],$_GET['studentMajor']);
        header("location: students.php");
    }else{
        $_SESSION['allStudentInfo'] = array();
        header("location: students.php");
    }
}
?>

<?php
#edit student information
if(isset($_FILES['personal_photo']) && isset($_POST['name']) && isset($_POST['city']) && isset($_POST['email']) && isset($_POST['tel']) &&
isset($_POST['university']) && isset($_POST['major']) && isset($_POST['project']) && isset($_POST['interested']) &&
isset($_GET['edit_Student_ID']) ){ // all info are posted
    $id = $_GET['edit_Student_ID'];

    //check if file empty
    if(empty($_FILES['personal_photo']['name'])){
        // edit all student informtion except the photo
        editStudentInfoWithoutImage($_GET['edit_Student_ID'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['university'], $_POST['major'], $_POST['project'], $_POST['interested']);
        header("location:student.php");
        
    }else{
        // chick if file uploaded ok
        if ($_FILES['personal_photo']["error"] == UPLOAD_ERR_OK) {// no error
            $newName = checkValidationPhotoFile($_FILES['personal_photo']['name'], $_FILES['personal_photo']['tmp_name'], $_FILES['personal_photo']['type'],  getUsernameFromStudentId($_GET['edit_Student_ID']) . "-photo", "student");
            if( strcmp( $newName,"error") != 0 ){
                // edit all student informtion
                editStudentInfoWithImage("students-photo/" . $newName, $_GET['edit_Student_ID'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['university'], $_POST['major'], $_POST['project'], $_POST['interested']);  
                header("location:student.php"); // go to syident page
            }else{
                // edit all student informtion except the photo
                editStudentInfoWithoutImage($_GET['edit_Student_ID'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['university'], $_POST['major'], $_POST['project'], $_POST['interested']);
                header("location:add-student?error=error on upload file&edit_Student_ID=$id");
            }


        }else{ // error
            // edit all student informtion except the photo
            editStudentInfoWithoutImage($_GET['edit_Student_ID'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['university'], $_POST['major'], $_POST['project'], $_POST['interested']);
            header("location:add-student?error=error on upload file&edit_Student_ID=$id");
        }
    }

    

    
} // end if
?>

<?php
#add new student information
if(isset($_FILES['personal_photo']) && isset($_POST['name']) && isset($_POST['city']) && isset($_POST['email']) && isset($_POST['tel']) &&
isset($_POST['university']) && isset($_POST['major']) && isset($_POST['project']) && isset($_POST['interested']) &&
isset($_GET['add_new_student_info_forUserID']) ){ // all info are posted

    if ($_FILES['personal_photo']["error"] == UPLOAD_ERR_OK) {// no error
        if(!empty($_FILES['personal_photo']) && !empty($_POST['name']) && !empty($_POST['city']) && !empty($_POST['email']) &&
        !empty($_POST['tel']) && !empty($_POST['university']) && !empty($_POST['major']) && !empty($_POST['project']) && !empty($_POST['interested'])){

            $newName = checkValidationPhotoFile($_FILES['personal_photo']['name'], $_FILES['personal_photo']['tmp_name'], $_FILES['personal_photo']['type']  , $_SESSION['username'] . "-photo", "student");
            if(strcmp($newName,"error") != 0 ){
                insertIntoStudentTable($_POST['name'], $_POST['city'], $_POST['email'], $_POST['tel'], $_POST['university'], $_POST['major'], $_POST['project'], $_POST['interested'], "students-photo/" . $newName, $_SESSION['user_id']);
                $_SESSION['student_id'] = getStudentIdFromUserId($_SESSION['user_id']);
                header("location:student.php"); // go to student page
            }else{
                $id = $_SESSION['user_id'];
                header("location:add-student.php?error=error on upload file&user_id=$id");
            }
        }else{
            $id = $_SESSION['user_id'];
            header("location:add-student.php?error=lake of some information&user_id=$id");
        }
    
    }else{
        $id = $_SESSION['user_id'];
        header("location:add-student.php?error=error on upload file&user_id=$id");
    }

    header("student.php");


}
?>
<?php
// chek nalidation of the file and move it to images folder with its new name
function checkValidationPhotoFile($file_name, $file_tmp_name, $file_type , $needed_fileName, $user_type){

    // limit the extention and file type
    $validExt = array("jpg", "png"); 
    $validMime = array("image/jpg","image/png");

    // chosen file extention
    $extension = end(explode(".", $file_name));

    // set file name
    $newName = $needed_fileName . "." . $extension;

    

    if (in_array($file_type, $validMime) && in_array($extension, $validExt)){

        $fileToMove = $file_tmp_name;
        $destination = null; // the destination needed to save new image
        if(strcmp( $user_type ,"student") == 0){ // the photo is for user of type student
            $destination = "students-photo/" . $newName;
        }elseif(strcmp($user_type, "company") == 0){// the photo is for user of type company
            $destination = "companies-logo/" . $newName;
        }
        

        if(file_exists($destination)){
            // delete file with the same name at the same folder (if exist)
            unlink($destination);
        }
        

        if (move_uploaded_file($fileToMove,$destination)) {
            return $newName;
        }
        return "error";
    }
    return "error";
}




?>

<?php
# student apply an application for training from the company
if(isset($_GET['student_id']) && isset($_GET['company_id']) && isset($_GET['action'])){
    if( strcmp($_GET['action'], "apply_an_application") == 0){
        $student_id = $_GET['student_id'];
        applyAnApplicationTraining($_GET['student_id'], $_GET['company_id']);
        header("location: students.php");
    }   
}
?>

<?php
#accept/reject an application for training
if(isset($_GET['application_status']) && isset($_GET['student_id']) && isset($_GET['company_id']) && isset($_GET['action'])){
    $student_id = $_GET['student_id']; // student id
    $company_id = $_GET['company_id']; // company id
    $status = $_GET['application_status']; // status
    if((strcmp($_GET['application_status'],"accept") == 0) && (  strcmp($_GET['action'],"accept_application_training") == 0)){ // accept
        updateApplicationStatus($student_id, $company_id, $status); // update
        header("location:student.php?studet_id=$student_id"); // go to student page
    }elseif((strcmp($_GET['application_status'],"reject") == 0) && (  strcmp($_GET['action'],"reject_application_training") == 0)){ // reject
        updateApplicationStatus($student_id, $company_id, $status); // update
        header("location:student.php?studet_id=$student_id"); // go to student page
    }
} // end if
?>


<?php
#search for companies
if(  isset($_GET['companyName'])  && isset($_GET['city'])){

    if(isCompanyNameExist($_GET['companyName'])){ // if the entered company name exist
        $_SESSION['companiesInformation'] = getAllCompaniesFromNameAndCityId($_GET['companyName'], $_GET['city']);
        header("location:companies.php");
    }else{// the name does not exist
        $_SESSION['companiesInformation'] = array();
        header("location:companies.php");
    }
    
}
?>


<?php
#edit company information
if(isset($_FILES['logo']) && isset($_POST['name']) && isset($_POST['city']) && isset($_POST['email']) && isset($_POST['tel']) &&
isset($_POST['positionCount']) && isset($_POST['positionDetails']) && isset($_GET['edit_company_id']) ){ // all info are posted

    $id = $_GET['edit_company_id'];

    //chech if file empty
    if(empty($_FILES['logo']['name'])){
        // edit all company informtion except the logo
        editCompanyInfoWithoutImage( $_GET['edit_company_id'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['positionCount'], $_POST['positionDetails']);
        header("location:company.php");
    }else{
        // chick if file uploaded ok
        if ($_FILES['logo']["error"] == UPLOAD_ERR_OK) {// no error
            $newName = checkValidationPhotoFile($_FILES['logo']['name'], $_FILES['logo']['tmp_name'], $_FILES['logo']['type'],  getCompanyNameFromCompanyId($_GET['edit_company_id']) . "-company-logo", "company");
            if( strcmp( $newName,"error") != 0 ){
                // edit all company informtion 
                editCompanyInfoWithImage("companies-logo/" . $newName, $_GET['edit_company_id'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['positionCount'], $_POST['positionDetails']);  
                header("location:company.php"); // do to company page
            }else{
                // edit all company informtion except the logo
                editCompanyInfoWithoutImage( $_GET['edit_company_id'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['positionCount'], $_POST['positionDetails']);
                header("location:add-company.php?error=error on upload file&edit_company_id=$id");
            }


        }else{ // error
            // edit all company informtion except the logo
            editCompanyInfoWithoutImage( $_GET['edit_company_id'], $_POST['name'], intval($_POST['city']), $_POST['email'], $_POST['tel'], $_POST['positionCount'], $_POST['positionDetails']);
            header("location:add-company.php?error=error on upload file&edit_company_id=$id");
        }
    }

    
} // end if
?>

<?php
#add new company information
if(isset($_FILES['logo']) && isset($_POST['name']) && isset($_POST['city']) && isset($_POST['email']) && isset($_POST['tel']) &&
isset($_POST['positionCount']) && isset($_POST['positionDetails']) && 
isset($_GET['add_new_company_info_forUserID']) ){ // all info are posted

    $id = $_GET['add_new_company_info_forUserID'];

    if ($_FILES['logo']["error"] == UPLOAD_ERR_OK) {// no error
        if(!empty($_FILES['logo']) && !empty($_POST['name']) && !empty($_POST['city']) && !empty($_POST['email']) &&
        !empty($_POST['tel']) && !empty($_POST['positionCount']) && !empty($_POST['positionDetails'])){

            $newName = checkValidationPhotoFile($_FILES['logo']['name'], $_FILES['logo']['tmp_name'], $_FILES['logo']['type']  , $_SESSION['username'] . "-ocmpany-logo", "company");
            if(strcmp($newName,"error") != 0 ){


                insertIntoCompanyTable($_POST['name'], $_POST['city'], $_POST['email'], $_POST['tel'], $_POST['positionCount'], $_POST['positionDetails'], "companies-logo/" . $newName, $_SESSION['user_id']);
                
                
                $_SESSION['company_id'] = getCompanyIdFormUserId($_SESSION['user_id']);
                header("location:company.php"); // go to student page

            }else{
                header("location:add-company.php?error=error on upload file&add_company_id=$id");
            }
        }else{
            header("location:add-company.php?error=lake of some information&add_company_id=$id");
        }
    
    }else{
        $id = $_SESSION['user_id'];
        header("location:add-company.php?error=error on upload file&user_id=$id");
    }
}
?>



<main>
    <h2>Form input from page <?php echo basename($_SERVER["HTTP_REFERER"]); ?></h2>
    
    <h3>GET Data</h3>
    <?php
    if (count($_GET) == 0) {
        echo "<p><em>There are no GET variables</em></p>";
    } else {
        foreach ($_GET as $key => $value) {
            echo "<strong>" . $key . " = </strong>" . $value . "<br/>\n";
        
            if (is_array($value)) {
                for ($i = 0; $i < count($value); $i ++) {
                    echo "--Index " . $i . " Selected value=" . $value[$i] . "<br/>";
                }
            }
        }
    }
    ?>
    
    <h3>POST Data</h3>
    <?php
    if (count($_POST) == 0) {
        echo "<p><em>There are no POST variables</em></p>";
    } else {
        foreach ($_POST as $key => $value) {
            echo "<strong>" . $key . " = </strong>" . $value . "<br/>\n";
        
            if (is_array($value)) {
                for ($i = 0; $i < count($value); $i ++) {
                    echo "--Index " . $i . " Selected value=" . $value[$i] . "<br/>";
                }
            }
        }
    }
    ?>
</main>
<aside>
    <h2>Paga Parameters Testing</h2>
    <p>
        This page will get the parameters from GET or POST and display them...
    </p>
</aside>
<?php include "parts/_footer.php" ?>
