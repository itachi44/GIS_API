


    var mylat = '14.7231';
    var mylon = '-17.4963';
    var myzoom = '12';
    var map = L.map('mapid').setView([mylat, mylon], myzoom);
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar'}).addTo(map);
    var polylinePoints = [
        [14.7231, -17.4963],
        [14.7233, -17.4954],
        [14.7228, -17.495],
        [14.723, -17.4948 ],
        [14.7221, -17.49453],
        [14.7222, -17.4947],
        [14.7226, -17.4952],
        [14.7231, -17.4963],
        [14.7231, -17.4964],
        [14.71966, -17.4969],
        [14.7096, -17.4867],
        [14.7027, -17.4788],
        [14.6902, -17.4738],
        [14.6774, -17.4645],
        [14.6764, -17.4558],
        [14.6683, -17.4438],
        [14.6599, -17.4362],
    ]; 
    var phoneLine = L.polyline(polylinePoints).addTo(map);  
    


    var devicePoints = [
        [14.723068,	-17.496372],
        [14.723138,	-17.496488],
        [14.723075,	-17.496395],
        [14.723102,	-17.49636],
        [14.722795,	-17.495068],
        [14.722392,	-17.494942],
        //[0,	0],
        //[0,	0],
        [14.723398,	-17.49594],
        [14.723068,	-17.49642],
        [14.72306,	-17.496428],
        [14.723035,	-17.496467],
        [14.723012,	-17.496432],
        [14.722965,	-17.496408],
        [14.723048,	-17.496462],
        [14.72264,	-17.498927],
        [14.71692,	-17.490195],
        [14.705092,	-17.482138],
        [14.693908,	-17.473338],
        [14.679972,	-17.468562],
        //[0,	0],
        [14.66867,	-17.444135],
        [14.6674,	-17.442512],
        [14.663083,	-17.438013],
        [14.656565,	-17.434983],
        [14.656095,	-17.435492]
        ]; 
        var deviceLine = L.polyline(devicePoints, {color:'red'}).addTo(map); 





                            //add many markers using a loop
                            for (var i = 0; i < data.length; i+=5){
                                setTimeout(function(){
                                    console.lo
                                    L.marker([data[i]['latitude'], data[i]['longitude']], {icon: redIcon}).addTo(map).bindPopup("<table> <tr> <th>time: </th><td>" + data[i]['time'] + "</td> </tr> <tr> <th>temperature:</th><td>" + data[i]['latitude'] + "</td></tr> <tr><th>humidity:</th><td>" + data[i]['longitude'] + "</td></tr> </table>");
        
                                }, 3000);
                                
                            }




                            var i = 0;
                            var intervalMarker = setInterval(function () {
    
                            // Your logic here
                            L.marker([data[i]['latitude'], data[i]['longitude']], {icon: redIcon}).addTo(map).bindPopup("<table> <tr> <th>time: </th><td>" + data[i]['time'] + "</td> </tr> <tr> <th>temperature:</th><td>" + data[i]['latitude'] + "</td></tr> <tr><th>humidity:</th><td>" + data[i]['longitude'] + "</td></tr> </table>");
    
    
                            if (++i === data.length) {
                                window.clearInterval(intervalMarker);
                            }
                            }, 1000);  


