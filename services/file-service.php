<?php



function deleteFile($file_to_delete)
{
  

    // Check if the file exists
    if (file_exists($file_to_delete)) {
        // Attempt to delete the file
        if (unlink($file_to_delete)) {
            return 0;
        } else {
            return -1;
        }
    } else {
        return -1;
    }
    
    
}
?>