<?php
/**
 * @author     Dennis Rogers <dennis@drogers.net>
 * @address    www.drogers.net
 * @date       1/26/15
 */

require_once 'app.php';

if(!empty($_POST['save'])){
    switch($_REQUEST['mode']){
        case 'page':
            $app->savePage($_POST);
            break;
        case 'images':
            if(is_array($_FILES)) {
                // upload new images
                $target_dir = $app->baseDir . "images/";
                $target_file = $target_dir . $app->underscore(basename($_FILES["filename"]["name"]));
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                // Check if image file is a actual image or fake image
                if(isset($_POST["save"])) {
                    $check = getimagesize($_FILES["filename"]["tmp_name"]);
                    if($check !== false) {
                        $app->alert("File is an image - " . $check["mime"] . ".");
                        $uploadOk = 1;
                    } else {
                        $app->alert("File is not an image.", 'warning');
                        $uploadOk = 0;
                    }
                    // Check if file already exists
                    if (file_exists($target_file)) {
                        $app->alert("Sorry, file already exists.", 'warning');
                        $uploadOk = 1;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        $app->alert("Sorry, your file was not uploaded.", 'danger');
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file)) {
                            $app->alert("The file ". basename( $_FILES["filename"]["name"]). " has been uploaded.", 'success');
                            $app->saveImage(array('filename' => 'images/' . basename( $target_file)));
                        } else {
                            $app->alert("Sorry, there was an error uploading your file.", 'danger');
                        }
                    }
                }
            }
            return header('Location: '.$app->baseUrl . 'admin.php?mode=images');
            break;
        case 'image':
            $app->saveImage($_POST);
            return header('Location: '.$app->baseUrl . 'admin.php?mode=images');
            break;
    }
    header('Location: '.$app->baseUrl . 'admin.php');
} else if(!empty($_POST['delete'])){
    switch($_REQUEST['mode']){
        case 'image':
            $app->deleteImage($_POST);
            return header('Location: '.$app->baseUrl . 'admin.php?mode=images');
            break;
    }
    header('Location: '.$app->baseUrl . 'admin.php');
} else {
    switch($_REQUEST['mode']){
        case 'logout':
            $_SESSION = array();
            session_destroy();
            header('Location: '.$app->baseUrl . 'admin.php'); 
            break;
        case 'rebuild':
            $siteHtml = file_get_contents($app->baseDir . 'template/index.html');
            
            // pages
            $pages = $app->getPages();
            $pageList = '';
            $pageContent = '';
            foreach($pages as $page) {
                $pageList .= '<li><a data-toggle="modal" data-target="#'.$app->underscore($page->title).'">'.$page->title.'</a></li>';
                
                $pageHtml = file_get_contents($app->baseDir . 'template/page.html');
                $pageHtml = $app->tag('title', $page->title, $pageHtml);
                $pageHtml = $app->tag('key', $app->underscore($page->title), $pageHtml);
                $pageHtml = $app->tag('content', $page->content, $pageHtml);
                $pageContent .= $pageHtml;
            }
            $siteHtml = $app->tag('page_titles', $pageList, $siteHtml);
            $siteHtml = $app->tag('pages', $pageContent, $siteHtml);
            
            // images
            $images = $app->getImages();
            $imageCounter = '';
            $imageHtml = '';
            $i = 0;
            foreach($images as $image) {
                $imageCounter .= '<li data-target="#main-carousel" data-slide-to="'.$i.'"'.($i==0?' class="active"':'').'></li>';
                $imageHtml .= '<div class="item'.($i++==0?' active':'').'"><img src="'.$image->filename.'" alt="'.$image->caption.'" /></div>';
            }
            $siteHtml = $app->tag('image_counter', $imageCounter, $siteHtml);
            $siteHtml = $app->tag('images', $imageHtml, $siteHtml);
            
            file_put_contents($app->baseDir . 'index.html', $siteHtml);
            $app->alert("Site has been rebuilt.", 'success');
            break;
    }
    if(!empty($_SESSION['messages'])) {
        echo implode('', $_SESSION['messages']);
        unset($_SESSION['messages']);
    }
}



