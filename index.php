<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Snack Finder map Html</title>
<meta content="width=device-width, initial-scale=1" name="viewport" />
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&display=swap" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<div class="container-fluid">
  <div class="row row-eq-height">
    <div class="col-md-6">
      <div class="column">
        <h1 class="main-heading">Snack Finder</h1>        
        <form id="myForm" class="form-inline" method="POST" action="">
          <input type="text" id="zipcode" name="zipcode" class="form-control" placeholder="Enter city or zipcode">
          <input type="hidden" class="searching" id="default" name="default">
          <button type="submit" class="find btn btn-primary">Snack Me!</button>
          <div class="col-md-12"><strong>Product</strong>
            <ul>
              <li>
              <input type="radio" id="product" name="product" value="" checked>
                All Snacks</li>
              <li>
              <input type="radio" id="product" name="product" value="Montucky Grapefruit Seltz">
                Montucky Grapefruit Seltz</li>
              <li>
              <input type="radio" id="product" name="product" value="Montucky Cold Snacks">
                Montucky Cold Snacks</li>
              <li>
              <input type="radio" id="product" name="product" value="Montucky Seasonal">
                Montucky Seasonal</li>
            </ul>
          </div>
          <div class="col-md-12"><strong>Location Types</strong>
            <ul>
              <li>
              <input type="radio" id="location" name="location" value="any" checked>
                Any Location</li>
              <li>
              <input type="radio" id="location" name="location" value="on">
                Out for a Snack</li>
              <li>
              <input type="radio" id="location" name="location" value="off">
                Snacks to go</li>
            </ul>
          </div>
          <div class="col-md-12"><strong>Range</strong>
            <ul>
              <li>
              <input type="radio" id="range" name="range" value="1">
                1 mile</li>
              <li>
              <input type="radio" id="range" name="range" value="5">
                5 mile</li>
              <li>
              <input type="radio" id="range" name="range" value="25" checked>
                25 mile</li>
	      <li>
              <input type="radio" id="range" name="range" value="100">
                100 mile</li>
            </ul>
          </div>
        </form>
      </div>
    </div>
    <div class="col-md-6">
      <div class="showmap" style="width: 100%">     
      </div>
    </div>
  </div>
</div>
<script>
            $(document).ready(function(){
				
				setTimeout(function(){
					if($(".searching").val() == '')
					{
						 $("#zipcode").val("80014");
						 $(".searching").val("eee");
						 $(".find").click();
					}
				}, 1000);
		
	                $("#myForm").submit(function(event){
			$(".searching").val("eee");
                        var zipcode = $("#zipcode").val();
                        var product = $('input[name=product]:checked', '#myForm').val();
                        var location= $('input[name=location]:checked', '#myForm').val();
                        var range= $('input[name=range]:checked', '#myForm').val();                                                
                        event.preventDefault();
                        $.ajax({
                            method: 'POST',
                            url: 'ajax.php',                            
                            data: {zipcode: zipcode, product:product, location:location, range:range},
                            success: function(data){
				if(data == "--")
				{
				   $("#zipcode").val("80014");
				   $(".find").click();
				}
				else
				{
				  //$("#zipcode").val("");
				}
		
                                $(".showmap").html(data);
				$(".searching").val("eee");
                                //window.initMap = initMap;
                            },
                            error: function(xhr, desc, err){                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
                                console.log(err);
                            }
                        });
                    });                
            });

function initMarker(iconImage, latitude, longitude, title, infoWindowContent) {
    var icon = {
        url: iconImage + '?ver=' + new Date().getTime(), // url
        //scaledSize: new google.maps.Size(35, 59), // scaled size
        origin: new google.maps.Point(0,0), // origin
        anchor: new google.maps.Point(25, 40) // anchor
    };
    var latlng = new google.maps.LatLng(Number(latitude), Number(longitude));

    // Create info window
    var infowindow = new google.maps.InfoWindow({
        maxWidth: 500,
        pixelOffset: new google.maps.Size(-10,-25)
    });
 

    var mark = new google.maps.Marker({
        position: latlng,
        map: map,
        title: title,
        // label: serial.toString(),
        icon: icon
    });
    google.maps.event.addListener(mark, 'click', function() {
        infowindow.setContent(infoWindowContent); 
        infowindow.open(map,mark);

        });
         google.maps.event.trigger(mark, 'click');      
}	
</script> 
</body>
</html>

