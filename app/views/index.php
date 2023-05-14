<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="p-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-3">Welcome!</h2>
            <p class="fs-4">Northern Australian Times is a premier news website dedicated to delivering the latest news, stories, and updates from across Northern Australia.
                Our commitment to journalistic integrity and local focus sets us apart as the trusted voice for our community.
                Stay informed and engaged with up-to-date coverage on politics, business, sports, culture, and more.
            </p>
            <a href="<?php echo URLROOT ?>/articles/index"><button class="btn btn-primary btn-lg" type="button">View Articles</button></a>
    </div>
</div>

<div class="p-5">
    <div class="container">
        <div class="row">
            <h1 class="display-5 fw-bold mb-3">Latest News</h1>
            <div class="row">
                <?php foreach ($data['articles'] as $article) : ?>
                    <article class="col-md-6 col-lg-4 mb-5 text-center">
                        <div class="">
                            <a href="<?php echo URLROOT; ?>/articles/display/<?php echo $article->id ?>">
                                <img class="rounded" src="<?php echo URLROOT ?>/img/card-img.svg" alt="Image">
                            </a>
                            <a class="article-title" href="<?php echo URLROOT; ?>/articles/display/<?php echo $article->id ?>">
                                <h3 class="mb-2 mt-3">
                                    <?php echo $article->title ?>
                                </h3>
                            </a>
                            <div class="text-secondary mb-2">
                                <span>By <?php echo $article->user_name ?></span>
                                <span>-</span>
                                <span><?php echo displayDate(($article->created_at)) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>