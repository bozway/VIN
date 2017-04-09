<?php
    $vinFileName = "VINs.txt";
    $checkedFileName = "wPackage.txt";
    
    //$checkedFle = fopen($checkedFileName,"w") or die ("Unable to open" . $checkedFileName);
    
    $vinLines = file($vinFileName) or die ("Unable to open " . $vinFileName);
    
    $newLines = array();
    
    
    function searchPackage($vin){
        $url = "https://www.vindecoderz.com/EN/check-lookup/" . $vin;
        $content = file_get_contents($url);
        
        if($content === false){
            throw new Exception ("failed to grab content <br />");
        }
        $contentLow = strtolower($content);
        
        if(strpos($contentLow,"too many attempts")!==false){
            throw new Exception ($contentLow);
        }
        
        $drAsPac = strpos($contentLow,"assistance package");
        $tecPac = strpos($contentLow,"technology package");
        $bldSpt = strpos($contentLow,"blind spot");
        
        echo $drAsPac . " " . $tecPac + " " . $bldSpt . substr($contentLow,0,100);
        
        if($drAsPac===false && $tecPac ===false && $bldSpt === false){
            echo "Can't find Match <br />";
            return false;
        }
        echo $drAsPac . " " . $tecPac . " " . $bldSpt . "<br />";
        return true;
    }
    
    /*
    //Go throgh what's in VIN.txt file, and process each VIN.
    //Remove prossed records from $vinFile
    //Append matching records to $checkedFile
    while(isset($vinLines[0])){
        if(strpos($vinLines[0],"3E")!==false){
            echo $vinLines[0] . "<br />";
            array_push($newLines,$vinLines[0]);
        }
        array_shift($vinLines);
        if(sizeof($newLines)==2){
            break;
        }
    }
    file_put_contents($checkedFileName,$newLines,FILE_APPEND | LOCK_EX);
    file_put_contents($vinFileName,$vinLines);
    
    //*/
    
    
    try{
        if(searchPackage($vinLines[9])){
            array_push($newLines,$vinLines[9]);
        }
        $vinLines = array_shift($vinLines);
    }catch(Exception $e){
        echo $e . "<br />";
    }
    
    print_r($newLines);
    
    

  
  /*
  
  for ($i = 0; i<count($lines); i++){
      
  }
  
  //*/
?>