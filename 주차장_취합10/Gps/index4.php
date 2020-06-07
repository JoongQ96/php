<!DOCTYPE html>

<html>

    <head>

        <meta charset="euc-kr">

        <title>HTML5 test</title>

        <style>

            /*

                .supported {

                    width:300px; 

                    border:1px solid #e3e3e3; 

                    padding : 5px; 

                    font-family : Arial; 

                    font-size:0.9em; 

                    line-height:160%;

                }

            */ 

        </style> 

        <script type="text/javascript"

            src="http://maps.google.com/maps/api/js?sensor=false"></script>

        <script language="javascript"> 

            /* 위치 확인 */

            function locationTest() {

                navigator.geolocation.getCurrentPosition(handleLocation, handleError); 

            }

            // 위치콜백 

            function handleLocation(position)  {

                var outDiv = document.getElementById("result"); 

                 

                // 좌표보기 

                var posStr = "위도 : " + position.coords.latitude + "<br/>";

                posStr += "경도 : " + position.coords.longitude; 

                outDiv.innerHTML = posStr; 

 

                // 위치정보 만들고 

                var latlng = new google.maps.LatLng(position.coords.latitude, 

                        position.coords.longitude);     

 

                // 지도 옵션 

                var mapOption = {

                    zoom: 19,

                    center: latlng,

                    mapTypeControl: false,

                    mapTypeId: google.maps.MapTypeId.ROADMAP

                };

 

                // 지도만들고 

                var map = new google.maps.Map(

                        document.getElementById("mapCanvas"), mapOption);

             

                // 위치표시 

                new google.maps.Marker({position : latlng, map : map, title : "here!!"});

            } 

            // 에러콜백 

            function handleError(err) {

                var outDiv = document.getElementById("result");

                 

                if(err.code == 1) {

                    outDiv.innerHTML = "사용자가 위치정보 공유를 거부함";

                }

                else {

                    outDiv.innerHTML = "에러발생 : " + err.code;

                }

            } 

        </script> 

    </head>

    <body onload="javascript:locationTest();">

        <div id="result" class="supported">

 

        </div>

        <br/> 

        <div id="mapCanvas" style="width:560px; height:400px; border:1px solid #e3e3e3">

         

        </div> 

    </body>

<html>