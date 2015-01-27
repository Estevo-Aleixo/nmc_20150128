<?php 
if($page == "go.php") {
    echo '<input type="hidden" id="youtube-id" value="'.$id.'"/>';
    if($edit) {
        echo '<input type="hidden" id="caption-edit" value="True"/>';
    }
}
?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<?php if($page == "index.php") { ?>
<script src="assets/js/index.js"></script>
<?php } elseif($page == "go.php") { ?>
<script src="assets/js/script.js"></script>
<?php } ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-22770104-6', 'auto');
  <?php
  if($gaid = var_check($_GET['id'], True)) {
    echo "ga('set', 'YOUTUBE_ID', '".$gaid."');\n";
  }
  ?>
  ga('send', 'pageview');
</script>
