<!-- WAG NIYO NANG GALAWIN ITO - Denz -->
!
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>Side Bar Menu</title>
    <style>
        /*=============== GOOGLE FONTS ===============*/
        @import url("https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,200..1000&display=swap");

        /*=============== VARIABLES CSS ===============*/
        :root {
            --header-height: 3.5rem;

            /*========== Colors ==========*/
            /*Color mode HSL(hue, saturation, lightness)*/
            --first-color: hsl(228, 85%, 63%);
            --title-color: hsl(228, 18%, 16%);
            --text-color: hsl(228, 8%, 56%);
            --body-color: hsl(228, 100%, 99%);
            --shadow-color: hsla(228, 80%, 4%, .1);

            /*========== Font and typography ==========*/
            /*.5rem = 8px | 1rem = 16px ...*/
            --body-font: "Nunito Sans", system-ui;
            --normal-font-size: .938rem;
            --smaller-font-size: .75rem;
            --tiny-font-size: .75rem;

            /*========== Font weight ==========*/
            --font-regular: 400;
            --font-semi-bold: 600;

            /*========== z index ==========*/
            --z-tooltip: 10;
            --z-fixed: 100;
        }

        /*========== Responsive typography ==========*/
        @media screen and (min-width: 1150px) {
            :root {
                --normal-font-size: 1rem;
                --smaller-font-size: .813rem;
            }
        }

        /*=============== BASE ===============*/
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: var(--body-font);
            font-size: var(--normal-font-size);
            background-color: var(--body-color);
            color: var(--text-color);
            transition: background-color .4s;
        }

        a {
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        button {
            all: unset;
        }

        /*=============== VARIABLES DARK THEME ===============*/
        body.dark-theme {
            --first-color: hsl(228, 70%, 63%);
            --title-color: hsl(228, 18%, 96%);
            --text-color: hsl(228, 12%, 61%);
            --body-color: hsl(228, 24%, 16%);
            --shadow-color: hsla(228, 80%, 4%, .3);
        }

        /*========== 
    Color changes in some parts of 
    the website, in dark theme
==========*/
        .dark-theme .sidebar__content::-webkit-scrollbar {
            background-color: hsl(228, 16%, 30%);
        }

        .dark-theme .sidebar__content::-webkit-scrollbar-thumb {
            background-color: hsl(228, 16%, 40%);
        }

        /*=============== REUSABLE CSS CLASSES ===============*/
        .container {
            margin-inline: 1.5rem;
        }

        .main {
            padding-top: 5rem;
        }

        /*=============== HEADER ===============*/
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: var(--z-fixed);
            margin: .75rem;
        }

        .header__container {
            width: 100%;
            height: var(--header-height);
            background-color: var(--body-color);
            box-shadow: 0 2px 24px var(--shadow-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-inline: 1.5rem;
            border-radius: 1rem;
            transition: background-color .4s;
        }

        .header__logo {
            display: inline-flex;
            align-items: center;
            column-gap: .25rem;
            color: var(--title-color);
            font-weight: var(--font-semi-bold);
        }

        .header__logo i {
            font-size: 1.5rem;
            color: var(--first-color);
        }

        .header__toggle {
            font-size: 1.5rem;
            justify-content: flex-end;
            color: var(--title-color);
            cursor: pointer;
        }

        /*=============== SIDEBAR ===============*/
        .sidebar {
            position: fixed;
            left: -120%;
            top: 0;
            bottom: 0;
            z-index: var(--z-fixed);
            width: 288px;
            background-color: var(--body-color);
            box-shadow: 2px 0 24px var(--shadow-color);
            padding-block: 1.5rem;
            margin: .75rem;
            border-radius: 1rem;
            transition: left .4s, background-color .4s, width .4s;
        }

        .sidebar__container,
        .sidebar__content {
            display: flex;
            flex-direction: column;
            row-gap: 3rem;
        }

        .sidebar__container {
            height: 100%;
            overflow: hidden;
        }

        .sidebar__user {
            display: grid;
            grid-template-columns: repeat(2, max-content);
            align-items: center;
            column-gap: 1rem;
            padding-left: 2rem;
        }

        .sidebar__logo {
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }

        .sidebar__logo img {
            width: 75px;
            height: auto;
        }

        .sidebar__img {
            position: relative;
            width: 50px;
            height: 50px;
            background-color: var(--first-color);
            border-radius: 50%;
            overflow: hidden;
            display: grid;
            place-items: center;
        }

        .sidebar__img img {
            width: 36px;
            height: auto;
        }

        .sidebar__info h3 {
            font-size: var(--normal-font-size);
            color: var(--title-color);
            transition: color .4s;
        }

        .sidebar__info span {
            font-size: var(--smaller-font-size);
        }

        .sidebar__content {
            overflow: hidden auto;
        }

        .sidebar__content::-webkit-scrollbar {
            width: .4rem;
            background-color: hsl(228, 8%, 85%);
        }

        .sidebar__content::-webkit-scrollbar-thumb {
            background-color: hsl(228, 8%, 75%);
        }

        .sidebar__title {
            width: max-content;
            font-size: var(--tiny-font-size);
            font-weight: var(--font-semi-bold);
            padding-left: 2rem;
            margin-bottom: 1.5rem;
            ;
        }

        .sidebar__list,
        .sidebar__actions {
            display: grid;
            row-gap: 1.5rem;
        }

        .sidebar__link {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, max-content);
            align-items: center;
            column-gap: 1rem;
            color: var(--text-color);
            padding-left: 2rem;
            transition: color .4s, opacity .4s;
            text-decoration-color: transparent;
        }

        .sidebar__link i {
            font-size: 1.25rem;
        }

        .sidebar__link span {
            font-weight: var(--font-semi-bold);
        }

        .sidebar__link:hover {
            color: var(--first-color);
        }

        .sidebar__actions {
            margin-top: auto;
        }

        .sidebar__actions button {
            cursor: pointer;
        }

        /* Show sidebar */
        .show-sidebar {
            left: 0;
        }

        /* Active link */
        .active-link {
            color: var(--first-color);
        }

        .active-link::after {
            content: "";
            position: absolute;
            left: 0;
            width: 3px;
            height: 20px;
            background-color: var(--first-color);
        }

        /*=============== BREAKPOINTS ===============*/
        /* For small devices */
        @media screen and (max-width: 360px) {
            .header__container {
                padding-inline: 1rem;
            }

            .sidebar {
                width: max-content;
            }

            .sidebar__info,
            .sidebar__link span {
                display: none;
            }

            .sidebar__user,
            .sidebar__list,
            .sidebar__actions {
                justify-content: center;
            }

            .sidebar__user,
            .sidebar__link {
                grid-template-columns: max-content;
            }

            .sidebar__user {
                padding: 0;
            }

            .sidebar__link {
                padding-inline: 2rem;
            }

            .sidebar__title {
                padding-inline: .5rem;
                margin-inline: auto;
            }
        }

        /* For large devices */
        @media screen and (min-width: 1150px) {
            .header {
                margin: 1rem;
                padding-left: 340px;
                transition: padding .4s;
            }

            .header__container {
                height: calc(var(--header-height) + 2rem);
                padding-inline: 2rem;
            }

            .header__logo {
                order: 1;
            }

            .sidebar {
                left: 0;
                width: 316px;
                margin: 1rem;
            }

            .sidebar__info,
            .sidebar__link span {
                transition: opacity .4s;
            }

            .sidebar__user,
            .sidebar__title {
                transition: padding .4s;
            }

            /* Reduce sidebar */
            .show-sidebar {
                width: 90px;
            }

            .show-sidebar .sidebar__user {
                padding-left: 1.25rem;
            }

            .show-sidebar .sidebar__title {
                padding-left: 0;
                margin-inline: auto;
            }

            .show-sidebar .sidebar__info,
            .show-sidebar .sidebar__link span {
                opacity: 0;
            }

            .main {
                padding-left: 340px;
                padding-top: 8rem;
                transition: padding .4s;
            }

            /* Add padding left */
            .left-pd {
                padding-left: 114px;
            }
        }
    </style>
</head>

<body class="link-underline-opacity-0">
    <!--=============== HEADER ===============-->
    <header class="header" id="header">
        <div class="header__container">
            <button class="">
                       <?php echo "ADMIN" ?>
                    </button>
            <?php
                date_default_timezone_set('Asia/Manila');
               echo date("F j, Y");
            ?>

           
        </div>
    </header>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar__container">
            
            <div class="sidebar__logo">
                <img src="Images/butterfly.png" alt="Logo">
            </div>

            <div class="sidebar__content">
                <div>
                    
                    <h3 class="sidebar__title">MAIN MENU</h3>
                    <div class="sidebar__list">
                        <a href="dashboard.php" class="sidebar__link">
                            <i class="ri-dashboard-2-fill"></i>
                            <span>Dashboard</span>
                        </a>

                        <a href="inventory.php" class="sidebar__link">
                            <i class="ri-add-box-fill"></i>
                            <span>Products</span>
                        </a>

                        <a href="inventory.php" class="sidebar__link">
                            <i class="ri-add-box-fill"></i>
                            <span>Inventory</span>
                        </a>

                        <a href="#" class="sidebar__link">
                            <i class="ri-bar-chart-box-fill"></i>
                            <span>Sales Report</span>
                        </a>

                        <a href="#" class="sidebar__link">
                            <i class="ri-bar-chart-box-line"></i>
                            <span>Sales</span>
                        </a>

                    </div>
                </div>  
                <div>
                    <h3 class="sidebar__title">ADMIN</h3>
                    <div class="sidebar__list">
                        <a href="userManagement.php" class="sidebar__link">
                            <i class="ri-user-fill"></i>
                            <span>User Management</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="sidebar__actions">
                <div class="sidebar__user">
                    <div class="sidebar__img">
                        <img src="assets/img/perfil.png" alt="Profile">
                    </div>
                    <div class="sidebar__info">
                        <h3 class="fw-bold"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?></h3>
                        <span class="text-secondary"><?php echo isset($_SESSION['id_number']) ? htmlspecialchars($_SESSION['id_number']) : 'Not logged in'; ?></span>
                    </div>
                </div>
                <a href="php/logout.php" class="sidebar__link">
                    <i class="ri-logout-box-r-fill"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main content area -->
    <main class="main" id="main">
        <!-- Your main content goes here -->
    </main>

    <!--=============== MAIN JS ===============-->
    <script>
        const sidebarLink = document.querySelectorAll('.sidebar__link');

        const linkColor = (event) => {
            sidebarLink.forEach(l => l.classList.remove('active-link'));
            event.currentTarget.classList.add('active-link');
        };

        sidebarLink.forEach(l => l.addEventListener('click', linkColor));

        // Set active link based on current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const links = document.querySelectorAll('.sidebar__link');
            
            links.forEach(link => {
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active-link');
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>