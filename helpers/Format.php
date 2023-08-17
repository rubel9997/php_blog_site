<?php
 class Format{

        public function validation($data){
            return $data;
            $data = trim($data);
            $data = stripcslashes($data);
            return $data;
        }
 }

?>