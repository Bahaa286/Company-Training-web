<!DocType html>
<?php include "_db.php"?>

<html lang="en">
    <head>
        <link rel="stylesheet" href="css/layout.css" />
        <link rel="stylesheet" href="css/website.css" />
        <title>Student Training</title>
    </head>
    <body>
        <header>
            <img src="images/training.png" alt="logo" />
            <h1>Student Training</h1>
            <nav>
                <?php 
                session_start(); // start session
                $user_type = null;

                if(isset($_SESSION['user_id'])){
                    $user_type = getUserTypeFromUserID($_SESSION['user_id']);
                }
                
                
                ?>
                <?php if(  strcmp($user_type, "student") == 0  ):?> <!-- if user is student -->

                    <a href="logout.php">logout</a>

                    <?php if(isset($_SESSION['student_id'])):  ?><!-- if the student has a student id and information in details -->
                        <a href="student.php">welcome <?php echo getStudentName($_SESSION['username']);?> </a>
                    <?php else:?><!-- new student without student details -->
                        <a href="student.php">welcome <?php echo $_SESSION['username'];?> </a>
                    <?php endif;?>

                    
                    <a href="students.php">Students List</a>
                    <a href="companies.php">Companies List</a>
                <?php elseif(strcmp($user_type, "company") ==0 ):?> <!-- if the user is company -->

                    <a href="logout.php">logout</a>

                    <?php
                    if(isset($_SESSION['company_id'])){
                        $name = getCompanyNameFromCompanyId($_SESSION['company_id']);
                        echo "<a href=\"company.php\">Welcome $name company</a>";
                    }else{
                        $username = $_SESSION['username'];
                        echo "<a href=\"company.php\">welcome $username company</a>";
                    }    
                    ?>
                        
                    <a href="students.php">Students List</a>
                    <a href="companies.php">Companies List</a>
                <?php else:?>
                    <a href="index.php">Home</a>
                <?php endif;?>
                
            </nav>
        </header>
