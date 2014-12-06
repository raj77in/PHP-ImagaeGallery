<?php
//path to directory to scan. i have included a wildcard for a subdirectory
$directory = "images/*/";

//get all image files with a .jpg extension.
$images = glob("" . $directory . "*.[jJ][Pp][Gg]");
echo <<<EOF
<center><h3>Gallery script by Amit Agarwal</h3></center>


       <script language="javascript" type="text/javascript"
       src="/javascript/jquery/jquery.js"></script>

       <script language="javascript" type="text/javascript"
       src="/javascript/jquery-mousewheel/jquery.mousewheel.js"></script>

       <script language="javascript" type="text/javascript"
       src="/javascript/jquery-easing/jquery.easing.js"></script>
 <!-- Add mousewheel plugin (this is optional) -->
 <script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

 <!-- Add fancyBox -->
 <link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
 <script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<!-- Optionally add helpers - button, thumbnail and/or media -->
 <link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
 <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
 <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

 <link rel="stylesheet" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
 <script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<script>
var images = new Array();

EOF;

$imgs = '';
$olddir="";
$count=1;
echo "var images={\n";
foreach ($images as $image){
    $exif=array();
    $parts=pathinfo($image);
    if ($olddir != $parts['dirname'] ) {
        if ( $count != 1) echo "],\n";
        echo "$count: [\n";
        $count++;
        $olddir=$parts['dirname'];
        //$html.='<a class="open_fancybox" href="'.$image.'"><img width=250 src="'.$image.'" alt=""/></a>'."\n";
        $html.='<a class="open_fancybox" href="'.$image.'">
            <div style="width:250 ; float: left; font-size:80%;text-align:center;">
            <img width=250 src="'.$image.'" alt="" style="padding-bottom:0.5em;" />
            '.substr($olddir,7).'
            </div>
            </a>'."\n";
        //<figure>
        //<img width=250 src="'.$image.'" alt=""/>
        //<figcaption>'.substr($olddir,7).'</figcaption>
        //</figure>
    }
    $exif = exif_read_data($image, 'IFD0');
    $title=join(',', array(
        $parts['filename'],
        $exif['Model'],
        "Exp: ".$exif['ExposureTime'],

    ));
    echo "{\nhref : '$image',
        title: '".$title."'
        \n},\n";


}
echo ']';

echo <<<EOF
};

$(document).ready(function() {
$(".fancybox").fancybox();

$(".open_fancybox").click(function() {
$.fancybox.open(images[$(this).index()+1],{
padding:0,
preload:3,
helpers:  {
        thumbs : {
            width: 150,
            height: 150
        },
 title : {
            type : 'inside'
        },
        overlay : {
            showEarly : true
        }

    }

});
return false;
});

});

</script>

EOF;
echo '<div style="width:100%;max-width:100%; max-height:100%; float=none;clear:both;overflow:auto;border: 1px solid #000000;
;z-index:100;">';
echo $html;
echo '</div><br>';
exit;
?>

