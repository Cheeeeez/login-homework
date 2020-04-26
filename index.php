<?php
session_start();

include_once "validate/function.php";
include_once "class/Member.php";
include_once "class/MemberManager.php";

$memberManager = new MemberManager("data.json");
$memberManager->getMemberListFromJson();

define("PHONE_FORMAT", [
    '086',
    '096',
    '097',
    '098',
    '032',
    '033',
    '034',
    '035',
    '036',
    '037',
    '038',
    '039',
    '089',
    '090',
    '093',
    '070',
    '079',
    '077',
    '076',
    '078',
    '088',
    '091',
    '094',
    '083',
    '084',
    '085',
    '081',
    '082'
]);
define('FIRST_NUMBER_INDEX', 0);
define('THIRD_NUMBER', 3);

// Register request
if (isset($_REQUEST["register-submit"])) {
    if (empty($_REQUEST["email"])) {
        $emailErr = "*Email is required";
    } else {
        if (checkEmail($_REQUEST["email"])) {
            $email = $_REQUEST["email"];
        } else {
            $emailErr = "*Invalid email format";
        }
    }

    if (empty($_REQUEST["phone"])) {
        $phoneErr = "*Phone number is required";
    } else {
        if (checkPhoneNumber($_REQUEST["phone"], PHONE_FORMAT)) {
            $phone = $_REQUEST["phone"];
        } else {
            $phoneErr = "*Invalid phone number format";
        }
    }

    if (empty($_REQUEST["password"])) {
        $passwordErr = "*Password is required";
    } else {
        if (checkPassword($_REQUEST["password"])) {
            $passWord = $_REQUEST["password"];
        } else {
            $passwordErr = "*Password must be use 8 or more characters with a mix of letters, numbers & one of these symbols (@, !, ^, -, %, $)";
        }
    }

    if (empty($_REQUEST["confirm-password"])) {
        $confirmPasswordErr = "*Confirm password is required";
    } elseif ($_REQUEST["confirm-password"] != $_REQUEST["password"]) {
        $confirmPasswordErr = "*Confirm your password";
    }

    $memberList = $memberManager->getMemberList();
    if (isUsedEmail($email, $memberList)) {
        $emailErr = "Email was used";
    }


    if (empty($emailErr) && empty($phoneErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        $member = new Member($email, $phone, $passWord);
        $memberManager->addMember($member);
    }
}

// Login request
if (isset($_REQUEST["login-submit"])) {

    $_SESSION["members"] = $memberManager->getMemberList();

    $loginEmail = $_REQUEST["email"];
    $loginPassword = $_REQUEST["password"];

    if (empty($_REQUEST["email"])) {
        $emailError = "*Email is required";
    } elseif (empty($_REQUEST["password"])) {
        $passwordError = "*Password is required";
    } else {
        for ($i = 0; $i < count($_SESSION["members"]); $i++) {
            if ($loginEmail == $_SESSION["members"][$i]->email) {
                $_SESSION["email"] = $loginEmail;
                if ($loginPassword == $_SESSION["members"][$i]->password) {
                    $_SESSION["password"] = $loginPassword;
                    header("Location: home.php");
                } else {
                    $passwordError = "*Wrong password";
                    session_destroy();
                }
            }
        }
        if (empty($_SESSION["email"])) {
            $emailError = "*Email is not registered";
            session_destroy();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="style.css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
<?php if (isset($email) && isset($phone) && isset($passWord) && empty($emailErr)): ?>
    <script>
        alert("Register is successful!")
    </script>
<?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-login">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="#" class="active" id="login-form-link">Login</a>
                        </div>
                        <div class="col-xs-6">
                            <a href="#" id="register-form-link">Register</a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="login-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" role="form"
                                  <?php if (isset($emailErr) || isset($phoneErr) || isset($passwordErr) || isset($confirmPasswordErr)): ?>style="display: none"
                                  <?php else: ?>style="display: block"<?php endif; ?>>
                                <div class="form-group">
                                    <input type="text" name="email" id="email" tabindex="1" class="form-control"
                                           placeholder="Email Address" value="">
                                    <span style="color: red">
                                        <?php echo $emailError; ?>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2"
                                           class="form-control" placeholder="Password">
                                    <span style="color: red">
                                        <?php echo $passwordError ?>
                                    </span>
                                </div>
                                <div class="form-group text-center">
                                    <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                    <label for="remember"> Remember Me</label>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="login-submit" id="login-submit" tabindex="4"
                                                   class="form-control btn btn-login" value="Log In">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center">
                                                <a href="" tabindex="5"
                                                   class="forgot-password">Forgot Password?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form id="register-form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"
                                  role="form"
                                  <?php if (isset($emailErr) || isset($phoneErr) || isset($passwordErr) || isset($confirmPasswordErr)): ?>style="display: block"
                                  <?php else: ?>style="display: none"<?php endif; ?>
                            >
                                <div class="form-group">
                                    <input type="email" name="email" id="email" tabindex="1" class="form-control"
                                           placeholder="Email Address" value="<?php echo $email ?>">
                                    <span style="color: red">
                                        <?php echo $emailErr ?>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="phone" id="phone" tabindex="1" class="form-control"
                                           placeholder="Phone Number" value="<?php echo $phone ?>">
                                    <span style="color: red">
                                        <?php echo $phoneErr ?>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2"
                                           class="form-control" placeholder="Password">
                                    <span style="color: red">
                                        <?php echo $passwordErr ?>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirm-password" id="confirm-password" tabindex="2"
                                           class="form-control" placeholder="Confirm Password">
                                    <span style="color: red">
                                        <?php echo $confirmPasswordErr ?>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="register-submit" id="register-submit"
                                                   tabindex="4" class="form-control btn btn-register"
                                                   value="Register Now">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#login-form-link').click(function (e) {
            $("#login-form").delay(100).fadeIn(100);
            $("#register-form").fadeOut(100);
            $('#register-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });
        $('#register-form-link').click(function (e) {
            $("#register-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $('#login-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });
    });
</script>
</body>
</html>