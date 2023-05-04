<html>

<body>
    <div>
        <?php
            // Request Variables
            $base = $_SERVER['PHP_SELF'];
            $limit = $_GET["limit"] ?? 5;
            $page = $_GET["page"] ?? 0;
            $breed_id = $_GET["breed_id"];
            $data = file_get_contents("https://api.thecatapi.com/v1/breeds?limit=" . $limit . "&page=" . $page);
            $dataj = json_decode($data);

            //Ensure that $limit is >=5 and <=10
            if($limit < 5) $limit = 5;
            else if($limit > 10) $limit = 10
        ?>
        <!-- Navbar -->
        <div>
            <a href="<?= $base ?>">Home</a>
            <?php $link = $base . "?limit=" . $limit . "&page=" . (intval($page) - 1); ?>
            <a href="<?= $link ?>">Previous</a>
            <?php $link = $base . "?limit=" . $limit . "&page=" . (intval($page) + 1); ?>
            <a href="<?= $link ?>">Next</a>
        </div>
        <!-- Cat Breed Link List -->
        <ul>
            <?php
                //Get list of breeds using limit and page offset provided in request variables
                $data = file_get_contents("https://api.thecatapi.com/v1/breeds?limit=" . $limit . "&page=" . $page);
                $dataj = json_decode($data);
            ?>
            <?php foreach ($dataj as $i => $value) : ?>
                <!-- Cat Breed List Item -->
                <li>
                    <?php $link = $base . "?limit=" . $limit . "&page=" . $page . "&breed_id=" . $dataj[$i]->id; ?>
                    <a href="<?= $link ?>">
                        <div>
                            <?php echo $dataj[$i]->name; ?>
                        </div>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
        <?php
            //Get image data for the selected breed
            $urlstring = "https://api.thecatapi.com/v1/images/search?breed_id=" . $breed_id;
            $images = json_decode(file_get_contents($urlstring));
        ?>
        <!-- Selected Breed Image -->
        <img src="<?php echo $images[0]->url ?>" alt="<?= $dataj[$i]->id ?>" width="100" , height="100"> </img>
        <?php
            //Get info about the selected cat breed
            $urlstring = "https://api.thecatapi.com/v1/breeds/" . $breed_id;
            $breed = json_decode(file_get_contents($urlstring));
        ?>
        <!-- Selected Breed Name -->
        <div>Name: <?= $breed->name ?></div>
    </div>
</body>

</html>