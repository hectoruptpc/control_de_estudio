$(".dropdown-toggle").on("mouseenter", function () {
    // make sure it is not shown:
    if (!$(this).parent().hasClass("show")) {
        $(this).click();
    }
});


$(".btn-group, .dropdown").on("mouseleave", function () {
    // make sure it is shown:
    if ($(this).hasClass("show")){
        $(this).children('.dropdown-toggle').first().click();
    }
});


$(document).ready(function () {
    var links = $('.navbar ul li a');
    $.each(links, function (key, va) {
        if (va.href == document.URL) {
            $(this).addClass('active');
        }
    });
});


function mayus(e) {
    const start = e.selectionStart;
    const end = e.selectionEnd;
    e.value = e.value.toUpperCase();
    e.selectionStart = start;
    e.selectionEnd = end;
  }