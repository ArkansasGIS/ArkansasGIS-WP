
function toggleClipper(){
	var cliptype = document.getElementById('clip_type').value;
	document.getElementById('county_clipper_field').style.display = 'none';
	document.getElementById('city_clipper_field').style.display = 'none';
	document.getElementById('extent_clipper_field').style.display = 'none';
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
