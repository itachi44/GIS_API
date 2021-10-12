let district = document.getElementById("district_name");
document.addEventListener("click", function () {
    let elt = document.activeElement;
    if (elt.classList.contains("details")) {
        let district_name = elt.parentElement.children[4].textContent.split(":")[1].trim();
        district.textContent = district_name;
    }
});


document.getElementById("seeAllBtn").addEventListener("click", function (e) {
    $('#infoModal').modal('hide');
});

let dataSet = [
    ["<a href='#'> Kushina Uzumaki</a>", "2011-03-09"],
    ["<a href='#'>Minato Namikaze</a>", "2009-12-09"]

]
$(document).ready(function () {
    $('#dataTable').DataTable({
        language: {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json",
            searchPlaceholder: "Date ex: 2021-10-02"
        },
        data: dataSet,
        columns: [
            { title: "Informations" },
            { title: "Date" }
        ]
    });
});