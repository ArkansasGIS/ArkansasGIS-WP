
function toggleClipper(){
	var cliptype = document.getElementById('clip_type').value;
	document.getElementById('county_clipper_field').style.display = 'none';
	document.getElementById('city_clipper_field').style.display = 'none';
	document.getElementById('extent_clipper_field').style.display = 'none';
	var vector_formats = document.getElementById('vector_format_type');
			var vector_options = vector_formats.getElementsByTagName("option");
			for(var v = 0; v < vector_options.length; v++){
				vector_options[v].disabled = false;
			}
			var projection_types = document.getElementById('projection');
			var projection_options = projection_types.getElementsByTagName("option");
			for(var v = 0; v < projection_options.length; v++){
				projection_options[v].disabled = false;
			}
	switch(cliptype) {
		case 'County':
			document.getElementById('county_clipper_field').style.display = 'block';
			break;
		case 'City':
			document.getElementById('city_clipper_field').style.display = 'block';
			break;
		case 'Extent':
			document.getElementById('extent_clipper_field').style.display = 'block';
			showExtentMapWindow();
			break;
		case 'State':
			var allowed_vectors = ['SHAPE','FILEGDB'];
			var allowed_projections = ['26915'];
			var vector_formats = document.getElementById('vector_format_type');
			var vector_options = vector_formats.getElementsByTagName("option");
			for(var v = 0; v < vector_options.length; v++){
				if(allowed_vectors.indexOf(vector_options[v].value) == -1){
					vector_options[v].disabled = true;
				}else{
					if(vector_options[v].value == allowed_vectors[0]){
						vector_options[v].selected = 'selected';
					}
				}
			}
			var projection_types = document.getElementById('projection');
			var projection_options = projection_types.getElementsByTagName("option");
			for(var v = 0; v < projection_options.length; v++){
				if(allowed_projections.indexOf(projection_options[v].value) == -1){
					projection_options[v].disabled = true;
				}else{
					projection_options[v].selected = 'selected';
				}
			}
			break;
	}
}

var mapWindow;
var map;
Ext.onReady(function(){
	Ext.define('Ext.ux.LeafletMapView',{
		extend: 'Ext.Component',
		alias: 'widget.leafletmapview',
		config: { map: null},
		afterRender: function(t,eOpts){
			this.callParent(arguments);
			var leafletRef = window.L;
			if(leafletRef == null){
				this.update('No leaflet library loaded');
			}else{
				map = L.map(this.getId());
				map.setView([34.749, -92.286], 8);
				this.setMap(map);
				L.esri.basemapLayer('Gray').addTo(map);
    			L.esri.basemapLayer('GrayLabels').addTo(map); 
			}
			
		}
	});
});

function showExtentMapWindow(){
	mapWindow = Ext.create('Ext.window.Window',{
		layout: 'fit',
		title: 'Extent Map Window',
		id: 'MapWindow',
		modal: true,
		resizeable: false,
		width: '90%',
		height: '80%',
		closeAction: 'destroy',
		items:[
			{
				xtype: 'leafletmapview'
			}
		],
		bbar: [{
			xtype: 'button',
			text: 'Use Current Extent',
			handler: function(){
				var extent = map.getBounds().toBBoxString();
				document.getElementById('extent_clipper').value = extent;
				mapWindow.close();
			}
		}]
	});
	mapWindow.show();
	map.invalidateSize();
}

function addMainMap(){
	var map = L.map('map').setView([34.749, -92.286], 8);

    L.esri.basemapLayer('Gray').addTo(map);
    L.esri.basemapLayer('GrayLabels').addTo(map);
}

function addACFMap(){
	var map = L.map('map').setView([34.749, -92.286], 8);

    L.esri.basemapLayer('Gray').addTo(map);
    L.esri.basemapLayer('GrayLabels').addTo(map);

    var ACFStatusLayer = L.esri.featureLayer('http://services.arcgis.com/PwY9ZuZRDiI5nXUB/ArcGIS/rest/services/ACF_Status_20140827/FeatureServer/0', {
        simplifyFactor: 0.5,
        fillOpacity: 1,
        color: '#000000 ',
        style: function (feature) {
            if (feature.properties.ACFYear === 2004) {
                return { fillColor: '#990000', weight: 2 };
            } else if (feature.properties.ACFYear === 2005) {
                return { fillColor: '#A00000', weight: 2 };
            } else if (feature.properties.ACFYear === 2006) {
                return { fillColor: '#B00000', weight: 2 };
            } else if (feature.properties.ACFYear === 2007) {
                return { fillColor: '#C00000', weight: 2 };
            } else if (feature.properties.ACFYear === 2008) {
                return { fillColor: '#D00000', weight: 2 };
            } else if (feature.properties.ACFYear === 2009) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.ACFYear === 2010) {
                return { fillColor: 'white', weight: 2 };
            } else if (feature.properties.ACFYear === 2011) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.ACFYear === 2012) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.ACFYear === 2013) {
                return { fillColor: '#E80000', weight: 2 };
            } else if (feature.properties.ACFYear === 2014) {
                return { fillColor: '#000066', weight: 2 };
            }
        }
    }).addTo(map);

 
    var oldStyle;
    var oldID;
    var popup;
    ACFStatusLayer.on('mouseover', function (e) {
        popup = L.popup({offset: L.point(0, -5), keepInView: true, closeButton: false });
        popup.setLatLng(e.latlng);
        popup.setContent(e.layer.feature.properties.COUNTY_NAM + "<br>" + e.layer.feature.properties.ACFYear);
        popup.openOn(map);
        oldID = e.layer.feature.id;
        oldStyle = { fillColor: e.layer.options.fillColor, weight: e.layer.options.weight };
        e.layer.bringToFront();
        ACFStatusLayer.setFeatureStyle(e.layer.feature.id, {
            fillColor: '#9D78D2',
            weight: 3,
            opacity: 1
        });
    });

    ACFStatusLayer.on('mouseout', function (e) {
        ACFStatusLayer.setFeatureStyle(oldID, oldStyle);
    }); 
    document.getElementById('searchform').style.visibility = "hidden";
    document.getElementById('searchform').style.height = 0;
    
    var overlays = {
    	"ACF Status": ACFStatusLayer
    };
    
    L.control.layers(overlays).addTo(map);
}

function addCAMPMap(){
	
}
