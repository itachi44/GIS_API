<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="leaflet/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="main.css" />
    <link rel="shortcut icon" type="image" href="icon/favicon.jpeg" />
    <script type="text/javascript" src="leaflet/leaflet.js"></script>
    <script type="text/javascript" src="jquery.min.js"></script>
    <title>GIS Suivi du déploiement</title>
</head>

<body>
    <?php
    if (isset($_GET["district"])) {
        include_once 'Databases.php';
        $district = strtoupper($_GET["district"]);
        echo $district;
        $database = new Database();
        $conn = $database->getConnexion();
    }
    ?>

    <div style="min-height:800px; margin:0; padding:0;" class="container-fluid">
        <!--Modal-START-->


        <!-- Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Information district : <span id="district_name"></span> </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Do something</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal-END-->
        <nav class="navbar navbar-expand-lg mb-0 navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img width="100" height="50" alt="" class="d-inline-block align-middle mr-2" src="./icon/logo_ipd6.png" style='border-radius:10%;'></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul style="width:70%;" class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <p style="color: rgba(0,88,156,255); font-size: 1.4em;" class="text-center mt-1">Suivi du déploiement de l'application Teranga</p>
                        </li>
                    </ul>
                    <button style="margin-right: 5%; min-width:9%; color:#fff;" type="button" class="btn btn-info live">en direct </button>
                    <div style="margin-right: 3%;" class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            type de carte
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">vue satellite</a></li>
                            <li><a class="dropdown-item" href="#">vue normale</a></li>
                            <li><a class="dropdown-item" href="#">vue terrain</a></li>
                        </ul>
                    </div>
                    <form id="search_district" style="width:40%;" class="d-flex">
                        <input id="searched_district" class="form-control me-2" type="search" placeholder="Rechercher un district" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">rechercher</button>
                    </form>
                </div>
            </div>
        </nav>
        <div style="width:100%; min-height: 700px; margin:0;" class="container-fluid" id="mapid"></div>
    </div>

    <script>
        //red_marker
        var redIcon = L.icon({
            iconUrl: 'icon/map-marker-r.png',

            iconSize: [28, 28], // size of the icon
            iconAnchor: [12, 24], // icon position 
            popupAnchor: [0, -24] // point from which the popup should open relative to the iconAnchor
        });
        //orange_marker
        var orangeIcon = L.icon({
            iconUrl: 'icon/map-marker-o.png', //à changer

            iconSize: [28, 28],
            iconAnchor: [12, 24],
            popupAnchor: [0, -24]
        });

        //green_marker
        var greenIcon = L.icon({
            iconUrl: 'icon/map-marker-g.png', //à changer

            iconSize: [28, 28],
            iconAnchor: [12, 24],
            popupAnchor: [0, -24]
        });


        //function contain to check wether an object is in an array or not
        function contain(array, object) {
            let state = false;
            for (elt of array) {
                if (elt[0] == object[0] && elt[1] == object[1]) {
                    state = true;
                    break;
                }
            }

            return state;

        }
        //function to get the index of geographical point
        function getIndex(array, object) {
            let index = -1;
            for (let i = 0; i < array.length; i++) {
                if (array[i][0] == object[0] && array[i][1] == object[1]) {
                    index = i;
                    break;
                }
            }
            return index;
        }

        //function to get coordinates 
        function getCoordinates(district_name, districts) {
            let latitude;
            let longitude;
            for (let i = 0; i < districts.length; i++) {
                if (districts[i][2].toLowerCase() == district_name.toLowerCase()) {
                    latitude = districts[i][0];
                    longitude = districts[i][1];
                }
            }
            return [latitude, longitude];
        }

        //function to get the district
        function getDistrict(coordinates, districts) {
            let district;
            for (let i = 0; i < districts.length; i++) {
                if (districts[i][0].toLowerCase() == coordinates[0] && districts[i][1].toLowerCase() == coordinates[1]) {
                    district = districts[i][2];
                }
            }
            return district;
        }
        //function to find a marker index
        function getMarkerIndex(coordinates, markers) {
            let index = -1;
            for (let i = 0; i < markers.length; i++) {
                if (coordinates[0] == markers[i]._latlng.lat && coordinates[1] == markers[i]._latlng.lng) {
                    index = i;
                    break;
                }
            }

            return index;
        }

        //initializing map 
        let lat = '14.71005856';
        let lng = '-17.17218771';
        let zoom = '12';
        let map = L.map('mapid').setView([lat, lng], zoom);
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {
            foo: 'bar'
        }).addTo(map);



        //search handlind
        document.getElementById("search_district").addEventListener("submit", function(e) {
            e.preventDefault();
            let district_name = document.getElementById("searched_district").value;
            $.ajax({
                type: 'POST',
                contentType: 'application/json',
                url: 'search_district.php',
                data: JSON.stringify({
                    district: district_name
                }),
                success: function(data) {
                    lat = data.coordinates.latitude;
                    lng = data.coordinates.longitude;
                    zoom = data.coordinates.zoom;
                    //fly to the position
                    map.flyTo(new L.LatLng(lat, lng), zoom);
                    /*
                    let i1 = getIndex(marked_inProgress, [lat, lng]);
                    let i2 = getIndex(marked_completed, [lat, lng]);
                    if (i1 != -1) {
                        //=> orangeIcon

                    } else if (i2 != -1) {
                        //=> greenIcon

                    } else {
                        //=> redIcon

                    }
                    //change marker and tooltip size
                    //first get the marker object
                    // let marker = markers[getMarkerIndex([lat, lng], markers)];
                    // let info_marker = marker._icon.width;
                    // console.log(marker._icon);
                    // console.log(marker._icon.style.width);
                    // marker._icon.style.width = "5em";
                    // marker._icon.style.height = "5em";
                    */
                    var tooltip = $('.leaflet-tooltip');
                    tooltip.css('font-size', 25);

                },
                error: function(error) {
                    console.log(error.responseText)
                }

            });

        })
        let markers = []; //marker array
        let circles = []; //circles array
        let ajaxresult = []; //result of ajax request
        let districts = []; //districts we have after processing the result
        let marked_inProgress = [];
        let marked_completed = [];


        /*-----------------------------------------------------------------------------------------------------------------*/
        async function getDistrictsData() {
            let result;
            result = await $.ajax({
                type: 'GET',
                contentType: 'application/json',
                url: 'getDistrictsData.php',
                success: function(data) {
                    for (let item of data.centroids79districts) {
                        ajaxresult.push(item);
                    }
                    ajaxresult.shift();
                    ajaxresult.forEach(function(elt) {
                        districts.push([elt.latitude, elt.longitude, elt.district_sanitaire]);

                    })
                    //add many markers using a loop 
                    districts.forEach(function(point) {
                        let marker = L.marker([point[0], point[1]], {
                            icon: redIcon
                        });
                        marker.bindTooltip(point[2], {
                            permanent: true,
                            className: "my-label",
                            offset: [0, 0],
                            direction: 'bottom'
                        });
                        marker.addTo(map).bindPopup("<div style='line-height:25px; font-size:1.2em;'>" + "<span>Etat du déploiement: </span> <span style='font-weight:bold; color:#EA2027;'>pas encore commencé</span>" + "<br>" + "<span accesskey='district'>District: " + point[2] + "</span>" +
                            "<br>" +
                            "<div style='text-align:center; margin-top:5%;'><button style='background-color:#00a8ff;color:#fff; border-radius:5px; ' type='button' class='btn commencer'>Commencer</button></div>" + "</div>");
                        markers.push(marker);

                    });
                    //return markers;
                },
                error: function(error) {
                    console.log(error);
                },

            });

            return markers;
        }

        //ajax1
        async function getMarkedInProgess() {
            let ajax1Result;
            ajax1Result = $.ajax({
                type: 'GET',
                contentType: 'application/json',
                url: 'getMarked_inProgress.php',
                success: function(data) {
                    result = [];
                    if (data.marked_locations.length) {
                        for (let item of data.marked_locations) {
                            result.push(item);
                        }
                        //result.shift();
                        result.forEach(function(elt) {
                            marked_inProgress.push([elt.latitude, elt.longitude, elt.district]);
                        })
                    }
                    console.log("data loaded");
                    return marked_inProgress;
                },
                error: function(error) {
                    console.log(error)

                }

            });
            return marked_inProgress;
        }
        //ajax2
        async function getMarkedCompleted() {
            let ajax2Result;
            ajax2Result = $.ajax({
                type: 'GET',
                contentType: 'application/json',
                url: 'getMarked_completed.php',
                success: function(data) {
                    result = [];
                    if (data.marked_locations.length) {
                        for (let item of data.marked_locations) {
                            result.push(item);
                        }
                        //result.shift();
                        result.forEach(function(elt) {
                            marked_completed.push([elt.latitude, elt.longitude, elt.district]);

                        })
                    }
                    console.log("data loaded");
                },
                error: function(error) {
                    console.log(error)
                }

            });
            return marked_completed;
        }
        const ajaxResults = () => {
            $.when(getDistrictsData(), getMarkedInProgess(), getMarkedCompleted()).then(function(markers, marked_inProgress, marked_completed) {

                //logic to change the marker icon 
                //execute every 0.5s
                function runLogic() {
                    setTimeout(function() {
                        if (marked_inProgress.length != 0) {
                            marked_inProgress.forEach(function(item) {
                                //TODO chercher l'index du marker de latitude item[0] et de longitude item[1] puis le supprimer
                                // let index=getMarkerIndex([item[0],item[1]],markers);
                                // if(index!=-1){
                                //     map.removeLayer(markers[index]);
                                // }
                                let m = L.marker([item[0], item[1]], {
                                    icon: orangeIcon
                                }).addTo(map).bindPopup("<span style='font-size:1.3em;'>Etat du déploiement: </span>" + "<span style='font-size:1.3em; font-weight:bold; color:#F79F1F;'>en cours</span>" + "<br><br>" +
                                    "<div style='line-height:25px; font-size:1.2em;'>" +
                                    "<span class='lat'>latitude: " + item[0] + "</span>" + "<br>" + "<span class='lng'>longitude: " + item[1] + "</span>" + "<br>" +
                                    "<span> District: " + item[2] + "</span>" + "<br>" +
                                    "<a href='#' class='details' data-bs-toggle='modal' data-bs-target='#infoModal' style=''>Détails <img style='transform:scale(0.9);' src='./icon/info.png'></a>" + "<br>" +
                                    "<div style='text-align:center; margin-top:5%; display:flex; flex-direction:row;'>" +
                                    "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn inProgress'>Annuler</button>" +
                                    "<button  style='background-color:#44bd32;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn'>terminé</button>" +
                                    "</div>" +
                                    "</div>", {
                                        minWidth: 200 // Largeur minimale 
                                    });
                                markers.push(m);
                            });
                        }
                        if (marked_completed.length != 0) {
                            marked_completed.forEach(function(item) {
                                //TODO chercher l'index du marker de latitude item[0] et de longitude item[1] puis le supprimer
                                // let index=getMarkerIndex([item[0],item[1]],markers);
                                // if(index!=-1){
                                //     map.removeLayer(markers[index]);
                                // }
                                let m = L.marker([item[0], item[1]], {
                                    icon: greenIcon
                                }).addTo(map).bindPopup("<span style='font-size:1.3em;'>Etat du déploiement: </span>" + "<span style='font-size:1.3em; font-weight:bold; color:#44bd32;'>terminé </span>" + "<br><br>" +
                                    "<div style='line-height:25px; font-size:1.2em;'>" +
                                    "<span class='lat'>latitude: " + item[0] + "</span>" + "<br>" + "<span class='lng'>longitude: " + item[1] + "</span>" + "<br>" +
                                    "<span> District: " + item[2] + "</span>" + "<br>" +
                                    "<a href='#' class='details' data-bs-toggle='modal' data-bs-target='#infoModal' style=''>Détails <img style='transform:scale(0.9);' src='./icon/info.png'></a>" + "<br>" +
                                    "<div style='text-align:center; display:flex; margin-top:5%; justify-content:center; flex-direction:row;'>" +
                                    "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn completed'>Annuler</button>" +
                                    "</div>" +
                                    "</div>", {
                                        minWidth: 200 // Largeur minimale 
                                    });
                                markers.push(m);
                            });
                        }
                    }, 500);
                }

                runLogic();


                document.addEventListener("click", function() {
                    let elt = document.activeElement;
                    if (elt.textContent.toLowerCase() == "terminé") {
                        btn = elt;
                        //catch the click event on the buttons
                        btn.addEventListener("click", function(e) {
                            map.closePopup();
                            //we get coodinates
                            let district_name = btn.parentElement.parentElement.children[4].textContent.split(":")[1].trim();
                            console.log(district_name);
                            let coordinates = getCoordinates(district_name, districts);
                            let current_lat = coordinates[0];
                            let current_lng = coordinates[1];
                            //TODO chercher l'index du marker de latitude item[0] et de longitude item[1] puis le supprimer
                            let index = getMarkerIndex([current_lat, current_lng], markers);
                            if (index != -1) {
                                map.removeLayer(markers[index]);
                            }
                            //change the icon to green
                            let m = L.marker([current_lat, current_lng], {
                                icon: greenIcon
                            }).addTo(map).bindPopup("<span style='font-size:1.3em;'>Etat du déploiement: </span>" + "<span style='font-size:1.3em; font-weight:bold; color:#44bd32;'>terminé </span>" + "<br><br>" +
                                "<div style='line-height:25px; font-size:1.2em;'>" +
                                "<span class='lat'>latitude: " + current_lat + "</span>" + "<br>" + "<span class='lng'>longitude: " + current_lng + "</span>" + "<br>" +
                                "<span> District: " + district_name + "</span>" + "<br>" +
                                "<a href='#' class='details' data-bs-toggle='modal' data-bs-target='#infoModal' style=''>Détails <img style='transform:scale(0.9);' src='./icon/info.png'></a>" + "<br>" +
                                "<div style='text-align:center; display:flex; margin-top:5%; justify-content:center; flex-direction:row;'>" +
                                "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn completed'>Annuler</button>" +
                                "</div>" +
                                "</div>", {
                                    minWidth: 200 // Largeur minimale 
                                });
                            m.bindTooltip(district_name, {
                                permanent: true,
                                className: "my-label",
                                offset: [0, 0],
                                direction: 'bottom'
                            });
                            //markers.push(m);
                            if (!contain(marked_completed, [parseFloat(current_lat), parseFloat(current_lng)])) {
                                console.log("adding the point...");
                                marked_completed.push([parseFloat(current_lat), parseFloat(current_lng)]);
                                //store marked locations in the database
                                $.post("completed.php", {
                                    "latitude": marked_completed[marked_completed.length - 1][0],
                                    "longitude": marked_completed[marked_completed.length - 1][1],
                                    "district": district_name
                                }, function(data) {
                                    console.log(data);
                                })
                            }
                            //runLogic();
                        });
                    } else if (elt.textContent.toLowerCase() == "annuler") {
                        btn = elt;
                        btn.addEventListener("click", function(e) {
                            map.closePopup();
                            //we get coodinates
                            let district_name = btn.parentElement.parentElement.children[4].textContent.split(":")[1].trim();
                            let coordinates = getCoordinates(district_name, districts);
                            console.log(coordinates);
                            let current_lat = coordinates[0];
                            let current_lng = coordinates[1];
                            if (btn.classList.contains("inProgress")) {
                                $.post("delete_marked_inProgress.php", {
                                    "latitude": current_lat,
                                    "longitude": current_lng
                                }, function(data) {
                                    console.log(data);
                                })
                                // //TODO chercher l'index du marker de latitude item[0] et de longitude item[1] puis le supprimer
                                // let index=getMarkerIndex([current_lat,current_lng],markers);
                                // if(index!=-1){
                                //     map.removeLayer(markers[index]);
                                // }
                                //change the marker to orange
                                let m = L.marker([current_lat, current_lng], {
                                    icon: redIcon
                                }).addTo(map).bindPopup(
                                    "<div style='line-height:25px; font-size:1.2em;'>" + "<span>Etat du déploiement: </span> <span style='font-weight:bold; color:#EA2027;'>pas encore commencé</span>" + "<br>" + "<span accesskey='district'>District: " + district_name + "</span>" +
                                    "<br>" +
                                    "<div style='text-align:center; margin-top:5%;'><button style='background-color:#00a8ff;color:#fff; border-radius:5px; ' type='button' class='btn commencer'>Commencer</button></div>" + "</div>");
                                m.bindTooltip(district_name, {
                                    permanent: true,
                                    className: "my-label",
                                    offset: [0, 0],
                                    direction: 'bottom'
                                });
                                //markers.push(m);

                                //get index of the point
                                let indice = getIndex(marked_inProgress, [parseFloat(current_lat), parseFloat(current_lng)]);
                                //remove the point occurences to marked points
                                console.log("removing the point...");
                                marked_inProgress.splice(indice, 1);
                                //runLogic();

                            } else if (btn.classList.contains("completed")) {
                                $.post("delete_marked_completed.php", {
                                    "latitude": current_lat,
                                    "longitude": current_lng
                                }, function(data) {
                                    console.log(data);
                                })

                                // //TODO chercher l'index du marker de latitude item[0] et de longitude item[1] puis le supprimer
                                // let index=getMarkerIndex([current_lat,current_lng],markers);
                                // if(index!=-1){
                                //     map.removeLayer(markers[index]);
                                // }
                                //change the marker to orange
                                let m = L.marker([current_lat, current_lng], {
                                    icon: orangeIcon
                                }).addTo(map).bindPopup("<span style='font-size:1.3em;'>Etat du déploiement: </span>" + "<span style='font-size:1.3em; font-weight:bold; color:#F79F1F;'>en cours</span>" + "<br><br>" +
                                    "<div style='line-height:25px; font-size:1.2em;'>" +
                                    "<span class='lat'>latitude: " + current_lat + "</span>" + "<br>" + "<span class='lng'>longitude: " + current_lng + "</span>" + "<br>" +
                                    "<span> District: " + district_name + "</span>" + "<br>" +
                                    "<a href='#' class='details' data-bs-toggle='modal' data-bs-target='#infoModal' style=''>Détails <img style='transform:scale(0.9);' src='./icon/info.png'></a>" + "<br>" +
                                    "<div style='text-align:center; margin-top:5%; display:flex; flex-direction:row;'>" +
                                    "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn inProgress'>Annuler</button>" +
                                    "<button  style='background-color:#44bd32;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn'>terminé</button>" +
                                    "</div>" +
                                    "</div>", {
                                        minWidth: 200 // Largeur minimale 
                                    });
                                m.bindTooltip(district_name, {
                                    permanent: true,
                                    className: "my-label",
                                    offset: [0, 0],
                                    direction: 'bottom'
                                });
                                //markers.push(m);

                                //get index of the point
                                let indice = getIndex(marked_completed, [parseFloat(current_lat), parseFloat(current_lng)]);
                                //remove the point occurences to marked points
                                console.log("removing the point...");
                                marked_completed.splice(indice, 1);
                                //runLogic();


                            }


                        });

                    } else if (elt.textContent.toLowerCase() == "commencer") {
                        btn = elt;
                        btn.addEventListener("click", function(e) {
                            map.closePopup();
                            //we get coodinates
                            let district_name = btn.parentElement.parentElement.children[3].textContent.split(":")[1].trim()
                            let coordinates = getCoordinates(district_name, districts);
                            let current_lat = coordinates[0];
                            let current_lng = coordinates[1];
                            //change the icon to green
                            //TODO chercher l'index du marker de latitude item[0] et de longitude item[1] puis le supprimer
                            //  let index=getMarkerIndex([current_lat,current_lng],markers);
                            //  console.log(index);
                            //  markers.splice(index,1);
                            //  if(index!=-1){
                            //      map.removeLayer(markers[index]);
                            //  }
                            let m = L.marker([current_lat, current_lng], {
                                icon: orangeIcon
                            }).addTo(map).bindPopup("<span style='font-size:1.3em;'>Etat du déploiement: </span>" + "<span style='font-size:1.3em; font-weight:bold; color:#F79F1F;'>en cours</span>" + "<br><br>" +
                                "<div style='line-height:25px; font-size:1.2em;'>" +
                                "<span class='lat'>latitude: " + current_lat + "</span>" + "<br>" + "<span class='lng'>longitude: " + current_lng + "</span>" + "<br>" +
                                "<span> District: " + district_name + "</span>" + "<br>" +
                                "<a href='#' class='details' data-bs-toggle='modal' data-bs-target='#infoModal' style=''>Détails <img style='transform:scale(0.9);' src='./icon/info.png'></a>" + "<br>" +
                                "<div style='text-align:center; margin-top:5%; display:flex; flex-direction:row;'>" +
                                "<button  style='background-color:#F21D00;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn inProgress'>Annuler</button>" +
                                "<button  style='background-color:#44bd32;color:#fff; border-radius:5px; margin-right:20%; display:inline;' type='button' class='btn'>terminé</button>" +
                                "</div>" +
                                "</div>", {
                                    minWidth: 200 // Largeur minimale 
                                });
                            m.bindTooltip(district_name, {
                                permanent: true,
                                className: "my-label",
                                offset: [0, 0],
                                direction: 'bottom'
                            });
                            //markers.push(m);

                            if (!contain(marked_inProgress, [parseFloat(current_lat), parseFloat(current_lng)])) {
                                console.log("adding the point...");
                                marked_inProgress.push([parseFloat(current_lat), parseFloat(current_lng)]);
                                //store marked locations in the database
                                $.post("inProgress.php", {
                                    "latitude": marked_inProgress[marked_inProgress.length - 1][0],
                                    "longitude": marked_inProgress[marked_inProgress.length - 1][1],
                                    "district": district_name
                                }, function(data) {
                                    console.log(data);
                                })
                            }
                            //runLogic();
                        });
                    }
                });

            });

        }
        ajaxResults();
    </script>
    <script src="main.js"></script>
</body>

</html>