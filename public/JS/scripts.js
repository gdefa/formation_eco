// Seacr Bar index formation

$(document).ready(function () {
    $("#searchbar").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".formationGlobal").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

//