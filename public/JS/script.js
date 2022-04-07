$(document).ready(function () {
    $("#searchbar").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".formationTitle").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});


