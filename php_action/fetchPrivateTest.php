<?php
// Set the folder to save files
error_reporting(E_ALL); 
ini_set('display_errors', '1'); 

if (isset($_FILES['files'])) {
  $folder = '../assets/images/test/';
  $names = $_FILES['files']['name'];
  $tmp_names = $_FILES['files']['tmp_name'];
  $upload_data = array_combine($tmp_names, $names);

  foreach ($upload_data as $temp_folder => $file) {
      // Move the uploaded file to the target path
      if (move_uploaded_file($temp_folder, $folder . $file)) {
          echo $file . ' has been uploaded successfully.';
      } else {
          echo 'Error moving file ' . $file . '. Error code: ' . $_FILES['files']['error'];
      }
      }
    } else {
      echo json_encode(['error' => 'No file uploaded.']);
    }
?>