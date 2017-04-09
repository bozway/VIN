<?php

    $content = file_get_contents('https://www.vindecoderz.com/EN/check-lookup/JN8AZ2NE7E9063243');
    //$content = substr(strtolower($cotnent),5,20);
    //echo substr(strtolower($cotnent),1150,8);
    echo $content;
    echo strpos($content, "Assistance Package");
?>