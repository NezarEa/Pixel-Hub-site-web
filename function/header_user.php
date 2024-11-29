<nav>
    <div class="logo animate__flash">
        <a href="#">
            <h1>Pixel<span>Hub</span></h1>
        </a>
    </div>
    <ul class="nav-links">
        <li><a href="accueil.php" class="nav-link"><i class="fas fa-home"></i> Accueil</a></li>
        <li><a href="FAQ.php" class="nav-link"><i class="fas fa-question-circle"></i> FAQ</a></li>
        <li><a href="contact.php" class="nav-link"><i class="fas fa-envelope"></i> Contact</a></li>
        <li><a href="politique.php" class="nav-link"><i class="fas fa-balance-scale"></i>
                Politique</a>
        </li>
        <li>
            <form class="search-form" action="search.php" method="GET">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search" name="query"
                    value="<?php echo isset($search_query) ? htmlspecialchars($search_query) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </li>
    </ul>
    <?php if(isset($_SESSION['userId'])): ?>
    <div class="action">
        <div class="profile" onclick="menuToggle()">
            <img src="<?php echo isset($user_photo) ? htmlspecialchars($user_photo) : ''; ?>" alt="">
        </div>
        <div class="menu">
            <h3><?php echo isset($user_name) ? htmlspecialchars($user_name) : ''; ?> <span><?php echo isset($user_email) ? htmlspecialchars($user_email) : ''; ?></span></h3>
            <ul>
                <li><a href="profile.php" class="nav-link"><i class="fas fa-user"></i> My
                        profile</a></li>
                <li><a href="logout.php" class=" btn btn-danger"><i class="fas fa-sign-out-alt"></i>
                        Logout</a></li>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <div class="menu-icon">
        <i class="fas fa-bars"></i>
    </div>
</nav>
