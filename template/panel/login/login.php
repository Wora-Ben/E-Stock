<?php
echo <<<EOT
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/E-Stock/assets/css/login.css">
    <link rel="shortcut icon" href="/E-Stock/assets/images/stock.svg" type="image/x-icon">

</head>
<body>
<div>
    <div class="container">
EOT;
if (isset($error)) {
    echo "<div class=\"error-notification\" ><ul>";
    foreach ($error as $value) {
        echo "<li>$value</li>";
    }
    echo "</ul></div>";
}
echo <<<EOT
        <div class="container-logo">
            <a href="index.php">
                <img src="/E-Stock/assets/images/stock-sm.svg" alt="logo">
                <div class="heading-title text-center">E-Stock</div>
            </a>
        </div>

        <div>
            <form action="?login" method="post">
                <table>
                    <tr>
                        <td>
                            <label for="username">Username</label>

                        <td>
                            <input type="text" name="username" id="username">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="password">Password</label>
                        </td>
                        <td>
                            <input type="text" class="search-input" name="password" id="password">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><input class="btn btn-edit" type="submit" value="Connecter"></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
        <div class="container-bg"></div>
</body>
</html>
EOT;