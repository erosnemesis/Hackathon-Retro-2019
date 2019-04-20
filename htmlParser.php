<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(E_ALL);
/**
 * User: Robert Bachta
 * Date: 4/19/2019
 * Time: 2:15 PM
 */

if(!empty($_GET["url"])){
	$remove = false;
	if(isset($_GET["removehead"])){
		$remove = true;
	}
	echo HtmlLookup::GetHTML($_GET["url"], $remove);
} else {
	exit();
}

/*exit();

foreach($tns as $tn){
    $npa = substr($tn, 0, 3);
    $nnx = substr($tn, 3, 3);
    print_r(CoAddressLookup::GetAddressByNPANNX($npa, $nnx));
}*/

class HtmlLookup{

        public function __construct()
        {
        }

    public static function GetHTML($url = "", $removeHead = true){
        //extract data from the get

            //open connection
            $ch = curl_init();

            //set the url and options for CURL
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);

            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
	    //print($result);

            $dom = new DOMDocument;
            $dom->loadHTML($result);

	    $dom->preserveWhiteSpace = false;

		if($removeHead == true){
			$head = $dom->getElementsByTagName('head');

			$remove = [];
			foreach($head as $item)
			{
				$remove[] = $item;
			}

			foreach ($remove as $item)
			{
  				$item->parentNode->removeChild($item); 
			}

			$dom->createElement('head');
		}
	
            $header = $dom->getElementsByTagName('head');
	    foreach($header as $h){
		$script = $h->appendChild($dom->createElement('script'));
		$script->setAttribute("LANGUAGE", "JavaScript");
		$script->setAttribute("SRC", "javascript/JSFX_Layer.js");
		$script = $h->appendChild($dom->createElement('script'));
                $script->setAttribute("LANGUAGE", "JavaScript");
		$script->setAttribute("SRC", "javascript/JSFX_Mouse.js");
		$script = $h->appendChild($dom->createElement('script'));
                $script->setAttribute("LANGUAGE", "JavaScript");
		$script->setAttribute("SRC", "javascript/JSFX_pinwheel.js");
		$script = $h->appendChild($dom->createElement('script', "function JSFX_StartEffects(){	JSFX.Pinwheel(); }"));
		$script->setAttribute("LANGUAGE", "JavaScript");
		
		$script = $h->appendChild($dom->createElement('link'));
		$script->setAttribute("rel", "stylesheet");
		$script->setAttribute("type", "text/css");
		$script->setAttribute("href", "90s_style.css");

//<link rel="stylesheet" type="text/css" href="90s_style.css">
	    }

	    //$new_div = $dom->createElement('div');
	    //$new_div->setAttribute('id', 'page-wrap');

	    $bottomImage = $dom->createElement('img');
	    $src_url = urldecode("http://siniseus.com/retro/myst0.png");
	    $bottomImage->setAttribute("src", $src_url);

	    $body = $dom->getElementsByTagName('body');
	    foreach($body as $b){
		$b->setAttribute("onLoad", "JSFX_StartEffects()");
		$image = $b->appendChild($dom->createElement('div'));
		$image->setAttribute("style", "width: relative; position: fixed; bottom: 0; right: 0; margin-bottom: -5px; z-index: 100;");
		$image->appendChild($bottomImage);

		$new_div = $dom->createElement('div');
		$new_div->setAttribute('id', 'page-wrap');

		$script = $new_div->appendChild($dom->createElement('div'));
                $script->setAttribute("id", "ad");

		$parent = $b->parentNode;
		$clone_body = $b->cloneNode(true);

		$new_div->appendChild($clone_body);

		$b->parentNode->removeChild($b);

		$parent->appendChild($new_div);
	    }

	    $allImages = $dom->getElementsByTagName('img');
	    foreach($allImages as $img){
		$imagepath = urldecode($img->getAttribute('src'));
		if(substr($imagepath, 0, 4) === "http"){
			continue;
		}
		$new_url = $url.$imagepath;
		$img->setAttribute('src', $new_url);
	    }

	    $data = $dom->saveHTML();

            //$data = $dom->getElementById('Table3');

            //$data = $dom->saveHTML($data);

            //$dom2 = new DOMDocument;

            //$dom2->loadHTML($data);

            //$data = $dom2->getElementsByTagName('td');

            //$items = array();

            /*foreach($data as $d){
                array_push($items, strip_tags($dom2->saveHTML($d)));
            }

            $tnAddress = array();

            for($i = 5; $i < sizeof($items); $i++){
                if($i % 2 == 0){
                    continue;
                }
                array_push($tnAddress, $items[$i]);
            }

            $tnToAddressMap = array();

            for($i = 0; $i < sizeof($tnAddress); $i += 2){
                $tnToAddressMap[$tnAddress[$i]] = str_replace("Show All COs at this address -or- Show All Exchanges at this CO", "", $tnAddress[$i+1]);
            }*/

            return($data);
        }
}
