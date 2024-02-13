<?php
require_once 'core.php';

$response = [];
$extensions = array('jpg', 'png', 'gif', 'jpeg');
$maxFileSize = 40 * 1024 * 1024; // 40 MB
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$active = $_POST['active'];
$status = $_POST['status'];
$credit = $_POST['credit'];
$filelist = '';
if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error uploading file.';
    } else {
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext == '') {
            // Use the file type provided by $_FILES
            $file_mime_type = $file['type'];
            $file_ext = array_search(
                $file_mime_type,
                [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/jpeg' => 'jpeg',
                ],
                true
            );
        }
        $file_size = $file['size'];
        if ($file_size > $maxFileSize) {
            $response['message'] = 'File size exceeds the allowed limit.';
        } elseif (!in_array($file_ext, $extensions)) {
            $response['message'] = 'Invalid file extension.' . $file_ext;
        } else {
            $uniqueFilename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
            $folder = '../assets/images/profile/';
            $destination = $folder . $uniqueFilename;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $filelist = $destination;
                // $response['message'] = 'File uploaded successfully.';
            } else {
                $response['message'] = 'Error moving the uploaded file';
            }
        }
    }
}

if (empty($response)) {
    if($filelist !== '') {
        $sql = 'UPDATE users SET username=?, email=?, credit=?, active=?, status=?, user_image=? WHERE user_id=?';
    } else {
        $sql = 'UPDATE users SET username=?, email=?, credit=?, active=?, status=? WHERE user_id=?';
    }
    $stmt = $connect->prepare($sql);
    if(!$stmt) {
        $response = ['result' => 'error', 'message' => 'Prepare failed: (' . $connect->error . ') ' . $connect->error];
    } else {
        if($filelist !== '') {
            $stmt->bind_param('ssissss', $username, $email, $credit, $active, $status, $filelist, $user_id);
        } else {
            $stmt->bind_param('ssissi', $username, $email, $credit, $active, $status, $user_id);
        }
        if (!$stmt->execute()) {
            $response = ['result' => 'error', 'message' => 'Execute failed: (' . $stmt->error . ') ' . $stmt->error];
        } else {
            $response = ['result' => 'success', 'message' => 'Update successful'];
        }
        $stmt->close();
    }
}
$connect->close();

$j = json_encode($response);
die($j);
?>


