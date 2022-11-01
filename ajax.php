<?php
    $data = NULL;
    $zipcode = $_POST['zipcode'];
    $range = $_POST['range'];
    $product = $_POST['product'];
    $location = $_POST['location'];

    if($zipcode!= '' && $range!= '' && $location !='')
    {
        $custID = 'MCS';
        $secret = '6DB088952A7AMCSF1B15F5B8CCB';                            
        $params = array(
            'action' => 'results',
            'zip' => $zipcode,
            'miles' => $range,        
            'format' => 'json',
	    'pagesize' => '400',
        );
        if($product != '' && !empty($product))
        {
            $params = array_merge($params, array("brand" => $product));
        }

        if($location != 'any')
        {
            $params = array_merge($params, array("storeType" => $location));
        }
            
        $url = "https://api.vtinfo.com/analytics/v2/finder";
        date_default_timezone_set('GMT');
        $stamp = date("D, j M Y H:i:00 T", time());
        $sigString = $stamp . $secret . http_build_query($params, '', '&') . $custID;
        $sigHash = hash('sha256', $sigString);
        /////////////////////// SET API KEYS AND TOKENS /////////////////////////////////////

        /////////////////////// Initialize the cURL ////////////////////////////////////////
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'vipCustID: '. $custID,
        'vipTimestamp: '. $stamp,
        'vipSignature: '. $sigHash
        ));
        /////////////////////// Initialize the cURL ////////////////////////////////////////

        /////////////////////// Execute /////////////////////////////////////////////////////
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        $output = json_decode($result, true);
        /////////////////////// Execute /////////////////////////////////////////////////////    
        if( empty($output['location']) || empty($output['locationsTotal']) )
        {
            if(!empty($output['errMsg']))
            {
                //echo $output['errMsg'];
 		  echo "--";

            }
            else
            {
                //echo "No Data Found";
		  echo "--";
            }        
        }
        else
        {
            $data =  '<div id="map">
                <script lang="javascript">                
                    var markersList = [];
                    function initMap() 
                    {
                        const uluru = { lat: '.$output['location'][0]['lat'] . ', lng:'. $output['location'][0]['long'].'};
                        window.map = new google.maps.Map(document.getElementById("map"), {
                            zoom: 13,
                            center: uluru,
                        });';                
                        foreach($output['location'] as $row)
                        {
                            $name = $row['dba'];
                            $street = $row['street'];
                            $city = $row['city'];
                            $state = $row['state'];
                            $zip = $row['zip'];
                            $phoneFormatted = $row['phoneFormatted'];
                            $lat = $row['lat'];
                            $long = $row['long'];
                            $otherBrand = $row['otherBrand'][0];
                            $data .= "initMarker('blank.png ',".$lat.", ". $long .", '". $name ."', '". $name ."</b> <br />". $street . " " . $city . ", <br />" . $state . ", " . $zip .  "<br />" . $phoneFormatted . " <br />" . $otherBrand . "');";
                        }                
                    $data .='}';
                    $data.='window.initMap = initMap;';                    
                $data .='</script>';
                $data.='<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwIMGpCqD2PmWykQvNMrenPPif7hi1tiI&callback=initMap&v=weekly" defer></script>';
            $data.='</div>';    
        }
    }
    else
    {
        //$data = 'Missing Required parameters!';
          $data = '--';

    }
    echo $data;
