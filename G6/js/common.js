$(document).ready(function(){
    // Increase Font Size
    $("#increase_font").click(function(){
        var currentFontSize = $('html').css('font-size');
        var currentFontSizeNum = parseFloat(currentFontSize, 10);
        var newFontSize = currentFontSizeNum*1.1;
        $('html').css('font-size', newFontSize);
        return false;
    });

    // Decrease Font Size
    $("#decrease_font").click(function(){
        var currentFontSize = $('html').css('font-size');
        var currentFontSizeNum = parseFloat(currentFontSize, 10);
        var newFontSize = currentFontSizeNum*0.9;
        $('html').css('font-size', newFontSize);
        return false;
    });
});

function disable_stylesheets(){
    for(i=0;i<document.styleSheets.length;i++){
        void(document.styleSheets.item(i).disabled=true);
        }
    }