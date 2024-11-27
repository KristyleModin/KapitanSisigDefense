<?php

if(isset($_SESSION['loggedIn'])){
    $username = validate($_SESSION['loggedInUser']['username']);

    $query = "SELECT * FROM admins WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 0) {
        logoutSession();
        redirect('../login.php', 'Access denied!');
    } else {
        $row = mysqli_fetch_assoc($result);
        if($row['is_banned'] == 1) {
            logoutSession();
            redirect('../login.php', 'Your account has been banned. Contact your administrator.');
        }
    }
} else {
    redirect('../login.php', 'Log in to continue.');
}

?>