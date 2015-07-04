<?php
    include "server.php";
     
    if (!empty($_POST)) {
        Polygon::saveCoords ($_POST['coords']);
    }
     
    $data   = Polygon::getCoords();
     
    $coords = null;
     
    if(false!=$data) {
        // parse data
        preg_match_all('/\((.*?)\)/', $data, $matches);
         
        $coords= $matches[1];
    }
?>
<!DOCTYPE>
 
<html>
    <head>
     
    <title>Google Map V3 Polygon Creator by Xu Ding</title>
     
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
     
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
     
    <script type="text/javascript" src="polygon.min.js"></script>
     
    <script type="text/javascript">
    $(function(){
         // create map
         var usCenter=new google.maps.LatLng(43.392985, -80.409906);
 
         var myOptions = {
            zoom: 14,
            center: usCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
         }
          
         map = new google.maps.Map(document.getElementById('main-map'), myOptions);
 
         // attached a polygon creator drawer to the map
         var creator = new PolygonCreator(map);
 
         // reset button
         $('#reset').click(function(){
                creator.destroy();
                creator=null;              
                creator=new PolygonCreator(map);               
         });   
 
         // set polygon data to the form hidden field
         $('#map-form').submit(function () {
            $('#map-coords').val(creator.showData());
         });
          
 
         <?php if (null!=$coords): ?>
          // create
         var polygonCoords = [<?php
                                foreach ( $coords as $i=>$coord ):
                                    echo 'new google.maps.LatLng('.$coord.')';
                                    if ( $i<=count($coords)) {
                                     echo ',';
                                    }
                                endforeach;?>];
 
         // construct the polygon
         polygon = new google.maps.Polygon({
                               paths: polygonCoords,
                               strokeColor: "#FF0000",
                               strokeOpacity: 0.8,
                               strokeWeight: 2,
                               fillColor: "#FF0000",
                               fillOpacity: 0.35
         });
 
         // show polygon on the map
         polygon.setMap(map);
         <?php endif;?>
                     
    });
    </script>
     
 
</head>
<body>
 <div style="margin:auto;  width: 500px; ">
     
        <div id="main-map" style="height: 400px;"></div>
     
        <form action="index.php" method="POST" id="map-form">
         
            <input type="hidden" name="coords" id="map-coords" value=""/>
             
            <input type="submit" value="Send"/>
             
            <input type="button" value="Reset" id="reset"/>
        </form>
     
    </div>
</body>
</html>
