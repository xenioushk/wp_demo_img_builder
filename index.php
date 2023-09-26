<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Demo Image Builder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="">
    <link rel="apple-touch-icon-precomposed" href="">
    <link rel="shortcut icon" href="favicon.png">


    <style type="text/css">
        body {
            font-family: sans-serif;
            font-size: 15px;
        }
    </style>

</head>


<body>



    <?php

    $placeholder_dir = 'placeholder';
    $orginal_dir = 'src_uploads';
    $new_demo_dir = 'dist_uploads';

    function get_file_extension($file_name)
    {
        return substr(strrchr($file_name, '.'), 1);
    }

    function get_place_holder_image($width = 0)
    {

        if ($width > 1000) {

            $custom_width = "_1000";
        } else if ($width > 500 && $width < 1000) {

            $custom_width = "_500";
        } else if ($width > 200 && $width < 500) {
            $custom_width = "_200";
        } else if ($width > 100 && $width < 50) {

            $custom_width = "_100";
        } else {
            $custom_width = "_50";
        }

        return "demo_img" . $custom_width . ".jpg";
    }

    function ak_img_resize($target, $newcopy, $w, $h, $ext)
    {

        list($w_orig, $h_orig) = getimagesize($target);
        $scale_ratio = $w_orig / $h_orig;
        if (($w / $h) > $scale_ratio) {
            $w = $h * $scale_ratio;
        } else {
            $h = $w / $scale_ratio;
        }
        $img = "";
        $ext = strtolower($ext);
        if ($ext == "gif") {
            $img = imagecreatefromgif($target);
        } else if ($ext == "png") {
            $img = imagecreatefrompng($target);
        } else {
            $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        if ($ext == "gif") {
            imagegif($tci, $newcopy);
        } else if ($ext == "png") {
            imagepng($tci, $newcopy);
        } else {
            imagejpeg($tci, $newcopy, 84);
        }
    }

    function ak_img_thumb($target, $newcopy, $w, $h, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $src_x = ($w_orig / 2) - ($w / 2);
        $src_y = ($h_orig / 2) - ($h / 2);
        $ext = strtolower($ext);
        $img = "";
        if ($ext == "gif") {
            $img = imagecreatefromgif($target);
        } else if ($ext == "png") {
            $img = imagecreatefrompng($target);
        } else {
            $img = imagecreatefromjpeg($target);
        }
        $tci = imagecreatetruecolor($w, $h);
        imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
        if ($ext == "gif") {
            imagegif($tci, $newcopy);
        } else if ($ext == "png") {
            imagepng($tci, $newcopy);
        } else {
            imagejpeg($tci, $newcopy, 84);
        }
    }

    function listFolderFiles($dir)
    {

        global $orginal_dir, $new_demo_dir, $placeholder_dir;
        $ffs = scandir($dir);
        echo '<ol>';

        $excluded_files = array(
            'close.gif' => 'close.gif',
            'down_arrow.png' => 'down_arrow.png',
            'next.gif' => 'next.gif',
            'preload.gif' => 'preload.gif',
            'preload-circle.png' => 'preload-circle.png',
            'uparr-48-b.png' => 'uparr-48-b.png',
            'logo.png' => 'logo.png',
            'logo-footer.png' => 'logo-footer.png',
            'loader.gif' => 'loader.gif',
            'separator.png' => 'separator.png',
            'favicon.png' => 'favicon.png',
            'pattern_1.png' => 'pattern_1.png'
        );

        echo "<pre>";
        print_r($excluded_files);
        echo "</pre>";

        foreach ($ffs as $ff) {

            if ($ff != '.' && $ff != '..') {

                echo '<li>' . $dir . '/' . $ff;

                if (is_dir($dir . '/' . $ff)) :

                    $only_dir = $dir . '/' . $ff;

                    echo "<pre style='background:#EEE;'>";
                    echo $only_dir;
                    echo "</pre>";

                    echo "<pre style='background:#EEE;'>";
                    echo $new_folder = str_replace($orginal_dir, $new_demo_dir, $only_dir);
                    echo "</pre>";

                    if (!file_exists($new_folder)) {
                        mkdir($new_folder);
                    }

                    listFolderFiles($only_dir);

                else :

                    $file_url = $dir . '/' . $ff;

                    $file_extension = get_file_extension($file_url);
                    list($width, $height) = getimagesize($file_url);

                    echo "<pre>";
                    echo "width: " . $width . "<br />";
                    echo "height: " . $height;
                    echo "</pre>";

                    //                    $placeholder_file = get_place_holder_image($width);
                    $placeholder_file = 'placehold_image.jpg';

                    $placeholder_url = $placeholder_dir . '/' . $placeholder_file;

                    $file_name = basename($file_url);

                    if ($file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "png") {

                        echo "<pre>";
                        echo $file_url;
                        echo "</pre>";

                        echo "<pre style='background:#fafafa;'>";
                        echo $new_file = str_replace($orginal_dir, $new_demo_dir, $file_url);
                        echo "</pre>";

                        if (!file_exists($new_file)) {

                            echo "<pre>";
                            echo "Create new file with name: " . $file_name;
                            echo "</pre>";

                            echo "<pre>";
                            echo "PL: " . $placeholder_url;
                            echo "</pre>";

                            $new_file_path = str_replace($file_name, '', $new_file);

                            echo "<pre>";
                            echo "New File Destination: " . $new_file_path;
                            echo "</pre>";


                            if (array_key_exists($file_name, $excluded_files)) {
                                $placeholder_url = $file_url;
                                echo "<pre style='background: yellow;'>";
                                print_r($placeholder_url);
                                echo "</pre>";

                                $replace = 0;
                            } else {

                                $replace = 1;
                            }

                            //                            copy($placeholder_url, $new_file);

                            $target_file = $placeholder_url;
                            $resized_file = $new_file;
                            $wmax = $width;
                            $hmax = $height;
                            $fileExt = $file_extension;

                            echo "<pre>";
                            echo "target_file" . $target_file;
                            echo "<br>";
                            echo "resized_file" . $resized_file;
                            echo "<br>";
                            echo "Wmax" . $wmax;
                            echo "<br>";
                            echo "Hmax" . $hmax;
                            echo "<br>";
                            echo "FileExt" . $fileExt;
                            echo "</pre>";

                            if ($replace == 1) {

                                ak_img_thumb($target_file, $resized_file, $wmax, $hmax, $fileExt);
                            } else {

                                copy($placeholder_url, $new_file);
                            }
                        }
                    }


                endif;
                echo '</li>';
            }
        }
        echo '</ol>';
    }

    listFolderFiles($orginal_dir);

    ?>


</body>

</html>