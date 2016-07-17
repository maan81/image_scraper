<?php
require('simple_html_dom.php');

function page_urls_fn($url){

    $html = file_get_html($url);

    $li = $html->find('.btn-reader-page',0);

    $a_all = $li->find('a');
    $a_first = $li->find('a',1)->href;

    $pages_count = count($a_all);
    $pages_count = $li->find('a',$pages_count-1)->href;

    $pages_count = array_pop(explode('/',$pages_count));

    $page_urls = [];

    $tmp2 = explode('/',$a_first);
    array_pop($tmp2);
    $processed_url = implode('/',$tmp2);


    for($i=1;$i<=($pages_count);$i++){
        $page_urls[] = $processed_url.'/'.$i;
    }

    return $page_urls;
}
function scrape_urls($url){

    $html = file_get_html($url);

    $img = $html->find('#manga-page',0);

    $img_url = $img->src;

    return $img_url;
}

$page_urls = [];
$img_urls = [];
$errors = '';
$url = '';


if(!empty($_POST['url'])){

    $url = $_POST['url'];

    if (filter_var($url, FILTER_VALIDATE_URL) !== false){

        $urls = page_urls_fn($url);
        foreach ($urls as $url) {
            $img_urls[] = scrape_urls($url);
            // print_r($img_urls);
            // die;
        }
        // print_r($img_urls);
        // die;

    }else{
        $errors = 'Error retriving urls.';
    }

}


?>
<!doctype html>
<html>
<head>
    <!-- <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
</head>
<body>

    <div class="container">

        <br>

        <div class="row">

            <div class="alert alert-danger"><?=$errors?></div>

            <form class="form-inline" role="form" method="post">
                <div class="form-group">
                    <label for="url">Url :</label>
                    <input type="text" class="form-control" id="url" name="url" value="<?=$url?>">
                </div>
                <input type="submit" class="btn btn-default" name="submit" value="Submit" />
            </form>
        </div>

        <br>

        <div class="row">
            <div>
                <?php foreach($img_urls as $img_url) { ?>
                    <div><?=$img_url?></div>
                <?php } ?>

                <div>http://google.com</div>
                <div>http://google.com</div>
                <div>http://google.com</div>
                <div>http://google.com</div>
                <div>http://google.com</div>
                <div>http://google.com</div>
                <div>http://google.com</div>
                <div>http://google.com</div>
            </div>
        </div>

    </div>



    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

</body>
</html>