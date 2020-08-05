<?php
 
get_header();
 
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
 
        <article class="post">
            <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
			<?php the_content() ?>
        </article>
	
	<?php endwhile;
 
else :
	echo '<p>There are no posts!</p>';
 
endif;

get_footer();
 
?>

<div id="map" style = "height: 500px;"></div>
<script defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAC__rl2LSYGDpYQwIH983-7WZz4Bo6Pl8&callback=initMap">
</script>
 <script>
 	      var customLabel = {
        restaurant: {
          label: 'R'
        },
        bar: {
          label: 'B'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-33.863276, 151.207977),
          zoom: 12
        });
        var infoWindow = new google.maps.InfoWindow;
          downloadUrl('/blog/wp-content/themes/customtheme/connect.php', function(data) {
            var xml = data.responseXML;
            console.log(xml);
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var id = markerElem.getAttribute('id');
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
 </script>
 
