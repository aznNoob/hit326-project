<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="p-5">
    <div class="container">
        <h1 class="display-5 fw-bold">Welcome!</h2>
            <p class="fs-4">Northern Australian Times is a premier news website dedicated to delivering the latest news, stories, and updates from across Northern Australia.
                Our commitment to journalistic integrity and local focus sets us apart as the trusted voice for our community.
                Stay informed and engaged with up-to-date coverage on politics, business, sports, culture, and more.
            </p>
            <a href="<?php echo URLROOT ?>/articles/index"><button class="btn btn-success btn-lg" type="button">View Articles</button></a>
    </div>
</div>

<div class="bg-dark text-light p-5">
    <div class="container">
        <h1 class="display-5 fw-bold">Latest News</h2>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>