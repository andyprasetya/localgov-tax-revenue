<?php
include('class.upload.php');

if ($_POST['proses'] == 'unggah') {
  $handle = new upload($_FILES['file']);

  if ($handle->uploaded) {
    // Set the new filename of the uploaded image
    $uniqName = uniqid();
    $ext = 'jpg';
    $handle->file_new_name_body = $uniqName;
    $handle->file_new_name_ext = $ext;
    // Make sure the image is resized
    $handle->image_resize = true;
    // Ensure the height of the image is calculated based on ratio
    $handle->image_ratio = true;
    // Set the width of the image
    $handle->image_x = 800;
    // Set the width of the image
    $handle->image_y = 600;
    // Process the image resize and save the uploaded file to the directory
    $handle->process('uploaded-files');
    // Proceed if image processing completed sucessfully
    if ($handle->processed) {
      // Your image has been resized and saved
      $return = array('status' => 'berhasil', 'nama' => $uniqName.'.'.$ext);
      // Reset the properties of the upload object
      $handle->clean();
    } else {
      // Write the error to the screen
      $return = array('status' => 'gagal', 'pesan' => 'Error: ' . $handle->error);
    }
    echo json_encode($return);
  }
} else {
  $namaFile = $_POST['nama'];

  $hapus = unlink('uploaded-files/'.$namaFile);

  $return = array('status' => $hapus ? 'berhasil' : 'gagal', 'nama' => $namaFile);
  echo json_encode($return);
}
