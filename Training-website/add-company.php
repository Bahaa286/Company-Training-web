<?php include "parts/_header.php" ?>

<?php $_SESSION['previous_location'] = 'add-company.php' ?>

<aside>
    <h2>Help</h2>
    <p>
        Add company and positions details so that students can find you...
    </p>
</aside>

<main>

    <?php


    if(isset($_GET['edit_company_id'])){ // edit information
        $id = $_GET['edit_company_id'];
        echo "<h2>Edit Company</h2>";

        // add form with get edit_company_id
        echo "<form method=\"post\" action=\"process.php?edit_company_id=$id\" enctype=\"multipart/form-data\">";

        if($id != $_SESSION['company_id']){
            header("location: company.php");
        }

    }elseif(isset($_GET['add_company_id'])){ // add new inofrmation
        echo "<h2>Add Company</h2>";
        $id = $_GET['add_company_id']; // user id for the company

        // add form with get add_new_student_info_forUserID
        echo "<form method=\"post\" action=\"process.php?add_new_company_info_forUserID=$id\" enctype=\"multipart/form-data\">";

        if($id != $_SESSION['user_id']){
            $id = $_SESSION['user_id'];
            header("location: add-company.php?user_id=$id");
        }
    }



    ?>

        <table>
            <tr>
                <td><label>Logo</label></td>
                <td><input type="file" name="logo" id="logo"/></td>
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
                <td><label>Position Count</label></td>
                <td><input type="text" id="positionCount" name="positionCount"/></td>
            </tr>

            <tr>
                <td><label>Position Details</label></td>
                <td><textarea id="positionDetails" name="positionDetails" cols="50" rows="5"></textarea></td>
            </tr>
        </table>

        <div>
            <?php if(isset($_GET['edit_company_id'])):?>
                <input type="submit" id="editCompany" value="Edit"/>
            <?php elseif(isset($_GET['add_company_id'])):?>
                <input type="submit" id="addCompany" value="Add Company"/>
            <?php endif;?>

            <button type="reset" id="clear">Clear</button>
        </div>

    </form>

    <?php
    if(isset($_GET['error'])){
        $error = $_GET['error'];
        echo "<p class=\"error\">$error</p>";
    }
    
    ?>
    
</main>

<?php include "parts/_footer.php" ?>

