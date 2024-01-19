<?

$mysql = new mysqli('localhost', 'root', '', 'lfl');

function dd($data) {
    echo '<div style="background-color:white;padding:10px">';
    echo '<pre style="margin:0">';
    var_dump($data);
    echo '</pre>';
    echo '</div>';
    die;
}