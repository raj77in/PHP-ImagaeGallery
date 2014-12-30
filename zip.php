<?php
$source=$_REQUEST['s'];
$destination=$_REQUEST['d'];
$archive=$_REQUEST['a'];
// $source='path/folder'// Path To the folder;
// $destination='path/folder/abc.zip'// Path to the file and file name ; 
$include_dir = false;
// $archive = 'abc.zip'// File Name ;

if (!extension_loaded('zip') || !file_exists($source)) {
    return false;
}

if (file_exists($destination)) {
    unlink ($destination);
}

$zip = new ZipArchive;

if (!$zip->open($archive, ZipArchive::CREATE)) {
    return false;
}
$source = str_replace('\\', '/', realpath($source));
if (is_dir($source) === true)
{

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

    if ($include_dir) {

        $arr = explode("/",$source);
        $maindir = $arr[count($arr)- 1];

        $source = "";
        for ($i=0; $i < count($arr) - 1; $i++) { 
            $source .= '/' . $arr[$i];
        }

        $source = substr($source, 1);

        $zip->addEmptyDir($maindir);

    }

    foreach ($files as $file)
    {
        $file = str_replace('\\', '/', $file);

        // Ignore "." and ".." folders
        if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
            continue;

        $file = realpath($file);

        if (is_dir($file) === true)
        {
            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
        }
        else if (is_file($file) === true)
        {
            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
        }
    }
}
else if (is_file($source) === true)
{
    $zip->addFromString(basename($source), file_get_contents($source));
}
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$archive);
header('Content-Length: '.filesize($archive));
readfile($archive);
unlink($archive);
?>
