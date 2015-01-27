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
                <div class="col-md-8 text-center">
                       <span class="<?php echo $edit ? "text-success" : "text-danger"; ?>"><big><b><?php echo $info['title']; ?></b></big></span>
                </div>
                <div class="col-md-4 text-center">
                
                    <!--
                        <?php echo "<span>ID: <b>".$id."</b> | ".$caption_type."</span>"; ?>
                
                    -->

                </div>
            </div>
            <div class="row main">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12" id="video-container">
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

                <div class="col-md-4 caption-editor-wrapper">
                    <div class="row">
                        <div class="col-md-8">
                            <?php echo $edit ? $button_go_edit : $button_go_craption; ?>
                        </div>
                        <div class="col-md-6">
                            <!-- 

                            <button type="submit" id="settings" class="btn btn-default btn-block" data-toggle="modal" data-target="#settings-modal"><span class="glyphicon glyphicon-cog"></span> Settings</button>
                            
                            -->
                            <!-- Small modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">How do I get started?</button>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                    <h1>How do I fix the craptions?</h1>
                                    <p>STEP 1: Click on the yellow CURRENT caption text box.</p>
                                    <p>STEP 2: This pauses the video so you can make your corrections.</p>
                                    <p>STEP 3: Make sure you save your corrections by using SHIFT + ENTER key combo.</p>
                                    <p>STEP 4: Rinse and Repeat until you have fixed the entire video.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <!-- TWEET BUTTON via Twitter -->
                            <a href="https://twitter.com/share" class="text-right twitter-share-button" data-via="mlockrey" data-size="large">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-11">
                            <div id="edit_area">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><big>PREVIOUS <?php echo $caption_type; ?>:</big></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo '<textarea id="edit-before" class="form-control" cols="50" '.($edit ? "readonly=\"readonly\"" : "disabled").'></textarea>'; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><big>CURRENT <?php echo $caption_type; ?>:</big></span>
                                    </div>
                                    
                                    <!-- COMMENTED OUT CODE - ML - 20150101

                                    <div class="col-md-2">
                                        <span id="edit-current-start">00.00</span>
                                    </div>
                                    <div class="col-md-1">
                                        <span><b>TO</b></span>
                                    </div>
                                    <div class="col-md-2">
                                        <span id="edit-current-end">00.00</span>
                                    </div>

                                    -->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo '<textarea id="edit-current" class="form-control"'.($edit ? "" : " disabled").'></textarea>'; ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span><big>NEXT <?php echo $caption_type; ?>:</big></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo '<textarea id="edit-after" class="form-control" '.($edit ? "readonly=\"readonly\"" : "disabled").'></textarea>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 

            <div class="row">
                <div class="col-md-12">
                    <hr class="low-margin"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-1 col-md-3">
                    <button type="submit" id="help" class="btn btn-default" data-toggle="modal" data-target="#help-modal"><span class="glyphicon glyphicon-info-sign"></span> How does it works?</button>
                </div>
                <div class="col-md-offset-1 col-md-3">
                    <button type="submit" id="googleform" class="btn btn-default" data-toggle="modal" data-target="#googleform-modal"><span class="glyphicon glyphicon-pencil"></span> Report your user viewpoint</button>
                </div>
            </div>
            

            <div class="row">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            
            -->
        </div>
    </div>
    <div class="modal fade" id="settings-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Settings</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <label for="settings-jump-back-delay">Jump back delay when caption get saved:</label>
                            <input type="text" id="settings-jump-back-delay" name="settings-jump-back-delay" class="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <label for="settings-jump-delay">Jump delay by using Shift + Left/Right:</label>
                            <input type="text" id="settings-jump-delay" name="settings-jump-delay" class="form-control"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-3 col-md-6">
                            <label for="settings-caption-position">Caption position (not yet working):</label>
                            <select id="settings-caption-position" name="settings-caption-position" class="form-control">
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="settings-modal-save" class="btn btn-primary" data-dismiss="modal">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="googleform-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Settings</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <iframe src="https://docs.google.com/forms/d/1dEFzKEM1CuhFkckDLB3D8Lonyl_jKfjwesBeY6ZU9lU/viewform?embedded=true" frameborder="0" border="1" marginheight="0" marginwidth="0" style="width: 100%; height: 400px;">Loading...</iframe>
                        </div>
                    </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Help</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <big><b>Here are some tips that will make it easier for you</b></big>
                            <ul>
                                <li>Press Shift + Enter in the caption editing area to save and update.</li>
                                <li>Press Shift + Space to toggle play and pause.</li>
                                <li>Press Shift + Left to go backward some seconds in time</li>
                                <li>Press Shift + Right to go forward some seconds in time</li>
                                <li>Press Shift + Bottom to go back to the beginning</li>
                                <li>Press Shift + Top to go to the end</li>
                            </ul>
                        </div>
                    </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
        
    </body>
    <?php require_once("inc/footer.php"); 
              require_once("inc/js.php"); 
        ?>

</html>
