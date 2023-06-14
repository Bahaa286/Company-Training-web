<?php include "parts/_header.php" ?>

<aside>
    <h2>Distinguished Students</h2>
    <p>
        Student Ali Ahmad from Birzeit is very special and he is looking for training in Computer Science.
    </p>
</aside>

<main>
    <h2>Students List</h2>
    
    <form method="get" action="process.php" id="studentForm">
        <label>Student study major:</label>
        <input type="search" id="studentMajor" name="studentMajor"/>
        <label>City:</label>
        <select id="city" name="city">

            <?php $allCitiesDetails = getAllCitiesDatails(); foreach($allCitiesDetails as $row): ?>
                <?php $name = $row['name'];?>
                <?php $id = $row['id'];?>
                <option value="<?php echo "$id";?>" name="<?php echo "$name";?>"> <?php echo "$name";?> </option>

            <?php endforeach;?>    
        </select>
        <button type="submit" id="search">search</button>
    </form>

    <?php if(isset($_SESSION['allStudentInfo'])):?>
        <?php $allStudentsInfo = $_SESSION['allStudentInfo']; ?>
    <?php else:?>
        <?php $allStudentsInfo = getAllStudentsInfo(); ?>
    <?php endif; ?>   
    <?php unset($_SESSION['allStudentInfo']); ?>

    <?php if(!empty($allStudentsInfo)):?>
        <table>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>City</th>
                <th>University</th>
                <th>Major</th>

            </tr>
             
            
            <?php foreach($allStudentsInfo as $row):?>

            <?php
            $photoPath = $row['photo_path'];
            $studentName = $row['student_name'];
            $city = $row['city_name'];
            $university = $row['university'];
            $major = $row['major'];    
            ?>

                <tr>
                    <td> <img src= "<?php echo "$photoPath";?>" alt="student image"/> </td>
                    <td> <a href="student.php?student_id=<?php $student_id = $row['student_id'];echo "$student_id";?> " ><?php echo "$studentName";?></a> </td>
                    <td> <?php echo "$city"?> </td>
                    <td> <?php echo "$university"?> </td>
                    <td> <?php echo "$major"?> </td>
                </tr>

            <?php endforeach; ?>
                    
        </table>

    <?php else:?>
        <p>no students information</p>
    
    <?php endif;?> 
    
</main>

<?php include "parts/_footer.php" ?>

