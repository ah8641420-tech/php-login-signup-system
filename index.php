<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Home</title>
</head>
<body class="bg-gray-100">

<?php 
include 'partials/nav.php'; 
?>

<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded shadow text-center">

    <h1 class="text-4xl font-bold mb-4">PHP Login & Signup System</h1>

    <p class="text-gray-600 mb-6">
        Secure authentication system using PHP, MySQL, Sessions and Tailwind CSS.
    </p>

    <a href="auth/signup.php"
    class="bg-blue-600 text-white px-6 py-3 rounded mr-4 hover:bg-blue-700">
        Get Started
    </a>

    <a href="auth/login.php"
    class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
        Login
    </a>

</div>

<?php 
include 'partials/footer.php'; 
?>

</body>
</html>