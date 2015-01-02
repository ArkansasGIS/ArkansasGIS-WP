//redirect all the users with the wrong URL
var site = window.location.hostname;
if (site == 'beta.geostor.arkansas.gov' || site == 'apps.geostor.arkansas.gov' ) {
	alert("The address you are using no longer works, please update your bookmarks. Click OK to proceed to the proper site.");
	window.location = "http://www.geostor.arkansas.gov/G6/Home.html";
}
if (site == 'maps.arkansas.gov') {
	window.location = "http://www.geostor.arkansas.gov/G6/Viewer.html";
}
//avoid Flash of Unstyled Content (FOUC)
document.write('<style type="text/css">body{display:none}</style>');
//set this to true to disable the download link
var disabledl=new Boolean(false);	
$(function(){
	$('body').css('display','block');
    //generic stuff
    //sitewide vars
    var $warn = $("#warn");
    var $info = $("#info");
    //change this to add info label to whole site
    //change to true to show message, false to not show it
    var showmaininfo=new Boolean(false);
    if (showmaininfo == true) {
        var maininfomsg = 'GeoStor downloads will be offline this weekend due to a software upgrade.';
        var $maininfo = $("#maininfo");
        $("#maininfodata").html(maininfomsg);
        $maininfo.show("slow");
    }
	//load blog feeds
	$.jGFeed('http://geostor.blogspot.com/feeds/posts/default',
		function(feeds){
			var output = '<strong>Latest GeoStor Blog Posts</strong><br /><ul>';
		  // Check for errors
		  if(!feeds){
			// there was an error
			output += 'There was an error retrieving the blog posts!';  
		  }
		  
		  // do whatever you want with feeds here
		  for(var i=0; i<feeds.entries.length; i++){
			var entry = feeds.entries[i];
			output += '<li><a href="' + entry.link + '" target="_blank">' + entry.title + '</a></li>';
		  }
		  output += '</ul>';
		  $("#blogposts").html(output);
		}, 4);
		
	$("#twitter").getTwitter({
		userName: "geostor",
		numTweets: 2,
		loaderText: "Loading tweets...",
		slideIn: false,
		slideDuration: 750,
		showHeading: false,
		headingText: "Latest GeoStor Tweets",
		showProfileLink: false,
		showTimestamp: true
	});
				
	//does pagination need to be enabled?
	var pagination=new Boolean(false);
	
	//set the page title and gets any GET variables e.g. home.html?testing=blah&goober=yep
    var loc = location.href;
    if (!(loc.indexOf("?") > 0)) {
        var pagetitle = "GeoStor 6.0 | " + location.href.substring(location.href.lastIndexOf('/')+1).replace(/.html/,"").replace(/%20/," ");
    } else {
       var urlHalves = String(document.location).split('?');
        //ugly - but works...
		var pagetitle = "GeoStor 6.0 | " + urlHalves[0].substring(urlHalves[0].lastIndexOf('/')+1).replace(/.html/,"").replace(/%20/," ");
    }
    document.title = pagetitle;
    
    //set default growl close div
    $.jGrowl.defaults.closerTemplate = '<div>[ close all ]</div>';
	
    //only create vars if they exist on the current page
    //home page and advanced search
    if (pagetitle.toLowerCase() == "geostor 6.0 | home" || pagetitle.toLowerCase() == "geostor 6.0 | advanced search") {
        //create vars for elements here
        //divs
        var $results = $('#results');
        var $metadata = $('#metadata');
        var $dl_error = $("#dl_error");
        var $searchhelp = $("#searchhelp");
        //navigation
        var $dldialog = $("#dldialog");
        var $tabs = $('#tabs');
        var $mddialog = $('#mddialog');
        //buttons
        var $btnSearch = $("#btnSearch");
        //input
        var $txtSearch = $("#txtSearch");
        var $txtEmail = $("#txtEmail");
        var $county = $("#county");
        var $city = $("#city");
        var $water = $("#water");
        var $townrange = $("#townrange");
        var $quad = $("#quad");
        var $ddlFormat = $("#ddlFormat");
		var $ddlCoorSys = $("#ddlCoorSys");
		var $savprefs = $("#savprefs");
        //output
        var $hidTablespace = $("#hidTablespace");
        var $dltype = $("#dltype");
        
        //check for url parameter search
        if ($(document).getUrlParam("q")) {
            if (!($(document).getUrlParam("q")[0] == "")) {
                searchData($(document).getUrlParam("q")[0],"","");
            }
        }
		if ($(document).getUrlParam("id")) {
            if (!($(document).getUrlParam("id")[0] == "")) {
                searchData("","",$(document).getUrlParam("id")[0]);
            }
        }
        
        //give main text focus
        focus_highlight($txtSearch);
                
        //search help        
		if (pagetitle.toLowerCase() == "geostor 6.0 | home") {
			var searchhelptext = 'You can search several ways:<ul><li>Keywords - fire, marine, ortho</li>' +
				'<li>Literal search - "county ortho"</li><li>Data types - ftp, offsite, wms</li>' +
				'<li>You can also combine the types - "county ortho" ftp</li>' +
				'<li>SEARCH FOR "HELP" TO GET HELP!</li></ul>';
				//<li>Address - 123 Main St, Little Rock</li>
		} else {
			var searchhelptext = 'You can search several ways:<ul><li>Select a Category, Publisher, and/or Type/Format</li>' +
				'<li>Select a date range</li><li>Keywords - fire, marine, ortho</li><li>Hint: You can select one of the drop downs and click search to get all of that type</li></ul>';
		}
		//showWarning("Just search for what you need. Yes, it is that simple!");
		$searchhelp.click(function() {
            showInfo(searchhelptext);
        });
		
        // Tabs
        $tabs.tabs();
        
        // Dialog			
        $dldialog.dialog({
            autoOpen: false,
            width: 500,
            modal: true,
            title: "XML Name",
            resizable: false,
            draggable: false,
            buttons: {
                "Request Data": function() { 
                    if (validate_dl()) {
                        //submit_dl
                        showInfo('Your download has been submitted, you will be notified upon its completion.');
                        var selected = $tabs.tabs('option', 'selected');
                        var WhereClause = "";
                        var Clipper = "";
                        var Clippee = $hidTablespace.attr("value");
                        var dltype = $dltype.attr("value");
                        switch (selected) {
                            case 0:
                                WhereClause = "where COUNTY_NAM = |" + $('#county option:selected').text() + "|";
                                Clipper = "ADMIN.COUNTIES_AHTD";
                                break;
                            case 1:
                                WhereClause = "where CITY_NAME = |" + $('#city option:selected').text() + "|";
                                Clipper = "ADMIN.CITY_LIMITS_AHTD";
                                break;
                            case 2:
                                WhereClause = "where HUC_8 = |" + $('#water').val() + "|";
                                Clipper = "WATER.BASINS_HUC8_NRCS";
                                break;
                            case 3:
                                WhereClause = "where CITY_NAME = |Bryant|";
                                //statewide or extent
                                Clipper = "EXTENTS";
                                //check for clip by BB from advanced search
                                if (!($("#tab3header").html() == "Extent")) {
                                    //set hidden val to statewide BB, put in html to save JS code?
                                    $("#hidExtent").val("344224.592,3638997.708,818491.166,4053732.918");
                                }
                                break;
                            case 4:
                                if (!($('#quad option:selected').text() == "-- Select Item --")) {
                                    Clipper = "ADMIN.QUADRANGLES_CAST";
                                    WhereClause = "where QUAD_NAME = |" + $('#quad option:selected').text() + "|";
                                }
                                if (!($('#townrange option:selected').text() == "-- Select Item --")) {
                                    Clipper = "CADAS.PLSS_TOWNSHIPS_USGS_1990";
                                    WhereClause = "where ATTRIBUTE = |" + $('#townrange option:selected').text() + "|";
                                }
                                break;
                            default:
                                if ($("#county").val() == null) {
                                    //getSelect("county");
                                }
                                break;
                        }
                        gen_postvars(site,WhereClause,$('#ddlCoorSys option:selected').val(),$('#ddlFormat option:selected').val(),$txtEmail.val(),Clippee,Clipper,dltype);
                        $tabs.tabs('select',0);
                        $dl_error.hide("slow");
						if ($('input[name=saveprefchk]').is(':checked')) {
							if (dltype == "Vector") {
								saveprefs($txtEmail.val() ,$('#ddlFormat option:selected').prevAll().size(),'X',$('#ddlCoorSys option:selected').prevAll().size());
							} else if (dltype == "Raster") {
								saveprefs($txtEmail.val(),'X',$('#ddlFormat option:selected').prevAll().size(),$('#ddlCoorSys option:selected').prevAll().size());
							}
						}
						$ddlFormat.removeOption(/./);
                        $(this).dialog("close");
                    }
                }, 
                "Close": function() {
                    $dl_error.hide("slow");
                    $tabs.tabs('select',0);
                    $ddlFormat.removeOption(/./);
                    $(this).dialog("close");
                } 
            }
        });
        $mddialog.dialog({
            autoOpen: false,
            modal: true,
            width: 810,
            height: 810,
            title: "Metadata",
            resizable: false,
            draggable: false,
            buttons: {
                "Close": function() {
                    $(this).dialog("close");
                }
            }
        });
        
        $btnSearch.click(function() {
            submitSearch();
        });
        //if the user hits the enter key, submit the search
        $txtSearch.keyup(function(e) {
            if(e.which == 13) {
                submitSearch();
            }
        });        
    }
    
    //services
    if (pagetitle.toLowerCase() == "geostor 6.0 | services") {
        var $gl = $("#gl");
        var $bn = $("#bn");
        var $guidelines = $("#guidelines");
        var $benefits = $("#benefits");
        //select random service upon load
        var selected_acc = genRandomNumber(10);
                
        $("#accordion").accordion({
            header: ".accheader",
            active: selected_acc,
            autoHeight: false,
            collapsible: true
        });
    }
    
    //advanced search page
    if (pagetitle.toLowerCase() == "geostor 6.0 | advanced search") {
        var $category = $("#category");
        var $publish = $("#publish");
        var $typeformat = $("#typeformat");
        var $chkBoundingBox = $("#chkBoundingBox");
        var $txtStrtDate = $("#txtStrtDate");
        var $txtEndDate = $("#txtEndDate");
    
        getSelect("publish", 0);
        // Datepickers
        $txtStrtDate.datepicker({
            //inline: true
        });
        $txtEndDate.datepicker({
            //inline: true
        });
        //disable map viewer unless user is clipping by the map
        $chkBoundingBox.click(function() {
            if ($("input[@id=chkBoundingBox]:checked").length > 0) {
                enablenav();
                $("#coverdiv").removeClass("cover");
                $("#mapstatus").html("Select by Map (Enabled)");
                $tabs.tabs( 'select' , 3 );
                $tabs.tabs('option', 'disabled', [0, 1, 2, 4]);
                $("#tabs-4").html("Download by extent");
                $("#tab3header").html("Extent");
            } else {
                disablenav();
                $("#coverdiv").addClass("cover");
                $("#mapstatus").html("Select by Map (Disabled)");
                //re-enable the tabs
                $tabs.data('disabled.tabs', []);
                $tabs.tabs( 'select' , 0 );
                $("#tabs-4").html("Statewide Dataset");
                $("#tab3header").html("Statewide");
            }
        });    
    }    

    //stats page
    if (pagetitle.toLowerCase() == "geostor 6.0 | stats") {
        var $stats = $("#stats");
        $stats.html(genStats());
        $("table.results tr:odd").addClass("even");
    }
    //functions - we haz em
    //ajax functions
    function getSelect(dtype, index) {
        $.ajax({
            type: "POST",
            url: "http://" + site + "/GeoStorWebService/GeoStorWS.asmx/getData",
            data: "{'dtype':'" + dtype + "'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(msg) {
                var json = eval('(' + msg.d + ')');
                var arlen = json.Tables[0].Rows.length;
                switch (dtype) {
                    case "publish":
                        for(x in json.Tables[0].Rows){
                           $("#" + dtype).addOption(x,json.Tables[0].Rows[x].Publish);                                
                        }
                        break
                    case "dltypevector":
                        //loop in reverse because we can't order them with the sql query
                        for (x=arlen-1;x>=0;x--) {
                           $ddlFormat.addOption(json.Tables[0].Rows[x].SName,json.Tables[0].Rows[x].LName);
                        }
						//select specific item
						$("#ddlFormat option:eq(" + index + ")").attr("selected", "selected");
                        break
                    case "dltyperaster":
                        //loop in reverse because we can't order them with the sql query
                        for (x=arlen-1;x>=0;x--) {
                           $ddlFormat.addOption(json.Tables[0].Rows[x].SName,json.Tables[0].Rows[x].LName);
                        }
						//select specific item
						$("#ddlFormat option:eq(" + index + ")").attr("selected", "selected");
                        break
                    default:
                        //alert("undetermined")
                }
                
            },
            error: function() {
                showWarning('There was an error retrieving data, please <a href="/G6_ASP/Feedback.aspx">contact us</a>.');
            }
        });
    }         
    function searchData(input,stradv,ID) {
		var re = new RegExp(/[^a-zA-Z 0-9]+,/g);
		if (re.test(input) || input.match(/^\s+/)) {
			showWarning("Special characters are not allowed in the search string, please try again.");
			focus_highlight($txtSearch);
		} else {
			if (!addysearch(input)) {
				$.ajax({
					type: "POST",
					url: "http://" + site + "/GeoStorWebService/GeoStorWS.asmx/search",
					data: "{'input':'" + input + "','stradv':'" + stradv + "','ID':'" + ID + "'}",
					contentType: "application/json; charset=utf-8",
					dataType: "json",
					success: function(msg) {
						var json = eval('(' + msg.d + ')');
						var numresults = json.Tables[0].Rows.length;
						if (pagetitle.toLowerCase() == "geostor 6.0 | advanced search"){
							input = "your search";
						}
						if (numresults <= 21 && numresults > 0) {
							clearPagination();
							focus_highlight($txtSearch);
							outputResults(json,input + ID);
							$results.show("slow");
							showInfo("There are " + numresults + " result(s) for " + input + ".");
							showWarning("Click the result headers for help context about them.");
						} else if (numresults > 21) {
							pagination = true;
							outputResults(json,input + ID);
							//start up pagination
							initPagination(numresults);
							$("#Pagination").show("fast");
							$results.show("slow");
							showInfo("There are " + numresults + " result(s) for " + input + ", displaying 20 results per page.");
							showWarning("Click the result headers for help context about them.");
							focus_highlight($txtSearch);
						} else if (numresults == 0) {
							clearPagination();
							showWarning("There are " + numresults + " result(s) for " + input + ', please try another search term or use our <a href="Advanced Search.html">Advanced Search</a>.');
							focus_highlight($txtSearch);
						}
					},
					error: function() {
						showWarning('There was an error submitting your search, please <a href="/G6_ASP/Feedback.aspx">contact us</a>.');
					}
				});
			} else {
				//setup address search stuff here
				showWarning('We would search for an address here.');
				addr = input.split(",");
				if (addr.length == 4) {
					var address = {
					  Address : addr[0],
					  City: addr[1],
					  State: addr[2],
					  Zip: addr[3]
					};
					//load the esri mini dojo js
					$.getScript('http://serverapi.arcgisonline.com/jsapi/arcgis/?v=2.1compact', function() {
						//console.log('Load was performed.');
						dojo.require("esri.tasks.query");
						locator = new esri.tasks.Locator("http://" + mapserv + ".geostor.arkansas.gov/ArcGIS/rest/services/GeoStor_Locator/GeocodeServer");
					});
					
				} else {
					showWarning('Address not formatted properly.');
				}
			}
		}
        
    }
    function outputResults(json,input) {
        var counter = 0;
		var output = "";
        //var headervals = ["Rank","Link","Type","Metadata","Thumbnail"];
		var headervals = ["Link","Type","Metadata","Thumbnail"];
		input = input.replace(/ /g, "+");
		//check for url parameter search
        if ($(document).getUrlParam("id")) {
            if (!($(document).getUrlParam("id")[0] == "")) {
                var dirlink = '<a href="http://' + site + '/G6/Home.html?id=' + input + '">Direct link to these results</a>';
				var url = 'http://' + site + '/G6/Home.html?id=' + input;
            }
        } else {
			var dirlink = '<a href="http://' + site + '/G6/Home.html?q=' + input + '">Direct link to these results</a>';
			var url = 'http://' + site + '/G6/Home.html?q=' + input;
		}
		var bookmark = "<a href=\"javascript:void(0)\" onClick=\"bookmarksite('GeoStor 6.0 results for " + input + "','" + url + "');\">Bookmark these results</a>";
		if (pagetitle.toLowerCase() == "geostor 6.0 | home") {
			//var header = bookmark + ' | ' + dirlink + '<table class="results" border="1" RULES=NONE FRAME=BOX><thead><tr>';
			var header = bookmark + ' | ' + dirlink + '<table class="results"><thead><tr>';
		} else if (pagetitle.toLowerCase() == "geostor 6.0 | advanced search") {
			//var header = '<table class="results" border="1" RULES=NONE FRAME=BOX><thead><tr>';
			var header = '<table class="results"><thead><tr>';
		}
        for(y in headervals) {
            header = header + '<th><span class="rhlinks" style="color:white" title="Click here for help">' + headervals[y] + '</span></th>';
        }
        header = header + '</tr></thead><tbody>';
            for(x in json.Tables[0].Rows){
                var title = json.Tables[0].Rows[x].title;
                var filename = json.Tables[0].Rows[x].filename;
                var rank = json.Tables[0].Rows[x].rank;
                var DownloadType = json.Tables[0].Rows[x].DownloadType;
                var link = json.Tables[0].Rows[x].link;
                var ftype = json.Tables[0].Rows[x].ftype;
				//output = output + '<tr><td>'+ makeRank(rank) +'</td>' + setLinkType(x,title,DownloadType,link,filename,ftype) + 
				output = output + '<tr>'+ setLinkType(x,title,DownloadType,link,filename,ftype) +
				'<td><span id="type'+ x + '" class="rhlinks">' + DownloadType + '</span></td>' +
				'<td><span id="http://' + site + '/metadata/' + filename + '" class="mdlinks">View</span></td>';
				output = output + '<td><span id="http://' + site + '/metadata/' + filename.replace(".xml",".bmp") + '" class="tnlinks" title="' + title + '">View</span></td></tr>';
                counter++;
            }
        var footer = '</tbody></table><br />';
		//set header and footer
		$('#results').html(header + footer);
		if (pagination == true) {
			$('#hiddenresult').html(output);
		} else {
			$('#results tbody').empty().append(output);
			$("table.results tr:odd").addClass("even");
			searchBindClick();
		}
    }
    function searchBindClick() {
        //declare here because they don't exist on page load
        //downloads
        var $dllinks = $(".dllinks");
        $dllinks.each(function(y){
            $(this).bind("click", function(){
                $dldialog.dialog('option', 'title', $(this).text());
                $hidTablespace.attr("value",$(this).attr("id"));
                $dldialog.dialog('open');
                if ($.cookie('g6_prefs') != null) {
					var sr = $.cookie('g6_prefs').split(",");
					$txtEmail.val(sr[0]);
					$("#ddlCoorSys option:eq(" + sr[3] + ")").attr("selected", "selected");
				}
                var dltype = $(this).attr("title");
				//set format in dl dialog based upon type and pref
                if (dltype == "Vector") {
					if ($.cookie('g6_prefs') != null) {
						getSelect("dltypevector", sr[1]);
					} else {
						getSelect("dltypevector", "");
					}
                    $dltype.attr("value",$(this).attr("title"));
                }
                if (dltype == "Raster") {
					if ($.cookie('g6_prefs') != null) {
						getSelect("dltyperaster", sr[2]);
					} else {
						getSelect("dltyperaster", "");
					}
                    $dltype.attr("value",$(this).attr("title"));
                }
				return true;
            });
        });
        //metadata - uses the element id for the iframe src attr
        var $mdlinks = $(".mdlinks");
        $mdlinks.each(function(y){
            $(this).bind("click", function(){
                $mddialog.dialog('option', 'title', $(this).attr("id"));
                $metadata.attr("src",$(this).attr("id"));
                $mddialog.dialog('open');
                return true;
            });
        });
		//thumbnails - uses the element id for the iframe src attr
        var $tnlinks = $(".tnlinks");
        $tnlinks.each(function(y){
            $(this).bind("click", function(){
				showInfo('Thumbnail for ' + $(this).attr("title") + '<br /><img src="' + $(this).attr("id") + '" />');
                return true;
            });
        });
        //output help info
        var $rhlinks = $(".rhlinks");
        $rhlinks.each(function(y){
            $(this).bind("click", function(){
                var searchhelptext = 'Results columns legend:<ul><li>Rank - This is the ranking assigned to the dataset</li>' +
                    '<li>Link - This performs an action based upon the Type</li><li>Type - Please see our <a href="Services.html">Services</a> page for definitions of the different types' +
                    '<ul><li>APP = Applications</li><li>DL = Downloadable Data</li><li>EMG = Disaster Event</li>' + 
                    '<li>FTP = File Transfer Protocol</li><li>HLP = Help</li><li>OFF = Offsite Publisher</li><li>WFS = Web Feature Service</li>' +
                    '<li>WMS = Web Mapping Service</li><li>WS = Web Service</li></ul></li>' +
                    '<li>Metadata - Click this to view the metadata for the respective item</li></ul>';
                $("#infodata").attr("style","text-align:left");
                showInfo(searchhelptext);
                return true;
            });
        });
    }
    function adv_search(category,publisher,dltype,strtdate,enddate) {
        $.ajax({
            type: "POST",
            url: "http://" + site + "/GeoStorWebService/GeoStorWS.asmx/adv_search",
            data: "{'category':'" + category + "','publisher':'" + publisher + "','dltype':'" + dltype + "','strtdate':'" + strtdate + "','enddate':'" + enddate + "'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(msg) {
                //call search from here since it seems you can't assign msg.d to a variable
                //also had to escape single quotes in the webservice
                searchData($txtSearch.val(),msg.d,"");
            },
            error: function() {
                showWarning('There was an error submitting your search, please <a href="/G6_ASP/Feedback.aspx">contact us</a>.');
            }
        });
    };
    function setLinkType(x,title,DownloadType,link,filename,ftype) {
        var dltype = "";
        if (ftype == "V") {
            dltype = "Vector"
        } else if (ftype == "R") {
            dltype = "Raster"
        }
        if (DownloadType == "DL") {
			//check to see if downloads are disabled
			//console.log(disabledl);
			if (disabledl == false) {
				return '<td><span id="'+ filename.replace(".xml","") + '" class="dllinks" title="' + dltype + '">'+ title +'</span></td>';
			} else {
				return '<td><span id="http://' + site + '/metadata/' + filename + '" class="mdlinks">'+ title +'</span></td>';
			}
        } else if (DownloadType == "OFF" || DownloadType == "APP" || DownloadType == "FTP"  || DownloadType == "EMG" ) {
            return '<td><a href="' + link + '" target="_blank">'+ title +'</a></td>';
        } else {
            return '<td><span id="http://' + site + '/metadata/' + filename + '" class="mdlinks">'+ title +'</span></td>';
        }
    }
    function submitSearch() {
        //$results.hide("slow");
        $results.html("");
        //check for advanced search page
        if (pagetitle.toLowerCase() == "geostor 6.0 | advanced search") {
			if ($txtSearch.val() == "<-- Click for search help") {
					$txtSearch.val("");
					clearPagination();
			}
            if (validate_adv()) {
				adv_search($category.val(),$('#publish option:selected').text(),$typeformat.val(),$txtStrtDate.val(),$txtEndDate.val());
            }
        } else {
            if ($txtSearch.val() == "" || $txtSearch.val() == "Click for search help -->") {
				clearPagination();
                showWarning("Click the question mark icon for search help!");
                focus_highlight($txtSearch);
            } else {
                searchData($txtSearch.val(),"","");
            }
        }
    }
	function addysearch(data) {
		var addyRegxp = /^\d+\s\w+/;
		if (addyRegxp.test(data.toLowerCase())) {
			//check for comma, split if so, otherwise it is an invalid address
			if (data.indexOf(",") > 0) {
				return true;
			} else {
				return false;
			}
        }
		return false;
	}
    //info and warning functions
    function showWarning(data){
        $.jGrowl(data, {
                header: '<h3>Warning Message:</h3>',
                theme:  'warn',
				life: 5000
        });
    }
    function showInfo(data){
        $.jGrowl(data, {
                header: '<h3>Informational Message:</h3>',
                theme:  'info',
				life: 5000
        });
    }
    //misc functions
	function genRandomNumber(range){
		var randomnumber=Math.floor(Math.random()*range);
		return randomnumber;
	}
	function makeRank(rank){
        var results = "";
		for (i=1;i<=rank;i++) {
            //results = results + '<img src="/G6/images/rating/FilledStar.png" />';
            results = results + '<img src="/G6/images/rating/star-gold-16x16.png" />';
		}
        rank=5-rank;
        for (i=1;i<=rank;i++) {
            //results = results + '<img src="/G6/images/rating/EmptyStar.png" />';
            //results = results + '<img src="/G6/images/rating/star-pink-16x16.png" />';
		}
		return results;
	}
    function genStats() {
        var output = "";
        //add in the new year date here
        var mydates=new Array("2006","2007","2008","2009","2010");
        var myNamesHash = {};
        //add in any new app names here
        //format: app name = app link
        myNamesHash["AR Department of Education: School Facilities"] = "AR_Department_of_Education__School_Facilities";
        myNamesHash["AR Department of Environmental Quality Permits "] = "AR_Department_of_Environmental_Quality__Permits";
        myNamesHash["AR Game &amp; Fish Commission: Facilities"] = "AR_Game_and_Fish_Commission";
        myNamesHash["AR Oil and Gas Commission: Fayetteville Shale"] = "AR_Oil_and_Gas_Commission__Fayetteville_Shale";
        myNamesHash["Crawford County: Assessor Mapping"] = "Crawford_County__Assessor_Mapping";
        myNamesHash["City of Hope: Subdivisions and Zoning"] = "City_of_Hope__Subdivisions_and_Zoning";
        myNamesHash["Pulaski Area GIS Mapping Site"] = "Pulaski_Area_GIS_Mapping_Site";
        myNamesHash["AR Digital Ortho Program: ADOP II - Statewide"] = "Arkansas_Digital_Ortho_Program__Statewide";
        myNamesHash["AR Digital Ortho Program: ADOP II - City of Hot Springs"] = "Arkansas_Digital_Ortho_Program__City_of_Hot_Springs";
        myNamesHash["AR Digital Ortho Program: ADOP II - Miller County"] = "Arkansas_Digital_Ortho_Program__Miller_County";
        myNamesHash["AR Digital Ortho Program: ADOP II - Pulaski County"] = "Arkansas_Digital_Ortho_Program__Pulaski_County";
        myNamesHash["AR Digital Ortho Program: ADOP II - Sebastian County"] = "Arkansas_Digital_Ortho_Program__Sebastian_County";
        myNamesHash["GeoStor: Basemap"] = "GeoStor__Basemap";
        myNamesHash["GeoStor: Basemap Enhanced"] = "GeoStor__Enhanced_Basemap";
        myNamesHash["GeoStor: Elevation - DEM Hillshade and Aspect"] = "GeoStor__Elevation__DEM_Hillshade_and_Aspect";
        myNamesHash["GeoStor: All Feature Layers"] = "GeoStor__All_Feature_Layers";
        myNamesHash["GeoStor: Imagery - DRGs USGS Topos"] = "GeoStor__Imagery__DRGs_USGS_Topos";
        myNamesHash["GeoStor: Imagery - Land Use Land Cover"] = "GeoStor__Imagery__Land_Use__Land_Cover";
        myNamesHash["GeoStor: Imagery - Orthophotography Aerial Photos"] = "GeoStor__Imagery__Orthophotography__Aerial_Photos";
        myNamesHash["GeoStor: Imagery - Thematic Mapper"] = "GeoStor__Imagery__Thematic_Mapper";
        myNamesHash["GeoStor: Download requests and all image/feature services"] = "GeoStor__Download_requests_and_all_imagefeature_service_reports";
        output = '<center><table class="results">';
        for (var n in myNamesHash) {
            if (myNamesHash.hasOwnProperty(n)) {
                output = output + "<tr>";
                output = output + "<td><span>" + n + "</span></td>";
                for(j=0;j<mydates.length;j++){
                    output = output + '<td><A HREF="stats/output/' + myNamesHash[n] + '/' + mydates[j] + '/stats.htm">' + mydates[j] + '</a></td>';
                }
                output = output + "</tr>";
            }
        }
        output = output + "</table></center>";
		$("table.results tr:odd").addClass("even");
        return output;
    }
    function focus_highlight(inputname) {
        inputname.focus().select();
    }
    //validator functions
    function validate_adv() {
        var text = "-- Any --";
        var msg = "";
        var isvalid=new Boolean(true);
        //check to see if there is a keyword entered
        if ($txtSearch.val() == "") {
            //at least one ddl is selected or dates selected if no keyword
            if ($('#category option:selected').text() == text && $('#publish option:selected').text() == text && $('#typeformat option:selected').text() == text){
                isvalid = false;
                msg = "<li>You have to enter a search term and/or select at least one Category, Publisher, or Type</li>";
            }
            //no kw or ddl but search by date
            if (!($txtStrtDate.val() == "" || $txtEndDate.val() == "")) {
                isvalid = true;
            } else {
                msg = msg + "<li>Or you can search by date</li>";
            }
        }
        //both dates or none
        if (($txtStrtDate.val() == "" || $txtEndDate.val() == "") && !($txtStrtDate.val() == "" && $txtEndDate.val() == "")) {
            isvalid = false;
            msg = msg + "<li>You have to select both dates</li>";
        }
        //if anything is invalid, display errors
        if (!(isvalid)) {
            showWarning("<ul>" + msg + "</ul>");
			clearPagination();
            return false;
        }
        return true;
    }
    function validate_dl() {
        var text = "-- Select Item --";
        var selected = $tabs.data('selected.tabs');
		var format = $('#ddlFormat option:selected').val();
		var title = $dldialog.dialog('option', 'title').toLowerCase();
		//this regex is case sensitive!
        var emailRegxp = /\b[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}\b/;
        var msg = "";
        var isvalid=new Boolean(true);
        switch (selected) {
            case 0:
                if ($('#county option:selected').text() == text) {
                    msg = "<li>Please select a county</li>";
                    isvalid = false;
                }
                break;
            case 1:
                if ($('#city option:selected').text() == text) {
                    msg = "<li>Please select a city</li>";
                    isvalid = false;
                }
                break;
            case 2:
                if ($('#water option:selected').text() == text) {
                    msg = "<li>Please select a hydrologic unit</li>";
                    isvalid = false;
                }
                break;
            case 3:
                //statewide
                break;
            case 4:
                if (!($('#quad option:selected').text() == text && $('#townrange option:selected').text() == text)) {
                    if (!($('#quad option:selected').text() == text || $('#townrange option:selected').text() == text)) {
                        msg = "<li>Please select only one quadrangle or township-range</li>";
                        isvalid = false;
                    }
                }
                if ($('#quad option:selected').text() == text && $('#townrange option:selected').text() == text) {
                    msg = "<li>Please select a quadrangle or township-range</li>";
                    isvalid = false;
                }
                break;
            default:
                msg = "<li>I'm not sure WHAT just happened...</li>";
                break;
        }
        if ($txtEmail.val() == "") {
            msg = msg + "<li>Please enter an email address</li>";
            isvalid = false;
        } else if (!(emailRegxp.test($txtEmail.val().toLowerCase()))) {
            msg = msg + "<li>Please enter a valid email address</li>";
            isvalid = false;
        }
		//getting a weird 'undefined' parameter submitted for downloads, hoping to catch that here
		if (format == 'undefined') {
			msg = msg + "<li>Please select a format</li>";
            isvalid = false;
		}
		//check for invalid raster downloads here
		if (format == 'JPEG' || format == 'JPEG2000' || format == 'GIFRASTER' || format == 'ESRIHDR') {
			if (title.indexOf('digital elevation model') != -1 || title.indexOf('hillshade') != -1) {
				msg = msg + "<li>The output format is incompatible with the raster type you selected for download.</li>";
				isvalid = false;
			}
		}
		if (format == 'ESRIASCIIGRID') {
			if (!(title.indexOf('digital elevation model') != -1)) {
				msg = msg + "<li>The output format is incompatible with the raster type you selected for download.</li>";
				isvalid = false;
			}
		}
		//check for invalid vector downloads here
		if (format == 'SHAPE' || format == 'GEODATABASE_MDB') {
			if ((title.indexOf('parcel') != -1 || title.indexOf('zip plus') != -1 || title.indexOf('acf') != -1) && $tabs.tabs('option', 'selected') == '3') {
				msg = msg + "<li>The dataset is too large for the output format, please select another format.</li>";
				isvalid = false;
			}
		}
        //if anything is invalid, display errors
        if (!(isvalid)) {
            showWarning("<ul>" + msg + "</ul>");
			return false;
        }
        return true;
    }
    //download functions
    function submit_dl(site,postvars,dltype,fmesvc) {
        //fme download url & submit here
         //surround where clause item with pikes, the ws will replace them with single quotes for oracle
        $.ajax({
            type: "POST",
            url: "http://" + site + "/GeoStorWebService/GeoStorWS.asmx/fmeProxy",
            data: "{'site':'" + site + "','postvars':'" + postvars + "','dltype':'" + dltype + "','fmesvc':'" + fmesvc + "'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(msg) {
                showInfo("Your download was successfully submitted.");
            },
            error: function() {
                showWarning('There was an error submitting your download, please <a href="/G6_ASP/Feedback.aspx">contact us</a>.');
            }
        });
    }
    function gen_postvars(site,WhereClause,CoordinateSystem,Format,OPT_requesterEmail,Clippee,Clipper,dltype) {
        var MAXX = "&MAXX=";
        var MAXY = "&MAXY=";
        var MINX = "&MINX=";
        var MINY = "&MINY=";
		//remove leading/trailing spaces
		var email = OPT_requesterEmail.replace(/ /g, "");
        //determine if large or small clippee only for vectors
		if (dltype == "Vector") {
			if (Clippee == "TRANSP.ROADS_ACF" || Clippee == "CADAS.PARCEL_POLYGON_CAMP" || Clippee == "CADAS.PARCEL_CENTROID_CAMP" || Clippee == "STREAMLINE.ZIP9_POINTS") {
				LargeClippee = '&LargeClippee=' + encodeURI(Clippee);
				SmallClippee = '&SmallClippee=DEFAULT';
				FinalClippee = LargeClippee + SmallClippee;
			} else {
				SmallClippee = '&SmallClippee=' + encodeURI(Clippee);
				LargeClippee = '&LargeClippee=DEFAULT';
				FinalClippee = LargeClippee + SmallClippee;
			}
		} else {
			//raster downloads
			FinalClippee = '&Clippee=' + encodeURI(Clippee);
		}
        //setup params
        WhereClause = '&WhereClause=' + encodeURI(WhereClause);
        CoordinateSystem = '&CoordinateSystem=' + encodeURI(CoordinateSystem);
        Format = '&Format=' + encodeURI(Format);
        
        //get bounding box from hidden field
        //s = extent.xmin +","+ extent.ymin +","+ extent.xmax +","+ extent.ymax;
        if (Clipper == "EXTENTS") {
            if ($("#tab3header").html() == "Extent") {
                //set hidden val to statewide BB, put in html to save JS code?
                var loc = $("#advhidExtent").val().split(",");
            } else {
                 var loc = $("#hidExtent").val().split(",");
            }
            Clipper = '&Clipper=' + Clipper;
            MAXX = MAXX + loc[2];
            MAXY = MAXY + loc[3];
            MINX = MINX + loc[0];
            MINY = MINY + loc[1];
        } else {
            Clipper = '&Clipper=' + encodeURI(Clipper);
        }
        //build postvar
        var postvar = "";
        postvar = 'OPT_requesterEmail=' + email + '&OPT_serviceMode=async' + WhereClause + 
            Clipper + Format + CoordinateSystem + FinalClippee + MAXX + MAXY + MINX + MINY;
		//the webservice has changed so we need to change our variables
		var fmesvc = "fmedatadownload";
		if (site.toLowerCase() == "dev.geostor.arkansas.gov" || site.toLowerCase() == "agio-c5mw3k1.hds.arkgov.net")
		{
			dltype = "geostor_" + dltype.toLowerCase() + "-dl_dev.fmw";
		}
		else if (site.toLowerCase() == "prod.geostor.arkansas.gov" || site.toLowerCase() == "beta.geostor.arkansas.gov" || site.toLowerCase() == "www.geostor.arkansas.gov" || site.toLowerCase() == "geostor.arkansas.gov")
		{
			dltype = "geostor_" + dltype + "-dl.fmw";
		}
        //finally done, submit dl
        submit_dl(site, postvar, dltype,fmesvc);
    }
	//savepref functions
	function saveprefs(email, vp, rp, cp) {
		var options = { path: '/', expires: 999 };
		var data = email + ',' + vp + ',' + rp + ',' + cp;
		//get previously saved prefs per dl type
		if ($.cookie('g6_prefs') != null) {
			var sr = $.cookie('g6_prefs').split(",");
			if (vp == 'X'){
				vp = sr[1];
			} else if (rp == 'X') {
				rp = sr[2];
			}
		}
		data = email + ',' + vp + ',' + rp + ',' + cp;
		$.cookie('g6_prefs', data, options);
		//uncheck the prefs box
		$('input[name=saveprefchk]').attr('checked', false);
		showWarning("Your preferences have been saved.");
	}
	//pagination functions
	function pageselectCallback(page_index, jq){
		var items_per_page = 20;
        var max_elem = Math.min((page_index+1) * items_per_page, $('#hiddenresult tr').length);
		var newcontent = '';
		// Iterate through a selection of the content and build an HTML string
		for(var i=page_index*items_per_page;i<max_elem;i++)
		{
			newcontent += '<tr>' + $('#hiddenresult tr:eq('+i+')').html() + '</tr>';
		}
		$('#results tbody').empty().append(newcontent);
		searchBindClick();
		$("table.results tr:odd").addClass("even");
		
    }
	function initPagination(num_entries) {
		// Create pagination element
		$("#Pagination").pagination(num_entries, {
			num_edge_entries: 2,
			num_display_entries: 10,
			callback: pageselectCallback,
			items_per_page:20
		});
	 }
	function clearPagination() {
		$("#Pagination").attr("style","display:none");
		pagination = false;
		$('#hiddenresult').html("");
	}
});