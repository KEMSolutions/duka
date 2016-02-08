var safariPrivateBrowsing = {


    detectSafariPrivateBrowsing: function () {
        try {
            localStorage.test = "";
        }
        catch (e) {
            alert(Localization.private_browsing_alert);
        }
    },

    init: function () {
        var self = safariPrivateBrowsing;

        self.detectSafariPrivateBrowsing();
    }
}