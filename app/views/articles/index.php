<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1>Articles</h1>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT; ?>/articles/add" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> New Article
            </a>
        </div>
    </div>
    <article>
        <?php foreach ($data['articles'] as $article) : ?>
            <div class="card card-body mb-3">
                <h4 class="card-title"><?php echo $article->title ?></h4>
                <div class="bg-light p-2 mb-3">
                    Written by <?php echo $article->name ?> on <?php echo $article->created_at ?>
                </div>
                <p class="card-text">
                    <?php echo $article->body ?>
                </p>
                <a href="<?php echo URLROOT; ?>/articles/show/<?php echo $article->id ?>" class="btn btn-dark">More</a>
            </div>
        <?php endforeach ?>
    </article>
</div>
<?php require APPROOT . '/views/includes/footer.php'; ?>