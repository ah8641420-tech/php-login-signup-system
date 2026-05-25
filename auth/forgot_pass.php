<?php
    session_start();
    include '../config/database.php';

    $showError = "";
    $showSuccess = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        
        // Check if email exists
        $sql = "SELECT `id` FROM `logindata` WHERE `Email` = '$email'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        
        if($num == 1){
            // Generate reset token (6 digits)
            $token = rand(100000, 999999);
            // Set token expiry time (5 minutes from now)
            $expire = date("Y-m-d H:i:s", strtotime("+5 minutes"));
            
            // Update user with token and expiry - FIXED column name
            $updateSql = "UPDATE `logindata` SET `reset_token` = '$token', `reset_expires` = '$expire' WHERE `Email` = '$email'";
            $result = mysqli_query($conn, $updateSql);
            
            if($result){
                // Create reset link
                $resetLink = "reset_pass.php?token=$token";
                $showSuccess = "Password reset link: <a href='$resetLink' class='text-indigo-300 underline'>Click here to reset password</a><br><small class='text-gray-300'>Link expires in 5 minutes</small>";
            } else {
                $showError = "Something went wrong. Please try again!";
            }
        } else {
            $showError = "Email not found in our records!";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-gray-600 p-8 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center mb-4 text-white">Forgot Password?</h2>
        <p class="text-center text-white mb-6 text-sm">
            Enter your email address below and we'll send you a link to reset your password.
        </p>
        
        <!-- Display Error Message -->
        <?php 
        if ($showError) {
            echo '<div class="bg-red-200 px-6 py-4 mx-2 my-4 rounded-md text-lg flex items-center mx-auto max-w-lg">
            <svg viewBox="0 0 24 24" class="text-red-600 w-5 h-5 sm:w-5 sm:h-5 mr-3">
            <path fill="currentColor"
                d="M11.983,0a12.206,12.206,0,0,0-8.51,3.653A11.8,11.8,0,0,0,0,12.207,11.779,11.779,0,0,0,11.8,24h.214A12.111,12.111,0,0,0,24,11.791h0A11.766,11.766,0,0,0,11.983,0ZM10.5,16.542a1.476,1.476,0,0,1,1.449-1.53h.027a1.527,1.527,0,0,1,1.523,1.47,1.475,1.475,0,0,1-1.449,1.53h-.027A1.529,1.529,0,0,1,10.5,16.542ZM11,12.5v-6a1,1,0,0,1,2,0v6a1,1,0,1,1-2,0Z">
            </path>
            </svg>
            <span class="text-red-800"> ' . $showError . ' </span>
            </div>';
        }
        ?>
        
        <!-- Display Success Message -->
        <?php if($showSuccess): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $showSuccess; ?>
            </div>
        <?php endif; ?>
        
        <form action="forgot_pass.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm/6 font-medium text-gray-100">Email address</label>
                <div class="mt-2">
                    <input id="email" type="email" name="email" required autocomplete="email"
                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-50 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                >
                    Send Reset Link
                </button>
            </div>
        </form>
        <div class="text-center mt-6">
            <a href="login.php" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>