dojo.provide("dijits.identify.InfoWindowConnector");

dojo.require("dojo.dnd.Mover");
dojo.require("dojo.dnd.Moveable");
dojo.require("dojo.dnd.move");

dojo.declare("dijits.identify.InfoWindowConnector", null, {
  constructor: function(/*DOMNode | String*/ domNode) {
    this._node = dojo.byId(domNode);
    this._map = null;
    this._mapPoint = null;
    this._attached = false;
    this._visible = true;
    this._moveableHandle = null;

    var box = dojo.contentBox(domNode);
    this._width = box.w;
    this._height = box.h;

    this._zoomStartHandler = dojo.hitch(this, this._zoomStartHandler);
    this._zoomEndHandler = dojo.hitch(this, this._zoomEndHandler);
    this._onPanHandler = dojo.hitch(this, this._onPanHandler);
    this._onPanEndHandler = dojo.hitch(this, this._onPanEndHandler);
  },
  
  /*****************
   * Public Methods
   *****************/
  
  attachToMap: function(/*esri.Map*/ map) {
    this._map = map;
    if (!this._attached) {
      this._connectHandlers();
      if (this._moveableHandle) {
        this._moveableHandle.destroy();
      }
      this._attached = true;
      this._mapBox = dojo.coords(dojo.byId(map.id), true);
      this.onAttach(map);
    }
  },
  
  detachFromMap: function(/*String?*/ handleId) {
    this._disconnectHandlers();
    handleId = handleId || this._node.id;
    this._moveableHandle = new dojo.dnd.Moveable(this._node, { handle: handleId });
    this._attached = false;
    this._mapBox = null;
    this.onDetach();
  },
  
  isAttached: function() {
    return this._attached;
  },
  
  show: function(/*esri.geometry.Point*/ mapPoint) {
    this._mapPoint = mapPoint;
    if (this._attached) {
      this._repositionWidget(this._getXY(mapPoint));
    }
    if (!this._visible) {
      esri.show(this._node);
      this._visible = true;
      if (this._attached) {
        this._connectHandlers();
      }
    }
  },
  
  hide: function() {
    esri.hide(this._node);
    this._disconnectHandlers();
    this._visible = false;
  },
  
  isVisible: function() {
    return this._visible;
  },
  
  destroy: function() {
    this._disconnectHandlers();
    this._attached = false;
    if (this._moveableHandle) {
      this._moveableHandle.destroy();
    }
    this._node = this._map = this._mapPoint = this._mapBox = null; 
  },
  
  onAttach: function(map) {},
  onDetach: function(map) {},
  
  /*******************
   * Internal Methods
   *******************/
  
  _connectHandlers: function() {
    this._zsConnect = dojo.connect(this._map, "onZoomStart", this._zoomStartHandler);
    this._zeConnect = dojo.connect(this._map, "onZoomEnd", this._zoomEndHandler);
    this._opConnect = dojo.connect(this._map, "onPan", this._onPanHandler);
    this._opeConnect = dojo.connect(this._map, "onPanEnd", this._onPanEndHandler);
  },
  
  _disconnectHandlers: function() {
    dojo.disconnect(this._zsConnect);
    dojo.disconnect(this._zeConnect);
    dojo.disconnect(this._opConnect);
    dojo.disconnect(this._opeConnect);
  },
  
  _showIf: function() {
    if (this._map.extent.contains(this._mapPoint)) {
      esri.show(this._node);
    }
    else {
      esri.hide(this._node);
    }
  },
  
  _zoomStartHandler: function() {
    esri.hide(this._node);
  },
  
  _zoomEndHandler: function() {
    this._repositionWidget(this._getXY(this._mapPoint));
    this._showIf();
  },
  
  _onPanHandler: function(/*esri.geometry.Extent*/ extent, /*esri.geometry.Point (screen units)*/ delta) {
    var pos = this._getXY(this._mapPoint);
    pos.x += delta.x;
    pos.y += delta.y;
    this._repositionWidget(pos);
  },
  
  _onPanEndHandler: function(/*esri.geometry.Extent*/ extent, /*esri.geometry.Point (screen units)*/ endPoint) {
    this._repositionWidget(this._getXY(this._mapPoint));
    this._showIf();
  },
  
  _getXY: function(/*esri.geometry.Point*/ mapPoint) {
    var screenPoint = this._map.toScreen(mapPoint), divBox = this._mapBox;
    return { x: screenPoint.x + divBox.x, y: screenPoint.y + divBox.y, anchor: this._map.getInfoWindowAnchor(screenPoint) };
  },
  
  _repositionWidget: function(/*{x: <Number>, y: <Number>, anchor: <String> }*/ pos) {
    var x = pos.x, y = pos.y, anchor = pos.anchor, style = this._node.style, wd = this._width, ht = this._height;
    if (anchor === esri.dijit.InfoWindow.ANCHOR_UPPERLEFT) {
      style.left = x - wd - 5 + "px";
      style.top = y - ht - 5 + "px";
    }
    else if (anchor === esri.dijit.InfoWindow.ANCHOR_UPPERRIGHT) {
      style.left = x + 5 + "px";
      style.top = y - ht - 5 + "px";
    }
    else if (anchor === esri.dijit.InfoWindow.ANCHOR_LOWERRIGHT) {
      style.left = x + 5 + "px";
      style.top = y + 5 + "px";
    }
    else if (anchor === esri.dijit.InfoWindow.ANCHOR_LOWERLEFT) {
      style.left = x - wd - 5 + "px";
      style.top = y + 5 + "px";
    }
  }
});