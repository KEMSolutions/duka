/**
 * This object holds all our helper functions.
 */
var Utilities =
{
    /**
     * Strip HTML tags from a string.
     * @param string html   The string to be stripped.
     * @return string       The stripped result.
     */
    stripTags: function(html) {
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }
};
