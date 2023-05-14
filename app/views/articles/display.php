<?php require APPROOT . '/views/includes/header.php'; ?>

<div class="container">
    <div class="row ">
        <h1><?php echo $data['articles']->title ?></h1>
    </div>
    <div class="row mb-4">
        <p>By <?php echo $data['articles']->user_name ?></p>
        <p><?php echo date('d F, Y', strtotime($data['articles']->created_at)) ?></p>
    </div>
    <div class="row">
        <p><?php echo $data['articles']->body ?></p>
    </div>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>