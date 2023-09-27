<?php
    $query = "select * from inv_pinremarksstatus";
    $result = runmysqlquery($query);
    while($fetch = mysqli_fetch_array($result))
    {
        echo '<option value="'.$fetch['id'].'">'.$fetch['status'].'</option>';
    }
?>
