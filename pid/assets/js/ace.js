let base_url = window.location.protocol + "//" + window.location.host + window.location.pathname.split("/").slice(0, -1).join("/") + '/libraries/filemanager/ace/';

// setup paths
require.config(
    {
        baseUrl: base_url,
        paths: { "ace" : 'lib/ace'}
    }
);

require(
    [
        "ace/ace",
        "ace/ext/language_tools",
        //"ace/ext/emmet",
    ],
    function(ace) {}
);