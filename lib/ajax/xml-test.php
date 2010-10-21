<?php

$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>
<pages>
    <page title="Page Alpha" navtitle="Page A">
        <page title="Alpha-1" navtitle="A1" />
        <page title="Alpha-2" navtitle="A2" />
        <page title="Alpha-3" navtitle="A3" />
        <page title="Alpha-4" navtitle="A4" />
    </page>
    <page title="Page Beta" navtitle="Page B">
        <page title="Beta-1" navtitle="B1" />
        <page title="Beta-2" navtitle="B2" />
        <page title="Beta-3" navtitle="B3" />
        <page title="Beta-4" navtitle="B4" />
    </page>
    <page title="Page Charlie" navtitle="Page C">
        <page title="Charlie-1" navtitle="C1" />
        <page title="Charlie-2" navtitle="C2" />
        <page title="Charlie-3" navtitle="C3" />
        <page title="Charlie-4" navtitle="C4" />
    </page>
</pages>';

$json = '[{"text":"pkgs","id":"\/pkgs","cls":"folder"},{"text":"INCLUDE_ORDER.txt","id":"\/INCLUDE_ORDER.txt","leaf":true,"cls":"file"},{"text":"adapter","id":"\/adapter","cls":"folder"},{"text":"examples","id":"\/examples","cls":"folder"},{"text":"docs","id":"\/docs","cls":"folder"},{"text":"ext-all.js","id":"\/ext-all.js","leaf":true,"cls":"file"},{"text":"ext.jsb2","id":"\/ext.jsb2","leaf":true,"cls":"file"},{"text":"license.txt","id":"\/license.txt","leaf":true,"cls":"file"},{"text":"ext-all-debug.js","id":"\/ext-all-debug.js","leaf":true,"cls":"file"},{"text":"resources","id":"\/resources","cls":"folder"},{"text":"src","id":"\/src","cls":"folder"}]';
header ("content-type: text/xml"); 
print $json;
?>