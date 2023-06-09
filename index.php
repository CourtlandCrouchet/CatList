<html>
<head>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div id="container">
        <?php
            // Request Variables
            $base = $_SERVER['PHP_SELF'];
            $limit = $_GET["limit"] ?? 5;
            $page = $_GET["page"] ?? 0;
            $data = file_get_contents("https://api.thecatapi.com/v1/breeds?limit=" . $limit . "&page=" . $page);
            $dataj = json_decode($data);
            $breed_id = $_GET["breed_id"] ?? $dataj[0]->id ?? "abys";

            //Config Constants
            define("MAX_WIDTH", 1200);
            define("MAX_HEIGHT", 700);
            define("CAT_LIMIT", 67);

            //Ensure that $limit is >=5 and <=10
            if($limit < 5) $limit = 5;
            else if($limit > 10) $limit = 10
        ?>
        <!-- Navbar -->
        <div class="navbar">
            <!-- Home Button -->
            <div onclick="window.location.href='<?= $base . "?limit=" . $limit ?>'">Home</div>

            <!-- Previous Page Button -->
            <!-- Disable Previous Button if there is no previous data -->
            <?php if($page == 0) : ?>
                <div class="disabled">Previous</div>
            <?php else : ?>
                <?php $link = $base . "?limit=" . $limit . "&page=" . (intval($page) - 1); ?>
                <div onclick="window.location.href='<?= $link ?>'">Previous</div>
            <?php endif; ?>

            <!-- Next Page Button -->
            <!-- Disable Next Button if next page would have no data -->
            <?php if(($page+1) * $limit >= CAT_LIMIT) : ?>
                <div class="disabled">Next</div>
            <?php else : ?>
                <?php $link = $base . "?limit=" . $limit . "&page=" . (intval($page) + 1); ?>
                <div onclick="window.location.href='<?= $link ?>'">Next</div>
            <?php endif; ?>

            <!-- Page Number -->
            <div id="page-number">Page <?= intval($page) + 1 ?></div>
        </div>
        <div class="row">
            <div class="col-left">
                <!-- Cat Breed Link List -->
                <ul>
                    <?php foreach ($dataj as $i => $value) : ?>
                        <!-- Cat Breed List Item -->
                        <?php $link = $base . "?limit=" . $limit . "&page=" . $page . "&breed_id=" . $dataj[$i]->id; ?>
                        <li onclick="window.location.href='<?= $link ?>'"
                        class="breed-btn<?= $dataj[$i]->id == $breed_id ? ' active' : '' ?>">
                            <div>
                                <?= $dataj[$i]->name; ?>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php
                //Get info about the selected cat breed
                $urlstring = "https://api.thecatapi.com/v1/breeds/" . $breed_id;
                $breed = json_decode(file_get_contents($urlstring));
            ?>
            <div class="col-right">
                <div id="selected-breed-info" class="row">
                    <!-- Selected Breed Name -->
                    <div>Name: <?= $breed->name ?></div>
                    <!-- Wikipedia Link -->
                    <a href="<?= $breed->wikipedia_url ?>">More Info</a>
                </div>
                <?php
                    //Get image data for the selected breed
                    $urlstring = "https://api.thecatapi.com/v1/images/search?breed_id=" . $breed_id;
                    $images = json_decode(file_get_contents($urlstring));
                ?>
                <?php if(count($images) > 0) : ?>
                    <?php
                        //Scale down image until it fits
                        $scaleFactor = 1;
                        while($scaleFactor > 0
                        && (($images[0]->width * $scaleFactor) > MAX_WIDTH
                        || ($images[0]->height * $scaleFactor) > MAX_HEIGHT)) {
                            $scaleFactor -= .05;
                        }
                    ?>
                    <!-- Selected Breed Image -->
                    <img 
                        src="<?php echo $images[0]->url ?>" 
                        alt="<?= $breed->name ?>" 
                        width="<?= $images[0]->width * $scaleFactor ?>px"
                        height="<?= $images[0]->height * $scaleFactor ?>px"> </img>
                <?php else : ?>
                    <div id="no-image-msg">No Images Found for the Selected Breed</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Loading Screen hides container until it has finished loading -->
    <div id="loading-screen">
        <div>Loading Cats...</div>
    </div>
</body>
<script>
    //Fade out then delete the Loading Screen
    function hideLoadingScreen() {
        let loadingScreen = document.querySelector("#loading-screen");
        loadingScreen.classList.add("fade-out");
        //Wait until fade-out has completed before deleting
        setTimeout(() => {
            loadingScreen.remove();
        }, 1100);
    }
    //Fade out loading screen after 400ms
    setTimeout(hideLoadingScreen, 400);
</script>
</html>