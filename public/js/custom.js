particlesJS.load("particles", "/js/particles.json");

var clipboard = new Clipboard(".clipboard");

clipboard.on("success", function(event) {
    event.clearSelection();
    toastr.success("PGP Key Has Been Copied!");
});

clipboard.on("error", function(event) {
    event.clearSelection();
    toastr.error("Unable to copy PGP Key!");
});

$("#message_body").maxlength({
    threshold: 6,
    alwaysShow: true,
    warningClass: "m-badge m-badge--rounded m-badge--wide m--margin-top-5",
    limitReachedClass:
        "m-badge m-badge--danger m-badge--rounded m-badge--wide m--margin-top-5",
    appendToParent: true,
    separator: " of ",
    preText: "You have ",
    postText: " chars",
    validate: true
});

$("#send-btn").on("click", function() {
    $(this)
        .removeClass()
        .addClass(
            "btn btn-block btn-focus m-btn m-btn--wide m-btn--air modal-btn m-loader m-loader--light"
        )
        .html("");
});

$("#get-started").hover(function() {
    $("#btn-icon").css("padding-left", "5px");
});

$("#get-started").mouseleave(function() {
    $("#btn-icon").css("padding-left", "0px");
});

$("#add-new-file").on("click", function(event) {
    event.preventDefault();
});

$("#message-body").maxlength({
    threshold: 6,
    alwaysShow: true,
    warningClass: "m-badge m-badge--rounded m-badge--wide m--margin-top-5",
    limitReachedClass:
        "m-badge m-badge--danger m-badge--rounded m-badge--wide m--margin-top-5",
    appendToParent: true,
    separator: " of ",
    preText: "You have ",
    postText: " chars",
    validate: true
});
