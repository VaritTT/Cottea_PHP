<html>

<head>
    <meta charset="UTF-8">
    <!-- bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_home_customer.css">
    <link rel="stylesheet" href="<?= ROOT ?>/css/style_category.css">
    <title>Homepage</title>
</head>

<body>
    <!-- Modal -->
    <div class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid container-homepage-customer">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= ROOT ?>/img/banner1.jpg" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="<?= ROOT ?>/img/banner2.jpg" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="<?= ROOT ?>/img/banner3.jpg" class="d-block w-100">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container-fluid categories-suggest mt-5">
            <div class="row">
                <?php foreach ($data['category'] as $category) { ?>
                    <a href="<?= ROOT ?>/category?category_id=<?php echo $category->category_id ?>" class="col">
                        <img src="<?= ROOT ?>/img/product_image/<?php echo $category->category_image ?>" class="img-fluid rounded-circle">
                        <p class="label-image" style="font-weight: 500;"><?php echo $category->category_name ?></p>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>