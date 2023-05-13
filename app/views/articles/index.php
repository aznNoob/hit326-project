<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row mb-3">
        <div class="col-md-9 col-lg-10">
            <h1>Articles</h1>
        </div>
        <?php if (userHasRole('journalist') || userHasRole('editor')) : ?>
            <div class="col-md-3 col-lg-2">
                <a href="<?php echo URLROOT; ?>/articles/add">
                    <button class="btn btn-primary pull-right btn-new-article"><i class="fa fa-plus mx-1"></i>New Article</button>
                </a>
            </div>
        <?php endif ?>
    </div>
    <div class="row">
        <?php foreach ($data['articles'] as $article) : ?>
            <article class="col-md-6 col-lg-4 mb-4">
                <div class="card article">
                    <img class="card-img-top" src="<?php echo URLROOT ?>\img\card-img.svg" alt="Card Image">
                    <div class="card-body">
                        <h3 class="card-title">
                            <?php echo $article->title ?>
                        </h3>
                        <span class="card-subtitle">
                            By <?php echo $article->name ?>
                        </span>
                        <div class="card-text">
                            <?php echo substr($article->body, 0, 100) ?>...
                        </div>
                    </div>
                    <div class="card-footer">
                        <?php echo date("d F Y", strtotime($article->created_at)) ?>
                    </div>
                    <a href="<?php echo URLROOT; ?>/articles/show/<?php echo $article->id ?>" class="stretched-link"></a>
                </div>
            </article>
        <?php endforeach ?>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>