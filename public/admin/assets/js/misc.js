(function ($) {
  "use strict";
  $(function () {
    var body = $("body");
    var sidebar = $(".sidebar");
    function addActiveClass(element) {
        var currentUrl = window.location.href.split("?")[0];
        var elementHref = element.prop("href").split("?")[0];        
        
        if (currentUrl === elementHref) {
            $(
                ".sidebar .nav-item, .sidebar .sub-menu .nav-link"
            ).removeClass("active");
            element.parents(".nav-item").last().addClass("active");

            if (element.parents(".sub-menu").length) {
                element.closest(".collapse").addClass("show");
                element.addClass("active");
            }
        }
    }
    $(".nav li a", sidebar).each(function () {
        var $this = $(this);
        addActiveClass($this);
    });

    $(".horizontal-menu .nav li a").each(function () {
        var $this = $(this);
        addActiveClass($this);
    });

    sidebar.on("show.bs.collapse", ".collapse", function () {
        sidebar.find(".collapse.show").collapse("hide");
    });
    applyStyles();
    function applyStyles() {
        if (!body.hasClass("rtl")) {
            if (
                $(".settings-panel .tab-content .tab-pane.scroll-wrapper")
                    .length
            ) {
                const settingsPanelScroll = new PerfectScrollbar(
                    ".settings-panel .tab-content .tab-pane.scroll-wrapper"
                );
            }
            if ($(".chats").length) {
                const chatsScroll = new PerfectScrollbar(".chats");
            }
            if (body.hasClass("sidebar-fixed")) {
                var fixedSidebarScroll = new PerfectScrollbar(
                    "#sidebar .nav"
                );
            }
        }
    }
    $('[data-toggle="minimize"]').on("click", function () {
        if (
            body.hasClass("sidebar-toggle-display") ||
            body.hasClass("sidebar-absolute")
        ) {
            body.toggleClass("sidebar-hidden");
        } else {
            body.toggleClass("sidebar-icon-only");
        }
    });
    $(".form-check label,.form-radio label").append(
        '<i class="input-helper"></i>'
    );

    $("#fullscreen-button").on("click", function toggleFullScreen() {
        if (
            (document.fullScreenElement !== undefined &&
                document.fullScreenElement === null) ||
            (document.msFullscreenElement !== undefined &&
                document.msFullscreenElement === null) ||
            (document.mozFullScreen !== undefined &&
                !document.mozFullScreen) ||
            (document.webkitIsFullScreen !== undefined &&
                !document.webkitIsFullScreen)
        ) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                document.documentElement.webkitRequestFullScreen(
                    Element.ALLOW_KEYBOARD_INPUT
                );
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    });
  });
})(jQuery);