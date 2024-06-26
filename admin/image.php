<?php
session_start();

if (!isset($_SESSION['post']['image_type']) || !isset($_SESSION['post']['image_data'])) {
    exit('No image data.');
}

switch ($_SESSION['post']['image_type']) {
    case IMAGETYPE_JPEG:
        header('content-type: image/jpeg');
        break;

    case IMAGETYPE_PNG:
        header('content-type: image/png');
        break;

    case IMAGETYPE_GIF:
        header('content-type: image/gif');
        break;

    default:
        exit('Invalid image type.');
}

echo $_SESSION['post']['image_data'];

// phpバージョン8以上の場合は、switch式ではなくmatch式利用を推奨します。
// https://www.php.net/manual/ja/control-structures.match.php
