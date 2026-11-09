(function ($) {
    "use strict";

    // Install + Activate button
    $("#install-activate-button").on("click", function (e) {
        if ( $(this).data("activated") ) {
            return;
        }

        e.preventDefault();

        var button = $(this);
        button.prop("disabled", true)
              .text("Installing & Activating recommended plugins…")
              .addClass("processing-spinner");

        $.post(creative_designer_portfolio_localize.ajax_url, {
            action: "creative_designer_portfolio_install_and_activate_plugins",
            nonce: creative_designer_portfolio_localize.nonce
        }, function (response) {
            if (response.success) {
                window.location.href = creative_designer_portfolio_localize.redirect_url;
            } else {
                button.text(response.data?.message || "Installation failed");
            }
        });
    });

})(jQuery);
