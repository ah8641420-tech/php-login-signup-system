<?php
    session_start();
    include '../config/database.php';

    $showError = "";
    $showSuccess = "";
    $token = isset($_GET['token']) ? mysqli_real_escape_string($conn, $_GET['token']) : '';

    // Verify token
    if($token){
        $current_time = date("Y-m-d H:i:s");
        // FIXED: Using correct column name 'reset_expires'
        $sql = "SELECT `id`, `Email` FROM `logindata` WHERE `reset_token` = '$token' AND `reset_expires` > '$current_time'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        
        if($num == 0){
            $showError = "Invalid or expired reset link! Please request a new one.";
            $token = "";
        } else {
            $user = mysqli_fetch_assoc($result);
            $user_id = $user['id'];
        }
    } elseif($_SERVER["REQUEST_METHOD"] != "POST") {
        $showError = "No reset token provided!";
    }

    // Handle password reset
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password']) && isset($_POST['user_id'])){
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_POST['user_id'];
        
        if(empty($password) || empty($confirm_password)){
            $showError = "Please fill all fields!";
        } elseif(strlen($password) < 6){
            $showError = "Password must be at least 6 characters long!";
        } elseif($password !== $confirm_password){
            $showError = "Passwords do not match!";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Update password and clear reset token - FIXED column names
            $updateSql = "UPDATE `logindata` SET `Password` = '$hashed_password', `reset_token` = NULL, `reset_expires` = NULL WHERE `id` = '$user_id'";
            
            if(mysqli_query($conn, $updateSql)){
                $showSuccess = "Password reset successful! You can now login with your new password.";
                $token = "";
            } else {
                $showError = "Failed to reset password. Please try again!";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-gray-600 p-8 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center mb-4 text-white">Reset Password</h2>
        
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
        
        <?php if($showSuccess): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $showSuccess; ?>
                <div class="mt-3">
                    <a href="login.php" class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded inline-block">
                        Go to Login
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if($token && !$showSuccess): ?>
            <form action="reset_pass.php" method="POST" class="space-y-6">
                <input type="hidden" name="user_id" value="<?php echo $user_id ?? ''; ?>">
                <div>
                    <label for="password" class="block text-sm/6 font-medium text-gray-100">New Password</label>
                    <div class="mt-2">
                        <input type="password" id="password" name="password" required
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-50 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                        <p class="text-xs text-gray-300 mt-1">Minimum 6 characters</p>
                    </div>
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm/6 font-medium text-gray-100">Confirm Password</label>
                    <div class="mt-2">
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-black outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-50 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-indigo-500 hover:bg-indigo-400 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                    >
                        Reset Password
                    </button>
                </div>
            </form>
        <?php elseif(!$token && !$showSuccess && !$showError): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                No reset token provided. Please use the link from your email.
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-6">
            <a href="forgot_pass.php" class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-indigo-400">
                Request New Reset Link
            </a>
        </div>
    </div>
</body>
</html>