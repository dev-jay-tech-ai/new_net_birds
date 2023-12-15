<?php
require_once 'core.php';

if(isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
	$sql = "SELECT * FROM users WHERE user_id = ?";
	$stmt = $connect->prepare($sql);
	$stmt->bind_param("s", $user_id);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();

    $username = $user_data['username'];
    $email = $user_data['email'];
    $credit = $user_data['credit'];
    $active = $user_data['active'];
    $status = $user_data['status'];

    echo '
    <div class="form-group">
        <div class="col-sm-8">
            <!-- the avatar markup -->
            <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>
            <div class="kv-avatar center-block">
                <input type="file" id="fileInput" class="form-control" placeholder="Profile" name="file" class="file-loading" accept="image/*" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
            <input type="hidden" id="user_id" name="user_id" value="' . $user_id . '">
            <input type="text"id="username" class="form-control" placeholder="Username" name="username" value="' . $username . '" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
            <input type="text" id="email" class="form-control" placeholder="Email" name="email" value="' . $email . '" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
            <select id="active" class="form-control" name="active">
                <option value="-1">Select</option>
                <option value="1" ' . ($active == 1 ? 'selected' : "") . '>Paid</option>
                <option value="2" ' . ($active == 2 ? 'selected' : "") . '>Unpaid</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
            <select id="status" class="form-control" name="status">
                <option value="-1">Select</option>
                <option value="1" ' . ($status == 1 ? 'selected' : "") . '>Admin</option>
                <option value="2" ' . ($status == 2 ? 'selected' : "") . '>Guest</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8">
            <input type="text" id="credit" class="form-control" placeholder="Credit" name="credit" value="' . $credit . '" autocomplete="off">
        </div>
    </div>
';
	} else {
        echo 'No data!';
	}
} else {
    echo 'User not found!';
}

$stmt->close();
$connect->close();

?>