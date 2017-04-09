<?php
    $vinFileName = "VINs.txt";
    $checkedFileName = "wPackage.txt";
    
    //$checkedFle = fopen($checkedFileName,"w") or die ("Unable to open" . $checkedFileName);
    
    $vinLines = file($vinFileName) or die ("Unable to open " . $vinFileName);
    
    $newLines = array();
    
    function curlContent($url){
    	$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_VERBOSE, true);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    	curl_setopt($ch, CURLOPT_URL,$url);
    	$result=curl_exec($ch);
    	curl_close($ch);
    	return $result;
    }
    
    
    function searchPackage($vin){
		//create url based on VIN #
        $url = "https://www.vindecoderz.com/EN/check-lookup/" . $vin;
        //Grab Content via URL
		$content = curlContent($url);
        echo $url . "<br / >";
		
		//Throw Exception if URL is not loadable.
        if($content === false){
            throw new Exception ("failed to grab content <br />");
        }
        $contentLow = strtolower($content);
        
		//Throw Exception if attempts alert came up
        if(strpos($contentLow,"too many attempts")!==false){
            throw new Exception ($contentLow);
        }
        
		//Check and see if these feature exists in the car
        $drAsPac = strpos($contentLow,"assistance package");
        $tecPac = strpos($contentLow,"technology package");
        $bldSpt = strpos($contentLow,"blind spot");
		
		
		$contentTitleStart = strpos($contentLow, "<title>")+7;
		$contentTitleEnd = strpos($contentLow, "</title>");
		$contentTitleLength = $contentTitleEnd-$contentTitleStart;
        echo $drAsPac . " " . $tecPac + " " . $bldSpt . substr($contentLow,$contentTitleStart,$contentTitleLength) . "<br />";
        
        if($drAsPac===false && $tecPac ===false && $bldSpt === false){
            echo "Can't find Match <br />";
            return false;
        }
        return true;
    }
    
    
    //Go throgh what's in VIN.txt file, and process each VIN.
    //Remove prossed records from $vinFile
    //Append matching records to $checkedFile
	//$countControl # of tries;
	$countControl = 0;
    while(isset($vinLines[0]) && $countControl <2){
		try{
			//check if vin has the right package or not, if so, added to newLines 
			if(searchPackage($vinLines[0])){
				array_push($newLines,$vinLines[0]);
				echo "Adding " . $vinLines[0] . " to $newLines, " . sizeof($newLines) . " records Found <br />";
			}
			//shift array to take out first vin processed;
			array_shift($vinLines);
			echo sizeof($vinLines) . " records left to be processed <br />";
		}catch(Exception $e){
			echo $e . "<br />";
			break;
		}
		$countControl++;
		sleep(5);
    }
	//*/
	
	/*
	echo "got here !";
	
	array_shift($vinLines);
	array_shift($vinLines);
	array_shift($vinLines);
	array_shift($vinLines);
	array_push($newLines,"aaaa\n");
	array_push($newLines,"bbbb\n");
	array_push($newLines,"cccc\n");
	
	echo sizeof($vinLines) . " + " . sizeof($newLines) . "<br />";
	*/
	
    file_put_contents($checkedFileName,$newLines,FILE_APPEND | LOCK_EX);
    file_put_contents($vinFileName,$vinLines);
    
    //*./

  
?>