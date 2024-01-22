<?

$postData = json_decode(file_get_contents("php://input"), true);
require_once './database.php';

$tournamentId = $postData['tournament_id'];
$bracket = $mysql->query("SELECT * FROM tournaments_brackets WHERE tournament_id = $tournamentId")->fetch_all(MYSQLI_ASSOC);

$mysql->begin_transaction();
try {
    if($postData['confirm_delete']) {
        foreach($bracket as $item) {
            $mysql->query("DELETE FROM calendar WHERE match_id = " . intval($item['match_id']));
        }
    }

    $mysql->query("DELETE FROM tournaments_brackets WHERE tournament_id = $tournamentId");
    
    $mysql->commit();
    $status = true;
    $message = "Сохранено";
} catch (Exception $e) {
    $mysql->rollback();
}

$mysql->close();