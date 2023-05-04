<html>

<body>
    <div>
        <?php
    $base = $_SERVER['PHP_SELF'];
    $limit = $_GET["limit"] ?? 3;
    $page = $_GET["page"] ?? 0;
    $breed_id = $_GET["breed_id"];
    $data = file_get_contents("https://api.thecatapi.com/v1/breeds?limit=" . $limit . "&page=" . $page);
    $dataj = json_decode($data);
    ?>
        <div>
            <a href="<?= $base ?>">Home</a>
            <?php $link = $base . "?limit=" . $limit . "&page=" . (intval($page) - 1); ?>
            <a href="<?= $link ?>">Previous</a>
            <?php $link = $base . "?limit=" . $limit . "&page=" . (intval($page) + 1); ?>
            <a href="<?= $link ?>">Next</a>
        </div>
        <ul>
            <?php
      $data = file_get_contents("https://api.thecatapi.com/v1/breeds?limit=" . $limit . "&page=" . $page);
      $dataj = json_decode($data);
      ?>
            <?php foreach ($dataj as $i => $value) : ?>
            <li>
                <?php $link = $base . "?limit=" . $limit . "&page=" . $page . "&breed_id=" . $dataj[$i]->id; ?>
                <a href="<?= $link ?>">
                    <div>
                        <?php echo $dataj[$i]->name; ?>
                        <!-- <img src="<?php echo $cats[0]->url ?>" width="100", height="100"> </img> -->
                    </div>
                </a>
            </li>
            <?php endforeach ?>
        </ul>
        <?php
    $urlstring = "https://api.thecatapi.com/v1/images/search?breed_id=" . $breed_id;
    $images = json_decode(file_get_contents($urlstring,));
    ?>
        <img src="<?php echo $images[0]->url ?>" alt="<?= $dataj[$i]->id ?>" width="100" , height="100"> </img>
        <?php
    $urlstring = "https://api.thecatapi.com/v1/breeds/" . $breed_id;
    $breed = json_decode(file_get_contents($urlstring,));
    ?>
        <div>Name: <?= $breed->name ?></div>
    </div>
</body>

</html>