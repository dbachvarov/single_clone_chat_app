<?php
    echo "home1";
    $content = '';
    $view_content = '';
    try {
        $db = new MyDB($config);
        $db->connect();
        $db->query("SELECT * FROM dbachv01db.w1_fma_files ORDER BY uploaded_date DESC");
        $query = $db->getResults();
        $db->disconnect();
 
        if (!empty($query)) {
            foreach ($query AS $row) {
                $imageURL = 'uploads/' . $row["file_name"];
                if (is_readable($imageURL)) {
                    list($img, $error, $width, $height, $resizedURL) = img_resize($imageURL, 'thumbs/resized_' . $row['file_name'], 150, 150, 100);
                    // If resizing was successful display images, in HTML image
                    if (!$img) {
                    throw new Exception($error) ;
                    }
                    $imgTitle = htmlentities($row['title']);
                    $home_a = file_get_contents('templates/home_<a_href.html'); //  rows 24 - 29 link&string display
                    $pass = str_replace('[+id+]', $row['id'], $home_a);
                    $pass1 = str_replace('[+img_url+]', $resizedURL, $pass);
                    $pass2 = str_replace('[+img_name+]', $row['file_name'], $pass1);
                    $pass3 = str_replace('[+width+]', $width, $pass2);
                    $pass4 = str_replace('[+height+]', $height, $pass3);
                
                    $home_li = file_get_contents('templates/home_list.html');
                    $pass5 = str_replace('[+list+]', $pass4, $home_li);
                    $content .= str_replace('[+img_title+]', $imgTitle, $pass5);
                }
            } // end foreach
        
            $tmp = file_get_contents('templates/ul.html');
            $view_content .= str_replace('[+ul_list+]', $content, $tmp);
        
        } else {
            throw new Exception('No images in the Database');
        }
    }
    catch(mysqli_sql_exception $ex){
        $err = "MYSQL Exception Raised : ".$ex->getMessage();
        $tmp= file_get_contents('templates/h2.html');
        $rep = str_replace('[+h_2+]', $err, $tmp);
        $view_content = $rep ;
    }
    catch(Exception $ex){
        $err = "General Exception Raised : " . $ex->getMessage();
        $tmp= file_get_contents('templates/h2.html');
        $rep = str_replace('[+h_2+]', $err, $tmp);
        $view_content = $rep ;
    }
    
    ?>
    
   