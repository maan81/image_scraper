<?php
require('simple_html_dom.php');

function page_title_fn($url){

    $html = file_get_html($url);

    $page_title = $html->find('.btn-reader-chapter',0)->find('a',0)->plaintext;

    return $page_title;
}
function page_urls_fn($url){

    $html = file_get_html($url);

    // $page_title = page_title_fn($html);
    // echo $page_title;
    // die;

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

$page_title = false;
$page_urls = [];
$img_urls = [];
$errors = false;
$url = '';


if(!empty($_POST['url'])){

    $url = $_POST['url'];

    if (filter_var($url, FILTER_VALIDATE_URL) !== false){

        $page_title = page_title_fn($url);

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
    <title>Image Scraper</title>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style type="text/css">
        .padding-top-bottom {
            padding: 5px 0;
        }
    </style>
</head>
<body>

    <div class="container" style="margin-left: auto; margin-right: auto; width: 750px;">

        <div id="query">

            <div class="alert alert-danger <?=$errors?'':'hide'?>"><?=$errors?></div>

            <form class="form-inline row padding-top-bottom" method="post">

                <div class="form-group col-md-12 padding-top-bottom form-inputs">
                    <label style="padding: 10px 0px;" class="col-md-2 control-label" for="url">
                        ReadMe Links :
                    </label>
                    <div style="padding-left: 0; padding-right: 0" class="col-md-10">
                        <input type="text" style="width: 100%" class="form-control" id="url"
                                name="url" placeholder="http://example.com" value="<?=$url?>">
                    </div>
                </div><!-- /form-inputs -->

                <div class="col-md-12 padding-top-bottom form-submit">
                    <input type="submit" value="Submit" name="submit"
                            class="btn btn-default col-md-2 center-block" style="float: none;">
                </div><!-- /form-submit -->

            </form>

        </div><!-- /query -->

        <hr>

        <div id="result" class="<?=empty($page_title)?'hide':''?>">

            <div class="row">

                <div class="col-md-12 padding-top-bottom result-header">
                    <small class="pull-right" style="margin-top: 10px; margin-right: 5px">
                        <?=count($img_urls)?> image(s)
                    </small>
                    <h4 class="text-center"><strong><?=$page_title?></strong></h4>
                </div><!-- /result-header -->

                <div class="col-md-12 padding-top-bottom result-list">
                    <textarea id="urls"
                            style="height: 350px; background: transparent none repeat scroll 0px 0px; resize:vertical;"
                            readonly="readonly"
                            class="col-md-12 form-control textarea"><?=implode(PHP_EOL, $img_urls)?></textarea>
                </div><!-- /result-list -->

                <div class="col-md-12 padding-top-bottom result-buttons">
                    <button id="select_all" class="btn btn-default" style="margin-right: 10px;">
                        Select All
                    </button>
                    <button id="copy" class="btn btn-default">
                        Copy
                    </button>
                </div><!-- /result-buttons -->

            </div><!-- /row -->

        </div><!-- /result -->

    </div><!-- /container -->



    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $('#submit').click(function(){

        });

        /* http://stackoverflow.com/questions/22581345/click-button-copy-to-clipboard-using-jquery */
        function copyToClipboard(elem) {
            // create hidden text element, if it doesn't already exist
            var targetId = "_hiddenCopyText_";
            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
            var origSelectionStart, origSelectionEnd;
            if (isInput) {
                // can just use the original source element for the selection and copy
                target = elem;
                origSelectionStart = elem.selectionStart;
                origSelectionEnd = elem.selectionEnd;
            } else {
                // must use a temporary form element for the selection and copy
                target = document.getElementById(targetId);
                if (!target) {
                    var target = document.createElement("textarea");
                    target.style.position = "absolute";
                    target.style.left = "-9999px";
                    target.style.top = "0";
                    target.id = targetId;
                    document.body.appendChild(target);
                }
                target.textContent = elem.textContent;
            }
            // select the content
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);

            // copy the selection
            var succeed;
            try {
                  succeed = document.execCommand("copy");
            } catch(e) {
                succeed = false;
            }
            // restore original focus
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }

            if (isInput) {
                // restore prior selection
                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
            } else {
                // clear temporary content
                target.textContent = "";
            }
            return succeed;
        }

        $('#copy').click(function(){
            copyToClipboard(document.getElementById('urls'));
        });

        $('#select_all').click(function(){
            $('#urls').select();
        });
    </script>

</body>
</html>
