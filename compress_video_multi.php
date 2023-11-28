<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bitrate = '2500k';

    // Specify the relative path for the output directory within your project directory
    $outputDirectory = "./output_videos/";

    // Create the output directory if it doesn't exist
    if (!file_exists($outputDirectory)) {
        mkdir($outputDirectory, 0777, true);
    }

    // Process each uploaded video file
    foreach ($_FILES["videos"]["tmp_name"] as $index => $video) {
        // Use escapeshellarg to escape user input for security
        $video = escapeshellarg($video);
        $bitrate = escapeshellarg($bitrate);

        // Construct the command with error output redirection
        $outputFile = $outputDirectory . "output_" . ($index + 1) . ".mp4";
        $outputFile = escapeshellarg($outputFile);
        $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate $outputFile 2>&1";

        // Execute the command and capture both stdout and stderr
        exec($command, $output, $returnCode);

        // Check if the command was successful
        if ($returnCode === 0) {
            echo "File $index has been converted<br>";
        } else {
            // Print the error output
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
