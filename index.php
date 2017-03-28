<?php 
$conn_string = "host=stampy.db.elephantsql.com port=5432 dbname=fzntsbqt user=fzntsbqt password=CtkEjRcQCIEQXh276-mINCY5h_g6yNUE";
$dbconn = \pg_connect($conn_string);
//simple check
$conn = \pg_connect($conn_string);
$result = \pg_query($conn, "SELECT data->'name' AS name, data->'keybase' AS keybase, data->'email' AS email FROM People");
highlight_string("<?php\n\$data =\n" . var_export(\pg_fetch_all($result), true) . ";\n?>");

echo "\r\n";
