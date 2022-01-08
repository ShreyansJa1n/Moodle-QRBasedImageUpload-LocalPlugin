var tester;
function displayImages(Y,element, url){
    element = JSON.parse(element);
    console.log(element);
    var capturelement = `question-${element['uniqueid']}-${element['slot']}`;
    console.log(capturelement);
    tester=capturelement;
    var ansbox = document.getElementById(capturelement).innerHTML;
    ansbox+=` <a target=_blank rel="noopener noreferrer" href=/local/qrbasedimage/imageAPI.php?img=${element.name}> Image ${element.name} ${element.tstamp} </a> <br> `;
    document.getElementById(capturelement).innerHTML=ansbox;
}