<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
    <?php 
    include '../partials/session.php'; 
    ?>
    <?php 
    include '../partials/nav.php'; 
    ?>

    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded shadow text-center">
        <h1 class="text-4xl font-bold mb-4">Welcome to Dashboard</h1>
        <p class="text-gray-600 mb-6">
            You are successfully logged in.
        </p>
        <a href="../auth/logout.php"
        class="bg-red-600 text-white px-5 py-3 rounded hover:bg-red-700">
            Logout
        </a>
    </div>


    <?php 
    include '../partials/footer.php'; 
    ?>
</body>
</html>