<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ParserImage</title>
    <script src="lib/jquery-3.3.1.min.js"></script>
    <script src="lib/script.js"></script>
</head>
<body>

<form id="form" method="post">
<span>Url</span>
<input id="url" name="url" value="<?=$_POST['url'];?>">
<span>Type</span>
<select id="type" name="type">
    <option>jpg</option>
    <option>png</option>
    <option>gif</option>
</select>
<button type="submit" name="send">Search</button>
</form>

<?php
include_once('lib/simple_html_dom.php');

function curl_get ($url, $referer = 'https://www.google.com.ua/'){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.110 Safari/537.36');
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

function find_img ($html, $type, $folder){

    foreach($html->find('a') as $el) {
        if ((substr($el->href, strlen($el->href)-3, 3) == $type) and (substr($el->href, 0, 4) == 'http')){
            $pieces = explode('/', $el->href);
            $name = $pieces[count($pieces)-1];
            file_put_contents($folder.'/'.$name, file_get_contents($el->href), FILE_APPEND);
        }
    }

    foreach($html->find('img') as $el) {		
        if ((substr($el->src, strlen($el->src)-3, 3) == $type) and (substr($el->src, 0, 4) == 'http')){
            $pieces = explode('/', $el->src);
            $name = $pieces[count($pieces)-1];
            file_put_contents($folder.'/'.$name, file_get_contents($el->src), FILE_APPEND);			
        }
    }

}

if(isset($_POST['url'])){
    
    $url = $_POST['url'];
    $type = $_POST['type'];
    $folder = 'image';
    
    if (file_exists($folder) == false){
        mkdir($folder);
    }

    find_img(str_get_html(curl_get($url)), $type, $folder);

}
?>

</body>
</html>