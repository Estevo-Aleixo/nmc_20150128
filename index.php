<?php
require_once("inc/start.php");
$title = Config::Get("index.title");
$description = Config::Get("index.description");
$keywords = Config::Get("index.keywords");
$error_message = var_check($_GET['error'], True);
$id = var_check($_GET['youtube-id'], True);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
    <?php require_once("inc/head.php"); ?>

    <body>
        <div class="wrapper">
            <?php
                require_once("inc/header.php");
                if($error_message) {
                    echo '<div class="row"><div class="bg-danger col-md-offset-3 col-md-6 text-center"><span class="text-danger"><big><b>'.$error_message.'</b></big></span></div></div>';
                    echo '<div class="row"><div class="col-md-12"><hr class="low-margin"/></div></div>';
                }
            ?>
            <div class="row">
                <div class="col-md-offset-3 col-md-6 text-center">
                    <form role="form">
                        <div class="form-group row">
                            <label for="youtube-id"><p class="lead">It's so easy to get started and fix the automatic craptioning on YouTube videos.</p> 
                                                    <p class="lead">Why should I bother? Well unless they're corrected they don't provide accessibility outcomes for users who rely on good quality captioning.</p></label>
                            <input type="text" class="form-control input-lg text-center" id="youtube-id" placeholder="Simply paste the YouTube URL or Video ID here" name="youtube-id" value="<?php echo $id; ?>">
                        </div>
                    </form>
                    <button id="submitButton" class="btn btn-lg" style="background-color:cornflowerblue">Submit</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center" id="youtube-video-button">
                </div>
            </div>
        </div>
    </body>
<?php require_once("inc/footer.php");
      require_once("inc/js.php"); 
?>    
</html>
