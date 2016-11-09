<!DOCTYPE html>
<html>
<body>
<!--  
### need ghostscript and php on the server to function
### the website will have a standard upload functionality that will check that the file is a pdf file
### if true the file is uploaded and an script (tr-pdfshrink.sh) is called
### tr-pdfshrink should be in /usr/bin
### the file will be compressed by downsampleing the pdf file with a resolution of 96 dpi
### all files will be saved in a folder uploads/ that is in the same folder as index.php
### all files will get a prefix "000ps-" where 0000 will be counted up
-->

<form action="index.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload & Convert PDF" name="submit">
</form>
<br>


<?php
$target_dir = "uploads/";
ob_implicit_flush(true);
ob_end_flush();

chdir($target_dir);

if(isset($_POST["submit"])) {
        if ($_FILES["fileToUpload"]["type"] <> "application/pdf"){
                echo "The file extension must be .pdf";
                exit(1);
        }
        $filecount = trim(shell_exec('ls *ps-*.pdf|sort -rn|sed '/^0000/d'|head -1|cut -b 1-4'));
        $filecount += 1;
        $filecount = sprintf('%04d',$filecount);
        $filename = $filecount ."ps-". pathinfo(basename($_FILES["fileToUpload"]["name"]))['filename'];//filename without extension
        $target_file = $filecount ."ps-". basename($_FILES["fileToUpload"]["name"]);//path to store orig file
        echo $filename."<br>".$target_file;
        $uploadOk = 1;
        /*$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }*/
        /*// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }*/
        /*// Check file size
        if ($_FILES["fileToUpload"]["size"] > 20000000) {
            echo "Sorry, your file is too large. 20MB max";
            $uploadOk = 0;
        }*/
        /*// Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }*/
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. -> starting to convert";
                $output = shell_exec("tr-pdfshrink.sh " . $filename . ".pdf");
                echo "<pre>$output</pre>";
                $link= "<a href=\"".$target_dir . $filename. "_small.pdf\">".$filename. "_small.pdf</a>";
                echo $link;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
}
?>
<a href="uploads/"> See all files </a>

</body>
</html>
