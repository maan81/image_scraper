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

    $img_url = $html->find('#manga-page',0)->src;

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
        }

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

        <div class="header">
            <h2 class="text-center">Image Scraper</h2>
        </div>

        <div class="box-body row" style="padding: 15px">

            <div class="alert alert-danger <?=$errors?'hide':''?>"><?=$errors?></div>

            <form role="form" class="form-horizontal" method="post">
                <div class="form-group form-group-lg " style="padding: 0 5px">
                    <label class="col-md-1 control-label">URL</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" id="url" name="url" value="<?=$url?>">
                    </div>
                    <div class="col-md-2" style="padding: 0px 15px;">
                        <input type="submit" class="btn btn-default" name="submit" value="Submit" style="width: 100%; height: 45px;">
                    </div>
                </div>
            </form>

        </div>

        <br>

        <div class="box-results row">
            <div class="" style="padding: 0 15px">
                <small class="pull-right">3 images</small>
                <h4>TItle of the Page</h4>
            </div>

            <ul class="list-group " style="margin-bottom: 0px;">
                <?php foreach($img_urls as $img_url) { ?>
                    <li class="list-group-item"><?=$img_url?></li>
                <?php } ?>
            </ul>
        </div>

    </div>

    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->

</body>
</html>