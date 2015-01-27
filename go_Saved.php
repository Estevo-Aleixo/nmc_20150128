<?php
require_once("inc/start.php");


if(!var_check($_GET['id'])) {
    redirect("index.php?error=No+Video+ID");
}

$id = $_GET['id'];
if(!youtube_validate_id($id)) {
    redirect("index.php?error=Invalid+Video+ID");
}

$edit = var_check($_GET['edit']);

$video = new Youtube($id);
$info = $video->retrieve_info();
if($info == False) {
    redirect("index.php?error=Video+Doesn%27t+Exist");
}

$title = format_string(Config::Get("go.title"), array("ID" => $id, "TITLE" => $info['title']));
$description = format_string(Config::Get("go.description"), array("ID" => $id, "TITLE" => $info['title']));
$keywords = format_string(Config::Get("go.keywords"), array("ID" => $id, "TITLE" => $info['title']));

$stream = $video->get_best_stream();

$caption_url = sprintf("ajax/get_caption.php?id=%s&type=vtt%s", $id, $edit ? "&edit=True" : "");

$caption = "<span class=\"text-success\"><b>Caption</b></span>";
$craption = "<span class=\"text-danger\"><b>Craption</b></span>";
$caption_type = $edit ? $caption : $craption;

#$button_go_craption = "<a href=\"go.php?id=".$id."&edit=True\" id=\"caption\" class=\"btn btn-default btn-block btn-success\" title=\"Edit this Craption\">Edit this Craption</a>";
#$button_go_edit = "<a href=\"go.php?id=".$id."\" id=\"caption\" class=\"btn btn-default btn-block btn-danger\" title=\"Take a look at the original Craption\">Take a look at the original Craption</a>";


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
    <?php require_once("inc/head.php"); ?>

    <body>
        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

        <div class="wrapper">
            <?php require_once("inc/header.php"); ?>
            <div class="row">
                <div class="col-md-12 text-center">
                       <span class="<?php echo $edit ? "text-success" : "text-danger"; ?>"><big><b><?php echo $info['title']; ?></b></big></span>
                </div>
            </div>
            <div class="main">
                        <div id="video-container">
                            <video id="player" autoplay="false" controls preload="metadata">
                            <?php
                        		echo "<source src=\"".$stream['url']."\">\n";
                        		echo "<track id=\"player-track\" kind=\"captions\" srclang=\"en\" src=\"".$caption_url."\" default label=\"default\"></track>\n";
                             ?>
                        	</video>
                    	</div>
                	</div>
                    
                    <!--

                    <div class="row">
                        <div class="col-md-offset-4 col-md-1">
                            <button type="submit" id="play-rate-down" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="Decrease speed rate"><span class="glyphicon glyphicon-minus-sign"></span></button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" id="play-rate-reset" class="form-control input-sm">Reset</button>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" id="play-rate-up" class="form-control input-sm" data-toggle="tooltip" data-placement="bottom" title="Increase speed rate"><span class="glyphicon glyphicon-plus-sign"></span></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr class="low-margin"/>
                        </div>
                    </div>

                    -->

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php
                            
                            #echo "<b>".$caption_type."</b>: <b>".$id."</b><br/>";
                            
                            echo "<big>Download your captions as: </big>";
                            $i = 0;
                            foreach($caption_format as $type) {
                                $i++;
                                echo '<a class="btn btn-default" href="ajax/get_caption.php?id='.$id.($edit ? "&edit=True" : "").'&type='.$type.'&dl=True">'.strtoupper($type).'</a>'.($i == sizeof($caption_format) ? " " : " ");
                            }
                            ?> 
                            
                        </div>
                    </div>
                </div>

                
        
    </body>
    <?php require_once("inc/footer.php"); 
              require_once("inc/js.php"); 
        ?>

</html>
