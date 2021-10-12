<!DOCTYPE html>

<?php
include_once './Databases.php';
?>
<html>

<head>
    <title> Password Reset</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image" href="icon/favicon.jpeg" />

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
            </div>
            <div class="col-sm-4">
                <?php
                if (isset($_GET['token'])) {
                    $token = $_GET['token'];
                    $database = new Database();
                    $db = $database->getConnexion();
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $db->prepare("SELECT * FROM pass_reset WHERE token=:token");
                    $stmt->execute(["token" => $token]);
                    if ($stmt->rowCount() == 0) {
                        exit();
                    }
                } else {
                    exit();
                }
                //form for submit 
                if (isset($_POST['sub_set'])) {
                    extract($_POST);
                    if ($password == '') {
                        $error[] = 'Veuillez entrer le mot de passe.';
                    }
                    if ($passwordConfirm == '') {
                        $error[] = 'Veuillez confirmer le mot de passe.';
                    }
                    if ($password != $passwordConfirm) {
                        $error[] = '
                        Les mots de passe ne correspondent pas.
                        Le lien a expiré ou quelque chose manque
                        ';
                    }
                    if (strlen($password) < 5) { // min 
                        $error[] = '
                        Le mot de passe est composé de 6 caractères.
                        Le lien a expiré ou quelque chose manque
                        ';
                    }
                    if (strlen($password) > 50) { // Max 
                        $error[] = '
                        Mot de passe : Longueur maximale de 50 caractères Non autorisé
                        Le lien a expiré ou quelque chose manque
                        ';
                    }
                    $database = new Database();
                    $db = $database->getConnexion();
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $db->prepare("SELECT * FROM pass_reset WHERE token=:token");
                    $stmt->execute(["token" => $token]);
                    if ($stmt->rowCount() > 0) {
                        $res = $stmt->fetch(PDO::FETCH_ASSOC);
                        $email = $res["email"];
                    }
                    if (isset($email) && !empty($email)) {
                        $emailtok = $email;
                    } else {
                        $error[] = 'Le lien a expiré ou quelque chose manque ';
                        $hide = 1;
                    }
                    if (!isset($error)) {
                        $database = new Database();
                        $db = $database->getConnexion();
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("UPDATE user SET password=:password WHERE email=:email");
                        $stmt->execute(["password" => $password, "email" => $email]);
                        if ($stmt->rowCount() > 0) {
                            $success = "<div class='successmsg'><span style='font-size:100px;'>&#9989;</span> <br> 
                            Votre mot de passe a été mis à jour avec succès
                            .. <br> </div>";
                            $stmt = $db->prepare("DELETE FROM pass_reset WHERE token=:token");
                            $stmt->execute(["token" => $token]);
                            $hide = 1;
                        }
                    }
                }
                ?>
                <div class="login_form">
                    <form action="" method="POST">
                        <div class="form-group">
                            <img src="./icon/logo_ipd6.png" alt="GIS APP" class="logo img-fluid">
                            <?php
                            if (isset($error)) {
                                foreach ($error as $error) {
                                    echo '<div style="color:red;" class="errmsg">' . $error . '</div><br>';
                                }
                            }
                            if (isset($success)) {
                                echo $success;
                            }
                            ?>
                            <?php if (!isset($hide)) { ?>
                                <label class="label_txt">Password </label>
                                <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="label_txt">Confirm Password </label>
                            <input type="password" name="passwordConfirm" class="form-control" required>
                        </div>
                        <button type="submit" name="sub_set" class="btn btn-primary btn-group-lg form_btn">Reset Password</button>
                    <?php } ?>
                    </form>
                </div>
            </div>
            <div class="col-sm-4">
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>

</html>