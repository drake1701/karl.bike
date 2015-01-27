<?php
/**
 * @author     Dennis Rogers <dennis@drogers.net>
 * @address    www.drogers.net
 * @date       1/26/15
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>karl.bike</title>
        
        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="css/admin.css" rel="stylesheet">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php require_once 'app.php'; ?>
        <?php require_once 'access.php'; ?>
        <div class="navbar navbar-inverse">
            <div class="container">    
                <div class="navbar-header">
                    <a class="navbar-brand" href="?">Admin</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" role="button" href="#" aria-expanded="false">Pages <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php foreach($app->getPages() as $page): ?>
                                <li><a href="?mode=page&id=<?php echo $page->id ?>"><?php echo $page->title ?></a></li>
                                <?php endforeach; ?>
                                <li class="divider"></li>
                                <li><a href="?mode=page">Add New</a></li>
                            </ul>
                        </li>
                        <li><a href="?mode=images">Images</a></li>
                        <li class="nav-right"><a href="?mode=logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container"><?php require_once 'control.php'; ?></div>
        <div class="well">
            <div class="container">
                <?php
                switch($_REQUEST['mode']){
                    case 'page':
                        $new = true;
                        if(!empty($_REQUEST['id'])) {
                            $page = $app->getPage($_REQUEST['id']);
                            $new = false;
                        }
                    ?>
                        <form method="post">
                            <h2 class="form-signin-heading"><?php echo $new ? 'New' : 'Edit' ?> Page</h2>
                            <label for="pageTitle">Title</label>
                            <input type="text" id="pageTitle" name="title" class="form-control" placeholder="Title" value="<?php if(!$new) echo $page->title ?>" required autofocus>
                            <label for="pageContent">Content</label>
                            <textarea id="pageContent" name="content" class="form-control" placeholder="Content" rows="20" required><?php if(!$new) echo $page->content; ?></textarea>
                            <div class="checkbox">
                                <label><input type="checkbox" id="pageStatus" name="status" <?php if(!$new && $page->status == 1) echo 'checked="checked"' ?>>Enabled</label>
                            </div>
                            <label for="pageOrder">Order</label>
                            <input type="text" id="pageTitle" name="order" class="form-control" placeholder="Order" value="<?php if(!$new) echo $page->order ?>">
                            <input type="hidden" value="1" name="save" />
                            <?php if(!$new): ?>
                            <input type="hidden" value="<?php echo $page->id ?>" name="id" />
                            <?php endif; ?>
                            <br/>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
                        </form>
                    <?php break;
                    case 'images': ?>
                        <div class="row">
                            <?php foreach($app->getImages() as $image): ?>
                            <div class="col-md-4<?php if($image->status == 0) echo ' disabled' ?>">
                                <a href="?mode=image&id=<?php echo $image->id ?>" ><img src="<?php echo $image->filename ?>" alt="<?php echo $image->caption ?>" /></a>
                                <p><?php echo $image->caption ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <form method="post" enctype="multipart/form-data">
                            <label for="imageFilename">Add a File</label>
                            <input type="file" name="filename" class="form-control" id="imageFilename">
                            <input type="hidden" value="1" name="save" />
                            <br/>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Upload</button>
                        </form>
                    <?php break;
                    case 'image':
                        if(!empty($_REQUEST['id'])) {
                            $image = $app->getImage($_REQUEST['id']);
                        } else {
                            header('Location: '.$app->baseUrl . 'admin.php?mode=images'); 
                        }
                    ?>
                        <a href="?mode=images"><button class="btn btn-lg btn-secondary btn-block"><span>View All</span></button></a><br/>
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post">
                                    <h2 class="form-signin-heading">Edit Image</h2>
                                    <label for="imageCaption">Caption</label>
                                    <input type="text" id="imageCaption" name="caption" class="form-control" placeholder="Caption" value="<?php echo $image->caption ?>" autofocus>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="status" <?php if($image->status == 1) echo 'checked="checked"' ?>>Enabled</label>
                                    </div>
                                    <label for="imageOrder">Order</label>
                                    <input type="text" id="imageOrder" name="order" class="form-control" placeholder="Order" value="<?php echo $image->order ?>">
                                    <input type="hidden" value="1" name="save" />
                                    <input type="hidden" value="<?php echo $image->id ?>" name="id" />
                                    <br/>
                                    <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <img src="<?php echo $image->filename ?>" alt="<?php echo $image->caption ?>" ?>
                            </div>
                        </div>
                    <?php break;
                    default:
                        ?><a href="?mode=rebuild"><button class="btn btn-lg btn-primary btn-block"><span>Rebuild Site</span></button></a><br/><?php
                        break;
                }
                ?>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
