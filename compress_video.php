<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video = $_FILES["video"]["tmp_name"];
    $originalVideoName = $_FILES["video"]["name"];
    $video_ext = strtolower(pathinfo($originalVideoName, PATHINFO_EXTENSION));
    $bitrate = '2500k';
    $allowedBitrates = ['350k', '700k', '1200k', '2500k', '5000k'];
    if (!in_array($bitrate, $allowedBitrates)) die("Invalid bitrate selected");
    $outputFile = './' . date('YmdHis') . '_' . uniqid() . '.' . $video_ext;
    $video = escapeshellarg($video);
    $bitrate = escapeshellarg($bitrate);
    $outputFile = escapeshellarg($outputFile);
    // $ffmpegPath = __DIR__ . '/ffmpeg';  // Assuming 'ffmpeg' is the name of your binary file
    $command = "ffmpeg -i $video -b:v $bitrate -bufsize $bitrate $outputFile 2>&1";
    // $command = "ffmpeg -i $video -b:v $bitrate -bufsize $bitrate $outputFile 2>&1";
    exec($command, $output, $returnCode);
    if ($returnCode === 0) {
        echo "File has been converted";
    } else {
        echo "Error: " . implode("\n", $output);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Bitrate</title>
</head>
<body>

<div class="container" style="margin-top: 200px;">
    <div class="row">
        <div class="col-md-4 offset-md-4">
            <h1>Change bitrate</h1>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Select video</label>
                    <input type="file" name="video" class="form-control" accept="video/*">
                </div>
                <input type="submit" name="change_bitrate" class="btn btn-info" value="Change bitrate">
            </form>
        </div>
    </div>
</div>

</body>
</html>
