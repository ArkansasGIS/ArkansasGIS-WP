<?php
/*
Template Name: Map Page Template
 * Created by RDP for GeoStor Map Viewer   GEOSTOREDITS
 * 09/24/2014
*/

get_header(); ?>
<style>
  #map {position: relative; width: 100%px; height: 800px; margin: 0 auto;}
  .label {
    font-weight: 700;
    text-transform: uppercase;
    text-align: center;
    margin-top: -1em;
  }

  .label div {
    position: relative;
    left: -50%;
    text-shadow: 0px 2px 1px rgba(255,255,255,0.85);
  }
  #info-pane {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    padding: 1em;
    background: white;
  }
</style>
<div id="map">
    
</div>
<div id="info-pane" class="leaflet-bar"></div>
<script>
	var map = L.map('map').setView([34.749, -92.286], 8);

    L.esri.basemapLayer('Gray').addTo(map);
    L.esri.basemapLayer('GrayLabels').addTo(map);
</script>
<?php get_footer(); ?>