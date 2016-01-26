<?php

    /**
     *  A script to emulate the UNIX tail function by displaying the last X number of lines in the file.  
     *
     *  Useful for large files, such as logs, when you want to process lines in PHP or write line to a database.
     */


      //define the name of the file to read
      define("FILENAME",'log.txt');

      //define the number of lines to display
      define("LINES_TO_DISPLAY",10);

      if(!is_file(FILENAME)) {
          die("Not a file: ".FILENAME."\n");
      }

      if(LINES_TO_DISPLAY < 1) {
          die("Number of lines to display must be greater than 0\n");
      }


      function get_lines($filename, $howmany) {

           //get the indicator handler pointer
           $fp = fopen($filename,"r");
 
           //if is null then return void
           if(!$fp) {
               return false;
           } 

           //set indicator file pointer to -2 to ignore end-of-file & new line CR&&EOF
           $pointer = -2;

           //char is set to ''
           $char = '';

           //set up initial false
           $beginning_of_file = false;

           //define an array of lines, initially is empty
           $lines = array();

           //from 1 to howmany
           for($i=1;$i<=$howmany;$i++) {

                if($beginning_of_file) {
                   continue;
                }

                //reads characters while is not end of line
                while($char != "\n") {

                      if(fseek($fp, $pointer, SEEK_END) < 0) {
 
                         $beginning_of_file = true;

                         //move the pointer at the first character;
                         rewind($fp); 

                         //out of control
                         break;
                      }

                      //substract one character from the pointer position
                      $pointer--;  

                      //move the pointer at the end of the file with 'offset'(pointer) position   
                      fseek($fp,$pointer,SEEK_END);
 
                      //get the current character from pointer
                      $char = fgetc($fp);
                }

                //get a character
                $line = fgets($fp);

                //push into array lines
                array_push($lines,$line);  

                //reset the character;
                $char = '';
           }  

        return array_reverse($lines);
      } 

      $lines = get_lines(FILENAME, LINES_TO_DISPLAY);

      foreach($lines as $line) {
              echo$line."<br/>";
      }      
      
      if(isset($_GET['show'])) {
         highlight_file($_SERVER['SCRIPT_FILENAME']); 
      }
      exit;
?> 