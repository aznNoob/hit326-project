<nav class="sticky-top navbar navbar-expand-lg navbar-dark bg-dark mb-3 p-3">
    <div class="container">
        <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php echo SITENAME; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapse" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapse">
            <div class="">

            </div>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link mx-2" href="<?php echo URLROOT; ?>"><i class="fa-solid fa-house-chimney mx-2"></i>Home</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/articles/"><i class="fa-solid fa-newspaper mx-2"></i>Articles</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="<?php echo URLROOT; ?>/pages/about"><i class="fa-solid fa-circle-info mx-2"></i>About</a>
                </li>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropDown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-user mx-2"></i><?php echo $_SESSION['user_name'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <p class="dropdown-item"><i class="fa-solid fa-address-card mx-2"></i><?php echo ucfirst($_SESSION['user_role']) ?></p>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo URLROOT; ?>/users/logout"><i class="fa-solid fa-right-to-bracket mx-2"></i>Logout</a>
                        </div>
                    </li>
                <?php else : ?>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/users/register"><i class="fa-solid fa-user-plus mx-2"></i>Register</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="<?php echo URLROOT; ?>/users/login"><i class="fa-solid fa-right-to-bracket mx-2"></i>Login</a>
                    </li>
            </ul>
        <?php endif; ?>
        </div>
    </div>
</nav>