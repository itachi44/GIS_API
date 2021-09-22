

let district = document.getElementById("district_name");
document.addEventListener("click", function () {
    let elt = document.activeElement;
    if (elt.classList.contains("details")) {
        let district_name = elt.parentElement.children[4].textContent.split(":")[1].trim();
        district.textContent = district_name;
    }
});
// let detailElts=document.getElementsByClassName("details");
// console.log(detailElts);

// detailElts.forEach(detail => {
//     detail.addEventListener("click",function(e){
//         console.log(detail);
//     });
// });
