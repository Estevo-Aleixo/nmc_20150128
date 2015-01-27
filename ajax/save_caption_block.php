<?php
require_once("../inc/start.php");

if(var_check($_POST['id']) && var_check($_POST['caption_block_value']) && var_check($_POST['caption_block_start'])) {
    if(!var_check($_POST['id'])) {
        die($error_no_id['value']);
    }
    
    $id = $_POST['id'];

    $cue = array(
        "start" => $_POST['caption_block_start'],
        "value" => $_POST['caption_block_value']
    );
    
    $video = new Youtube($id);
    echo $video->update_asr_cue($cue);
    return True;
}

?>
