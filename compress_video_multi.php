<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES["videos"])) {
    $bitrate = '2500k';
    $outputDirectory = "./output_videos/";
    if (!file_exists($outputDirectory)) {
        mkdir($outputDirectory, 0777, true);
    }
    foreach ($_FILES["videos"]["tmp_name"] as $index => $video) {
        $allowedExtensions = ["mp4", "mov", "avi"]; // Adjust this list based on your requirements
        $fileExtension = strtolower(pathinfo($_FILES["videos"]["name"][$index], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Error: File $index has an invalid extension. Only " . implode(", ", $allowedExtensions) . " are allowed.<br>";
            continue; // Skip to the next iteration
        }

        $video = escapeshellarg($video);
        $bitrate = escapeshellarg($bitrate);
        $outputFile = $outputDirectory . date('YmdHis') . '_' . uniqid() . '.' . $fileExtension;
        $outputFile = escapeshellarg($outputFile);
        $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate $outputFile 2>&1";
        exec($command, $output, $returnCode);
        if ($returnCode === 0) {
            echo "File $index has been converted successfully. Output: " . implode("<br>", $output) . "<br>";
        } else {
            echo "Error for file $index: " . implode("<br>", $output) . "<br>";
        }
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
                    <label>Select videos</label>
                    <input type="file" name="videos[]" class="form-control" accept="video/*" multiple>
                </div>
                <input type="submit" name="change_bitrate" class="btn btn-info" value="Change bitrate">
            </form>
        </div>
    </div>
</div>

</body>
</html>
