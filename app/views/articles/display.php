<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row ">
        <h1 class="display-5"><?php echo $data['articles']->title ?></h1>
    </div>
    <div class="row mb-4 fst-italic text-secondary">
        <span>By <?php echo $data['articles']->user_name ?> - <?php echo date('d F, Y', strtotime($data['articles']->created_at)) ?></span>
    </div>
    <div class="row">
        <p><?php echo $data['articles']->body ?></p>
    </div>
    <hr class="hr">
    </hr>
    <div class="row">
        <h2>Recommended Posts</h2>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>