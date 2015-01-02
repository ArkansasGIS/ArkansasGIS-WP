$(document).ready(function(){
	var $txtSearch = $("#txtSearch");
	// Dialog			
	$('#dialog').dialog({
		autoOpen: true,
		width: 180,
		resizable: false,
		draggable: false,
		buttons: {
			"Search": function() { 
				submitSearch($txtSearch.val());				
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	//if the user hits the enter key, submit the search
        $txtSearch.keyup(function(e) {
            if(e.keyCode == 13) {
                submitSearch($txtSearch.val());
            }
        });
	function submitSearch(query){
		window.open('http://www.geostor.arkansas.gov/G6/Home.html?q=' + query,'mywindow','width=1100,height=800,toolbar=yes, ' +
			'location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes, resizable=yes')
	}
});
