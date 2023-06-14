<?php include "parts/_header.php" ?>

<?php $_SESSION['previous_location'] = 'company.php.php' ?>

<aside>
    <h2>Similar Companies</h2>
    <p>
        Another companies in same location looking for students...
    </p>
</aside>

<main>

    <?php
    $companyInfo = array();
    if(isset($_GET['company_id'])){
        $companyInfo = getCompanyInfoFromCompanyId($_GET['company_id']);
    }elseif(isset($_SESSION['company_id'])){
        $companyInfo = getCompanyInfoFromCompanyId($_SESSION['company_id']);
    }
    ?>

    <?php if(!empty($companyInfo)):?>
        <h2><?php echo $companyInfo['company_name'];?></h2>

        <img src="<?php echo $companyInfo['logo_path'];?>" alt="company image"/>

        <dl>

            <dt><strong>City:</strong></dt>
            <dd><?php echo $companyInfo['city'];?></dd>

            <dt><strong>Email:</strong></dt>
            <dd><a href="<?php echo $companyInfo['email'];?>" ><?php echo $companyInfo['email'];?></a></dd>

            <dt><strong>Tel:</strong></dt>
            <dd><a href="<?php echo $companyInfo['tel'];?>" ><?php echo $companyInfo['tel'];?></a></dd>

            <dt><strong>Option position:</strong></dt>
            <dd><?php echo $companyInfo['open_position'];?></dd>

            <dt><strong>Position Details:</strong></dt>
            <dd><?php echo $companyInfo['position_details'];?></dd>

        </dl>

        <div>
            <a href="companies.php" >back to companies list</a>

            <?php if(isset($_SESSION['company_id'])):?>
                <?php if(isset($_GET['company_id'])){
                    if($_SESSION['company_id'] == $_GET['company_id']){
                        $id = $_SESSION['company_id'];
                        echo "<a href=\"add-company.php?edit_company_id=$id\" >Edit</a>";
                    }
                }else{
                    $id = $_SESSION['company_id'];
                    echo "<a href=\"add-company.php?edit_company_id=$id\" >Edit</a>";
                }    
                ?>
            <?php endif;?>
        </div> 

    <?php else:?>
        <?php
            $user_id = $_SESSION['user_id']; 
            header("location:add-company.php?add_company_id=$user_id");
        ?>
    <?php endif;?>

    
    
</main>

<?php include "parts/_footer.php" ?>

