<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video = $_FILES["video"]["tmp_name"];
    $bitrate = '2500k';

    // Validate and sanitize the bitrate input (you can add more validation as needed)
    $allowedBitrates = ['350k', '700k', '1200k', '2500k', '5000k'];
    if (!in_array($bitrate, $allowedBitrates)) {
        die("Invalid bitrate selected");
    }

    // Specify the relative path for the output file within your project directory
    $outputFile = "./output.mp4";

    // Use escapeshellarg to escape user input for security
    $video = escapeshellarg($video);
    $bitrate = escapeshellarg($bitrate);
    $outputFile = escapeshellarg($outputFile);

    // Construct the command with error output redirection
    $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate $outputFile 2>&1";

    // Execute the command and capture both stdout and stderr
    exec($command, $output, $returnCode);

    // Check if the command was successful
    if ($returnCode === 0) {
        echo "File has been converted";
    } else {
        // Print the error output
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
