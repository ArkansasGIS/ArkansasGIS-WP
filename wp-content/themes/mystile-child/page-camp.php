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

    var CAMPStatusLayer = L.esri.featureLayer('http://services.arcgis.com/PwY9ZuZRDiI5nXUB/ArcGIS/rest/services/CAMP_Status_20140624_Feature/FeatureServer/0', {
        simplifyFactor: 0.5,
        fillOpacity: 1,
        color: '#000000 ',
        style: function (feature) {
            if (feature.properties.CAMPYear === 2004) {
                return { fillColor: '#990000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2005) {
                return { fillColor: '#A00000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2006) {
                return { fillColor: '#B00000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2007) {
                return { fillColor: '#C00000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2008) {
                return { fillColor: '#D00000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2009) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2010) {
                return { fillColor: 'white', weight: 2 };
            } else if (feature.properties.CAMPYear === 2011) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2012) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2013) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.CAMPYear === 2014) {
                return { fillColor: '#000066', weight: 2 };
            } else if (feature.properties.CAMPYear === 0) {
                return { fillOpacity: '0', weight: 0 };
            }
        }
    }).addTo(map);

 
    var oldStyle;
    var oldID;
    var popup;
    CAMPStatusLayer.on('mouseover', function (e) {
        popup = L.popup({offset: L.point(0, -5), keepInView: true, closeButton: false });
        popup.setLatLng(e.latlng);
        popup.setContent(e.layer.feature.properties.COUNTY_NAM + "<br>" + e.layer.feature.properties.CAMPYear);
        popup.openOn(map);
        oldID = e.layer.feature.id;
        oldStyle = { fillColor: e.layer.options.fillColor, weight: e.layer.options.weight };
        e.layer.bringToFront();
        CAMPStatusLayer.setFeatureStyle(e.layer.feature.id, {
            fillColor: '#9D78D2',
            weight: 3,
            opacity: 1
        });
    });

    CAMPStatusLayer.on('mouseout', function (e) {
        CAMPStatusLayer.setFeatureStyle(oldID, oldStyle);
    }); 
    document.getElementById('searchform').style.visibility = "hidden";
    document.getElementById('searchform').style.height = 0;
    
    var overlays = {
    	"CAMP Status": CAMPStatusLayer
    };
    
    L.control.layers(overlays).addTo(map);
</script>
<?php get_footer(); ?>