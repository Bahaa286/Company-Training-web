<?php include "parts/_header.php" ?>

<?php $_SESSION['previous_location'] = 'add-student.php' ?>
<aside>
    <h2>Help</h2>
    <p>
        Add your student details including projects and interests so that companies can select you...
    </p>
</aside>
<main>
    <?php
    $id = 0;
    if(isset($_GET['edit_Student_ID'])){ // edit info
        $id = $_GET['edit_Student_ID'];
        echo "<h2>Edit Student</h2>";

        // add form with get edit_student_ID
        echo "<form method=\"post\" action=\"process.php?edit_Student_ID=$id\" enctype=\"multipart/form-data\">";

        if($id != $_SESSION['student_id']){
            header("location: student.php");
        }

    }elseif(isset($_GET['user_id'])){ // add new info
        echo "<h2>Add Student</h2>";
        $id = $_GET['user_id'];

        // add form with get add_new_student_info_forUserID
        echo "<form method=\"post\" action=\"process.php?add_new_student_info_forUserID=$id\" enctype=\"multipart/form-data\">";

        if($id != $_SESSION['user_id']){
            $id = $_SESSION['user_id'];
            header("location: add-student.php?user_id=$id");
        }
    }
    
    ?>

        <table>
            <tr>
                <td><label>Personal Photo</label></td>
                <td><input type="file" name="personal_photo" id="personal_photo"/></td>
            </tr>

            <tr>
                <td><label>Name</label></td>
                <td><input type="text" id="name" name="name"/></td>
            </tr>

            <tr>
                <td><label>City</label></td>
                <td>
                    <select id="city" name="city">

                    <?php $allCitiesDetails = getAllCitiesDatails(); foreach($allCitiesDetails as $row): ?>

                    <?php $name = $row['name'];?>
                    <?php $id = $row['id'];?>
                    <option value="<?php echo "$id";?>" name="<?php echo "$name";?>"> <?php echo "$name";?> </option>

                    <?php endforeach;?> 

                    </select>
                </td>
            </tr>

            <tr>
                <td><label>Email</label></td>
                <td><input type="text" id="email" name="email"/></td>
            </tr>

            <tr>
                <td><label>Tel</label></td>
                <td><input type="text" id="tel" name="tel"/></td>
            </tr>

            <tr>
                <td><label>Uneversity</label></td>
                <td><input type="text" id="university" name="university"/></td>
            </tr>

            <tr>
                <td><label>Major</label></td>
                <td><input type="text" id="major" name="major"/></td>
            </tr>

            <tr>
                <td><label>Project</label></td>
                <td><textarea id="projects" name="project" cols="50" rows="5"></textarea></td>
            </tr>

            <tr>
                <td><label>Interests</label></td>
                <td><textarea id="interests" name="interested" cols="50" rows="5"></textarea></td>
            </tr>
        </table>

        <div>

            <?php if(isset($_GET['edit_Student_ID'])):?>
                <input type="submit" id="addStudent" value="Edit"/>
            <?php elseif(isset($_GET['user_id'])):?>
                <input type="submit" id="addStudent" value="Add Student"/>
            <?php endif;?>        

            <button type="reset" id="clear">Clear</button>
        </div>

        <div>
            <a href="students.php">Cancel and return to Students List</a>
        </div>
    </form>

    <?php
    // if there is an invalid information
    if(isset($_GET['error'])){
        $error = $_GET['error'];
        echo "<p class=\"error\">$error</p>";
    }
    
    
    ?>
</main>

<?php include "parts/_footer.php" ?>

