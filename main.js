
document.getElementById("search_district").addEventListener("submit",function(e){
    e.preventDefault();
    //récupérer la valeur du champ et l'envoyer par POST vers search_district.php
    console.log("okk");
})

let district=document.getElementById("district_name");
document.addEventListener("click",function(){
let elt=document.activeElement;
if(elt.classList.contains("details")){
    let district_name=elt.parentElement.children[4].textContent.split(":")[1].trim();
    district.textContent=district_name;
}
});
// let detailElts=document.getElementsByClassName("details");
// console.log(detailElts);

// detailElts.forEach(detail => {
//     detail.addEventListener("click",function(e){
//         console.log(detail);
//     });
// });
