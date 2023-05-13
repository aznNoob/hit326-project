<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light">
                <h2 class="text-center">Sign Up</h2>
                <p class="text-center mb-4">Please fill out this form to register an account</p>
                <form action="<?php echo URLROOT; ?>/users/register" method="POST">
                    <div class="form-floating mb-2">
                        <input type="text" name="name" placeholder="Place name here" value="<?php echo $data['name'] ?>" class="form-control <?php echo (!empty($data['name_error'])) ? 'is-invalid' : ''; ?>">
                        <label for="name" class="form-label">Name</label>
                        <?php if (!empty($data['name_error'])) : ?><span class="invalid-feedback"><?php echo $data['name_error'] ?></span>
                        <?php endif ?>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="email" name="email" placeholder="Place email here" value="<?php echo $data['email'] ?>" class="form-control form-control-lg <?php echo (!empty($data['email_error'])) ? 'is-invalid' : ''; ?>">
                        <label for="email" class="form-label">Email</label>
                        <?php if (!empty($data['email_error'])) : ?><span class="invalid-feedback"><?php echo $data['email_error'] ?></span>
                        <?php endif ?>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="password" placeholder="Place password here" value="<?php echo $data['password'] ?>" class="form-control form-control-lg <?php echo (!empty($data['password_error'])) ? 'is-invalid' : ''; ?>">
                        <label for="password" class="form-label">Password</label>
                        <?php if (!empty($data['password_error'])) : ?><span class="invalid-feedback"><?php echo $data['password_error'] ?></span>
                        <?php endif ?>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="password" name="confirm_password" placeholder="Place confirm password here" value="<?php echo $data['confirm_password'] ?>" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_error'])) ? 'is-invalid' : ''; ?>">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <?php if (!empty($data['confirm_password_error'])) : ?><span class="invalid-feedback"><?php echo $data['confirm_password_error'] ?></span>
                        <?php endif ?>
                    </div>

                    <div class="row">
                        <div class="col-12 d-flex justify-content-center mt-2">
                            <input type="submit" value="Submit" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <p class="text-center mt-4">Already have an account? <a href="<?php echo URLROOT; ?>/users/login">Login here</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>