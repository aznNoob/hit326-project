<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">

    <div class="row">
        <div class="col-11">
            <h1 class="display-4 fw-bold"><?php echo $data['article']->title ?></h1>
        </div>

        <div class="row mb-2 fst-italic text-secondary">
            <span>By <?php echo $data['article']->author_name ?></span>
            <span><?php echo date('d F, Y - g.ia', strtotime($data['article']->created_at)) ?></span>
        </div>

        <div class="col-12">
            <?php foreach ($data['article']->tags as $tag) : ?>
                <a href="#" class="badge bg-secondary">
                    <?php echo $tag->tag_name; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <hr class="hr">
    </hr>

    <div class="row">
        <div class="col-10">
            <p><?php echo nl2br($data['article']->body) ?></p>
        </div>
    </div>


    <?php if (!empty($data['related_articles'])) : ?>
        <hr class="hr">
        </hr>
        <div class="row">
            <h2 class="display-6">Read More</h2>
        </div>

        <div class="row mb-4">
            <?php foreach ($data['related_articles'] as $related_article) : ?>
                <div class="col-12 mb-2">
                    <a href="<?php echo URLROOT; ?>/articles/display/<?php echo $related_article->id ?>">
                        <?php echo $related_article->title ?></span>
                    </a>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php if (userHasRole('editor') || (isset($_SESSION['user_email']) && $_SESSION['user_email'] == $data['article']->author_email)) : ?>
        <hr class="hr">
        </hr>
        <span class="">
            <a href="<?php echo URLROOT; ?>/articles/edit/<?php echo $data['article']->id ?>" class="btn btn-warning">Edit</a>
            <form action="<?php echo URLROOT; ?>/articles/delete/<?php echo $data['article']->id ?>" method="post">
                <input type="submit" value="Delete" class="btn btn-danger">
            </form>
        </span>
    <?php endif ?>

</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>