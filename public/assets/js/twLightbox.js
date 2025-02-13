// The views are located in the popup foler

function resetTypeWorkout() {
    $('.input_container').show();
    $('.typeOfWorkout').removeClass("typeOfWorkout-activated");
    $('.workoutsLibrary').hide();
}

function lightBox() {
    $(".lightBox").addClass("lightBox-activated");
    $(".popup_container").addClass("popup_container-activated");
    $(".lightbox_mask").addClass("lightbox_mask-activated");
    $("body").addClass('no_scroll_overlay');
    var div = $('.sharewokoutform');
    div.attr("workout",selectedItems.join(","));

}

function hidelightbox(e) {
    if (e.target == $('.lightBox')[0]){
        resetTypeWorkout();
        $(".lightBox").removeClass("lightBox-activated");
        $(".popup_container").removeClass("popup_container-activated");
        $(".lightbox_mask").removeClass("lightbox_mask-activated");
        $("body").removeClass('no_scroll_overlay');
    }
}

function hidelightboxWithoutE() {
        $(".lightBox").removeClass("lightBox-activated");
        $(".popup_container").removeClass("popup_container-activated");
        $(".lightbox_mask").removeClass("lightbox_mask-activated");
        $("body").removeClass('no_scroll_overlay');
}
