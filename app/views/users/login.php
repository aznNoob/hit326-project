<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2 class="text-center">Sign In</h2>
                <p class="text-center mb-4">Please fill out this form to login</p>
                <form action="<?php echo URLROOT; ?>/users/login" method="POST">
                    <?php if (!empty($data['login_error'])) : ?> <div class="alert alert-danger" role="alert"><?php echo $data['login_error'] ?></div>
                    <?php endif ?>
                    <div class="form-floating mb-2">
                        <input type="email" name="email" placeholder="Place password here" value="<?php echo $data['email'] ?>" class="form-control <?php echo (!empty($data['login_error'])) ? 'is-invalid' : ''; ?>">
                        <label for="email" class="form-label">Email</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="password" placeholder="Place password here" value="<?php echo $data['password'] ?>" class="form-control <?php echo (!empty($data['login_error'])) ? 'is-invalid' : ''; ?>">
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <input type="submit" value="Submit" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <p class="text-center mt-4">Do not have an account? <a href="<?php echo URLROOT; ?>/users/register">Register here</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>