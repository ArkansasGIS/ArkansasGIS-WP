dojo.provide("dijits.identify.Identify");

dojo.require("esri.tasks.identify");
dojo.require("dojo.data.ItemFileWriteStore");
dojo.require("dojox.grid.DataGrid");
dojo.require("dijit.layout.ContentPane");
dojo.require("dijit.layout.BorderContainer");
dojo.require("dijit.layout.StackContainer");
dojo.require("dojo.fx");
dojo.require("dijit.form.NumberSpinner");
dojo.require("dijit.form.CheckBox");
dojo.require("dijits.identify.InfoWindowConnector");
dojo.require("dijit._Widget");
dojo.require("dijit._Templated");

/***************
 * CSS Includes
 ***************/
//anonymous function to load CSS files required for this module
(function() {
  var css = [
    dojo.moduleUrl("dojox", "grid/resources/Grid.css"),       // required css for grids
    dojo.moduleUrl("dojox", "grid/resources/tundraGrid.css"), // tundra theme for grids
    dojo.moduleUrl("dijits", "identify/css/identify.css")     // custom css used by this dijit: you can customize this file to change the look and feel of this dijit
  ];

  var head = document.getElementsByTagName("head").item(0), link;
  for (var i=0, il=css.length; i<il; i++) {
    link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = css[i];
    head.appendChild(link);
  }
})();

/**************************
 * Third-party JS Includes
 **************************/
// This script is required to apply rounded-corners to this dijit
// in Internet Explorer. More info here:
// http://dillerdesign.com/experiment/DD_roundies/ 
if (dojo.isIE) {
  (function() {
    var js = [
      dojo.moduleUrl("dijits", "identify/libraries/DD_roundies_0.0.2a-min.js")
    ];
  
    var head = document.getElementsByTagName("head").item(0), script;
    for (var i=0, il=js.length; i<il; i++) {
      script = document.createElement("script");
      script.type = "text/javascript";
      script.src = js[i];
      head.appendChild(script);
    }
  })();
}

/*****************
 * Identify Dijit
 *****************/
dojo.declare("dijits.identify.Identify", [dijit._Widget, dijit._Templated], {

  // Let the dijit framework know that the template for this dijit 
  // uses other dijits such as BorderContainer, StackContainer, Grid etc
  widgetsInTemplate: true,
  
  // Let the dijit framework know the location of the template file where
  // the UI for this dijit is defined 
  templatePath: dojo.moduleUrl("dijits", "identify/templates/identify.html"),
  
  // Path to the folder containing the resources used by this dijit.
  // This can be used to refer to images in the template or other
  // resources
  basePath: dojo.moduleUrl("dijits.identify"),
  
  /*************
   * Overrides
   *************/
  
  // This section provides implementation for some of the extension points
  // (methods) exposed by the dijit framework. See the following document
  // for more information about a dijit's life-cycle and when these methods
  // are called, see:
  // http://docs.dojocampus.org/dijit/_Widget#lifecycle
  
  constructor: function(params, srcNodeRef) {
    params = params || {};
    if (!params.map) {
      console.error("dijits.identify.Identify: please provide 'map' property in the constructor");
    }
    
    this._chainHandler = dojo.hitch(this, this._chainHandler);
    this._showOptionsPage = dojo.hitch(this, this._showOptionsPage);
    this._closeWidget = dojo.hitch(this, this._closeWidget);
    this._gridRowClickHandler = dojo.hitch(this, this._gridRowClickHandler);
    this._gridRowMouseOverHandler = dojo.hitch(this, this._gridRowMouseOverHandler);
    this._backButtonClickHandler = dojo.hitch(this, this._backButtonClickHandler);
    this._gridUpdated = dojo.hitch(this, this._gridUpdated);
    this._mapClickHandler = dojo.hitch(this, this._mapClickHandler);
    this._showFeatures = dojo.hitch(this, this._showFeatures);
    this._focusButtonClicked = dojo.hitch(this, this._focusButtonClicked);
    this._pageSelectHandler = dojo.hitch(this, this._pageSelectHandler);
    
    this._featureRowTemplate = "${value}<br/><span id='${spanId}' class='genid-grid-row'><a id='${linkId}' href='#' class='genid-focus-link'>Focus</a></span><span class='genid-layer-name'>${layerName}</span>";
    this._attributeRowTemplate = "${value}<br/><span class='genid-attr-name'>${attr}</span>";
    this._optionsTemplate = "<input id='${id}' type='checkbox' title='${name}' dojoType='dijit.form.CheckBox' checked='checked'/><label for='${id}'>${name}</label><br/><br/>";

    /**************************
     * Configurable Properties
     **************************/
    // This section contains properties that can be used to customize the behavior
    // of this dijit. You can assign values to these properties when creating 
    // the dijit. All properties except "map" are optional
    
    // this dijit needs a reference to the map object for various reasons
    this.map = params.map; // [REQUIRED]
    
    this.label = params.label || "Identify";
    
    this.mapServices = params.mapServices || [];
    
    // the default tolerance in pixels for identify operation
    this.defaultTolerance = params.defaultTolerance || 1;
  
    // the symbol used to display polygon features
    this.fillSymbol = params.fillSymbol || new esri.symbol.SimpleFillSymbol(
      esri.symbol.SimpleFillSymbol.STYLE_SOLID, 
      new esri.symbol.SimpleLineSymbol( esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([ 64, 64, 64, 1 ]), 2 ), 
      new dojo.Color([ 255, 0, 0, 0.5 ])
    );
    
    // the symbol used to display polyline features  
    this.lineSymbol = params.lineSymbol || new esri.symbol.SimpleLineSymbol( esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([ 0, 0, 255 ]), 2 );
    
    // the symbol used to display point/multipoint features
    this.markerSymbol = params.markerSymbol || new esri.symbol.SimpleMarkerSymbol(
      esri.symbol.SimpleMarkerSymbol.STYLE_CIRCLE, 
      13, 
      new esri.symbol.SimpleLineSymbol( esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([ 0, 128, 0 ]), 2 ), 
      new dojo.Color([ 0, 255, 0 ])
    );
    
    // the symbol used to represent the location where the user clicked on the map
    this.locationMarker = params.locationMarker || new esri.symbol.SimpleMarkerSymbol(
      esri.symbol.SimpleMarkerSymbol.STYLE_X, 
      12, 
      new esri.symbol.SimpleLineSymbol( esri.symbol.SimpleLineSymbol.STYLE_SOLID, new dojo.Color([ 0, 0, 255 ]), 3 )
    );
  },
  
  postMixInProperties: function() {
    // overriding methods typically call their implementation up the inheritance chain 
    this.inherited(arguments);
    
    // create a list of map services to be displayed in the options page
    var services = this.mapServices, i, len = services.length, html = "";
    if (len > 0) {
      for (i = 0; i < len; i++) {
        services[i] = this._getTaskInfo(services[i]);
        html += services[i]._optionsHtml;
      }
    }
    else { // get the map services from map
      var layerIds = this.map.layerIds, layer, info;
      len = layerIds.length;
      for (i = 0; i < len; i++) {
        layer = this.map.getLayer(layerIds[i]);
        if (layer.declaredClass === "esri.layers.ArcGISTiledMapServiceLayer" || layer.declaredClass === "esri.layers.ArcGISDynamicMapServiceLayer" ) {
          info = this._getTaskInfo({ url: layer.url, name: null, layerIds: null, layerOption: esri.tasks.IdentifyParameters.LAYER_OPTION_ALL, displayOptions: null, id: layer.id });
          services.push(info);
          html += info._optionsHtml;
        }
      }
    }
    
    // "_optionsHtml" property will be used by the dijit framework to render 
    // the options page of this dijit. The value of this property will be 
    // substituted in this dijit's template in places where the variable
    // ${_optionsHtml} is used
    this._optionsHtml = html;
  },
  
  startup: function() {
    // overriding methods typically call their implementation up the inheritance chain
    this.inherited(arguments);

    // apply rounded-corners to the dijit in IE
    if (dojo.isIE) {
      DD_roundies.addRule('.genid-rounded-corner', '10px');
    }
    
    // [GOTCHA] add the domNode of this dijit to the page if it is not added already
    if (dojo.isIE) {
      if (!this.domNode.parentElement) {
        document.body.appendChild(this.domNode);
      }
    }
    else {
      if (!this.domNode.parentNode) {
        document.body.appendChild(this.domNode);
      }
    }
    
    // [GOTCHA] without this little piece of code, if the user did not provide a div 
    // while creating this dijit, we won't see it
    this._borderContainer.resize();
    
    this._uid1 = 0;
    this._featureGrid.setStore(new dojo.data.ItemFileWriteStore({
        data: { identifier: "UNIQ_ID", label: "UNIQ_ID", items: [] }
      })
    );
    this._fguConnect = dojo.connect(this._featureGrid, "update", this._gridUpdated);
    this._optTolerance.attr("value", this.defaultTolerance);
    this._selectedFeature = null;
    this._locationGraphic = null;
    
    for (var i = 0; i < this.mapServices.length; i++) {
      var service = this.mapServices[i], node = dijit.byId(service.chkId);
      node.associatedWidget = this;
      service._optConnect = dojo.connect(node, "onChange", this._layerSelectionChanged);
    }
    // number of map services currently enabled by the end-user for identify operation
    // This cannot go below '0'
    this._enabledCount = this.mapServices.length;
    this._pgsConnect = dojo.subscribe(this.id + "-stack-selectChild", this._pageSelectHandler);
    this._mclConnect = dojo.connect(this.map, "onClick", this._mapClickHandler);

    this._backButtonRegion.domNode.style.lineHeight = dojo.coords(this._backButtonRegion.domNode, true).h + "px";
    this._iwconnector = new dijits.identify.InfoWindowConnector(this.id);
    this._iwconnector.attachToMap(this.map);
    this._iwconnector.hide();
  },
  
  destroy: function() {
    this._disconnectHandlers();
    this._clearGraphics();
    this._iwconnector.destroy();
    this._locationGraphic = this._selectedFeature = this._stackContainer = this._backButtonRegion = this._featureGrid = this._infoGrid = this._iwconnector = this._optTolerance = this._focusSpan = null;
    this.inherited(arguments);
  },
  
  /*****************
   * Public Methods
   *****************/
  
  getInfoWindowConnector: function() {
    return this._iwconnector;
  },
  
  /*******************
   * Internal Methods
   *******************/
  
  _disconnectHandlers: function() {
    dojo.disconnect(this._fguConnect);
    dojo.unsubscribe(this._pgsConnect);
    dojo.disconnect(this._mclConnect);
    dojo.disconnect(this._focusAConnect);
    var i, service;
    for (i = 0; i < this.mapServices.length; i++) {
      service = this.mapServices[i];
      dijit.byId(service.chkId).associatedWidget = null;
      dojo.disconnect(service._optConnect);
      dojo.disconnect(service._taskConnect);
    }
  },
  
  _clearGraphics: function() {
    if (this._locationGraphic) {
      this.map.graphics.remove(this._locationGraphic);
    }
    if (this._selectedFeature) {
      this.map.graphics.remove(this._selectedFeature);
    }
  },
  
  _mapClickHandler: function(evt) {
    // cancel all previous identify requests
    var i, service, services = this.mapServices, mapPoint = evt.mapPoint, map = this.map;
    for (i = 0; i < services.length; i++) {
      dojo.disconnect(services[i]._taskConnect);
    }
    map.infoWindow.hide();

    // remove previously selected location and feature
    if (this._iwconnector.isAttached()) {
      this._iwconnector.hide();
    }
    if (this._locationGraphic) {
      map.graphics.remove(this._locationGraphic);
    }
    if (this._selectedFeature) {
      map.graphics.remove(this._selectedFeature);
    }
    
    // mark the current location
    this._locationGraphic = new esri.Graphic(mapPoint, this.locationMarker);
    map.graphics.add(this._locationGraphic);
    
    // clear all items in the featureGrid
    var item, grid = this._featureGrid, store = grid.store;
    while(item = grid.getItem(0)) {
      store.deleteItem(item);
    }
    dojo.byId(this.id + "-count").innerHTML = "(0)";
  
    // execute identify task
    var params, tol = this._optTolerance.attr("value") || this.defaultTolerance;
    for (i = 0; i < services.length; i++) {
      service = services[i];
      if (!service.selected)
        continue;
      
      params = new esri.tasks.IdentifyParameters();
      params.geometry = mapPoint;
      params.tolerance = tol;
      params.layerIds = service.layerIds;
      params.layerOption = service.layerOption;
      params.mapExtent = map.extent;
      params.returnGeometry = true;
      params.spatialReference = map.spatialReference;
      
      service._taskConnect = dojo.connect(service.task, "onComplete", dojo.hitch(this,
        function() {
          var options = service.displayOptions;
          return function(response) {
            this._showFeatures(response, mapPoint, options);
          } 
        }()
      ));
  
      service.task.execute(params);
    }
  },
  
  _showFeatures: function(idResults, mapPoint, options) {
    // add the list of identified features to the featureGrid
    var i, feature, idResult, store = this._featureGrid.store, uid, lid, fname, lalias;
    for (i = 0; i < idResults.length; i++) {
      idResult = idResults[i];
      feature = idResult.feature;
      uid = ++this._uid1;
      lid = idResult.layerId;
      feature._displayAttributes = options[lid] ? options[lid].attributes : null;
      feature._fieldAliases = (options[lid] && options[lid].fieldAliases) || {};
      fname = options[lid] ? options[lid].displayFieldName : "";
      lalias = options[lid] ? options[lid].layerAlias : "";
      store.newItem({ 
        UNIQ_ID: uid, 
        feature_name: dojo.string.substitute(this._featureRowTemplate, {
          value: feature.attributes[fname || idResult.displayFieldName] || "", 
          layerName: lalias || idResult.layerName, 
          spanId: this.id + "-fspan-" + uid, 
          linkId: this.id + "-flink-" + uid
        }),
        ref: feature 
      });
    
      // set the symbol for the feature
      switch (feature.geometry.type) {
        case "point":
          feature.setSymbol(this.markerSymbol);
          break;
        case "multipoint":
          feature.setSymbol(this.markerSymbol);
          break;
        case "polyline":
          feature.setSymbol(this.lineSymbol);
          break;
        case "polygon":
          feature.setSymbol(this.fillSymbol);
          break;
      }
    }
    
    // show the widget at the clicked location
    dojo.byId(this.id + "-count").innerHTML = "(" + this._featureGrid.rowCount + ")";
    this._iwconnector.show(mapPoint);
    this._stackContainer.selectChild(this._stackContainer.getChildren()[0]);
    this._borderContainer.layout();
  },
  
  _gridRowClickHandler: function(evt) {
    var graphic = evt.grid.getItem(evt.rowIndex).ref[0], attr = graphic.attributes, dispAttr = graphic._displayAttributes, aliases = graphic._fieldAliases;
    
    // add the field values to the infoGrid 
    var items = [], field, i = 0, len;
    if (dispAttr) {
      len = dispAttr.length;
      for (i = 0; i < len; i++) {
        field = dispAttr[i];
        items.push({
          UNIQ_ID: i, 
          field_value: dojo.string.substitute(this._attributeRowTemplate, { attr: aliases[field] || field, value: attr[field] })
        });
      }
    }
    else {
      for (field in attr) {
        items.push({
          UNIQ_ID: i++, 
          field_value: dojo.string.substitute(this._attributeRowTemplate, { attr: aliases[field] || field, value: attr[field] })
        });
      }
    }
    this._infoGrid.setStore(new dojo.data.ItemFileWriteStore(
      { data: { identifier: "UNIQ_ID", label: "UNIQ_ID", items: items } }
    ));
    
    // show the page containing the infoGrid
    this._stackContainer.selectChild(this._stackContainer.getChildren()[1]);
  },
  
  _gridRowMouseOverHandler: function(evt) {
    var item = evt.grid.getItem(evt.rowIndex), graphic = item.ref[0], uid = item.UNIQ_ID[0];
    
    // show the focus button
    this._focusSpan && esri.hide(this._focusSpan);
    esri.show(this._focusSpan = dojo.byId(this.id + "-fspan-" + uid));
    
    dojo.disconnect(this._focusAConnect);
    this._focusAConnect = dojo.connect(dojo.byId(this.id + "-flink-" + uid), "onclick", dojo.hitch(this, function(evt2) {
      this._focusButtonClicked(evt2, graphic.geometry);
    }));
  
    // remove previously selected feature
    if (this._selectedFeature) {
      this.map.graphics.remove(this._selectedFeature);
    }
  
    // add the selected feature to the map
    this.map.graphics.add(graphic);
    this._selectedFeature = graphic;
  },
  
  _gridUpdated: function() {
    // [GOTCHA] For some reason, whenever the list of features is displayed, 
    // dojo re-renders the Grid headers. So, here we need to set the feature 
    // count again.
    dojo.byId(this.id + "-count").innerHTML = "(" + this._featureGrid.rowCount + ")";
  },
  
  _pageSelectHandler: function(selectedPage) {
   if (selectedPage === this._stackContainer.getChildren()[0]) {
    this._backButtonRegion.attr("content", "");
   }
   else {
    this._backButtonRegion.attr("content", "&laquo;");
   }
  },
  
  _backButtonClickHandler: function() {
    this._stackContainer.selectChild(this._stackContainer.getChildren()[0]);
  },
  
  _showOptionsPage: function() {
    this._stackContainer.selectChild(this._stackContainer.getChildren()[2]);
  },

  _closeWidget: function() {
    this._iwconnector.hide();
    this._clearGraphics();
    //this.destroy();
  },
  
  _chainHandler: function() {
    var img = this._chainIcon;
    if (this._iwconnector.isAttached()) { // detach the dijit from map
      img.src = this._unchainImg || (this._unchainImg = dojo.moduleUrl("dijits", "identify/images/detached.png"));
      img.title = "click to attach to the map";
      this._iwconnector.detachFromMap(this.id + "-titlebar");
    }
    else { // attach dijit to the map
      img.src = this._chainImg || (this._chainImg = dojo.moduleUrl("dijits", "identify/images/attached.png"));
      img.title = "click to detach from the map";
      this._iwconnector.attachToMap(this.map);
    }
  },
  
  _layerSelectionChanged: function(checked) {
    var i, checkBox = this, self = checkBox.associatedWidget, chkId = checkBox.id, service;
    for (i = 0; i < self.mapServices.length; i++) {
      service = self.mapServices[i];
      if (service.chkId === chkId) {
        service.selected = checked;
        if (checked) {
          self._enabledCount++;
        }
        else {
          self._enabledCount--;
        }
        break;
      }
    }
    if (!self._enabledCount) {
      checkBox.setChecked(true);
    }
  },
  
  _getTaskInfo: function(service) {
    var url = service.url, name = service.name,
        segs = url.replace(/[\/]+$/, "").split("/"), name = name || segs[segs.length - 2].replace(/_/g, " "), 
        chkId = this.id + "-opt-layers-" + (service.id || ++this._uid1);
    return {
      task: new esri.tasks.IdentifyTask(url),
      chkId: chkId,
      selected: true,
      layerIds: service.layerIds,
      layerOption: service.layerOption || esri.tasks.IdentifyParameters.LAYER_OPTION_TOP,
      displayOptions: service.displayOptions || {},
      _optionsHtml: dojo.string.substitute(this._optionsTemplate, { id: chkId, name: name })
    };
  },
  
  _focusButtonClicked: function(evt, geometry) {
    if (geometry.type === "point") {
      this.map.centerAt(geometry);
    }
    else {
      this.map.setExtent(geometry.getExtent());
    }
    dojo.stopEvent(evt);
  }
});

dojo.mixin(dijits.identify.Identify, {
  // At Dojo 1.3.1, DataGrid escapes any HTML data.
  // Overrride that behavior by adding a formatter function 
  // for the grid cells and convert all &lt; back to <
  // See also: templates/identify.html where this formatter is bound to the grid column.
  // http://docs.dojocampus.org/dojox/grid#important-information-about-formatting-and-security
  _cellFormatter: function(value) {
    return value.replace(/&lt;/g, "<");
  }
});

