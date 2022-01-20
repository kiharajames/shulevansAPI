<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-Width');
//initalize the API
include_once "../core/initialize.php";

$phone = $_POST['phone'];

$target_dir = DRIVER_PATH."/img/profile_images/".$phone."/";
$newname = md5(rand(1,1000));
$target_file = $target_dir . $newname;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["profileImage"]["name"],PATHINFO_EXTENSION));
$target_file = $target_dir . $newname.".".$imageFileType;
$file_to_upload = $phone."/".$newname.".".$imageFileType;
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
//     if($check !== false) {
//         $uploadOk = 1;
//     } else {
//         header("location:../profile.php?rp=0095");
//     }
// }

// Check file size
if ($_FILES["profileImage"]["size"] > 5000000) {
     $status = 0;
     $data = array('message' => 'The image is too large, please upload an image less than 5MB');
    jsonResponse($status, $data);

}else{
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $status = 0;
        $data = array('message' => 'Please upload a jpg, png, jpeg or a gif file');
        jsonResponse($status, $data);
    }else{
        if (is_dir($target_dir)) {
            if ($dh = opendir($target_dir)){
                
                while (($file = readdir($dh)) !== false){
                  unlink($target_dir.$file);
                }                   
              }
        }else{
            mkdir($target_dir);
            
        }
        if ((move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)!== false)) {
            $sql = "UPDATE drivers SET avatar=:target_file WHERE phone=:phone";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam('target_file', $file_to_upload);
            $stmt->bindParam('phone', $phone);

            if ($stmt->execute()) {
                $status = 1;
                $data = array('message' => "The image has been uploaded successfully" );
                jsonResponse($status, $data);

            } else {
                $status = 0;
                $data = array('message' => "There was a problem updating the image data." );
                jsonResponse($status, $data);
            }
            
        }else{
            $status = 0;
            $data = array('message' => $target_file);
            jsonResponse($status, $data);
        }
    }
}

?>