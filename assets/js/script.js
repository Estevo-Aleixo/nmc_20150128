var captions;
var player;
var current_caption_index = -1;
var id;
var edit;
var jump_back_delay = 2;
var jump_delay = 2;
var speed_rate = 0.25;
var cue_changing;

function cue_refresh() { // Display the current cue in each block
    $.each(captions, function(index, caption) {
        timing_start = caption['start']
        timing_end = (+timing_start + +caption['dur']);
        if(player.currentTime > timing_start && player.currentTime < timing_end) {
            $("textarea#edit-before").val((index - 1 >= 0) ? captions[index - 1].value : "");
            $("span#edit-current-start").html(parseFloat(caption['start']).toFixed(2))
            $("span#edit-current-end").html(parseFloat(+caption['start'] + +caption['dur']).toFixed(2))            
            $("textarea#edit-current").val(caption.value);
            $("textarea#edit-after").val((index + 1 < captions.length) ? captions[index + 1].value : "");
            current_caption_index = index;
            return false; // No need to iterate anymore.
            
        }
    });
}

function cue_onchange() {
    if(player.textTracks[0].activeCues != null) {
        if(player.textTracks[0].activeCues.length != 0) {
            if(player.textTracks[0].activeCues[0].startTime != cue_changing) {
                cue_refresh();
                cue_changing = $("video")[0].textTracks[0].activeCues[0].startTime;
            }
        }
    }

}


$(document).ready(function() {

    edit = $("#caption-edit").val();
    id = $("#youtube-id").val();
    

    $("[data-toggle='tooltip']").tooltip();
    
    jQuery.extend({
        get_caption_json: function(id, edit) { // Get the caption in JSON for the editor
            var result = null;
            $.ajax({
                url: "ajax/get_caption.php?id=" + id + "&type=json" + ((edit) ? "&edit=True" : ""),
                type: 'get',
                dataType: 'json',
                async: false,
                cache: false,
                success: function(data) {
                    result = data;
                }
            });
            cue_change_interval = setInterval(cue_onchange, 50);
            return result;
        }
    });

    $("#play-rate-up").on("click", function() {
        player.playbackRate += player.playbackRate * speed_rate;
    });

    $("#play-rate-reset").on("click", function() {
        player.playbackRate = 1.0;
    });

    $("#play-rate-down").on("click", function() {
        player.playbackRate -= player.playbackRate * speed_rate;
    });

    $(document).on("keydown", function(e) {
        if(e.shiftKey) {
            if (e.keyCode == 39) { // Right: jump forward
                player.currentTime += +jump_delay;
            } else if(e.keyCode == 37) { // Left: jump backward
                player.currentTime -= +jump_delay;
            } else if(e.keyCode == 40) { // Bottom: Go beginning)
                player.currentTime = 0;
            } else if(e.keyCode == 38) { // Top: Go end
                player.currentTime = player.duration;
            }
        }
        if(e.keyCode == 27) { // Space: Toggle play/pause
            if(player.paused) {
                player.play();
            } else {
                player.pause();
            }
        }
    });


    if(edit) {

        $("#edit-before").on("click", function(e) {
            index = current_caption_index - 1;
            if(index < 0) {
                index = 0;
            }
            
            player.currentTime = captions[index]['start'] + 0.1;
            $("#edit-current").focus();
            player.play();
        });

        $("#edit-after").on("click", function(e) {
            index = current_caption_index + 1;
            if(index >= captions.length) {
                index = captions.length - 1;
            }
            
            player.currentTime = captions[index]['start'] + 0.1;
            player.play();
        });

        $("#edit-current").on("click input", function(e) {
            if(current_caption_index == -1) { // Not ready
                    return false;
                }
            player.pause();

        });

        $("#edit-current").on("keydown", function(e) { // enter don't put newline (just submit)
            if(e.keyCode == 13 && e.shiftKey){
                e.preventDefault();
            }
        });

        $("#edit-current").on("keyup", function(e) { // enter submit to save
            if(e.keyCode != 16 && e.shiftKey == false && e.keyCode == 32) { // CTRL
                player.pause();
            }
            if(e.keyCode == 13 && e.shiftKey) {
                if(current_caption_index == -1) { // Not ready
                    return false;
                }
                post_data = {
                    id: $("#youtube-id").val(),
                    caption_block_value: $("#edit-current").val(),
                    caption_block_start: captions[current_caption_index]['start'],
                }
                $.post("ajax/save_caption_block.php", post_data, function(data) {
                    clearInterval(cue_change_interval);
                    $("#player track").remove();
                    $("<track id=\"player-track\" kind=\"captions\" src=\"ajax/get_caption.php?id=" + id + "&type=vtt&edit=True&ts="+new Date().getTime()+"\" default>").appendTo("#player");
                    $("#player track").load(function() {
                        player.textTracks[0].mode = "showing";
                        captions = $.get_caption_json($("#youtube-id").val(), true);
                        player.currentTime = +player.currentTime - +jump_back_delay;
                        console.log(player.readyState)
                        cue_change_interval = setInterval(cue_onchange, 50);
                        player.play();
                    });
                    
                }); 
            }
        });

    }

    captions = $.get_caption_json($("#youtube-id").val(), edit);
    player = $("#player")[0];

    $("#settings").on("click", function() {
        player.pause();
        $("#settings-jump-back-delay").val(jump_back_delay);
        $("#settings-jump-delay").val(jump_delay);
    });

    $("#settings-modal-save").on("click", function() {
        jump_back_in_save_delay = $("#settings-jump-back-delay").val();
        jump_delay = $("#settings-jump-delay").val();
        setTimeout(function() {player.play()}, 800);
        
    });


});
