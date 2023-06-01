<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light">
                <h2 class="text-center">Create Article</h2>
                <p class="text-center mb-4">Please fill out this form to create an article</p>
                <form action="<?php echo URLROOT; ?>/articles/create" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-2">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" type="text" name="title" value="<?php echo $data['title'] ?>" class="form-control <?php echo (!empty($data['title_error'])) ? 'is-invalid' : ''; ?>">
                        <?php if (!empty($data['title_error'])) : ?><span class="invalid-feedback"><?php echo $data['title_error'] ?></span>
                        <?php endif ?>
                    </div>
                    <!-- <div class="form-group mb-2">
                        <label for="thumbnail" class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                    </div> -->
                    <div class="form-group mb-2">
                        <label for="body" class="form-label">Content</label>
                        <textarea id="body" name="body" class="form-control <?php echo (!empty($data['body_error'])) ? 'is-invalid' : ''; ?>" rows="5"><?php echo $data['body'] ?></textarea>
                        <?php if (!empty($data['body_error'])) : ?><span class="invalid-feedback"><?php echo $data['body_error'] ?></span>
                        <?php endif ?>
                    </div>
                    <div>
                        <label for="tags" class="form-label">Tags</label>
                        <input id="tags" name="tags" value='<?php echo $data['tags'] ?>' class="form-control <?php echo (!empty($data['tags_error'])) ? 'is-invalid' : ''; ?>">
                        <?php if (!empty($data['tags_error'])) : ?><span class="invalid-feedback"><?php echo $data['tags_error'] ?></span>
                        <?php endif ?>
                    </div>
                    <?php if (userHasRole('journalist') || userHasRole('editor')) : ?>
                        <div class="form-group mb-2">
                            <label for="status_option" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft">Save as Draft</option>
                                <option value="pending_review">Send for Review</option>
                                <?php if (userHasRole('editor')) : ?>
                                    <option value="published">Publish</option>
                                <?php endif ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <div class="col-12 d-flex justify-content-center mt-3 ">
                        <button type="submit" name="submit" class="btn btn-success btn-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>