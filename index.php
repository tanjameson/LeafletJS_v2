<!DOCTYPE html>
<html>
    <!-- get map setup   -->
    <?php include 'mapApi.html';?>

    <!-- Load markers in SQL -->
    <?php
$servername = "localhost";
$username = "root";
$password = "jameson123";
$dbname = "db_articles";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT lat, lng,address,description,radius FROM db_articles.location;";
$i = 0;

if ($result = $conn -> query($sql)) {
  //printf("<b>Select query returned %d rows.</b><br><br>", $result->num_rows);
    while ($row = $result -> fetch_row()) {
      $array[$i][0] = $row[0]; //lat
      $array[$i][1] = $row[1]; //lng
      $array[$i][2] = $row[2]; //address
      $array[$i][3] = $row[3]; //description
      $array[$i][4] = $row[4]; //radius
      $i++;
    }
    $data = $array;
    $result -> free_result();
  }

$conn->close();
?>
    <script type="text/javascript">

    //add markers retreived from SQL

    var data = <?php echo json_encode($data, JSON_PRETTY_PRINT) ?>;

var i;

if(data != null)
for (i = 0; i < data.length; i++) {
  //console.log("This",books);
  console.log(data[i][3]);
  console.log(data[i][0]);
  L.marker(
    L.latLng(
    parseFloat(data[i][0]),
    parseFloat(data[i][1])
  )
  )
      .addTo(mymap)
      .bindPopup("<form name='form' action='deleteData.php' method='post'>  <b>Address: </b>"+data[i][2] +"<br><br>"+"<b>Description: </b>" + data[i][3] + "<br> <br> <button type='submit' id='delete' name='delete' value='call'>Delete</button> </form>").on('click',function(e){
        //console.log(e.latlng);

        document.getElementById("delete").addEventListener("click", function() {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            console.log("this is lat", lat);
            console.log("this is lng", lng);
            document.cookie = "lat = " + lat;
            document.cookie = "lng = " + lng;
  
});
        // document.addEventListener("click", function() {
        //     //alert(this.getLatLng());
        //     // var temp = data[0][5];
        //     // console.log(temp);
            
        //     var lat = e.latlng.lat;
        //     var lng = e.latlng.lng;
        //     console.log("this is lat", lat);
        //     console.log("this is lng", lng);
        // });
      });

      var circle = L.circle(
                [parseFloat(data[i][0]), parseFloat(data[i][1])], {
                    color: "red",
                    fillColor: "#f03",
                    fillOpacity: 0.5,
                    radius: data[i][4],
                }
            ).addTo(mymap);

        
}
    //****************************SEARCH CONTROL************************************//




    // listen for the results event and add every result to the map
        var popup = L.popup();

        var theMarker = {};

mymap.on('click',function(e){
  lat = e.latlng.lat;
  lon = e.latlng.lng;

  console.log("You clicked the map at LAT: "+ lat+" and LONG: "+lon );
      //Clear existing marker, 

      if (theMarker != undefined) {
          mymap.removeLayer(theMarker);
      };

  //Add a marker to show where you clicked.
   theMarker = L.marker([lat,lon],{draggable: true}).addTo(mymap).bindPopup(e.latlng+"<br><br><form name='form' action='insertData.php' method='post'> <textarea type='text' rows='4' cols='40' name='subject' id='subject' value='' placeholder='Enter a Description'></textarea> <br><br> <table><tr><td>Radius of Red Zone: </td><td><input id='slide' type='range' min='0' max='5000' step='10' value='0' onchange='updateSlider(this.value)'></td><td id='sliderAmount'>0</td><td>mm</td></tr></table> <br>  <button type='submit' name='sub' value='call'>Save</button> <br> </form>");  

   document.cookie = "lat = " + lat;
   document.cookie = "lng = " + lon;
   document.cookie = "text = " + "";
});

document.addEventListener("click", function() {
  if(document.getElementById("sliderAmount") !=null)
  {
      console.log("ran here");
              var radiusInput = document.getElementById("sliderAmount").innerText;
              var temp = document.getElementById("subject").value;
              var temp2 = temp.toString();
              //var radiusInput = document.getElementById("radiusInput").value;
              console.log("radius input",radiusInput);
              document.cookie = "description = " + temp2;
              document.cookie = "radiusInput = " + radiusInput;
  }
          });
</script>
    
    <script>
    function updateSlider(slideAmount) {
        var sliderDiv = document.getElementById("sliderAmount");
        sliderDiv.innerHTML = slideAmount;
    }

    
</script>
</html>