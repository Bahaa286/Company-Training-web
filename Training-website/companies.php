<?php include "parts/_header.php" ?>

<?php $_SESSION['previous_location'] = 'companies.php' ?>

<aside>
    <h2>Highlighted Company</h2>
    <p>
        This will contain a random special company details...
    </p>
</aside>

<main>
    <h2>Companies List</h2>

    <form method="get" action="process.php" id="studentForm">
        <label>Company Name:</label>
        <input type="search" id="companyName" name="companyName"/>
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

    <?php 
    $companiesInfo = array();
    if(isset($_SESSION['companiesInformation'])){ // information from search
        $companiesInfo = $_SESSION['companiesInformation'];
    }else{ // all information from database
        $companiesInfo = getAllCompanies();
    }
    unset($_SESSION['companiesInformation']);
    ?>

    <?php if(!empty($companiesInfo)):?>

        <table>

            <tr>
                <th>logo</th>
                <th>Name</th>
                <th>City</th>
                <th>Open Positions</th>

            </tr>

            
                
            <?php foreach($companiesInfo as $row):?>
                <tr>
                    <td> <img src="<?php  echo $row['logo_path']; ?>" alt="company image" /> </td>
                    <td><a href="company.php?company_id=<?php echo $row['company_id'];?>"><?php echo $row['company_name']; ?></a></td>
                    <td><?php echo $row['city'];?></td>
                    <td><?php echo $row['open_position'];?></td>
                </tr>
                
            <?php endforeach;?> 
                
            

        </table>

    <?php else:?>
        <?php echo "<p> no companies information </p>";?>

    <?php endif;?>
    
</main>

<?php include "parts/_footer.php" ?>

