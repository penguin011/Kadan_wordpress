/**
 * Manage custom marker
 *
 * Custom marker is "Custom Overlays" in the Google MapsJavaScript API.
 */
var overlay;

// Set your custom overlay object's prototype to a new instance of google.maps.OverlayView(). 
// In effect, this will subclass the overlay class.
PBCustomOverlay.prototype = new google.maps.OverlayView();

// Create a constructor for your custom overlay, and set any initialization parameters.
function PBCustomOverlay(bounds, image, map, text) {

  // Initialize all properties.
  this.bounds_ = bounds;
  this.image_ = image;
  this.map_ = map;
  this.text_ = text;

  // Define a property to hold the image's div. We'll
  // actually create this div upon receipt of the onAdd()
  // method so we'll leave it null for now.
  this.div_ = null;

  // Explicitly call setMap on this overlay.
  this.setMap(map);
}

// Implement an onAdd() method within your prototype, and attach the overlay to the map. 
// OverlayView.onAdd() will be called when the map is ready for the overlay to be attached.
PBCustomOverlay.prototype.onAdd = function() {

  var div = document.createElement('div');
  div.className = 'pb_googlemap_custom-overlay';
  div.style.borderStyle = 'none';
  div.style.borderWidth = '0px';
  div.style.position = 'absolute';

  // Create the element and attach it to the div.
  var inner = document.createElement('div');
  inner.className = 'pb_googlemap_custom-overlay-inner';

  if (this.text_) { // Use text
    inner.textContent = this.text_;
  } else { // Use image
    var img = document.createElement('img');
    img.src = this.image_;
    img.className = 'pb_googlemap_custom-overlay-img';
    inner.appendChild(img);
  }

  div.appendChild(inner);

  this.div_ = div;

  // Add the element to the "overlayLayer" pane.
  var panes = this.getPanes();
  panes.overlayLayer.appendChild(div);
};

// Implement a draw() method within your prototype, and handle the visual display of your object. 
// OverlayView.draw() will be called when the object is first displayed.
PBCustomOverlay.prototype.draw = function() {

  // We use the south-west and north-east
  // coordinates of the overlay to peg it to the correct position and size.
  // To do this, we need to retrieve the projection from the overlay.
  var overlayProjection = this.getProjection();

  // Retrieve the south-west and north-east coordinates of this overlay
  // in LatLngs and convert them to pixel coordinates.
  // We'll use these coordinates to resize the div.
  var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
  var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());

  // Resize the image's div to fit the indicated dimensions.
  var div = this.div_;
  div.style.left = sw.x + 'px';
  div.style.top = ne.y + 'px';
  div.style.width = (ne.x - sw.x) + 'px';
  div.style.height = (sw.y - ne.y) + 'px';
};

// The onRemove() method will be called automatically from the API if
// we ever set the overlay's map property to 'null'.
PBCustomOverlay.prototype.onRemove = function() {
  this.div_.parentNode.removeChild(this.div_);
  this.div_ = null;
};

// Initialize the map and the custom overlay.
function initMap(mapId, address, mapSaturation, useCustomOverlay, srcImage, text) {
  var geocoder = new google.maps.Geocoder();
  geocoder.geocode({'address': address}, function(results, status) {
    if (status == 'OK') {
      var mapOptions = {
        center: results[0].geometry.location,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        zoom: 18,
        styles: [{
          stylers: [{
            saturation: mapSaturation
          }]
        }]
      };
      var map = new google.maps.Map(document.getElementById(mapId), mapOptions);
      if (useCustomOverlay) {
        var swBound = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
        var neBound = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
        var bounds = new google.maps.LatLngBounds(swBound, neBound);   
        overlay = new PBCustomOverlay(bounds, srcImage, map, text);
      } else {
        var marker = new google.maps.Marker({
          position: results[0].geometry.location,
          map: map
        });
      }
    }
  });
}
