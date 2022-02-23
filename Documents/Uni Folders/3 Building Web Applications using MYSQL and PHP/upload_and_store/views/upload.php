<?php
    session_start();
    
    $fileErr=$titleErr=$descErr="";
    $title=$description="";
    
    if(isset($_POST['submit'])) {
        if(!isset($_SESSION['filename'])) {
            $nameOf = $_FILES['userfile']['name'];
            $allowedTypes = array('.jpg', '.jpeg');
            if (!file_format_validation($_FILES['userfile']['name'], $allowedTypes)) {
                $fileErr = "Upload file ending with 'jpeg' of 'jpg'.";
            }
            elseif ($_FILES['userfile']['type'] != "image/jpeg") {
                $fileErr = "Only image files can be uploaded.";
            }
            elseif (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
                $upfilename = basename($_FILES['userfile']['name']);
                $newname = $updiir . $upfilename;
                $tmpname = $_FILES['userfile']['tmp_name'];
                $img_details = getimagesize($tmpname);
                
                if ($img_details !== false) {
                    if (file_exists($newname)) {
                        $fileErr = 'File already exist';
                    }
                    else {
                        if (move_uploaded_file($tmpname, $newname)) {
                            rotateImage($newname);  // function : includes/functions.php
                            $_SESSION['filename'] = basename($newname);
                            $_SESSION['width'] = $img_details[0];
                            $_SESSION['height'] = $img_details[1];
                            //$uploadFile= $newname;
                        }
                        else {
                            $fileErr = "File did not uploaded";
                        }
                    }
                }
                else {
                    $fileErr = "Not valid image/problem opening it";
                }
            } else {
                $err = $_FILES['userfile']['error'];
                if ($err == UPLOAD_ERR_INI_SIZE) {
                    $fileErr = 'File upload failed  - size exceeded***';
                } elseif ($err == UPLOAD_ERR_FORM_SIZE) {
                    $fileErr = 'File upload failed - form size exceeded***';
                } elseif ($err == UPLOAD_ERR_PARTIAL) {
                    $fileErr = 'File upload failed - partial exceeded***';
                } elseif ($err == UPLOAD_ERR_NO_FILE) {
                    $fileErr = 'File upload failed - no file ***';
                } else {
                    $fileErr = 'File upload failed - error code ' . $err;
                }
            }
        }
    
        // file title validation
        if (empty($_POST['image_title'])) {
            $titleErr = "Title is required";
        } else {
            $clean = clean_input($_POST['image_title']);
            if ((strlen($clean) == 0) or (strlen($clean) > 20)) {
                $titleErr = "Title must have one to twenty letters";
            } else {
                $title = ucwords($clean);
                $_SESSION['title'] = $title;
            }
        }
        // Image description validation
        if (empty($_POST['description'])) {
            $descErr = "Please add description";
        
        } else {
            $clean = clean_input($_POST['description']);
            if (textValidation($clean)) {
                $description = $clean;
                $_SESSION['description'] = $description;
            } else {
                $descErr = "No special characters allowed.";
            
            }
        }
    }
    if(isset($_POST['cancel'])){
        if(isset($_SESSION['filename'])) {
            unlink('uploads/' . $_SESSION['filename']);
            $_SESSION = array();
            session_destroy();
        }
    }
    // uploading form to database
    try {
        if (count($_SESSION) == 5) {
            $fileName = $_SESSION['filename'];
            $title = $_SESSION['title'];
            $description = $_SESSION['description'];
            $width = $_SESSION['width'];
            $height = $_SESSION['height'];
            $alt = $_SESSION['title'];
        
            $db = new MyDB($config);
            $link =$db->connect();
            $query = $db->query("insert into w1_fma_files ( file_name, title, description, width, height, alt,uploaded_date) values ('$fileName', '$title','$description','$width','$height','$alt', now());");
        
            if ($query === false) {
                throw new Exception(mysqli_error($link));
            } else {
                mysqli_commit($link);
                $_SESSION = array();
                session_destroy();
                header('Location:index.php?page=home');
                
            }
        }
    }
    catch (mysqli_sql_exception $ex)
    {
        unlink('uploads/' . $_SESSION['filename']);// new
        $_SESSION = array();
        session_destroy();
        echo 'Oops! There seems to be a problem. Please try again later'.'</br>'."MYSQL Exception Raised : " . $ex->getMessage();
        $msgErr='Oops! There seems to be a problem. Please try again later';
        $msgExc = "MYSQL Exception Raised : " . $ex->getMessage();
        
        
    }
   
    
    // html content
    $view_content='';
    $page_header='';
    $tc = '';
    if(isset($msgErr)){
        $page_header = $msgErr;
        $tc = $msgExc ;
    }else {
        $page_header = 'Upload Your Image File: ';
        $tc ='required field';
    }
    $table1 = file_get_contents('templates/table1.html');
    $table_if = file_get_contents('templates/table_if.html');
    $table_else = file_get_contents('templates/table_else.html');
    $action = htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8');
    
    $pass= str_replace('[+action+]',$action, $table1);
    $pass1= str_replace('[+header+]',$page_header,$pass);
    $pass2=str_replace('[+required+]',$tc ,$pass1);
    $pass3=str_replace('[+img_title+]', 'Image Title',$pass2);
    $pass4=str_replace('[+input_title+]',$title, $pass3);
    $pass5=str_replace('[+titleErr+]',$titleErr, $pass4);
    $pass6=str_replace('[+img_description+]','Image Description',$pass5);
    $pass7=str_replace('[+description+]',$description, $pass6);
    $pass8=str_replace('[+descErr+]', $descErr, $pass7);

    if(!isset($_SESSION['filename'])){
        $passA=str_replace('[+upload_file+]', 'Upload File', $table_if);
    $passB=str_replace('[+fileErr+]',$fileErr,$passA);
    $pass9=str_replace('[+if_else+]',$passB, $pass8);
    } else {
        $html_filename =htmlentities($_SESSION['filename']);
        $passC=str_replace('[+file_name+]',$html_filename, $table_else);
        $pass9=str_replace('[+if_else+]',$passC, $pass8);
    
    }
    $view_content=$pass9;
    
   
    ?>