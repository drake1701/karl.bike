<?php
/**
 * @author		Dennis Rogers
 * @address		www.drogers.net
 */

class Karl_App
{

    public $db;
    public $baseDir = '/var/www/html/dev/';
    public $baseUrl = 'http://karl.bike/dev/';

    protected $pages;
    protected $images;
    
    function __construct() {
        $this->db = new PDO('sqlite:'.$this->baseDir.'karl.sqlite');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /* Load specific page by id */
    public function getPage($pageId) {
        $pageRequest = $this->db->prepare('SELECT * FROM `page` WHERE id = ?;');
        $pageRequest->execute(array($pageId));
        
        $page = $pageRequest->fetchObject();
        return $page;
    }
    
    /* Load all pages */
    public function getPages() {
        if(empty($this->pages)){
            $pageRequest = $this->db->prepare('SELECT * FROM `page` ORDER BY `order` ASC, `title` ASC;');
            $pageRequest->execute();
            
            $pages = array();
            while($row = $pageRequest->fetchObject()){
                $pages[] = $row;
            }
            $this->pages = $pages;
        }
        return $this->pages;
    }
    
    /* save page */
    public function savePage($postData) {
        unset($postData['save']);
        if($postData['id']){
            $pageRequest = $this->db->prepare('UPDATE `page` SET `title` = ?, `content` = ?, `status` = ?, `order` = ? WHERE `id` = ?;');
            $pageData = array(
                $postData['title'],
                $postData['content'],
                (isset($postData['status']) ? 1 : 0),
                $postData['order'],
                $postData['id']
            );
        } else {
            $pageRequest = $this->db->prepare('INSERT INTO `page` (`title`, `content`, `status`, `order`) VALUES (?, ?, ?, ?);');
            $pageData = array(
                $postData['title'],
                $postData['content'],
                (isset($postData['status']) ? 1 : 0),
                $postData['order']
            );
        }
        $pageRequest->execute($pageData);
        $this->alert('Page saved.', 'success');
    }
        
    /* Load specific image by id */
    public function getImage($imageId) {
        $imageRequest = $this->db->prepare('SELECT * FROM `image` WHERE id = ?;');
        $imageRequest->execute(array($imageId));
        
        $image = $imageRequest->fetchObject();
        return $image;
    }
    
    /* Load all images */
    public function getImages() {
        if(empty($this->images)){
            $imagesRequest = $this->db->prepare('SELECT * FROM `image` ORDER BY `order` ASC, `caption` ASC;');
            $imagesRequest->execute();
            
            $pages = array();
            while($row = $imagesRequest->fetchObject()){
                $images[] = $row;
            }
            $this->images = $images;
        }
        return $this->images;
    }

    /* save image */
    public function saveImage($postData) {
        unset($postData['save']);
        if($postData['id']){
            $imageRequest = $this->db->prepare('UPDATE `image` SET `caption` = ?, `status` = ?, `order` = ? WHERE `id` = ?;');
            $imageData = array(
                $postData['caption'],
                (isset($postData['status']) ? 1 : 0),
                $postData['order'],
                $postData['id']
            );
        } else {
            // only new images will be uploads
            $imageRequest = $this->db->prepare('INSERT INTO `image` (`filename`, `status`) VALUES (?, ?);');
            $imageData = array(
                $postData['filename'],
                0
            );
        }
        $imageRequest->execute($imageData);
        $this->alert('Image saved.', 'success');
    }   
    
    function tag($tagName, $content, $html) {
        $tagName = str_replace("{{", "", str_replace("}}", "", $tagName));
        return preg_replace("#\{\{$tagName\}\}#", $content, $html);
    } 
    
    
    public function alert($msg, $mode='info') {
        $_SESSION['messages'][] = '<div class="alert alert-'.$mode.'" role="alert">'.$msg.'</div>';
    }
    
    public function underscore($string) {
        $string = strtolower($string);
        $string = preg_replace('#[^a-z0-9 \.]#s', '', $string);
        $string = str_replace(' ', '_', $string);
        return $string;
    }

}
$app = new Karl_App();







