<?php

if ( ! function_exists('get_file_curl') ):

	function get_file_curl($url, $fullpath, $to_variable = false, $iteration = 0) {
		
		$request = wp_remote_get($url);
		
		if ( ! is_wp_error($request) ){

			$rawdata = wp_remote_retrieve_body( $request );				
			
			if (empty($rawdata)){
				$result =  pmxi_curl_download($url, $fullpath, $to_variable);	
				if ( ! $result and ! $iteration){					
					$url = pmxi_translate_uri($url);
					return get_file_curl($url, $fullpath, $to_variable, 1);
				}
				return $result;
			}

			if ( ! @file_put_contents($fullpath, $rawdata) ) {
				$fp = fopen($fullpath,'w');
			    fwrite($fp, $rawdata);
			    fclose($fp);
			}													
			
		    if ( preg_match('%\W(jpg|jpeg|gif|png)$%i', basename($fullpath)) and ( ! ($image_info = @getimagesize($fullpath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG)) ) )
			{			
				$result =  pmxi_curl_download($url, $fullpath, $to_variable);	
				if ( ! $result and ! $iteration){
					$url = pmxi_translate_uri($url);
					return get_file_curl($url, $fullpath, $to_variable, 1);
				}
				return $result;
			}
			
		    return ($to_variable) ? $rawdata : true;

		}
		else{ 			
			
			$curl = pmxi_curl_download($url, $fullpath, $to_variable);							

			if ($curl === false and ! $iteration){				
				$url = pmxi_translate_uri($url);
				return get_file_curl($url, $fullpath, $to_variable, 1);
								
			}

			return ($curl === false) ? $request : $curl;			

		}
		
	}

endif;

if ( ! function_exists('pmxi_curl_download') ) {

	function pmxi_curl_download($url, $fullpath, $to_variable){

		if ( ! function_exists('curl_version') ) return false;
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$rawdata = curl_exec_follow($ch);	    	    

	    $result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    
		curl_close ($ch);

		if ( empty($rawdata) ) return false;

		if (!@file_put_contents($fullpath, $rawdata)){
			$fp = fopen($fullpath,'w');	    
		    fwrite($fp, $rawdata);
		    fclose($fp);			
		}	    

	    return ($result == 200) ? (($to_variable) ? $rawdata : true) : false;
	}

}

if ( ! function_exists('curl_exec_follow') ):

	function curl_exec_follow($ch, &$maxredirect = null) {
	  
	  $mr = $maxredirect === null ? 5 : intval($maxredirect);

	  if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {

	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
	    curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	  } else {
	    
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

	    if ($mr > 0)
	    {
	      $original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	      $newurl = $original_url;
	      
	      $rch = curl_copy_handle($ch);
	      
	      curl_setopt($rch, CURLOPT_HEADER, true);
	      curl_setopt($rch, CURLOPT_NOBODY, true);
	      curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
	      do
	      {
	        curl_setopt($rch, CURLOPT_URL, $newurl);
	        $header = curl_exec($rch);
	        if (curl_errno($rch)) {
	          $code = 0;
	        } else {
	          $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
	          if ($code == 301 || $code == 302) {
	            preg_match('/Location:(.*?)\n/', $header, $matches);
	            $newurl = trim(array_pop($matches));
	            
	            // if no scheme is present then the new url is a
	            // relative path and thus needs some extra care
	            if(!preg_match("/^https?:/i", $newurl)){
	              $newurl = $original_url . $newurl;
	            }   
	          } else {
	            $code = 0;
	          }
	        }
	      } while ($code && --$mr);
	      
	      curl_close($rch);
	      
	      if (!$mr)
	      {
	        if ($maxredirect !== null)	        
	        	$maxredirect = 0;
	        
	        return false;
	      }
	      curl_setopt($ch, CURLOPT_URL, $newurl);
	    }
	  }
	  return curl_exec($ch);
	}
	
endif;