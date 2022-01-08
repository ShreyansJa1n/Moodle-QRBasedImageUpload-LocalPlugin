var encryption;
var aid;
var url;

function getDetails(Y,enc,attemptid, urlM){

    encryption = enc;
    aid = attemptid; 
    url = urlM;

}

function onc(){
        console.log(encryption);
        var aid =encryption;
        $("#qim").remove();
        $.post(url+"/local/qrbasedimage/qrprint.php",{as: aid} ,function(returnedData){ $("#QRCode").append("<br><div id=qim>"+returnedData+"</div>");});

}

function showimage(){
    var aid=encryption;
    $('#img').remove();
    $.post(url+'/local/qrbasedimage/showimages.php',{aid:aid}, function(returnedData){ $('#images').append('<div id=img>'+returnedData+'</div>'); });
}
var element = document.getElementById("page-content").innerHTML;
element = element+'<row><button class="btn btn-success" onclick=onc() >Show QR Code</button>' + '<button class="btn btn-primary" onclick=showimage() >Show Images</button> </row>'+'<row> <div id=QRCode> </div>'+'<div id=images> </div> </row>' ;
document.getElementById("page-content").innerHTML = element;
