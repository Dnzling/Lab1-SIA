<?php 
include 'clerk_sidebar.php';
?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Clerk</title>
    <style>
       
    </style>
 </head>
 <body class="">
 <main class="main container" id="main">
        <?php
        // Check if session variables are set
        if (isset($_SESSION['username'])) {
            echo "Welcome, " . htmlspecialchars($_SESSION['username']);
        } else {
            echo "You are not logged in.";
        }

        
        ?>

<div class="sidebar__info">
        <h3 class="fw-bold"><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; ?></h3>
        <span class="text-secondary"><?php echo isset($_SESSION['id_number']) ? $_SESSION['id_number'] : 'Not logged in'; ?></span>
    </div>
    </main>

    <script>
    </script>
 </body>
 </html>