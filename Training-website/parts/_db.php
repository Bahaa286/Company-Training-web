<?php
$dbhost = 'localhost';
$dbusername = 'root';
$dbuserpassword = '22446688@123';
$default_dbname = 'Training';
$MYSQL_ERRNO = '';
$MYSQL_ERROR = '';


function db_connect($dbname = '', $username = '', $password = ''){
    global $dbhost, $dbusername, $dbuserpassword, $default_dbname;
    global $MYSQL_ERRNO, $MYSQL_ERROR;

    try {

        if (empty($dbname)) {
            $dbname = $default_dbname;
        }

        if (empty($username)){
            $username=$dbusername;
        }

        if (empty($password)){
            $password = $dbuserpassword;
        }

        /*
        * Create the pdo object
        * host: is the host name
        * dbname: database name
        */

        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $dbuserpassword);

        return $pdo;

    } catch (PDOException $e) {
        $MYSQL_ERRNO = 0;
        $MYSQL_ERROR = $e->getMessage();
        return 0;
    }
}

# execute query for select function
function execute($query){
    $pdo = db_connect();
    $dbResults = $pdo->query($query);

    return  $dbResults;
}

# execute query for update function
function executeUPDATE($sql){
    $pdo = db_connect();
    return $pdo->prepare($sql);
}

# check if the username and password maches it in the database
function chickUserLogin($username, $password): bool{
    $query = "SELECT username, password FROM user WHERE username = \"$username\" AND password = SHA1(\"$password\")";

    $result = execute($query);

    return !empty($result->fetch());
}

// get user info 
function getUserInfo($username){
    // get user information query
    $query = "SELECT * FROM user WHERE username = \"$username\";";
    $result = execute($query); // execute
    return $result->fetch(); // return array of information
}

// get usernamr from student id
function getUsernameFromStudentId($student_id){
    // get username from student id query
    $query = "SELECT U.username FROM user U, student S WHERE S.user_id = U.id AND S.id = $student_id;";
    $result = execute($query); // execute
    return $result->fetch()['username']; // return username

}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
# return student name from username
function getStudentName($username){
    $query = "SELECT name FROM user U, student S WHERE S.user_id = U.id AND U.username = \"$username\";";

    $result = execute($query);

    $name = $result->fetch();
    
    return $name['name'];
}

# return student name from student_id
function getStudentNameFromID($student_id){

    $query = "SELECT name FROM user U, student S WHERE S.user_id = U.id AND S.id = \"$student_id\";";

    $result = execute($query);

    $name = $result->fetch();
    
    return $name['name'];
}

// get student id from user id
function getStudentIdFromUserId($user_id){
    $query = "SELECT S.id FROM student S, user U WHERE S.user_id = U.id AND U.id = $user_id;";
    $result = execute($query);
    return $result->fetch()['id'];

}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
# return all student informaion from student id
function getStudentInfoFromSID($student_id){
    $query = "SELECT S.id as student_id, S.name as student_name, S.email, S.tel, S.university, S.major, S.projects, S.interests, S.photo_path, U.id as user_id, U.username, U.password, U.desplay_name, U.last_hit, C.name as city_name, C.country FROM student S, user U, city C WHERE S.user_id = U.id AND C.id = S.city_id AND S.id = \"$student_id\";";

    $result = execute($query);
    return $result->fetch();
}

# return all student informaion from username
function getStudentInfo($username){
    // get studet information based on username query
    $query = "SELECT S.id as student_id, S.name as student_name, S.email, S.tel, S.university, S.major, S.projects, S.interests, S.photo_path, U.id as user_id, U.username, U.password, U.desplay_name, U.last_hit, C.name as city_name, C.country FROM student S, user U, city C WHERE S.user_id = U.id AND C.id = S.city_id AND U.username = \"$username\";    ";
    $result = execute($query); // execute
    return $result->fetch(); // return result
}

# return all students informaion from city id and major
function getStudentInfoFromcityAndMajor($city_id, $major){
    // get studet information based on city and major query
    $query = "SELECT S.id as student_id, S.name as student_name, S.email, S.tel, S.university, S.major, S.projects, S.interests, S.photo_path, U.id as user_id, U.username, U.password, U.desplay_name, U.last_hit, C.name as city_name, C.country FROM student S, user U, city C WHERE S.user_id = U.id AND C.id = S.city_id AND S.city_id = \"$city_id\" AND S.major = \"$major\";";
    $result = execute($query); // execute
    return $result->fetchAll(); // return result
}

# get all students information
function getAllStudentsInfo(){

    // get student information query
    $query = "SELECT S.id as student_id, S.name as student_name, S.email, S.tel, S.university, S.major, S.projects, S.interests, S.photo_path, U.id as user_id, U.username, U.password, U.desplay_name, U.last_hit, C.name as city_name, C.country FROM student S, user U, city C WHERE S.user_id = U.id AND C.id = S.city_id";
    $result = execute($query); // execute
    return $result->fetchAll(); // return result
}  

// check if student has id
function isStudentHasId($user_id): bool{
    // get number of student which have user id in the student table (number = 0 or 1)
    $query = "SELECT COUNT(*) FROM user U, student S WHERE U.id = S.user_id AND U.id = $user_id;";
    $result = execute($query); // execute
    return $result->fetch()['COUNT(*)'] > 0; // return true if count larger than 1
}



//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
# get user type (student/company)
function getUserType($username){
    $query = "SELECT user_type FROM user WHERE username = \"$username\";"; // get user type query

    $result = execute($query); // get the resukt
    $userType = $result->fetch(); // get type from result

    return $userType['user_type']; //return user type
}

# get user type (student/company)
function getUserTypeFromUserID($id){
    $query = "SELECT user_type FROM user WHERE id = $id;"; // get user type query

    $result = execute($query); // get the resukt
    $userType = $result->fetch(); // get type from result

    return $userType['user_type']; //return user type
}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

#get all cities details
function getAllCitiesDatails(){
    $query = "SELECT * FROM city;"; // select query
    $result = execute($query); // get result
    return $result->fetchAll(); // return all information as double array
}

#chick if the major exest in the database
function isMajorExist($major){
    $query = "SELECT COUNT(*) FROM student WHERE UPPER(major)=UPPER(\"$major\");"; // count query
    $result = execute($query); // get the result
    $count = $result->fetch()['COUNT(*)']; // get the count number
    return $count > 0; // the major exest if there is count result larger than zero 
}

#get user id from company id
function getUserIdFromCompanyId($company_id){
    // get user id from company id query
    $query = "SELECT user_id FROM company WHERE id = $company_id;";
    $result = execute($query); // get the result
    return $result->fetch()['user_id'];

}

// get user id from username
function getUserIdFromUsername($username){
    // get user id from usernme query
    $query = "SELECT id FROM user WHERE username = \"$username\";";
    $result = execute($query); // get the result
    return $result->fetch()['id'];
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
# get comapnies offer for one student
function getCompaniesOfferFromStudentID($id){
    // select company name and application status query
    $query = "SELECT SA.apply_date as offer_date ,C.name as company_name, SA.application_status as status FROM student_applications SA, student S, company C WHERE SA.student_id = S.id AND SA.company_id = C.id AND S.id = $id ORDER BY SA.apply_date ASC;";
    $result = execute($query); // get the result
    return  $result->fetchAll(); // return all companies and statuses
} 

// check if company offer student before
function isCompanyOfferStudentBefore($student_id, $company_id): bool{
    // get number of offered time of specific company for specific user
    $query = "SELECT COUNT(*) FROM student_applications WHERE student_id = $student_id AND company_id = $company_id;";
    $result = execute($query); // get the result
    $count = $result->fetch()['COUNT(*)']; // get the count number
    return $count > 0; // company offer student before of the count larger than zero
}

// apply an application training
function applyAnApplicationTraining($student_id, $company_id){
    $date = date('y-m-d'); // get now date
    // insert into student_applications table query
    $query = "INSERT INTO student_applications(student_id, company_id, apply_date, requested_by_user_id) VALUES (:student_id, :company_id, :apply_date, :request_by_user_id);";
    $statement = executeUPDATE($query); // execute and prepare query
    // execute statement
    $statement->execute(array(':student_id'=>$student_id, ':company_id'=>$company_id, ':apply_date'=>$date,':request_by_user_id'=>getUserIdFromCompanyId($company_id)));
}

// update application training status
function updateApplicationStatus($student_id, $company_id, $status){
    // update application status query
    $query = "UPDATE student_applications SET application_status = :status WHERE student_id = :student_id AND company_id = :company_id;";
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':status'=>$status, 'student_id'=>$student_id, 'company_id'=>$company_id));  
}

// get application status
function getApplicationStatus($student_id, $company_id){
    // get application status query
    $query = "SELECT application_status as status FROM student_applications WHERE student_id = $student_id AND company_id = $company_id;";
    $result = execute($query); // get the result
    return  $result->fetch()['status'];
}

//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
#get company id from name
function getCompanyIdFromCompanyName($name){
    // get company id from company name query
    $query = "SELECT id FROM company WHERE name = \"$name\";";
    $result = execute($query); // get the result
    return $result->fetch()['id'];
}

// get company id from user id
function getCompanyIdFormUserId($id){
    // get company id from user id query
    $query = "SELECT C.id FROM company C, user U WHERE C.user_id = U.id AND U.id = $id;";
    $result = execute($query); // get the result
    return $result->fetch()['id'];
}

// get all companies
function getAllCompanies(){
    // get all companies information query
    $query = "SELECT CM.id as company_id, CM.name as company_name, CM.logo_path, CT.name as city, CM.email, CM.tel, CM.position_count as open_position, CM.position_details FROM company CM , city CT WHERE CM.city_id = CT.id;";
    $result = execute($query); // get the result
    return $result->fetchAll();
}

// get all companies from name and city id
function getAllCompaniesFromNameAndCityId($name, $city_id){
    // get all companies with sepicific name and city
    $query = "SELECT CM.id as company_id, CM.name as company_name, CM.logo_path, CT.name as city, CM.email, CM.tel, CM.position_count as open_position, CM.position_details FROM company CM , city CT 
        WHERE CM.city_id = CT.id AND UPPER(CM.name) = UPPER(\"$name\") AND CT.id = $city_id;";
        $result = execute($query); // get the result
        return $result->fetchAll();
}

// get company info from user id
function getCompanyInfoFromCompanyId($id){
    // get company information from user id query
    $query = "SELECT CM.id as company_id, CM.name as company_name, CM.logo_path, CT.name as city, CM.email, CM.tel, CM.position_count as open_position, CM.position_details FROM company CM , city CT
    WHERE CM.city_id = CT.id  AND CM.id = $id;";
    $result = execute($query); // get the result
    return $result->fetch(); // return 
}

// get company info form user id
function getCompanyInfoFromUserId($user_id){
    // get company info form user id query 
    $query = "SELECT CM.id as company_id, CM.name as company_name, CM.logo_path, CT.name as city, CM.email, CM.tel, CM.position_count as open_position, CM.position_details FROM company CM , city CT, user U
    WHERE CM.city_id = CT.id  AND CM.user_id = U.id AND U.id = $user_id";
    $result = execute($query); // get the result
    return $result->fetch(); // return 
}

// is company name exist
function isCompanyNameExist($name):bool{
    // count companies with this name query (count will be 0 or 1)
    $query = "SELECT COUNT(*) FROM company WHERE UPPER(name) = UPPER(\"$name\");";
    $result = execute($query); // execute
    return $result->fetch()['COUNT(*)'] > 0; 
}

// get company name from company id
function getCompanyNameFromCompanyId($id){
    // get company name from company id query
    $query = "SELECT name FROM company WHERE id = $id;";
    $result = execute($query); // execute
    return $result->fetch()['name']; // return company name

}

// check if the compan yhas details
function isCompanyHasId($user_id){
    // get number of company which have user id in the company table (number = 0 or 1)
    $query = "SELECT COUNT(*) FROM user U, company C WHERE U.id = C.user_id AND U.id = $user_id;";
    $result = execute($query); // execute
    return $result->fetch()['COUNT(*)'] > 0; // return true if count larger than 1

}
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

# update user last hit to the current date
function updateUserLastHit($username){
    $date = date('y-m-d'); // get now date
    $query = "UPDATE user SET last_hit = :date WHERE username = :username;"; // update last hit query

    // execute
    $statement = executeUPDATE($query); 
    $statement->execute(array(':date'=>$date, ':username'=>$username));
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// edit student information

function editStudentInfoWithoutImage($id, $name, $city, $email, $tel, $university, $major, $project, $interested){

    if(!empty($name)){ // if name is not empty
        updateStudentName($id,$name);
    }

    if(!empty($city)){ // if city is not empty
        updateStudentCity($id,$city);
    }

    if(!empty($email)){ // if email is not empty
        updateStudentEmail($id,$email);
    }

    if(!empty($tel)){ // if tel is not empty
        updateStudentTel($id,$tel);
    }

    if(!empty($university)){ // if university is not empty
        updateStudentUniversity($id,$university);
    }

    if(!empty($major)){ // if major is not empty
        updateStudentMajor($id,$major);
    }

    if(!empty($project)){ // if project is not empty
        updateStudentProject($id,$project);
    }

    if(!empty($interested)){ // if interested is not empty
        updateStudentInterested($id,$interested);
    }
}

function editStudentInfoWithImage($photo_path, $id, $name, $city, $email, $tel, $university, $major, $project, $interested){

    // edit all information except image
    editStudentInfoWithoutImage($id, $name, $city, $email, $tel, $university, $major, $project, $interested);

    if(!empty($photo_path)){ // if photo_path is not empty
        updateStudentPhoto_path($id,$photo_path);
    }

}

// udpate photo_path
function updateStudentPhoto_path($id, $photo_path){ 
    $query = "UPDATE student SET photo_path = :photo_path WHERE id = :id;"; // update photo_path query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':photo_path'=>$photo_path, ':id'=>$id));
}

// udpate interested
function updateStudentInterested($id, $interested){ 
    $query = "UPDATE student SET interests = :interested WHERE id = :id;"; // update interested query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':interested'=>$interested, ':id'=>$id));
}

// udpate project
function updateStudentProject($id, $project){ 
    $query = "UPDATE student SET projects = :project WHERE id = :id;"; // update project query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':project'=>$project, ':id'=>$id));
}

// udpate major
function updateStudentMajor($id, $major){ 
    $query = "UPDATE student SET major = :major WHERE id = :id;"; // update major query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':major'=>$major, ':id'=>$id));
}

// udpate university
function updateStudentUniversity($id, $university){ 
    $query = "UPDATE student SET university = :university WHERE id = :id;"; // update university query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':university'=>$university, ':id'=>$id));
}

// udpate tel
function updateStudentTel($id, $tel){ 
    $query = "UPDATE student SET tel = :tel WHERE id = :id;"; // update tel query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':tel'=>$tel, ':id'=>$id));
}

// udpate email
function updateStudentEmail($id, $email){ 
    $query = "UPDATE student SET email = :email WHERE id = :id;"; // update email query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':email'=>$email, ':id'=>$id));
}

// udpate city
function updateStudentCity($id, $city){ 
    $query = "UPDATE student SET city_id = :city WHERE id = :id;"; // update city query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':city'=>$city, ':id'=>$id));
}

// udpate name
function updateStudentName($id, $name){ 
    $query = "UPDATE student SET name = :name WHERE id = :id;"; // update name query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':name'=>$name, ':id'=>$id));
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
# add new information for student
function insertIntoStudentTable($name, $city_id, $email, $tel, $university, $major, $projects, $interests, $photo_path, $user_id){
    // insert into student table query
    $query = "INSERT INTO student(name, city_id, email, tel, university, major, projects, interests, photo_path, user_id)
    VALUES (:name, :city_id, :email, :tel, :university, :major, :projects, :interests, :photo_path, :user_id)";
    // execute
    $statement = executeUPDATE($query);
    $statement->execute(array(':name'=>$name, ':city_id'=>$city_id, ':email'=>$email, ':tel'=>$tel, ':university'=>$university, ':major'=>$major,
    ':projects'=>$projects, ':interests'=>$interests, ':photo_path'=>$photo_path, ':user_id'=>$user_id));
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
# add new information for comapny
function insertIntoCompanyTable( $name, $city_id, $email, $tel, $position_count, $position_details, $logo_path, $user_id){
    // insert into company table query
    $query = "INSERT INTO company(name, city_id, email, tel, position_count, position_details, logo_path, user_id)
    VALUES (:name, :city_id, :email, :tel, :position_count, :position_details, :logo_path, :user_id)";
    // execute
    $statement = executeUPDATE($query);

    $statement->execute(array(':name'=>$name, ':city_id'=>$city_id, ':email'=>$email, ':tel'=>$tel, ':position_count'=>$position_count, ':position_details'=>$position_details,
    ':logo_path'=>$logo_path, ':user_id'=>$user_id));
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
function editCompanyInfoWithoutImage($id, $name, $city, $email, $tel, $positionCount, $positionDetails){

    if(!empty($name)){ // if name is not empty
        updateCompanyName($id,$name);
    }

    if(!empty($city)){ // if city is not empty
        updateCompanyCity($id,$city);
    }

    if(!empty($email)){ // if email is not empty
        updateCompanyEmail($id,$email);
    }

    if(!empty($tel)){ // if tel is not empty
        updateCompanyTel($id,$tel);
    }

    if(!empty($positionCount)){ // if positionCount is not empty
        updateCompanyPositionCount($id,$positionCount);
    }

    if(!empty($positionDetails)){ // if positionDetails is not empty
        updateCompanyPositionDetails($id,$positionDetails);
    }
}

function editCompanyInfoWithImage($logo_path, $id, $name, $city_id, $email, $tel, $position_count, $position_details){

    // edit all information except image
    editCompanyInfoWithoutImage($id, $name, $city_id, $email, $tel, $position_count, $position_details);

    if(!empty($logo_path)){ // if photo_path is not empty
        updateCompanyLogo_path($id,$logo_path);
    }

}

// update logo_path
function updateCompanyLogo_path($id, $logo_path){
    $query = "UPDATE company SET logo_path = :logo_path WHERE id = :id;"; // update logo_path query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':logo_path'=>$logo_path, ':id'=>$id));
}

// update position_details
function updateCompanyPositionDetails($id,$position_details){
    $query = "UPDATE company SET position_details = :position_details WHERE id = :id;"; // update position_details query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':position_details'=>$position_details, ':id'=>$id));
}

// update position_count
function updateCompanyPositionCount($id,$position_count){
    $query = "UPDATE company SET position_count = :position_count WHERE id = :id;"; // update position_count query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':position_count'=>$position_count, ':id'=>$id));
}

// update tel
function updateCompanyTel($id,$tel){
    $query = "UPDATE company SET tel = :tel WHERE id = :id;"; // update tel query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':tel'=>$tel, ':id'=>$id));
}

// update email
function updateCompanyEmail($id,$email){
    $query = "UPDATE company SET email = :email WHERE id = :id;"; // update email query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':email'=>$email, ':id'=>$id));
}

// update city_id
function updateCompanyCity($id,$city_id){
    $query = "UPDATE company SET city_id = :city_id WHERE id = :id;"; // update city_id query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':city_id'=>$city_id, ':id'=>$id));
}

// update name
function updateCompanyName($id,$name){
    $query = "UPDATE company SET name = :name WHERE id = :id;"; // update name query
    $statement = executeUPDATE($query); // execute
    $statement->execute(array(':name'=>$name, ':id'=>$id));
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//


?>