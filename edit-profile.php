<?php
require_once("config.php");
$db = get_pdo_connection();
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//DELETE THE LINE ABOVE TO REVEAL ERRORS WHEN NEEDED
if (isset($_POST['bio'])) {
    $bio = $_POST['bio'];
    $uID = $_SESSION['uID'];

    $stmt = $db->prepare("UPDATE users SET bio = :bio WHERE uID = :uID");
    $stmt->bindParam(":bio", $bio);
    $stmt->bindParam(":uID", $uID);
    $stmt->execute();

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
</head>

<body>
    <header>
        <div class="container">
            <a href="sparksocial.php"><img src="images/sparksociallogo.png" alt="navbar-logo" class="logo"
                    style="width:75px; height:75px;"></a>

            <nav>
                <ul>
                    <li><a href="sparksocial.php">Public</a></li>
                    <li><a href="friends.php">Friends</a></li>
                    <li style="justify-content: center;"><a href="closefriends.php">Close Friends</a></li>
                    <li><a href="tos.php">TOS</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </nav>
        </div>
    </header>


    <main>
        <div class="wrapper">
            <div class="dropdown">

                <?php
                if (isset($_SESSION['uID'])) {
                    // get user's current profile picture
                    $stmt = $db->prepare("SELECT profilepic FROM users WHERE uID = ?");
                    $stmt->execute(array($_SESSION['uID']));
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $picture = $result['profilepic'];

                    if (isset($_POST['submit'])) {
                        // handle profilepic upload
                        if (isset($_FILES['profilepic'])) {
                            $file_name = $_FILES['profilepic']['name'];
                            $file_tmp = $_FILES['profilepic']['tmp_name'];
                            $file_type = $_FILES['profilepic']['type'];
                            $file_size = $_FILES['profilepic']['size'];
                            //under this is an error, grab file_size or remove end or figure something out.
                            $file_ext = strtolower(end(explode('.', $file_name)));

                            $extensions = array("jpeg", "jpg", "png", "gif");

                            if (in_array($file_ext, $extensions) === false) {
                                $errors[] = "Extension not allowed, please choose a JPEG, GIF, or PNG file.";
                            }
                            if ($file_size > 8388608) {
                                $errors[] = 'File size must be less than 8 MB';
                            }
                            if (empty($errors) == true) {
                                move_uploaded_file($file_tmp, "images/" . $file_name);
                                $picture = $file_name;
                                // update user's profile picture in the database
                                $stmt = $db->prepare("UPDATE users SET profilepic = ? WHERE uID = ?");
                                $stmt->execute(array($picture, $_SESSION['uID']));
                            }
                        }
                    }
                    echo '<div class="profile-pic"><img src="images/' . $picture . '" width="57" height="57" /></div>';
                } else {
                    echo '<div class="profile-pic"><img src="images/pfp.png" width="57" height="57" /></div>';
                }
                ?>
                <div class="dropdown-menu">
                    <?php
                    // Check if user is logged in
                    if (isset($_SESSION['uID'])) {
                        // Display logout and edit profile links
                        echo "<a href='logout.php'>Logout</a>";
                        echo "<a href='edit-profile.php'>Edit Profile</a>";
                    } else {
                        // Display register and login links
                        echo "<a href='register.php'>Register</a>";
                        echo "<a href='login.php'>Login</a>";
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>



        <center class="text">
            <div class="line">
                <h1 class='lineUp'>Edit profile.</h1>
            </div>
        </center>
        <?php


        if (isset($_SESSION['uID'])) {
            // get user's current profile picture
            $stmt = $db->prepare("SELECT profilepic FROM users WHERE uID = ?");
            $stmt->execute(array($_SESSION['uID']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $picture = $result['profilepic'];

            if (isset($_POST['submit'])) {
                // handle profilepic upload
                if (isset($_FILES['profilepic'])) {
                    $file_name = $_FILES['profilepic']['name'];
                    $file_tmp = $_FILES['profilepic']['tmp_name'];
                    $file_type = $_FILES['profilepic']['type'];
                    $file_size = $_FILES['profilepic']['size'];
                    //under this is an error, grab file_size or remove end or figure something out.
                    $file_ext = strtolower(end(explode('.', $file_name)));

                    $extensions = array("jpeg", "jpg", "png", "gif");

                    if (in_array($file_ext, $extensions) === false) {
                        $errors[] = "Extension not allowed, please choose a JPEG, GIF, or PNG file.";
                    }
                    if ($file_size > 8388608) {
                        $errors[] = 'File size must be less than 8 MB';
                    }
                    if (empty($errors) == true) {
                        move_uploaded_file($file_tmp, "images/" . $file_name);
                        $picture = $file_name;
                        // update user's profile picture in the database
                        $stmt = $db->prepare("UPDATE users SET profilepic = ? WHERE uID = ?");
                        $stmt->execute(array($picture, $_SESSION['uID']));
                    }
                }
            }
            //Display logout and edit profile links
            //echo $picture;
            echo "<center <div class='profile-pic'>
                  <img src='images/$picture' alt='Profile Picture' width='200' height='200' onmouseover='this.style.opacity=0.7;' onmouseout='this.style.opacity=1;'>
                  <div class='edit-profile-picture'>
                      <form method='post' enctype='multipart/form-data'>
                          <input type='file' name='profilepic' accept='image/*'>
                          <input type='submit' name='submit' value='Upload'>
                      </form>
                  </div>
              </div> </center></div>";
        } else {
            // Customer cannot edit profile
            echo "You must login in order to edit your profile.";
            usleep(30000);
        }
        if (isset($_SESSION['uID'])) {
            $stmt = $db->prepare("SELECT bio FROM users WHERE uID = ?");
            $stmt->execute([$_SESSION['uID']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $bio = $row['bio'];

            if ($bio === null) {
                echo "No bio, yet!";
            } else {
                echo $bio;
            }
        }


        ?>


    </main>
</body>

</html>