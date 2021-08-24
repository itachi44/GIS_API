 
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="leaflet/leaflet.css" /> 
        <link rel="stylesheet" type="text/css" href="main.css" /> 
        <link rel="shortcut icon" type="image" href="icon/favicon.jpeg" />
        <script type="text/javascript" src="leaflet/leaflet.js" ></script>
        <script type="text/javascript" src="jquery.min.js" ></script>
        <title>GIS Suivi du déploiement</title>
    </head>
    <body>
    <?php 
    if(isset($_GET["district"])){
        include_once 'Databases.php' ;
        $district=strtoupper($_GET["district"]);
        echo $district;
        $database = new Database() ;
        $conn = $database->getConnexion(); 
        $stmt = $conn->prepare("SELECT longitude,latitude FROM centroids79districts WHERE district_sanitaire LIKE :district");
        $stmt->execute(['district'=>'%'.$district.'%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $init_lat=$result["latitude"];
        $init_lng=$result["longitude"];
    }
    ?>
  

        <p class="text-center">Suivi du déploiement de l'application Teranga</p>

        <style>
            body{
                margin: 0;
                padding: 0;
            }
            .text-center{
                right: 35%;
                position:absolute;
                top: 0;
                margin: 0;
                color: rgba(0,88,156,255);
                font-size: 1.4em;
                text-decoration: underline;
            }
            #mapid{
                position: absolute;
                top: 0;
                bottom: 0;
                width: 100%;
                height: 90%;
              }
              .my-label{
                  transform:scale(0.5);
                  font-size:0.7em;
              }
        </style>

        <div class="container" id="mapid"></div>

        <script >
            //red_marker
            var redIcon = L.icon({
                iconUrl: 'icon/map-marker-r.png', 

                iconSize:     [28, 28], // size of the icon
                iconAnchor:     [12, 24], // icon position 
                popupAnchor:  [0, -24] // point from which the popup should open relative to the iconAnchor
            });   
            //orange_marker
            var orangeIcon = L.icon({
                iconUrl: 'icon/map-marker-o.png', //à changer

                iconSize:     [28, 28], 
                iconAnchor:     [12, 24], 
                popupAnchor:  [0, -24] 
            }); 
            
            //green_marker
            var greenIcon = L.icon({
                iconUrl: 'icon/map-marker-g.png', //à changer

                iconSize:     [28, 28],
                iconAnchor:     [12, 24],
                popupAnchor:  [0, -24]
            }); 


            //function contain to check wether an object is in an array or not
            function contain(array,object){
                let state=false;
                for (elt of array){
                    if(elt[0]==object[0] && elt[1]==object[1]){
                        state=true;
                        break;
                    }
                }

                return state;

            }
            //function to get the index of geographical point
            function getIndex(array,object){
                let index=-1;
                for(let i=0; i<array.length ;i++){
                    if(array[i][0]==object[0] && array[i][1]==object[1]){
                        index=i;
                        break;
                    }
                }
                return index;
            }

            //function to get coordinates 
            function getCoordinates(district_name,districts){
                let latitude;
                let longitude;
                for(let i=0; i<districts.length; i++){
                    if(districts[i][2].toLowerCase()==district_name.toLowerCase()){
                        latitude=districts[i][0];
                        longitude=districts[i][1];
                    }
                }
                return [latitude , longitude];
            }

            //function to get the district
            function getDistrict(coordinates,districts){
                let district;
                for(let i=0; i<districts.length; i++){
                    if(districts[i][0].toLowerCase()==coordinates[0] && districts[i][1].toLowerCase()==coordinates[1] ){
                        district=districts[i][2];
                    }
                }
                return district;
            }


            //initializing map 
            let mylat = '14.71005856';
            let mylng = '-17.17218771';
            let myzoom = '12';


            let map = L.map('mapid').setView([mylat, mylng], myzoom);
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar'}).addTo(map); 
            let markers=[]; //marker array
            let circles=[]; //circles array
            let result=[]; //result of ajax request
            let districts=[]; //districts we have after processing the result

/*-----------------------------------------------------------------------------------------------------------------*/
setTimeout(() => {
    $.ajax({
                 type: 'GET',
                 contentType: 'application/json',
                 url: 'getDistrictsData.php',						
                 success: function(data) { 
                         for(let item of data.centroids79districts){
                             result.push(item);
                         }
                         result.shift();
                         result.forEach(function(elt){
                            districts.push([elt.latitude,elt.longitude,elt.district_sanitaire]);

                             })
                 //add many markers using a loop 
                districts.forEach(function(point){
                    let marker=L.marker([point[0], point[1]], {icon: redIcon});
                    marker.bindTooltip(point[2], {permanent: true, className: "my-label", offset: [0, 0],direction:'bottom' });
                    marker.addTo(map).bindPopup("Etat du déploiement: pas encore commencé"+"<br>"+"<span>District: "+point[2]+"</span>" +
                    "<br><br>"+
                    "<div style='text-align:center;'><button style='background-color:#00a8ff;color:#fff; border-radius:5px; ' type='button' class='btn commencer'>Commencer</button></div>");
                    markers.push(marker);  
                    let circle= L.circle([point[0], point[1]], {
                        color: '',
                        fillColor: '',
                        fillOpacity: 0, //hidden
                        radius: 1000
                    }).addTo(map);
                    circles.push(circle);
         
                });
                    //handle error
                 },
                 error: function (error) {
                     console.log(error)
                 },

             });

    
}, 500);

setTimeout(() => {     
      //
                $.ajax({
                 type: 'GET',
                 contentType: 'application/json',
                 url: 'getMarked_inProgress.php',
                 success:function(data){
                     result=[];
                     if(data.marked_locations.length){
                    for(let item of data.marked_locations){
                             result.push(item);
                         }
                         //result.shift();
                         result.forEach(function(elt){
                            marked_inProgress.push([elt.latitude,elt.longitude,elt.district]);
                             })
                     }
                     console.log("data loaded");
                 },
                 error: function(error){
                    console.log(error)

                 }

                });
                //
                $.ajax({
                 type: 'GET',
                 contentType: 'application/json',
                 url: 'getMarked_completed.php',
                 success:function(data){
                     result=[];
                     if(data.marked_locations.length){
                    for(let item of data.marked_locations){
                             result.push(item);
                         }
                         //result.shift();
                         result.forEach(function(elt){
                            marked_completed.push([elt.latitude,elt.longitude, elt.district]);

                             })
                     }
                     console.log("data loaded");
                 },
                 error: function(error){
                    console.log(error)
                 }

                });
            }, 500);
/*-----------------------------------------------------------------------------------------------------------------*/

        //logic to change the marker icon 


            //load marked locations array from the database for the moment we use local storage
             
            let marked_inProgress=[];
            let marked_completed=[];
            /*
            marked=JSON.parse(localStorage.getItem("marked_inProgress_locations"));*/
            let latlng_phone;

            //execute every 0.5s
function runLogic(){
     setTimeout(function(){
        if(marked_inProgress.length!=0){
        marked_inProgress.forEach(function(item){
            L.marker([item[0], item[1]], {icon: orangeIcon}).addTo(map).bindPopup("Etat du déploiement: en cours" +"<br>"+
            "<div>"+
            "<span class='lat'>lat: "+item[0]+"</span>"+ "<br>"+"<span class='lng'>lng: "+item[1]+"</span>"+ "<br>"+
            "<span> District: "+item[2]+"</span>"+"<br>"+
            "<div style='text-align:center; display:flex; flex-direction:row;'>"+
            "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn inProgress'>Annuler</button>"+
            "<button  style='background-color:#44bd32;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn'>terminé</button>"+ 
            "</div>"+
            "</div>"); 
        });
        }
        if(marked_completed.length!=0){
        marked_completed.forEach(function(item){
            L.marker([item[0], item[1]], {icon: greenIcon}).addTo(map).bindPopup("Etat du déploiement: terminé" +"<br>"+
            "<div>"+
            "<span class='lat'>lat: "+item[0]+"</span>"+ "<br>"+"<span class='lng'>lng: "+item[1]+"</span>"+ "<br>"+
            "<span> District: "+item[2]+"</span>"+"<br>"+
            "<div style='text-align:center; display:flex; flex-direction:row;'>"+
            "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn completed'>Annuler</button>"+
            "<button  style='background-color:#44bd32;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn'>terminé</button>"+ 
            "</div>"+
            "</div>"); 
        });
        }
 }, 1000);
}

runLogic();

document.addEventListener("click",function(){
    let elt=document.activeElement;
    if(elt.textContent.toLowerCase()=="terminé"){
        btn=elt;
         //catch the click event on the buttons
            btn.addEventListener("click",function(e){
                     map.closePopup();
                     //we get coodinates
                    let district_name=btn.parentElement.parentElement.children[4].textContent.split(":")[1].trim();
                    console.log(district_name);
                    let coordinates=getCoordinates(district_name,districts);
                    let current_lat=coordinates[0];
                    let current_lng=coordinates[1];
                     //change the icon to green
                     let m=L.marker([current_lat, current_lng], {icon: greenIcon}).addTo(map).bindPopup("Etat du déploiement: terminé"+"<br> ");
                     if(!contain(marked_completed,[parseFloat(current_lat), parseFloat(current_lng)])){
                         console.log("adding the point...");
                            marked_completed.push([parseFloat(current_lat),parseFloat(current_lng)]);
                            //store marked locations in the database
                            $.post("completed.php", {"latitude" : marked_completed[marked_completed.length-1][0],"longitude":marked_completed[marked_completed.length-1][1],"district":district_name}, function(data){
                                console.log(data);
                      })
                     }
                     runLogic();
              });
    }
    else if(elt.textContent.toLowerCase()=="annuler"){
                btn=elt;
                btn.addEventListener("click",function(e){
                    map.closePopup();
                    //we get coodinates
                    let district_name=btn.parentElement.parentElement.children[4].textContent.split(":")[1].trim();
                    let coordinates=getCoordinates(district_name,districts);
                    let current_lat=coordinates[0];
                    let current_lng=coordinates[1];
                    if(btn.classList.contains("inProgress")){
                        $.post("delete_marked_inProgress.php", {"latitude" : current_lat,"longitude":current_lng}, function(data){
                                    console.log(data);
                        })
                        //change the marker to orange
                        L.marker([current_lat, current_lng], {icon: redIcon}).addTo(map).bindPopup("Etat du déploiement: pas encore commencé" +"<br>"+
                                "<div>"+
                                "<span> District: "+location[2]+"</span>"+"<br>"+
                                "<span class='lat'>lat: "+location[0]+"</span>"+ "<br>"+"<span class='lng'>lng: "+location[1]+"</span>"+ "<br><br>"+
                                "<button style='background-color:#6DC300; color:#fff; border-radius:5px; margin-right:20%;' type='button' class='btn btn-success'>Marquer</button>"+ 
                                "<span style='color:#4DA5F6; text-decoration:underline;'>Détails</span>"+ "</div>");

                        //get index of the point
                        let index=getIndex(marked_inProgress,[parseFloat(current_lat), parseFloat(current_lng)]);
                        //remove the point occurences to marked points
                        console.log("removing the point...");
                        marked_inProgress.splice(index,1);
                        runLogic();

                    }else if(btn.classList.contains("completed")){
                        $.post("delete_marked_completed.php", {"latitude" : current_lat,"longitude":current_lng}, function(data){
                                    console.log(data);
                        })
                        //change the marker to orange
                        L.marker([current_lat, current_lng], {icon: redIcon}).addTo(map).bindPopup("Etat du déploiement: en cours" +"<br>"+
                                "<div>"+
                                "<span> District: "+location[2]+"</span>"+"<br>"+
                                "<span class='lat'>lat: "+location[0]+"</span>"+ "<br>"+"<span class='lng'>lng: "+location[1]+"</span>"+ "<br><br>"+
                                "<button style='background-color:#6DC300; color:#fff; border-radius:5px; margin-right:20%;' type='button' class='btn btn-success'>Marquer</button>"+ 
                                "<span style='color:#4DA5F6; text-decoration:underline;'>Détails</span>"+ "</div>");

                        //get index of the point
                        let index=getIndex(marked_inProgress,[parseFloat(current_lat), parseFloat(current_lng)]);
                        //remove the point occurences to marked points
                        console.log("removing the point...");
                        marked_inProgress.splice(index,1);
                        runLogic();


                    }


        });
        
    }else if(elt.textContent.toLowerCase()=="commencer"){
        btn=elt;
            btn.addEventListener("click",function(e){
                     map.closePopup();
                     //we get coodinates
                     let district_name=btn.parentElement.parentElement.children[1].textContent.split(":")[1].trim()
                     let coordinates=getCoordinates(district_name,districts);
                     let current_lat=coordinates[0];
                     let current_lng=coordinates[1];
                     //change the icon to green
                     let m=L.marker([current_lat, current_lng], {icon: orangeIcon}).addTo(map).bindPopup("Etat du déploiement: en cours"+"<br> ");
                     if(!contain(marked_inProgress,[parseFloat(current_lat), parseFloat(current_lng)])){
                         console.log("adding the point...");
                            marked_inProgress.push([parseFloat(current_lat),parseFloat(current_lng)]);
                            //store marked locations in the database
                            $.post("inProgress.php", {"latitude" : marked_inProgress[marked_inProgress.length-1][0],"longitude":marked_inProgress[marked_inProgress.length-1][1],"district":district_name}, function(data){
                                console.log(data);
                      })
                     }
                     runLogic();
              });
    }
});
        </script> 

    </body>
 </html>
 </html>
