<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-9 col-lg-10">
            <h1>Articles</h1>
        </div>
    </div>
    <form action="<?php echo URLROOT; ?>/articles" method="GET" id="searchForm" class="form-inline mb-2">
        <div class="row">
            <div class="col-12">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" name="search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <?php if (empty($data['articles'])) : ?>
            <p class="fs-4">There are no news articles at the moment.</p>
        <?php else : ?>
            <?php foreach ($data['articles'] as $article) : ?>
                <article class="col-md-6 col-lg-4 mb-4">
                    <div class="card article">
                        <img class="card-img-top-bottom" src="<?php echo URLROOT ?>/img/card-img.svg" alt="Card Image">
                        <div class="card-body">
                            <h3 class="card-title">
                                <?php echo $article->title ?>
                            </h3>
                            <span class="card-subtitle text-secondary">
                                By <?php echo $article->name ?> - <?php echo displayDate(($article->status_time)) ?>
                            </span>
                            <div class="card-text mt-2">
                                <?php echo substr($article->body, 0, 200) ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?php foreach ($article->tags as $tag) : ?>
                                <a herf="#" class="badge bg-secondary">
                                    <?php echo $tag->tag_name; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?php echo URLROOT; ?>/articles/display/<?php echo $article->id ?>" class="stretched-link"></a>
                    </div>
                </article>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>