dojo.require("dijit.dijit"); // optimize: load dijit layer
dojo.require("dijit.layout.BorderContainer");
dojo.require("dijit.layout.ContentPane");
dojo.require("esri.map");
dojo.require("esri.toolbars.navigation");
dojo.require("esri.tasks.query");
dojo.require("dijit.Toolbar");
dojo.require("dijits.identify.Identify");
dojo.require("dijit.Menu");
dojo.require("dojo.io.script");
dojo.require("esri.dijit.Scalebar");
dojo.require("esri.dijit.Legend");
dojo.require("dojox.grid.DataGrid");
dojo.require("dojo.data.ItemFileReadStore");
dojo.require("esri.tasks.find");
dojo.require("dijit.layout.AccordionContainer");
dojo.require("esri.toolbars.draw");
dojo.require("esri.tasks.geometry");
dojo.require("dijit.ColorPalette");
dojo.require("esri.dijit.OverviewMap");

//this global object contains all references needed across functions 
var globals = {}, visible = [], map, dyn_svc, base_layer, app_layer, env, qString, base_layer1,queryTask, query,gsvc,drawColor,outAddr,globalGeometry;
var featureSet, onClickListener, clearInfoListener, navToolbar, user_svc,identifyDijit,title,findTask, findParams, locator,drawToolbar,legend,tc,LegendCP;
var legendLayers = [];
drawColor = [30,144,255,.5];
outAddr = "<br />Approximate Address:<br />No Address Found";
//we need to check the title throughout the app so assign to var here
if (getUrlParam("title")) {
	title = getUrlParam("title");
	title = title.toLowerCase();
}
//make code server agnostic
var site = window.location.hostname;
var mapserv = "";
if (site == "agio-c5mw3k1.hds.arkgov.net" || site == "dev.geostor.arkansas.gov") {
	mapserv = "dev";
} else if (site == "prod.geostor.arkansas.gov" || site == "beta.geostor.arkansas.gov" || site == "www.geostor.arkansas.gov") {
	mapserv = "www";
} else {
	mapserv = "dev";
}
//content
var navBar ='<div id="navToolbar" dojoType="dijit.Toolbar">' +
				  '<div dojoType="dijit.form.Button" id="zoomin" iconClass="zoominIcon" onClick="navToolbar.activate(esri.toolbars.Navigation.ZOOM_IN);" onMouseOver="(alert("Zoom!");)"></div>' +
				  '<div dojoType="dijit.form.Button" id="zoomout" iconClass="zoomoutIcon" onClick="navToolbar.activate(esri.toolbars.Navigation.ZOOM_OUT);"></div>' +
				  '<div dojoType="dijit.form.Button" id="zoomfullext" iconClass="zoomfullextIcon" onClick="fullExtent();"></div>' +
				  '<div dojoType="dijit.form.Button" id="zoomprev" iconClass="zoomprevIcon" onClick="navToolbar.zoomToPrevExtent();"></div>' +
				  '<div dojoType="dijit.form.Button" id="zoomnext" iconClass="zoomnextIcon" onClick="navToolbar.zoomToNextExtent();"></div>' +
				  '<div dojoType="dijit.form.Button" id="pan" iconClass="panIcon" onClick="navToolbar.activate(esri.toolbars.Navigation.PAN);"></div>' +
				  '<div dojoType="dijit.form.Button" id="deactivate" iconClass="deactivateIcon" onClick="navToolbar.deactivate()"></div>' +
				  '<div dojoType="dijit.form.Button" id="getQuery" iconClass="helpIcon" onClick="id_toolSelect(\'query\');"></div>' +
			  '<div dojoType="dijit.form.DropDownButton"> ' +
            	'<span>Map Types</span> ' +
            	'<div dojoType="dijit.Menu" id="gMapType"> ' +
                	'<div dojoType="dijit.MenuItem" label="Imagery" onclick="changeMap([acf,imagery]);"></div> ' +
                	'<div dojoType="dijit.MenuItem" label="Street" onclick="changeMap([streetMap]);"></div> ' +
                	'<div dojoType="dijit.MenuItem" label="Topo" onclick="changeMap([topo]);"></div> ' +
				'<div dojoType="dijit.MenuItem" label="Relief" onclick="changeMap([hillshade]);"></div> ' +
				'<div dojoType="dijit.MenuItem" label="HighRes" onclick="changeMap([acf,hires]);"></div> ' +
            	'</div> </div>';
var mapDiv = '<div id="map">' +
			'<span id="status" style="position: absolute; display:none; z-index: 999; right:5px; top:35px;' + 
			'background-color: black; color: white; padding: 3px; font-size: small; font-family: Arial Unicode MS,Arial,sans-serif; border: solid 1px white;">' +
			'Please wait...</span><span id="info" style="position:absolute; right:25px; bottom:5px; color:#000; z-index:50;"></span></div>' ;
var headerContent = '<span id="home" style="z-index:2;position:absolute;top:0;left:0;font-size:medium"><a href="Home.html">Home</a></span><div style="z-index:2;position:absolute;top:0;right:0;">' +
		'<input type="text" id="address" size="30" value="Street, City, State, Zip" onclick="this.value=\'\'" />' +
		 '<input type="button" value="Find"  onclick="locate(dojo.byId(\'address\').value)" /><br />'+
		 '<select id="countySelect" onChange="zoomCounty();">'+
			'<option value="default">-- Zoom to County --</option>'+
			'<option value="649648.96681217197,3795403.5127739101">Arkansas</option>'+
			'<option value="614796.99988170306,3673159.3169865198">Ashley</option>'+
			'<option value="559544.26845948503,4015983.3794657900">Baxter</option>'+
			'<option value="387291.33729256497,4022261.2433292498">Benton</option>'+
			'<option value="491784.02574423398,4018173.6384861101">Boone</option>'+
			'<option value="577833.45916956104,3703291.2154851300">Bradley</option>'+
			'<option value="546130.77766113204,3713254.0582466898">Calhoun</option>'+
			'<option value="451752.28998246399,4021913.6482996698">Carroll</option>'+
			'<option value="658902.78842217999,3682180.4723009500">Chicot</option>'+
			'<option value="483745.47190802899,3767803.4778462299">Clark</option>'+
			'<option value="731713.03289224603,4027912.3160559600">Clay</option>'+
			'<option value="588228.60540667397,3933157.8539587599">Cleburne</option>'+
			'<option value="575346.66193542699,3751180.5881986502">Cleveland</option>'+
			'<option value="478811.65163989097,3675057.8459907402">Columbia</option>'+
			'<option value="527147.28493562399,3902170.7881053300">Conway</option>'+
			'<option value="713815.22912935703,3967782.8627557200">Craighead</option>'+
			'<option value="387397.87325038901,3939000.9930614298">Crawford</option>'+
			'<option value="744998.46583256195,3899327.5184714301">Crittenden</option>'+
			'<option value="702670.46542847401,3908119.3191154599">Cross</option>'+
			'<option value="531930.63947073906,3758867.9024272398">Dallas</option>'+
			'<option value="661450.30162863003,3744875.9202175299">Desha</option>'+
			'<option value="618778.88128930598,3717361.3558317102">Drew</option>'+
			'<option value="560833.34455108398,3889539.1052226098">Faulkner</option>'+
			'<option value="419232.32225270401,3930213.2816935098">Franklin</option>'+
			'<option value="605993.99552385497,4026940.6982614701">Fulton</option>'+
			'<option value="486207.88093548798,3826110.1361536402">Garland</option>'+
			'<option value="553044.35486457904,3794459.4438052201">Grant</option>'+
			'<option value="719687.05174566701,3999761.2073908602">Greene</option>'+
			'<option value="438083.57045694703,3732999.6537257601">Hempstead</option>'+
			'<option value="504957.22316265898,3797386.4805100500">Hot Spring</option>'+
			'<option value="408361.87739939499,3772421.8687796001">Howard</option>'+
			'<option value="629335.29472653905,3956223.6827913802">Independence</option>'+
			'<option value="597820.71731729805,3995008.7882564599">Izard</option>'+
			'<option value="661728.97606370097,3940945.7978943498">Jackson</option>'+
			'<option value="598465.80211147002,3792422.9268484400">Jefferson</option>'+
			'<option value="458320.00664363499,3936345.8161598602">Johnson</option>'+
			'<option value="443437.97575325798,3678220.2812255202">Lafayette</option>'+
			'<option value="670537.03116428701,3990180.0410801200">Lawrence</option>'+
			'<option value="703126.28362435102,3850948.0505147302">Lee</option>'+
			'<option value="616934.74991301098,3758107.3049117201">Lincoln</option>'+
			'<option value="385538.45930755400,3729682.1822416899">Little River</option>'+
			'<option value="434778.86113864701,3897151.4912855299">Logan</option>'+
			'<option value="601718.99763403600,3846327.8549165898">Lonoke</option>'+
			'<option value="434704.91490432603,3985387.0050500198">Madison</option>'+
			'<option value="528374.11377980001,4013743.5855928198">Marion</option>'+
			'<option value="416955.83965314197,3686256.9637862602">Miller</option>'+
			'<option value="766391.69882268005,3961657.1311796098">Mississippi</option>'+
			'<option value="664559.18239247403,3838756.1291221501">Monroe</option>'+
			'<option value="439476.94779662899,3822103.5208762302">Montgomery</option>'+
			'<option value="471528.77417376998,3724926.6213369798">Nevada</option>'+
			'<option value="480345.46106528799,3975090.4398268899">Newton</option>'+
			'<option value="510951.29818538198,3717063.8821757301">Ouachita</option>'+
			'<option value="506246.61044014100,3867229.7238289998">Perry</option>'+
			'<option value="697717.07634037803,3811707.4348657602">Phillips</option>'+
			'<option value="439511.80995246698,3780474.1749697099">Pike</option>'+
			'<option value="711785.47435789998,3939233.9731386900">Poinsett</option>'+
			'<option value="387215.87588179298,3816690.3703567502">Polk</option>'+
			'<option value="496896.32647000800,3922691.9538297099">Pope</option>'+
			'<option value="632343.09206290799,3855099.3461461100">Prairie</option>'+
			'<option value="562972.73796049296,3847748.9767460902">Pulaski</option>'+
			'<option value="677016.64160987502,4023629.7245637798">Randolph</option>'+
			'<option value="529666.40176127804,3833906.9741141498">Saline</option>'+
			'<option value="402803.05459636397,3858117.1120448802">Scott</option>'+
			'<option value="527112.04513861903,3974107.2950857198">Searcy</option>'+
			'<option value="384004.28603471402,3895857.0696314499">Sebastian</option>'+
			'<option value="385374.61633338802,3762534.8922247901">Sevier</option>'+
			'<option value="636726.24752132501,4002880.3281082800">Sharp</option>'+
			'<option value="705477.95864157495,3877811.5962991500">St. Francis</option>'+
			'<option value="576142.74945869797,3968732.1418766500">Stone</option>'+
			'<option value="537562.53211186896,3670371.4071835298">Union</option>'+
			'<option value="543881.89086917904,3937542.4917988800">Van Buren</option>'+
			'<option value="390398.18118473102,3982298.3074564799">Washington</option>'+
			'<option value="614103.22628377599,3902183.6430168599">White</option>'+
			'<option value="659966.47033656004,3895104.7911706199">Woodruff</option>'+
			'<option value="462479.74611147901,3873421.9001760199">Yell</option>'+
			'</select></div></div><div id="headertitle" style="margin-left: auto;margin-right: auto;"><br /></div></div>';
var findAddress = '<div style="z-index:100;position:absolute;top:5px;right:5px;">' +
		'<input type="text" id="address" size="30" value="Street, City, State, Zip" onclick="this.value=\'\'" />' +
		 '<input type="button" value="Find"  onclick="locate(dojo.byId(\'address\').value)" /></div>';
var horMenu = '<div dojoType="dijit.form.DropDownButton"> ' +
            	'<span>Districts</span> ' +
            	'<div dojoType="dijit.Menu" id="horMenu"> ' +
                	'<div dojoType="dijit.MenuItem" label="Congress" onclick="changeHorMap([2,1]);"></div> ' +
                	'<div dojoType="dijit.MenuItem" label="Fire" onclick="changeHorMap([2,8]);"></div> ' +
                	'<div dojoType="dijit.MenuItem" label="Senate" onclick="changeHorMap([2,3]);"></div> ' +
					'<div dojoType="dijit.MenuItem" label="House" onclick="changeHorMap([2]);"></div> ' +
				'<div dojoType="dijit.MenuItem" label="School" onclick="changeHorMap([2,4]);"></div> ' +
            	'</div> </div>';
function init() {
	//check to see if map will be embedded or a whole page
	if (getUrlParam("embed") && getUrlParam("w") && getUrlParam("h")) {
		if (getUrlParam("embed") == "true") {
			//check size and set content based upon it
			var strContent = "";
			if (Number(getUrlParam("w")) < 400 && Number(getUrlParam("h")) < 400) {
				strContent = mapDiv;
			} else if (Number(getUrlParam("w")) < 600) {
				strContent = navBar + '</div>' + mapDiv;
			} else {
				if (title == "rep") {
					strContent = navBar + horMenu + '</div>' + findAddress + mapDiv;
				} else {
					strContent = navBar + '</div>' + findAddress + mapDiv;
				}
			}
			var bc = new dijit.layout.BorderContainer({style: "height: " + getUrlParam("h") + "px; width: " + getUrlParam("w") + "px;"});
			var center = new dijit.layout.ContentPane({
			   id: "center",
				region: "center",
				style: "overflow:hidden;",
			   content: strContent
			});
			bc.addChild(center);
			document.body.appendChild(bc.domNode);
			bc.startup();
			genMenu(bc,"center");
		}
	} else if (getUrlParam("mobile")) {
		if (getUrlParam("mobile") == "true") {
			strContent = navBar + '</div>' + mapDiv;
			var bc = new dijit.layout.BorderContainer({style: "width:100%; height:100%;"});
				var center = new dijit.layout.ContentPane({
				   id: "main",
					region: "center",
					style: "overflow:hidden;",
				   content: strContent
				});
				bc.addChild(center);
				document.body.appendChild(bc.domNode);
				bc.startup();
				genMenu(bc,"main");
		}
	} else {
		//whole page
		var bc = new dijit.layout.BorderContainer({style: "width:100%; height:100%;"});
			var header = new dijit.layout.ContentPane({
				id: "header",
			   region: "top",
			   content: headerContent
			});
			bc.addChild(header);
			//add the tc
			//tc = new dijit.layout.TabContainer({
			tc = new dijit.layout.AccordionContainer({
				id: "leftPane",
				region:"left"
			});
			var BaseLayers = new dijit.layout.ContentPane({
				content: '<span id="layer_list">Loading...</span><br />',
				title:"BaseLayers"
			});
			tc.addChild(BaseLayers);
			if (getUrlParam("app")) {
				//var UserLayers = new dijit.layout.ContentPane({
				var UserLayers = new dijit.layout.ContentPane({
					id:"ulayers",
					content: '<span id="user_svc_list">Loading...</span><br />',
					selected:"true",
					title:"AppLayers"
				});
				tc.addChild(UserLayers);
				findContent = '<input type="text" id="find" size="30" /><br />' +
				'<input type="button" id="findsubmit" value="Find"  onclick="executeFindTask(dojo.byId(\'find\').value)" />' +
				'<input type="button" value="Clear"  onclick="clearFresults();" /> <div id="resultsdiv" style="display:none;float:right"></div>' +
				'<table dojotype="dojox.grid.DataGrid" jsid="grid" id="grid" ><thead><tr><th field="0" width="250px">Results</th><th field="1" width="1px"></th></tr></thead></table>';
				var FindTab = new dijit.layout.ContentPane({
					content: findContent,
					title:"Find"
				});
				tc.addChild(FindTab);
			}
			//legend test
			LegendCP = new dijit.layout.ContentPane({
				content: '<span id="legendDiv"></span><br />',
				title:"Legend"
			});
			tc.addChild(LegendCP);
			var drawBar = '<div id="drawToolbar" dojoType="dijit.Toolbar">' +
				//'<div dojoType="dijit.form.Button" iconClass="pointIcon" onClick="drawToolbar.activate(esri.toolbars.Draw.POINT);id_toolSelect(\'draw\',\'Point\');"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="pointIcon" onClick="drawToolbar.activate(esri.toolbars.Draw.MULTI_POINT);id_toolSelect(\'draw\',\'MultiPoint\');"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="plineIcon" onClick="drawToolbar.activate(esri.toolbars.Draw.POLYLINE);id_toolSelect(\'draw\',\'PolyLine\');"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="polygonIcon" onClick="drawToolbar.activate(esri.toolbars.Draw.POLYGON);id_toolSelect(\'draw\',\'Polygon\');"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="fplineIcon" onClick="drawToolbar.activate(esri.toolbars.Draw.FREEHAND_POLYLINE);id_toolSelect(\'draw\',\'Freehand Line\');"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="fpolygonIcon" onClick="drawToolbar.activate(esri.toolbars.Draw.FREEHAND_POLYGON);id_toolSelect(\'draw\',\'Freehand Polygon\');"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="clearIcon" onClick="clearMap();dojo.byId(\'areaDisplay\').innerHTML = \'\';dojo.byId(\'distDisplay\').innerHTML = \'\';"></div>' +
				'<div dojoType="dijit.form.Button" iconClass="deactivateIcon" onClick="drawToolbar.deactivate();map.showZoomSlider();map.setCursor(\'default\');dojo.byId(\'activeDrawTool\').innerHTML = \'\';">' +
				'</div></div><span id="colorPalette"></span><div id="selColor" style="float:right;width:30px;padding:28px;border-style:solid;border-width:5px;background-color:rgb(30,144,255);"></div><br /><br />' +
				'<div id="measureResults"><b><u>Results</u></b><span id="activeDrawTool" style="float:right;font-size:small"></span><hr><span id="distDisplay"></span><br /><span id="areaDisplay"></span></div><br /><br /><br /><br /><br />' + 
				'<div id="print"><b><u>Print</u></b><hr>Title:<input type="text" id="printTitle" size="30" value="Printed from GeoStor" onclick="this.value=\'\'" />' +
				'<input type="button" id="printSubmit" value="Print"  onclick="printMap();" /></div>';
			//'<input type="button" id="printSubmit" value="Print"  onclick="printMap();" /><button dojoType="dijit.form.Button" onClick="exportImage(user_svc)">Print Map</button></div>';
			var DrawingTab = new dijit.layout.ContentPane({
				content: drawBar,
				title:"Draw & Print"
			});
			tc.addChild(DrawingTab);
			
			bc.addChild(tc);
			var center = new dijit.layout.ContentPane({
				id: "center",
			   region: "center",
				style: "overflow:hidden;",
				content: navBar + '</div>' + mapDiv
			});
			//
			bc.addChild(center);
			document.body.appendChild(bc.domNode);
			bc.startup();
			genMenu(bc,"center");
		//make sure the header is loaded first!
		if (dojo.byId("header")) {
			//check for url parameter
			if (getUrlParam("title")) {
				title = getUrlParam("title");
				document.title = title;
				dojo.byId("headertitle").innerHTML = title;
			} else {
				dojo.byId("headertitle").innerHTML = "GeoStor Viewer";
				title = "GeoStor Viewer";
			}
		}
	}
	//needed for the geometry operations
	esriConfig.defaults.io.proxyUrl = "/G6_ASP/proxy.ashx";
    esriConfig.defaults.io.alwaysUseProxy = false;
	var initExtent = new esri.geometry.Extent({
          "xmin": 311464,
          "ymin": 3637283,
          "xmax": 837324,
          "ymax": 4092367,
          "spatialReference": {
            "wkid": 26915
          }
        });
	//define our LODs here
	var lods = [
	  {"level" : 0, "resolution" : 661.459656252646, "scale" : 2500000},
	  {"level" : 1, "resolution" : 396.875793751588, "scale" : 1500000},
	  {"level" : 2, "resolution" : 264.583862501058, "scale" : 1000000},
	  {"level" : 3, "resolution" : 132.291931250529, "scale" : 500000},
	  {"level" : 4, "resolution" : 66.1459656252646, "scale" : 250000},
	  {"level" : 5, "resolution" : 26.4583862501058, "scale" : 100000},
	  {"level" : 6, "resolution" : 6.3500127000254, "scale" : 24000},
	  {"level" : 7, "resolution" : 2.64583862501058, "scale" : 10000},
	  {"level" : 8, "resolution" : 1.58750317500635, "scale" : 6000},
	  {"level" : 9, "resolution" : 0.529167725002117, "scale" : 2000},
	  {"level" : 10, "resolution" : 0.264583862501058, "scale" : 1000}
	];
	//map = new esri.Map("map", {logo:false, spatialReference: new esri.SpatialReference({ wkid: 26915 }),lods:lods});
	map = new esri.Map("map", {logo:false, spatialReference: new esri.SpatialReference({ wkid: 26915 })});
	//map = new esri.Map("map", {logo:false, extent:initExtent});
	
	//load basemap
	streetMap = new esri.layers.ArcGISTiledMapServiceLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/BASEMAP/MapServer", {id:"streetMap"});
	//set default layer
	//legendLayers.push({layer:streetMap,title:'Basemap'});
	map.addLayer(streetMap);
	legendLayers.push({layer:streetMap,title:'Basemap'});
	
	//dojo.connect(dojo.byId("print"),"onclick",dojo.hitch(esri.arcgisonline.map.print,"printMap"));
	
	//load the other layers
	imagery = initLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/IMAGE_SERVICES/2006_1M_Statewide_Ortho/ImageServer", "imagery", "ais");
	hillshade = initLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/IMAGE_SERVICES/DRG_24K_HILLSHADE/ImageServer", "hillshade", "ais");
	topo = initLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/IMAGE_SERVICES/DRG_24K/ImageServer", "topo", "ais");
	hires = initLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/IMAGE_SERVICES/HIGH_RES/ImageServer", "hires", "ais");
	acf = initLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/BASEMAP_HYBRID/MapServer", "acf", "tiled");
	acf.hide();
	legendLayers.push({layer:acf,title:'Basemap Hybrid'});

	//load basemap dynamic last so it's always on top
	dyn_svc = new esri.layers.ArcGISDynamicMapServiceLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/BASEMAP_DYNAMIC/MapServer",{id:"dyn_svc"});
	dyn_svc.setOpacity(".5")
	legendLayers.push({layer:dyn_svc,title:'Basemap Dynamic'});
		
	if (getUrlParam("app")) {
		//ex, layer = APP_SALINE
		var svc = getUrlParam("app");
		var layers =new Array();
		if (svc.indexOf("(") > 0) {
			temp = svc.substring(svc.indexOf("(") + 1, svc.length - 1)
			if (temp.length > 1) {
				layers = temp.split(",");
			} else {
				layers[0] = temp;
			}
			svc = svc.substring(0,svc.indexOf("("));
		}
		user_svc = new esri.layers.ArcGISDynamicMapServiceLayer("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/APPS/" + svc + "/MapServer",{id:"user_svc"});
		user_svc.setOpacity(".7")
		//dojo.connect(user_svc, "onLoad", function(evt){ legendLayers.push({layer:user_svc,title:svc.toUpperCase()})});
		legendLayers.push({layer:user_svc,title:svc.toUpperCase()});
		if (layers.length > 0) {
			user_svc.setVisibleLayers(layers)
		}
		//we dont want identify for the districts app
		if (!(svc.toLowerCase() == "app_districts" && title.toLowerCase() == "rep")) {
			var mapadd = dojo.connect(map, "onLayerAdd", initIdent);
		}
		
		//setup find for the apps
		//create find task with url to map service
        findTask = new esri.tasks.FindTask("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/APPS/" + svc + "/MapServer");
		setFindParams(svc);
		//event handler for results grid click
		if (!(getUrlParam("embed"))) {
			dojo.connect(grid, "onRowClick", onRowClickHandler);
		}
		//not sure why it was showing the acf layer for apps, but hide it here
		acf.hide();		
	}
	
	//build dynamic list from layer's features
	//don't need to build the layer list for the embedded map
	//if (!(getUrlParam("embed"))) {
		//basemap dynamic
	  dojo.connect(dyn_svc, "onLoad", function(evt){ buildLayerList(dyn_svc,"layer_list", "Basemap Dynamic")});
	  if (getUrlParam("dyn")) {
		  var layers = getUrlParam("dyn").split(',');
		  dyn_svc.setVisibleLayers(layers);
	  }
	  //app layer list
		if (getUrlParam("app")) {
				dojo.connect(user_svc, "onLoad", function(evt){ buildLayerList(user_svc,"user_svc_list",getUrlParam("app"))});
		}
	//~ } else {
		//~ //embedded map
		//~ if (getUrlParam("app")) {
				//~ dojo.connect(user_svc, "onLoad", function(evt){ buildLayerList(user_svc,"user_svc_list",getUrlParam("app"))});
		//~ }
	//~ }

	if (!(strContent == mapDiv)) {
		navToolbar = new esri.toolbars.Navigation(map);
		dojo.connect(navToolbar, "onExtentHistoryChange", extentHistoryChangeHandler);
	}

	//locator
	locator = new esri.tasks.Locator("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/GeoStor_Locator/GeocodeServer");
	var addloc = dojo.connect(locator, "onAddressToLocationsComplete", showLResults);
	//var locadd = dojo.connect(locator, "onLocationToAddressComplete", returnRResults);

	//build query task
	// I CHANGED THIS LINE RIGHT HERE!!!!!!!!
	queryTask = new esri.tasks.QueryTask("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/APPS/APP_DISTRICTS/MapServer/0");
	
	//build query filter
	query = new esri.tasks.Query();
	//query.returnGeometry = true;
	query.outFields = ["TYPE","NAME","URL"];

	gsvc = new esri.tasks.GeometryService("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/Geometry/GeometryServer");
	//needed for calculating area and length
	dojo.connect(gsvc, "onLengthsComplete", outputDistance);
	dojo.connect(gsvc, "onAreasAndLengthsComplete", outputAreaAndLength);

	//resize/reposition issue fix
	var resizeTimer;
	dojo.connect(map, 'onLoad', function(theMap) {
          var scalebar = new esri.dijit.Scalebar({
            map: map,
            scalebarUnit:'english'
          });
          dojo.connect(dijit.byId('map'), 'resize', function() { //resize the map if the div is resized
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
              map.resize();
              map.reposition();
            }, 500);
          });
        });
	
	//check for address in URL
	if (getUrlParam("addr")) {
		var addr = getUrlParam("addr");
	//	addr = addr.split(",");
		dojo.connect(map, "onLoad", function(evt){ locate(addr)});
	}
	
	//check for xy in URL
	dojo.connect(map, "onLoad", function(evt){ xyZoom()});
	
	//check for base in URL
	if (getUrlParam("base")) {
		var base = getUrlParam("base");
		base = base.split(",");
		for (var i in base) {
			switch(base[i]) {
				case "acf":
					base[i] = acf;
					break;
				case "streetMap":
					base[i] = streetMap;
					break;
				case "imagery":
					base[i] = imagery;
					break;
				case "topo":
					base[i] = topo;
					break;
				case "hillshade":
					base[i] = hillshade;
					break;
				case "hires":
					base[i] = hires;
					break;
			}
		}
		//wait till the last base layer has been loaded
		var onchangemap = dojo.connect(map, "onLayerAdd", function(evt){ changeMap(base);});
		//var onchangemap = dojo.connect(map, "onLayersAddResult", function(evt){ changeMap(base);});
	}
	//add drawing toolbar
	dojo.connect(map, "onLoad", function(evt){
		drawToolbar = new esri.toolbars.Draw(map);
		//if (getUrlParam("app")) { destroyIdent() }	
        dojo.connect(drawToolbar, "onDrawEnd", addToMap);
	});
	
	//init color palette
	var myPalette = new dijit.ColorPalette({
		palette: "3x4",
		onChange: function(val) {
			var selColor = dojo.byId("selColor");
			//selColor.style.color = val; //backgroundColor 
			selColor.style.backgroundColor = val
			//selColor.innerHTML = 'Current Color';
			color = dojo.colorFromHex(val)
			drawColor = color.toRgba();
			drawColor[3] = .5;			
		}
	},
	"colorPalette");
	//do not need zoomslider for mobile map
	//dojo.connect(map, "onLoad", function(evt){ if (getUrlParam("mobile")) { map.hideZoomSlider(); }});	
	
	//legend init
	legend = new esri.dijit.Legend({
	  map:map,
		layerInfos:legendLayers
	},"legendDiv");
	if (!(getUrlParam("embed"))) { legend.startup();}

	//add the overview map 
	dojo.connect(map, "onLoad", function(evt){
		var overviewMapDijit = new esri.dijit.OverviewMap({
			map: map,
			attachTo:"bottom-right",
			visible:true
		});
		if (!(getUrlParam("embed"))) { overviewMapDijit.startup();}
	});
	
	//dirty hack to fix scalebar issue
	dojo.connect(map, "onLoad", function(evt){
		var pnt = new esri.geometry.Point(579024.920766846,3846966.3755111854,new esri.SpatialReference({ wkid: 26915}));
		map.centerAndZoom(pnt, 0);
	});
} //end init

//show x,y on map
function showCoordinates(evt) {
	var mp = evt.mapPoint;
	dojo.byId("info").innerHTML = mp.x + ", " + mp.y;
}

//query functions begin
function executeQueryTask(evt) {
	var loading = dojo.byId("status");
	loading.innerHTML = "Please wait...";
	var content = "";
	map.infoWindow.hide();
	featureSet = null;
	//check to see if evt is an onclick or point
	if (evt.mapPoint) {
		query.geometry = evt.mapPoint;
	} else {
		query.geometry = evt;
	}
	var symbol = new esri.symbol.SimpleMarkerSymbol();
	symbol.setStyle(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE);
	symbol.setColor(new dojo.Color([255,0,0,0.75]));
	//Execute task and call showResults on completion
	queryTask.execute(query, function(fset) {
		var feature = fset.features[0];
		//set symbol
		var symbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_SOLID, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([255,0,0]), 2), new dojo.Color([255,255,0,0.5]));
		feature.setSymbol(symbol);
		//construct infowindow title and content
		var numFeatures = fset.features.length;
                var iwtitle_dist = "Districts - ";
                var iwtitle_add = "New districts based on 2010 census.";
		var iwtitle = iwtitle_dist + iwtitle_add.fontcolor("Red").italics();
                //var iwtitle = iwtitle_dist;
		var i,winwidth;
		winwidth = 25;
		for (i=0; i<numFeatures; i++) {
			var graphic = fset.features[i];
			if (!(graphic.attributes.URL == "NA")) {
				content = content + graphic.attributes.TYPE + ' : ' + '<a href = "' + graphic.attributes.URL + '" target="blank_">' + graphic.attributes.NAME + '</a><br />';
				temp = graphic.attributes.TYPE + " : " + graphic.attributes.NAME
				temp1 = temp.length
			} else {
				content = content + graphic.attributes.TYPE + " : " + graphic.attributes.NAME + "<br />";
				temp = graphic.attributes.TYPE + " : " + graphic.attributes.NAME
				temp1 = temp.length
			}
			//setup a custom width for the infoWindow
			if (winwidth < temp1) {
				winwidth = temp1;
			}
		}
		map.graphics.add(feature);
		map.infoWindow.setTitle(iwtitle);
		map.infoWindow.setContent(content);
		//dynamically resize our infoWindow
                //map.infoWindow.resize(winwidth*7.5,i*18);
		map.infoWindow.resize(winwidth*10,i*20);
		if (evt.mapPoint) {
			(evt) ? map.infoWindow.show(evt.screenPoint,map.getInfoWindowAnchor(evt.screenPoint)) : null;
		} else {
			sp = esri.geometry.toScreenGeometry(map.extent, map.width, map.height, evt)
			map.infoWindow.show(sp,map.getInfoWindowAnchor(sp));
		}
		esri.hide(loading);
	});
	//now disconnect the listener
	dojo.disconnect(onClickListener);
	clearInfoListener = dojo.connect(map.infoWindow, "onHide", function() {map.graphics.clear();});
	if (getUrlParam("app") && !(getUrlParam("title") == "rep") ) {
		id_toolSelect("ident");
	}
}

//find functions begin
function setFindParams(layer) {
	//create find parameters and define known values
	layer = layer.toLowerCase();
	findParams = new esri.tasks.FindParameters();
	findParams.returnGeometry = true;
	switch(layer) {
		case "app_saline":
			findParams.layerIds = [0];
			findParams.searchFields = ["GisOut_OW_NAME","parcel_id"];
			break;
		case "app_jonesboro":
			findParams.layerIds = [6];
			findParams.searchFields = ["OW_NAME"];
			break;
		case "app_adeq":
			findParams.layerIds = [0,1];
			findParams.searchFields = ["PMTNBR","FNAME","AFIN"];
			break;
		case "app_aogc":
			findParams.layerIds = [0,8];
			findParams.searchFields = ["OW_NAME","FNAME"];
			break;
		case "app_agfc":
			findParams.layerIds = [0,8,3];
			findParams.searchFields = ["FNAME","NAME"];
			break;
		case "app_districts":
			findParams.layerIds = [1,2,3];
			findParams.searchFields = ["NAME"];
			break;
	}
}
function executeFindTask(searchText) {
	if (!searchText == '') {
		var loading = dojo.byId("status");
		loading.innerHTML = "Please wait...";
		esri.show(loading);
		//set the search text to find parameters
		findParams.searchText = searchText;
		findTask.execute(findParams, showFResults);
	} else {
		alert('Please enter a search parameter');
	}
}

function showFResults(results) {
	var loading = dojo.byId("status");
	//symbology for graphics
	var markerSymbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_SQUARE, 10, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([255, 0, 0]), 1), new dojo.Color([255, 0, 0, 0.25]));
	var lineSymbol = new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_DASH, new dojo.Color([255, 0, 0]), 1);
	//var polygonSymbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_NONE, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_DASHDOT, new dojo.Color([255, 0, 0]), 2), new dojo.Color([255, 0, 0, 0.25]));
	var polygonSymbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_SOLID, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([0,0,0]), 2), new dojo.Color([255, 0, 0,0.5]));
	
	dojo.byId('resultsdiv').innerHTML = 'Results: <b>' + results.length + '</b>';
	esri.show(dojo.byId('resultsdiv'));
	//find results return an array of findResult.
	map.graphics.clear();
	var dataForGrid = [];
	//Build an array of attribute information and add each found graphic to the map
	dojo.forEach(results, function(result) {
		var mapgraphic = result.feature;
		dataForGrid.push([result.value,result.feature.attributes.OBJECTID]);
		//dataForGrid.push([result.feature.attributes.OBJECTID]);
		switch (mapgraphic.geometry.type) {
		case "point":
		mapgraphic.setSymbol(markerSymbol);
		break;
		case "polyline":
		mapgraphic.setSymbol(lineSymbol);
		break;
		case "polygon":
		mapgraphic.setSymbol(polygonSymbol);
		break;
		}
		map.graphics.add(mapgraphic);
	});
	var data = {
		//identifier: "OBJECTID",
	  items: dataForGrid
	};
	var store = new dojo.data.ItemFileReadStore({
	  data: data
	});
	grid.setStore(store);
	//grid.setQuery({ PARCELID: '*' });
	esri.hide(loading);
}

//Zoom to the parcel when the user clicks a row
function onRowClickHandler(evt){
	var clickedTaxLotId = grid.getItem(evt.rowIndex);
	var selectedTaxLot;
	for (var i=0, il=map.graphics.graphics.length; i<il; i++) {
	  var currentGraphic = map.graphics.graphics[i];
	  if ((currentGraphic.attributes) && currentGraphic.attributes.OBJECTID == clickedTaxLotId[1]){
		selectedTaxLot = currentGraphic;
		break;
	  }
	}
	if (selectedTaxLot.geometry.type == "polygon" || selectedTaxLot.geometry.type == "polyline" ) {
		//only works for polygons & polylines
		var taxLotExtent = selectedTaxLot.geometry.getExtent();
	} else {
		//points have to have their extent generated from their x,y
		var pt = selectedTaxLot.geometry;
		var factor = 1000; //adds to extent, basically what zoom level you want
		taxLotExtent = new esri.geometry.Extent(pt.x - factor, pt.y - factor, pt.x + factor, pt.y + factor, pt.spatialReference);
	}
	map.setExtent(taxLotExtent);
}
function clearFresults() {
	clearMap();
	var dataForGrid = [];
	var newStore = new dojo.data.ItemFileReadStore({data: {items:dataForGrid}});
	grid.setStore(newStore);
	esri.hide(dojo.byId('resultsdiv'));
	dojo.byId('find').value = '';
	dojo.byId('find').focus();
}
//layer functions begin
//build dynamic list from layer's features
function buildLayerList(blayer, id, title) {
	if (!(getUrlParam("embed"))) {
		var infos = blayer.layerInfos, info;
		var items = [];
		for (var i=0, il=infos.length; i<il; i++) {
		  info = infos[i];
			items[i] = "<input type='checkbox' class='" + id + "_item' id='" + info.id + "' onclick='updateLayerVisibility(\"" + blayer.id + "\",\"." + id + "_item\");' /><label for='" + info.id + "'>" + info.name + "</label><br>";
			if (getUrlParam("dyn") && title == "Basemap Dynamic") {
				dlayers = getUrlParam("dyn").split(',');
				for (var j in dlayers) {
					if (dlayers[j] == info.id) {
						items[i] = "<input type='checkbox' class='" + id + "_item' id='" + info.id + "' checked=\"true\" onclick='updateLayerVisibility(\"" + blayer.id + "\",\"." + id + "_item\");' /><label for='" + info.id + "'>" + info.name + "</label><br>";
					}
				}
			}
			//only need to set default vis on apps
			if (title.indexOf("(") > 0) {
				var layers =new Array();
				//this should be when there is an app
				temp = title.substring(title.indexOf("(") + 1, title.length - 1)
				if (temp.length > 1) {
					layers = temp.split(",");
				} else {
					layers[0] = temp;
				}
				for (var j in layers) {
					if (layers[j] == info.id) {
						items[i] = "<input type='checkbox' class='" + id + "_item' id='" + info.id + "' checked=\"true\" onclick='updateLayerVisibility(\"" + blayer.id + "\",\"." + id + "_item\");' /><label for='" + info.id + "'>" + info.name + "</label><br>";
					}
				}
			} else if (info.defaultVisibility) {
				 //check the boxes if they are visible by default
				visible.push(info.id);
				items[i] = "<input type='checkbox' class='" + id + "_item' id='" + info.id + "' checked=\"true\" onclick='updateLayerVisibility(\"" + blayer.id + "\",\"." + id + "_item\");' /><label for='" + info.id + "'>" + info.name + "</label><br>";
			} else {
				//items[i] = "<input type='checkbox' class='" + id + "_item' id='" + info.id + "' onclick='updateLayerVisibility(\"" + blayer.id + "\",\"." + id + "_item\");' /><label for='" + info.id + "'>" + info.name + "</label><br>";
			}
		}
		if (title.indexOf("(") > 0) {
			title = title.substring(0,title.indexOf("("));
		}
		dojo.byId(id).innerHTML = "<b>" + title.toUpperCase() + "</b><br />" + items.join("");
	}
	//console.log(blayer.id);
	//legendLayers.push({layer:blayer,title:title.toUpperCase()});
	map.addLayer(blayer);
}


function initLayer(url, id, type) {
	//init based upon type
	if (type == "tiled") {
		var layer = new esri.layers.ArcGISTiledMapServiceLayer(url, {id:id});
	} else if (type == "dyn") {
		var layer = new esri.layers.ArcGISDynamicMapServiceLayer(url, {id:id});
	} else if (type == "ais") {
		var params = new esri.layers.ImageServiceParameters();
		//if we need to overlay the highres on the statewide it needs to be a png
		/*if (id == "imagery") {
			params.format = "jpg";
		} else {
			params.format = "png";
		}*/
		params.format = "jpg";
		params.noData = 255;
		params.compressionQuality = 80;
		var layer = new esri.layers.ArcGISImageServiceLayer(url, {id:id,imageServiceParameters: params,visible:false});		
	}
	dojo.connect(layer, "onLoad", function() {
	  map.addLayer(layer);
	  layer.hide();
	});
	return layer;
}

function changeMap(layers) {
	hideImageTiledLayers(layers);
	for (var i=0; i<layers.length; i++) {
	  layers[i].show();
	}
}

function changeHorMap(ids) {
		user_svc.setVisibleLayers(ids)
}

function hideImageTiledLayers(layers) {
	for (var j=0, jl=map.layerIds.length; j<jl; j++) {
		  var layer = map.getLayer(map.layerIds[j]);
		  if (dojo.indexOf(layers, layer) == -1) {
			//we want to keep our dynamic layers visible
			if (!(layer.id == "dyn_svc" || layer.id == "user_svc")) {
				layer.hide();
			}
		  }
	}
}

function updateLayerVisibility(layer, id) {
	var inputs = dojo.query(id), input;
	visible = [];
	for (var i=0, il=inputs.length; i<il; i++) {
	  if (inputs[i].checked) {
		visible.push(inputs[i].id);
	  }
	}
	if (layer == "dyn_svc") {
		if (visible.length == 0) {
			dyn_svc.hide();
			legend.refresh(legendLayers);
		} else {
			dyn_svc.show();
			dyn_svc.setVisibleLayers(visible);
			legend.refresh(legendLayers);
		}
	} else if (layer == "user_svc") {
		if (visible.length == 0) {
			user_svc.hide();
			legend.refresh(legendLayers);
		} else {
			user_svc.show();
			user_svc.setVisibleLayers(visible);
			legend.refresh(legendLayers);
		}
	}
} 
//layer functions end 
//geocode functions start
function locate(add) {
	var loading = dojo.byId("status");
	clearMap();
	esri.show(loading);
	dojo.disconnect(clearInfoListener);
        var address = {Singleline: add};
//	var address = {
//	  Address : add[0],
//	  City: add[1],
//	  State: add[2],
//	  Zip: add[3]
//        Singleline: add;
	locator.addressToLocations(address,["Loc_name"]);
}

function showLResults(candidates) {
	var loading = dojo.byId("status");
	var matched=false;
	var candidate,pt,addr_point;
	var symbol = new esri.symbol.SimpleMarkerSymbol();
	symbol.setStyle(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE);
	symbol.setColor(new dojo.Color([255,0,0,0.75]));
	for (var i=0, il=candidates.length; i<il; i++) {
	  candidate = candidates[i];
		if (candidate.score > 70) {
			var attributes = { address: candidate.address, score:candidate.score, locatorName:candidate.attributes.Loc_name };
			var graphic = new esri.Graphic(candidate.location, symbol);
			map.graphics.add(graphic);
			map.graphics.add(new esri.Graphic(candidate.location, new esri.symbol.TextSymbol(attributes.address).setOffset(0, 10)));
			//only show one point based upon dataset
			if (candidate.attributes.Loc_name == "AddPt_Locator") {
				pt = new esri.geometry.Point(candidate.location.x, candidate.location.y, new esri.SpatialReference({ wkid: 26915 }))
				matched=true;
				break;
			}
			if (candidate.attributes.Loc_name == "ACF_Locator") {
				pt = new esri.geometry.Point(candidate.location.x, candidate.location.y, new esri.SpatialReference({ wkid: 26915 }))
				matched=true;
				break;
			}
			if (candidate.attributes.Loc_name == "Zip9_Locator") {
				pt = new esri.geometry.Point(candidate.location.x, candidate.location.y, new esri.SpatialReference({ wkid: 26915 }))
				matched=true;
				break;
			}
			
		}
	}
	//make sure there were matches, if not notify user
	if (matched) {
		map.centerAndZoom(pt, 7)
		//now get the districts info
		executeQueryTask(pt)
	} else {
		alert("No matching address found, or the address wasn't inputted correctly.");
		esri.hide(loading);
	}
}
function reverseGeocode(evt) {
	outAddr = "<br />Approximate Address:<br />No Address Found";
	var pt = new esri.geometry.Point(evt.mapPoint.x, evt.mapPoint.y, new esri.SpatialReference({ wkid: 26915 }))
	locator.locationToAddress(pt, 200,returnRResults);
}

function returnRResults(candidates) {
	outAddr = '';
	outAddr = '<br />Approximate Address:<br />' + candidates.address["Singleline"]; // + '<br />' + candidates.address["City"] + ', AR, ' + candidates.address["ZIP"];
}

//geocode functions end
function resizeMap() {
	//resize the map when the browser resizes - view the 'Resizing and repositioning the map' section in 
	//the following help topic for more details http://help.esri.com/EN/webapi/javascript/arcgis/help/jshelp_start.htm#jshelp/inside_guidelines.htm
	var resizeTimer;
	clearTimeout(resizeTimer);
	resizeTimer = setTimeout(function() {
	  globals.map.resize();
	  globals.map.reposition();
	}, 500);
}

function extentHistoryChangeHandler() {
	dijit.byId("zoomprev").disabled = navToolbar.isFirstExtent();
	dijit.byId("zoomnext").disabled = navToolbar.isLastExtent();
}

function zoomCounty() {
	var temp = new Array();
	temp = dojo.byId('countySelect').value.split(',');
	if (!(temp[0] == "default")) {
		var pnt = new esri.geometry.Point(Number(temp[0]),Number(temp[1]),new esri.SpatialReference({ wkid: 26915}));
		map.centerAndZoom(pnt, 4);
	}
}

function xyZoom() {
	//check for URL params then zoom
	//the lat/long should come in as y,x NOT x,y
	if (getUrlParam("ll") && getUrlParam("z")) {
		var z = getUrlParam("z");
		var temp = new Array();
		temp = getUrlParam("ll").split(',');
		//check to make sure the ll is within the state BB
		//check for valid UTM coordinates
		if ((temp[1] < 4050000 && temp[1] > 3640000) && (temp[0] < 800000 && temp[0] > 340000)) {
			var pnt = new esri.geometry.Point(Number(temp[0]),Number(temp[1]),new esri.SpatialReference({ wkid: 26915}));
			map.centerAndZoom(pnt, Number(z));
			//check for valid lat/long
		} else if ((temp[0] < 37 && temp[0] > 32) && (temp[1] > -95 && temp[1] < -89)) {
			var outSR = new esri.SpatialReference({ wkid: 26915});
			var pnt = new esri.geometry.Point(Number(temp[1]),Number(temp[0]),new esri.SpatialReference({ wkid: 4326 }));
			gsvc.project([pnt], outSR, function(projectedPoints) {
				pt = projectedPoints[0];
				map.centerAndZoom(pt, Number(z));
			});
		} else {
			alert('Please enter valid coordinates!');
		}
	}
}

//these kill old onclick listeners and creates new ones for the selected tool:
function id_toolSelect(toolmode, tool){
	//disconnect possible listeners:
	dojo.disconnect(onClickListener);
	dojo.disconnect(clearInfoListener);
	//clearMap();
	var loading = dojo.byId("status");
	loading.innerHTML = "Click map to query";
	switch(toolmode){
		case "query":
			//create listener for id_coords:
			esri.show(loading);
			destroyIdent();
			onClickListener = dojo.connect(map, "onClick", executeQueryTask);
			clearInfoListener = dojo.connect(map.infoWindow, "onHide", function() {map.graphics.clear();});
			break;
		case "ident":
			onClickListener = dojo.connect(map, "onClick", initIdent());
			break;
		case "latlong":
			esri.show(loading);
			destroyIdent();
			onClickListener = dojo.connect(map, "onClick", getLatLong);
			clearInfoListener = dojo.connect(map.infoWindow, "onHide", function() {map.graphics.clear();});
			break;
		case "draw":
			if (identifyDijit) { destroyIdent(); }
			map.hideZoomSlider();
			map.setCursor('crosshair');
			dojo.byId("activeDrawTool").innerHTML = tool + ' tool active';
			break;
		default:
		   //This does the query infowindow:
		   id_connect = dojo.connect(map,"onClick",executeQueryTask);
		   break;
	}
}

function clearMap() {
	map.graphics.clear();
	map.infoWindow.hide();
}

function getUrlParam(name, url) { // optionally pass an URL to parse
	if (!url) url = window.location.href;								// if no parameter url is given, use the page URL
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");			// instruction needed if we want to extract an array
	var results = new RegExp("[\\?&]"+name+"=([^&#]*)").exec(url);
	if( results == null ) 
		return null;									// if the name is not found, return null
	else // decodeURIComponent doesn't recognize '+' as encoding for space
		return decodeURIComponent(results[1].replace(/\+/g," ")); 
}

function initIdent() {
	// wait for the layers to be loaded and accessible
	var mapURL = "";
	var mapName = "";
	if (getUrlParam("app")) {
		//ex, layer = APP_SALINE
		var app = getUrlParam("app");
		if (app.indexOf("(") > 0) {
			app = app.substring(0,app.indexOf("("));
		}
		mapURL = "http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/APPS/" + app	 + "/MapServer";
		mapName = app;
	} else {
		mapURL = "http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/BASEMAP_DYNAMIC/MapServer";
		mapName = "Basemap Dynamic";
	}
	if (map.layerIds.length > 2) {
		identifyDijit = new dijits.identify.Identify({ 
	   map: map,
	   defaultTolerance: 2,
	   mapServices: [
		 {
		   url: mapURL,
		   name: mapName,
			layerOption: esri.tasks.IdentifyParameters.LAYER_OPTION_VISIBLE
		 }
	   ]
	 });
		identifyDijit.startup();
	}
}

function destroyIdent() {
	if (getUrlParam("app") && !(title == "rep") ) {
		identifyDijit.destroy();
		identifyDijit = false;
	}
}
//generate context menu for map
function genMenu(bc, target) {
	//right click context menu for map
			pMenu = new dijit.Menu({
				style: "display:none;",
				targetNodeIds: [target]
			});
			pMenu.addChild(new dijit.MenuItem({
				label: "Get Location",
				onClick: function() {
					id_toolSelect('latlong');
				}
			}));
			pMenu.addChild(new dijit.MenuItem({
				label: "Get Districts",
				onClick: function() {
					id_toolSelect('query');
				}
			}));
			if (!(getUrlParam("embed"))) {
				pMenu.addChild(new dijit.MenuItem({
					label: "Permalink",
					onClick: function() {
						genPermalink();
					}
				}));
			}
			bc.addChild(pMenu);
}

function getLatLong(evt) {
	reverseGeocode(evt);
	var loading = dojo.byId("status");
	var outSR = new esri.SpatialReference({ wkid: 4326});
	var utm = "<br>UTM X : " + evt.mapPoint.x.toFixed(5) + "<br>UTM Y : " + evt.mapPoint.y.toFixed(5);
	map.infoWindow.setTitle("My Location");
	map.infoWindow.resize(250,155);
	gsvc.project([evt.mapPoint], outSR, function(projectedPoints) {
	  pt = projectedPoints[0];
		var x = pt.x.toFixed(5);
		var y = pt.y.toFixed(5);
		var links = '<br>Links: <a href="http://maps.google.com/maps?q=' + y + ',' + x + '" target="blank_" >Google</a>' +
			' | <a href="http://www.bing.com/maps/?q=' + y + ',' + x + '" target="blank_">Bing</a>' +
			' | <a href="http://maps.yahoo.com/#mvt=m&lat=' + y + '&lon=' + x + '&q1=' + y + ',' + x + '" target="blank_">Yahoo</a>' +
			' | <a href="http://www.openstreetmap.org/?mlat=' + y + '&mlon=' + x + '&zoom=15" target="blank_">OSM</a>';
		//outAddr = '<br />Approximate Address:<br />' + outAddr;
		map.infoWindow.setContent("Latitude : " + y + "<br>Longitude : " + x + utm + outAddr + links);
		(evt) ? map.infoWindow.show(evt.screenPoint,map.getInfoWindowAnchor(evt.screenPoint)) : null;
	});
	esri.hide(loading);
	if (getUrlParam("app") && !(getUrlParam("title") == "rep") ) {
		id_toolSelect("ident");
	}
	dojo.disconnect(onClickListener);
}

function genPermalink() {
	var pt = map.extent.getCenter();
	var base = "";
	var parameters;
	//get location
	//check for addr first
	if (getUrlParam("addr")) {
		var addr = getUrlParam("addr");
		parameters = 'addr=' + addr;
	} else {
		var z = 'z=' + map.getLevel();
		var ll = 'll=' + pt.x + ',' + pt.y;
		parameters = ll + '&' + z;
	}
	//get visible services and each of their visible layers
	for(var j = 0; j < map.layerIds.length; j++) {
		var layer = map.getLayer(map.layerIds[j]);
		if (layer.visible) {
			var service = map.getLayer(layer.id);
			var visbleLayers = service.visibleLayers;
			var layers = service.layerInfos;
			//we only want the basemap dynamic layer and/or the app layers
			if (layer.id == "dyn_svc" || layer.id == "user_svc") {
				var visibleLayerNames = dojo.map(visbleLayers, function(layer) {
				    return layers[layer].id;
				}, this);
				if (layer.id == "dyn_svc") { parameters += '&dyn=' + visibleLayerNames.join() }
				if (layer.id == "user_svc") { 
					var mtitle;
					if (getUrlParam("title")) {
						mtitle = getUrlParam("title");
					} else {
						mtitle = "GeoStor Viewer";
					}
					var app = getUrlParam("app");
					if (app.indexOf("(") > 0) {
						app = app.substring(0,app.indexOf("("));	
					}
					parameters += '&app=' + app + '(' + visibleLayerNames.join() + ')';
					parameters += '&title=' + mtitle;
				}
			} else {
				//this should be the visible basemap layer since only one is visible at a time
				if (base.length == 0) {
					base += layer.id;
				} else {
					base += ',' + layer.id;
				}
			}
		}
	}
	parameters += '&base='+ base;
	var url = "http://" + mapserv + ".geostor.arkansas.gov/G6/Viewer.html?" + parameters
	//var url = "http://agio-c5mw3k1.hds.arkgov.net/g6/Viewer.html?" + parameters
	url = encodeURI(url);
	map.infoWindow.setTitle("Permalink");
	map.infoWindow.resize(200,110);
	var content = 'Copy/Paste the link below:<br><input type="input" id="plink" value=' + url + ' /><br><input type="button" value="Shorten Link" onClick="shortenURL(\'' + url + '\');" />';
	map.infoWindow.setContent(content);
	sp = map.toScreen(pt);
	map.infoWindow.show(sp,map.getInfoWindowAnchor(sp));
	dojo.byId('plink').focus()
}

function shortenURL(lurl){
	lurl = encodeURIComponent(lurl);
	//The parameters to pass to xhrGet, the url, how to handle it, and the callbacks.
	var jsonpArgs = {
		url:"http://api.bit.ly/v3/shorten?longUrl=" + lurl + "&login=geostor&apiKey=R_552be35b56490390ad02148b7be9da8d",
		callbackParamName: "callback",
		load: function(data) {
			dojo.byId('plink').value = data["data"].url;
			dojo.byId('plink').focus()
		},
		error: function(error) {
			alert("An unexpected error occurred: " + error);
		}
	};
	dojo.io.script.get(jsonpArgs);
}

function fullExtent() {
	//keep full extent the same for apps with custom zoom settings
	if (getUrlParam("ll") && getUrlParam("app")) {
		xyZoom();
	} else {
		navToolbar.zoomToFullExtent()
	}
}

//drawing functions
function addToMap(geometry) {
	globalGeometry = geometry;
	map.showZoomSlider();
	var areasAndLengthParams = new esri.tasks.AreasAndLengthsParameters();
	var lengthParams = new esri.tasks.LengthsParameters();
   switch (geometry.type) {
	  case "point":
		var symbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, 10, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color("black"), 1), new dojo.Color(drawColor));
		break;
	  case "polyline":
		var symbol = new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color(drawColor), 3);
		  lengthParams.polylines = [geometry];
          lengthParams.lengthUnit = esri.tasks.GeometryService.UNIT_METER;
          gsvc.lengths(lengthParams);
		break;
	  case "polygon":
		var symbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_SOLID, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_DASHDOT, new dojo.Color("black"), 1), new dojo.Color(drawColor));
		areasAndLengthParams.lengthUnit = esri.tasks.GeometryService.UNIT_METER;
		areasAndLengthParams.areaUnit = esri.tasks.GeometryService.UNIT_SQUARE_METERS;
		areasAndLengthParams.polygons =  [geometry];
		gsvc.areasAndLengths(areasAndLengthParams);
		break;
	  case "extent":
		var symbol = new esri.symbol.SimpleFillSymbol(esri.symbol.SimpleFillSymbol.STYLE_SOLID, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_DASHDOT, new dojo.Color("black"), 1), new dojo.Color(drawColor));
		break;
	  case "multipoint":
		var symbol = new esri.symbol.SimpleMarkerSymbol(esri.symbol.SimpleMarkerSymbol.STYLE_DIAMOND, 20, new esri.symbol.SimpleLineSymbol(esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color("black"), 1), new dojo.Color(drawColor));
		break;
	}
	var graphic = new esri.Graphic(geometry, symbol);
	map.graphics.add(graphic);
	map.setCursor('default');
	dojo.byId("activeDrawTool").innerHTML = '';
	if (getUrlParam("app")) { id_toolSelect('ident');}
	drawToolbar.deactivate();
}

function outputDistance(result) {
	var distance = (result.lengths[0] * 3.2808399).toFixed(4);
	console.log(globalGeometry);
	var textSymbol =  new esri.symbol.TextSymbol('testing').setColor(new dojo.Color([128,0,0])).setAlign(esri.symbol.Font.ALIGN_START).setAngle(45).setFont(new esri.symbol.Font("12pt").setWeight(esri.symbol.Font.WEIGHT_BOLD));
	var txtgraphic = new esri.Graphic(globalGeometry, textSymbol);
	//map.graphics.add(txtgraphic);
	if (distance > 5280) {
		dojo.byId("distDisplay").innerHTML = "Distance = " + (distance * 0.000189393939).toFixed(4) + " miles";
	} else {
		dojo.byId("distDisplay").innerHTML = "Distance = " + distance + " feet";
	}
}

function outputAreaAndLength(result) {
	dojo.byId("areaDisplay").innerHTML = 'Area = ' + (result.areas[0] * 0.000247105381).toFixed(4) + " acres<br />" + 
		'Perimeter = ' + (result.lengths[0] * 3.2808399).toFixed(4) + " feet";
}

//print function
function printMap(text) {
	//params: filename with timestamp, title, extent
	alert('Coming Soon!\n Title = ' + dojo.byId('printTitle').value);
	//tc.selectChild(LegendCP);
	//text = document
	//print(text)
	//gp = new esri.tasks.Geoprocessor("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/Toolbox/GPServer/Print");
	//Extent ({XMin}, {YMin}, {XMax}, {YMax})
	params = {};
	//gp.submitJob(params,alert('Job complete!'));
	//tc.selectChild(LegendCP);
}

function exportImage(layer)
      {
      console.log("exportImage");
      	    var imageParams = new esri.layers.ImageParameters();

    imageParams.extent = map.extent;

    imageParams.transparent = true;

		layer.exportMapImage(imageParams, completeExportMapImage);
      }
      
      function completeExportMapImage(mapImage)
      {
      	//console.log(mapImage.href);
      	//window.location = mapImage.href;
		  //console.log(map.extent);
		  var basemapExportURL = 'http://www.geostor.arkansas.gov/ArcGIS/rest/services/BASEMAP/MapServer/export?bbox=' + 
					map.extent.xmax + ',' + map.extent.ymax + ',' + map.extent.xmin + ',' + map.extent.ymin + '&f=image&format=jpg&size=700,700'
		  console.log(basemapExportURL);
		  window.location = basemapExportURL;
		  //window.open(basemapExportURL,'Print','width=800,height=800');
      }

//show map on load 
dojo.addOnLoad(init);