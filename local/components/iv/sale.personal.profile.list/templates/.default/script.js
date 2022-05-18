$(document).ready(() => {
    $("table.ka .toggle").on("click", KAToggle);
});

function KAToggle(e) {
    let el = $("#"+$(this).data("toggle"));
    if (el.length === 1) {
        if (el.hasClass('hidden')) {
            $(this).addClass('toggled');
            el.removeClass('hidden');
        } else {
            $(this).removeClass('toggled');
            el.addClass('hidden');
        }
    }
}