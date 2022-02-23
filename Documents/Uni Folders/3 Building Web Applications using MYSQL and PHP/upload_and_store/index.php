<?php
    include 'myDB.php';
    $statusMsg ="";
    
    if(isset($_POST['submit'])&& !empty($_FILES['file']['name'])){
        
        $targetDir ="uploads/";
        $fileName = basename($_FILES['file']['name']);
        $targetFilePath = $targetDir.$fileName;
        $fileType= pathinfo($targetFilePath, PATHINFO_EXTENSION);// returns 'php'
        
        // allowing only certain file formats
        $allowTypes = array('.jpg', '.jpeg');
        if(in_array(".".$fileType, $allowTypes)){
            //upload file to server
            if(move_uploaded_file($_FILES['file']['tmp_name'],$targetFilePath)){
                // now to insert into database
                $query=$db->query("INSERT INTO fma_images(file_name, uploaded_on) VALUES('".$fileName."',NOW())");
                if($query){
                    $statusMsg= 'The file '.$fileName.' has been uploaded successfully.';
                }else{
                    $statusMsg="File upload filed, please try again.";
                }
            }else {
                $statusMsg= "Sorry, there was an error uploading your file.";
            }
            
            
        }else{
            $statusMsg= "Sorry, only JPG AND JPEG files are allowed to be uploaded.";
        }
    } else {
        $statusMsg = "Please selct a file to upload.";
    }
    
   echo $statusMsg;
    
    
    
    
    
    
     ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>$Title$</title>
</head>
<body>

    <h1>Upload an Image:</h1>


    <form enctype="multipart/form-data" action="<?php echo htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
        <div>
            <label for="fileinput">Upload an Image:</label>
    
            <input name="file" type="file" id="fileinput" />
        </div>
        <div>
            <input type="submit" value="Upload File" name="submit" />
    </div>
    </form>

</body>
</html>