<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Create Account</h2>
        </div>
        <form id="inscription.php" class="form">
            <div class="form-control">
                <label for="username">Full name</label>
                <input type="text" placeholder="enter your name" id="username" />
                <i class="fas fa-check-circle"></i>
                <i class="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">
                <label for="username">Email</label>
                <input type="email" placeholder="enter a valid email" id="email" />
                <i class="fas fa-check-circle"></i>
                <i class="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">
                <label for="username">Password</label>
                <input type="password" placeholder="password" id="password"/>
                <i class="fas fa-check-circle"></i>
                <i class="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <div class="form-control">
                <label for="username">Password check</label>
                <input type="password" placeholder="comfirm your password" id="password2"/>
                <i class="fas fa-check-circle"></i>
                <i class="fas fa-exclamation-circle"></i>
                <small>Error message</small>
            </div>
            <button>Check informations and submit</button>
        </form>
    </div>
    <script src="js/form.js"></script>
</body>
</html>