<?php

/**
 * @package local_qrbasedimage
 * @author Pearl Miglani <miglanipearl@gmail.com> and Shreyans Jain <shreyansja1n@outlook.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

    require_once(__DIR__."/../../config.php");
    global $DB;
    include 'url.php';
    $hased=htmlspecialchars($_GET['id']);
    $decryption = base64_decode($hased);
    $dec = explode('-', $decryption);  
    $uid = $dec[0];
    $aid = $dec[1];
    $qid = $dec[2];
    $sid = $dec[3];
    $uniqueid = $dec[4];
    $check = $DB->record_exists('randomnumber',['attemptid'=>$aid,'ran_num'=>$uid]);
    require_once('timeQR.php');
    if ($check){
        $tstamp = $DB->get_field('randomnumber','tstamp',['attemptid'=>$aid, 'ran_num'=>$uid]);
        $tstamp = strtotime($tstamp);
        $tstamp = date("Y-m-d H:i:s",$tstamp);
        $ctime = date("Y-m-d H:i:s", time());
        $diff = strtotime($ctime)-strtotime($tstamp);
        $timezone = date_default_timezone_get();

        if ($diff>$time4qr){
            echo"
            <p id='box' style='text-align:center'></p>
            <script>alert('TIME EXPIRED');
            document.getElementById('box').innerHTML = 'TIME EXPIRED';
            </script>";
        }
    
        else{
            echo '<p style="text-align:center" id="demo"></p>
    
            <script>
            
            var countDownDate = new Date().getTime()+('.$time4qr.'-'.$diff.')*1000;
            
            // Update the count down every 1 second
            var x = setInterval(function() {
            
            
            var now = new Date().getTime();
            
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            
            // Time calculations for days, hours, minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Display the result in the element with id="demo"
            document.getElementById("demo").innerHTML = minutes + "m " + seconds + "s ";
            
            // If the count down is finished, write some text 
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
            }
            }, 1000);
            </script>';
        }
    }
    else{
        echo "<h2 style='text-align:center'>Error: String not encrypted correctly</h2>";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Moodle Enhancement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alata&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Anaheim&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Andada&amp;display=swap">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/upload1.css">
    <script type="text/javascript">

//added event handler for the file upload control to access the files properties.
document.addEventListener("DOMContentLoaded", init, false);

//To save an array of attachments 
var AttachmentArray = [];

//counter for attachment array
var arrCounter = 0;

//to make sure the error message for number of files will be shown only one time.
var filesCounterAlertStatus = false;

//un ordered list to keep attachments thumbnails
var ul = document.createElement('ul');
ul.className = ("thumb-Images");
ul.id = "imgList";

function init() {
    //add javascript handlers for the file upload event
    document.querySelector('#files').addEventListener('change', handleFileSelect, false);
}

//the handler for file upload event
function handleFileSelect(e) {
    //to make sure the user select file/files
    if (!e.target.files) return;

    //To obtaine a File reference
    var files = e.target.files;

    // Loop through the FileList and then to render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

        //instantiate a FileReader object to read its contents into memory
        var fileReader = new FileReader();

        // Closure to capture the file information and apply validation.
        fileReader.onload = (function (readerEvt) {
            return function (e) {
                
                //Apply the validation rules for attachments upload
                ApplyFileValidationRules(readerEvt)

                //Render attachments thumbnails.
                RenderThumbnail(e, readerEvt);

                //Fill the array of attachment
                FillAttachmentArray(e, readerEvt)
            };
        })(f);

        // Read in the image file as a data URL.
        // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.
        // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme
        fileReader.readAsDataURL(f);
    }
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
}

//To remove attachment once user click on x button
jQuery(function ($) {
    $('div').on('click', '.img-wrap .close', function () {
        var id = $(this).closest('.img-wrap').find('img').data('id');

        //to remove the deleted item from array
        var elementPos = AttachmentArray.map(function (x) { return x.FileName; }).indexOf(id);
        if (elementPos !== -1) {
            AttachmentArray.splice(elementPos, 1);
        }

        //to remove image tag
        $(this).parent().find('img').not().remove();

        //to remove div tag that contain the image
        $(this).parent().find('div').not().remove();

        //to remove div tag that contain caption name
        $(this).parent().parent().find('div').not().remove();

        //to remove li tag
        var lis = document.querySelectorAll('#imgList li');
        for (var i = 0; li = lis[i]; i++) {
            if (li.innerHTML == "") {
                li.parentNode.removeChild(li);
            }
        }

    });
}
)

function ApplyFileValidationRules(readerEvt)
{
   
    if (CheckFilesCount(AttachmentArray) == false) {
        if (!filesCounterAlertStatus) {
            filesCounterAlertStatus = true;
            alert("You have added more than 10 files. According to upload conditions you can upload 10 files maximum");
        }
        e.preventDefault();
        return;
    }
}


function CheckFilesCount(AttachmentArray) {
    var len = 0;
    for (var i = 0; i < AttachmentArray.length; i++) {
        if (AttachmentArray[i] !== undefined) {
            len++;
        }
    }
    if (len > 9) {
        return false;
    }
    else
    {
        return true;
    }
}

function RenderThumbnail(e, readerEvt)
{
    var li = document.createElement('li');
    ul.appendChild(li);
    li.innerHTML = ['<div class="img-wrap" style"padding-right:20px"> <span class="close">&times;</span>' +
        '<img class="thumb" src="', e.target.result, '" title="', escape(readerEvt.name), '" data-id="',
        readerEvt.name, '"/>' + '</div>'].join('');
    document.getElementById('Filelist').insertBefore(ul, null);
}

function FillAttachmentArray(e, readerEvt)
{
    AttachmentArray[arrCounter] =
    {
        AttachmentType: 1,
        ObjectType: 1,
        FileName: readerEvt.name,
        FileDescription: "Attachment",
        NoteText: "",
        MimeType: readerEvt.type,
        Content: e.target.result.split("base64,")[1],
        FileSizeInBytes: readerEvt.size,
    };
    arrCounter = arrCounter + 1;
}
</script>

</head>
<body>
    <script>
    function onc(){
        var url = "<?php include 'url.php'; echo $url;?>";
        $("#re").removeClass('hidden');
        $("#result").remove();
        var enst= '<?php echo $_GET['id']; ?>';
        $.ajax({method: "POST", url:url+"/local/qrbasedimage/up.php", data:{files:AttachmentArray, id:enst}, error: function(jqXHR,  exception){
            alert(jqXHR.status);
        } }).done(function(returnedData){
            $("#container").append('<h3 id=result>'+returnedData+'</h3>');
            alert(returnedData);
            $("#re").addClass("hidden");
            $("#subbutton").remove();
            location.reload();
        });
    }
    </script>
    <div class="container">
        <div class="row justify-content-center" style="width: auto;height: auto;text-align: center;">
            <div class="col-md-12" style="width: auto;height: auto;"><img src="./nu.png" style="width: 327px; padding-bottom:20px"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p style="font-family: Andada, serif;font-size: 20px;">Upload Answers</p>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <?php 
                    require_once('upinstructions.php');
                    foreach ($instructions as $instruction){
                        echo "<li>$instruction</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
        <output id="Filelist" style="padding: 50px;"></output>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12 align-self-center">
            <label for="files" class="custom-file-upload">Capture Image
            
            </label> <input type="file" name="files[]" id="files" accept="image/*" capture="camera" multiple>
            </div>
        </div>
    </div>
    <div class="container" id="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12 text-center" style="margin-top: 62px;" id="subbutton"><button class="btn btn-primary text-center" onclick="onc()" type="button">Submit</button>
            <div id="re" class="hidden">
                <img src="<?php include 'url.php'; echo $url;?>/local/qrbasedimage/ajax-loader.gif">
            </div>
            <h3 id="result"></h3>

        </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>