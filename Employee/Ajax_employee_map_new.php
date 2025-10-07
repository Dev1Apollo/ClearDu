<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $where = "where 1=1";
    $whereNew = "where 1=1";
    if ($_REQUEST['fosId'] != "") {
        $where.=" and  strFosId = '" . $_REQUEST['fosId'] . "' ";
        $whereNew .= "  and loginId='".$_REQUEST['fosId']."'";
    }
    if ($_REQUEST['strDate'] != NULL && isset($_REQUEST['strDate'])) {
          //$where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('" . $_REQUEST['strDate'] . "','%d-%m-%Y') ";
          $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y') = STR_TO_DATE('" . $_REQUEST['strDate'] . "','%d-%m-%Y') ";
     }

    $filterstr = "SELECT * FROM `fosloginlog`  " . $where . " and  strLocation !='FOS Auto Log' order by iFosLogid asc";
    $rowEmpstr = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  " . $whereNew . " order by agencymanagerid asc"));
    // print_r($rowEmpstr);
    // exit;
    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <style>
            html,
            body {
              height: 80%;
              margin: 0;
              padding: 0;
            }
            
            #container {
              height: 100%;
              display: flex;
            }
            
            #sidebar {
              flex-basis: 15rem;
              flex-grow: 1;
              padding: 1rem;
              max-width: 30rem;
              height: 100%;
              box-sizing: border-box;
              overflow: auto;
            }
            
            #map {
              flex-basis: 0;
              flex-grow: 4;
              height: 80%;
            }
            
            #map {
              height: 500px;
              width: 100%;
            }

            #directions-panel {
              margin-top: 10px;
            }
        </style>
        <div class="row" id="container">
            <div class="col-md-12">
                <div class="col-md-8">
                    
                    <div id="map" style="position: static !important;"></div>
                </div>
                <div class="col-md-4">
                    <div id="sidebar">
                        <div id="directions-panel"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            $data = "";
            $iCounter = 1;
            $html = "";
            if($iCounter == 1){
                //$html ='{"lat":'. $rowfilter['strLatitude'] .', "lng":'. $rowfilter['strLongitude'] .',name: "Station '.$iCounter.'"},'; 
                $data .='{"lat":'. $rowEmpstr['strLatitude'] .', "lng":'. $rowEmpstr['strLongitude'] .',name: "Station '.$iCounter.'"},'; 
            }
            
            while ($rowfilter = mysqli_fetch_assoc($resultfilter)) {
                if($rowfilter['strLatitude'] != "" && $rowfilter['strLongitude'] != ""){
                    $iCounter++; 
                    $data.='{"lat":'. $rowfilter['strLatitude'] .', "lng":'. $rowfilter['strLongitude'] .',name: "Station '.$iCounter.'"},';
                    // $iCounter++; 
                }
            } 
            $iCounter = $iCounter + 1;
            $html ='{"lat":'. $rowEmpstr['strLatitude'] .', "lng":'. $rowEmpstr['strLongitude'] .',name: "Station '. $iCounter .'"},'; 
            $data .= $html;
            $data = rtrim($data,","); 
            //print_r($data);
        if(empty($data)){ ?>
            <div class="row">
                <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                    <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                        <h1 class="font-white text-center"> No Data Found ! </h1>
                    </div>   
                </div>
            </div>
        <?php    
        }
    } else {
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                    <h1 class="font-white text-center"> No Data Found ! </h1>
                </div>   
            </div>
        </div>
        <?php
    }
}
?>
<?php if ($totalrecord > $per_page) { ?>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
            <div class="form-actions noborder">
                <?php
                echo '<div class="pagination">';

                if ($totalrecord > $per_page) {
                    echo paginate($reload = '', $show_page, $total_pages);
                }
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
<?php } ?>
<script>
  function initMap() {
    var service = new google.maps.DirectionsService;
    var map = new google.maps.Map(document.getElementById('map'));
    var renderer = new google.maps.DirectionsRenderer;
    // list of points
    var stations = [
        <?= $data ?>
    ];
    
    // Zoom and center map automatically by stations (each station will be in visible map area)
    var lngs = stations.map(function(station) { return station.lng; });
    var lats = stations.map(function(station) { return station.lat; });
    map.fitBounds({
        west: Math.min.apply(null, lngs),
        east: Math.max.apply(null, lngs),
        north: Math.min.apply(null, lats),
        south: Math.max.apply(null, lats),
    });

    // Show stations on the map as markers
    for (var i = 0; i < stations.length; i++) {
        new google.maps.Marker({
            position: stations[i],
            map: map,
            title: stations[i].name
        });
    }

    // Divide route to several parts because max stations limit is 25 (23 waypoints + 1 origin + 1 destination)
    for (var i = 0, parts = [], max = 25 - 1; i < stations.length; i = i + max)
        parts.push(stations.slice(i, i + max + 1));

    // Service callback to process service results
    var service_callback = function(response, status) {
        // alert(status);
        if (status == 'ZERO_RESULTS') {
            // Handle zero results here
            console.log('No route found.');
            return;
        } else if (status != 'OK') {
            console.log('Directions request failed due to ' + status);
            return;
        }
        
        renderer.setMap(map);
        renderer.setOptions({ suppressMarkers: true, preserveViewport: true });
        renderer.setDirections(response);
    };

    // Send requests to service to get route (for stations count <= 25 only one request will be sent)
    for (var i = 0; i < parts.length; i++) {
        // Waypoints does not include first station (origin) and last station (destination)
        var waypoints = [];
        for (var j = 1; j < parts[i].length - 1; j++)
            waypoints.push({location: parts[i][j], stopover: false});
            // Service options
            var service_options = {
                origin: parts[i][0],
                destination: parts[i][parts[i].length - 1],
                waypoints: waypoints,
                //travelMode: 'WALKING'
                travelMode: google.maps.TravelMode.DRIVING
            };
            // Send request
            service.route(service_options, service_callback);
            calculateAndDisplayRoute(service, renderer,waypoints,service_options);
        }
  }
  
  function calculateAndDisplayRoute(service, renderer,waypoints,service_options) {
        var start = waypoints[0];
        var end = waypoints[waypoints.length - 1];
        var request = service_options;
        const directionsService = new google.maps.DirectionsService();
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                renderer.setDirections(response);
                console.log(response);
                var route = response.routes[0];
                var summaryPanel = document.getElementById('directions-panel');
                summaryPanel.innerHTML = '';
                //alert(route.legs.length);
                
                // Calculate and display distance between consecutive waypoints
                // summaryPanel.innerHTML += '<b>Display distance between consecutive waypoints : </b><br>';
                // for (var k = 0; k < waypoints.length - 1; k++) {
                    
                //     var distance = haversineDistance(
                //         waypoints[k].location.lat,
                //         waypoints[k].location.lng,
                //         waypoints[k + 1].location.lat,
                //         waypoints[k + 1].location.lng
                //     );
                //     summaryPanel.innerHTML += 'Distance from Step ' + (k + 1) + ' to Step ' + (k + 2) + ': ' + distance.toFixed(2) + ' km<br>';
                // }
                
                // For each route, display summary information.
                for (var i = 0; i < route.legs.length; i++) {
                    var routeSegment = i + 1;
                    summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
                    summaryPanel.innerHTML += ' From : ' + route.legs[i].start_address + ' <br> To : ';
                    summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                    summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
                    
                    // For each step in the leg, display distance.
                    for (var j = 0; j < route.legs[i].steps.length; j++) {
                        summaryPanel.innerHTML += 'Step ' + (j + 1) + ': ' + route.legs[i].steps[j].distance.text + '<br>';
                    }
    
                    summaryPanel.innerHTML += '<br>';
                }
            }
        });
    }
    
    // function haversineDistance(lat1, lon1, lat2, lon2) {
    //     // Radius of the Earth in kilometers
    //     var R = 6371;
    
    //     // Convert latitude and longitude from degrees to radians
    //     var radLat1 = (lat1 * Math.PI) / 180;
    //     var radLon1 = (lon1 * Math.PI) / 180;
    //     var radLat2 = (lat2 * Math.PI) / 180;
    //     var radLon2 = (lon2 * Math.PI) / 180;
    
    //     // Calculate the differences between the coordinates
    //     var dLat = radLat2 - radLat1;
    //     var dLon = radLon2 - radLon1;
    
    //     // Haversine formula
    //     var a =
    //         Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    //         Math.cos(radLat1) * Math.cos(radLat2) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    //     var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    //     var distance = R * c;
    
    //     return distance;
    // }
    // function calculateDistance(lat1, lon1, lat2, lon2) {
    //     const R = 6371; // Radius of the Earth in kilometers
    //     const dLat = (lat2 - lat1) * (Math.PI / 180);
    //     const dLon = (lon2 - lon1) * (Math.PI / 180);
    //     const a =
    //         Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    //         Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
    //         Math.sin(dLon / 2) * Math.sin(dLon / 2);
    //     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    //     const distance = R * c; // Distance in kilometers
    //     return distance;
    // }
    // function haversineDistance(lat1, lon1, lat2, lon2) {
    //     const R = 6371; // Radius of the Earth in kilometers
    //     const dLat = (lat2 - lat1) * (Math.PI / 180);
    //     const dLon = (lon2 - lon1) * (Math.PI / 180);
    //     const a =
    //         Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    //         Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    //     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    //     const distance = R * c; // Distance in kilometers
    
    //     return distance;
    // }
    // function haversineDistance(lat1, lon1, lat2, lon2) {
    //     const R = 6371; // Radius of the Earth in kilometers
    //     const dLat = (lat2 - lat1) * (Math.PI / 180);
    //     const dLon = (lon2 - lon1) * (Math.PI / 180);
    //     const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    //     const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    //     const distance = R * c; // Distance in kilometers
    //     return distance;
    // }
    
    function haversineDistance(lat1, lon1, lat2, lon2) {
        // Convert latitude and longitude from degrees to radians
        lat1 = toRadians(lat1);
        lon1 = toRadians(lon1);
        lat2 = toRadians(lat2);
        lon2 = toRadians(lon2);
    
        // Haversine formula
        const dlat = lat2 - lat1;
        const dlon = lon2 - lon1;
    
        const a = Math.sin(dlat / 2) * Math.sin(dlat / 2) +
                  Math.cos(lat1) * Math.cos(lat2) *
                  Math.sin(dlon / 2) * Math.sin(dlon / 2);
    
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    
        // Radius of Earth in kilometers (change to miles if needed)
        const R = 6371;
    
        // Calculate the distance
        const distance = R * c;
    
        return distance;
    }
    
    function toRadians(degrees) {
        return degrees * (Math.PI / 180);
    }



</script>
<!--<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_D2OGy8DhGv9DbPeTguV6XNt-X61s9HA&callback=initMap"></script>-->
<!--Tarang-->
<!--<script type="text/javascript" async  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_D2OGy8DhGv9DbPeTguV6XNt-X61s9HA&callback=initMap"></script>-->
<!--New-->
<script type="text/javascript" async  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPzFD2CfOOMLxSl04wsqGcXqX0t1mQFrI&callback=initMap"></script>

