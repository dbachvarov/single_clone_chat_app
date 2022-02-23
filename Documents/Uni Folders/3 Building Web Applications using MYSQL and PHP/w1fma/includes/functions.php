<?php
    /**
     * validating file extension
     * @param string with the name of the file
     * @param array with holding files allowed
     * @return boolean -true if format matches
     */
    function file_format_validation($str, $allowed=array()){
        $arr = array();
        $val = false;
        $arr[0] = substr($str, -4);
        $arr[1] = substr($str, -5);
        foreach ($arr as $value) {
            if (in_array($value, $allowed)) {
                $val = true;
            }
        }
        return $val;
    }
    /**
     * cleaning user's input
     * @param  input from user
     * @return string
     */
    function clean_input($str) {
        $clean = trim($str);
        $clean= stripslashes($clean);
        $clean = htmlspecialchars($clean);
        return $clean;
    }
    /* Validation of text for all alphanumeric characters and punctuation symbols.
     */
    function textValidation($data){
        if (!preg_match('/^[\p{L} \d.?!,:;"@#]+$/u', $data)){
            return false;
        }else {
            return true;
        }
    }
    ?>
 