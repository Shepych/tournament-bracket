<?php

$postData = json_decode(file_get_contents("php://input"), true);

$matches = $postData['matches'];
$tournamentId = intval($postData['tournament_id']);
require_once './database.php';
$tournamentInfo = $mysql->query("SELECT * FROM tournaments WHERE tournament_id = $tournamentId")->fetch_assoc();
$tournamentMatches = $mysql->query("SELECT * FROM calendar WHERE tournament_id = $tournamentId")->fetch_all(MYSQLI_ASSOC);

# Создать массив с ID матчей в турнире и сверять $item['team_home_id и team_away_id'] с этим массивом. Если нету - тогда создаём матч и получаем его ID
# Если есть - тогда сразу забираем ID.

$mysql->begin_transaction();
$query = "DELETE FROM tournaments_brackets WHERE tournament_id = $tournamentId";
$mysql->query($query);
try {
    foreach($matches as &$item) {
        // Если чемпион, то матч не создаётся.
        $matchId = 'null';
        $matchFoundedInTournament = false;

        foreach($tournamentMatches as $tourMatch) {
            if($tourMatch['match_id'] == $item['match_id']) {
                $matchId = $tourMatch['match_id'];
                $matchFoundedInTournament = true;
                break;
            }

            if($tourMatch['home_id'] == $item['team_home_id'] && $tourMatch['away_id'] == $item['team_away_id']) {
                # Взять ID матча
                $matchId = $tourMatch['match_id'];
                $matchFoundedInTournament = true;
                break;
            }
        }

        if(!$matchFoundedInTournament && $tournamentInfo && !$item['is_champion']) {
            $matchData = array();

            if(!isset($item['team_home_id']) || !$item['team_home_id'] || $item['team_home_id'] == 'null') {
                $teamHomeId = 0;
            } else {
                $teamHomeId = $item['team_home_id'];
            }

            if(!isset($item['team_away_id']) || !$item['team_away_id'] || $item['team_away_id'] == 'null') {
                $teamAwayId = 0;
            } else {
                $teamAwayId = $item['team_away_id'];
            }

            $matchData['league_id'] = $tournamentInfo['league_id'];
            $matchData['division_id'] = $tournamentInfo['division_id'];
            $matchData['season_id'] = $tournamentInfo['season_id'];
            $matchData['tournament_id'] = $tournamentInfo['tournament_id'];
            $matchData['tour'] = $item['match_tour'];
            $matchData['match_number'] = $item['match_number'];
            $matchData['home_id'] = $teamHomeId;
            $matchData['away_id'] = $teamAwayId;
            $matchData['checked'] = 0;
            $matchData['home_score'] =  'null';
            $matchData['away_score'] =  'null';
            $matchData['home_points'] = 'null';
            $matchData['away_points'] = 'null';
            $matchData['match_date_time '] = 'null';
            $matchData['referee_id'] = 0;
            $matchData['stadium_id'] = 0;
            $matchData['home_formation'] = 0;
            $matchData['away_formation'] = 0;
            $matchData['home_shirt'] = "''";
            $matchData['away_shirt'] = "''";
            $matchData['home_shirt_keeper'] = "''";
            $matchData['away_shirt_keeper'] = "''";
            $matchData['match_cast'] = 'null';
            $matchData['protocol'] = 0;
            $matchData['next_match'] = 0;
            $matchData['last_edit_date'] = 'null';
            $matchData['edited_by_id'] = 0;
            $matchData['note'] = 'null';
            $matchData['comment'] = 'null';
            $matchData['technical_defeat'] = 0;
            $matchData['in_archive'] = 0;
            $matchData['gallery_link'] = 'null';
            $matchData['show_stats'] = 0;
            $matchData['show_empty_cells'] = 0;
            $matchData['home_after_penalties'] = 'null';
            $matchData['away_after_penalties'] = 'null';
            $res = $mysql->query("INSERT INTO calendar 
            (league_id,division_id,season_id,tournament_id,tour,match_number,home_id,away_id,checked,home_score,away_score,home_points,away_points,match_date_time,referee_id,stadium_id,home_formation,away_formation,home_shirt,away_shirt,home_shirt_keeper,away_shirt_keeper,match_cast,protocol,next_match,last_edit_date,edited_by_id,note,comment,technical_defeat,in_archive,gallery_link,show_stats,show_empty_cells,home_after_penalties,away_after_penalties) 
            VALUES (
                {$matchData['league_id']},
                {$matchData['division_id']},
                {$matchData['season_id']},
                {$matchData['tournament_id']},
                {$matchData['tour']},
                {$matchData['match_number']},
                {$matchData['home_id']},
                {$matchData['away_id']},
                {$matchData['checked']},
                {$matchData['home_score']},
                {$matchData['away_score']},
                {$matchData['home_points']},
                {$matchData['away_points']},
                {$matchData['match_date_time ']},
                {$matchData['referee_id']},
                {$matchData['stadium_id']},
                {$matchData['home_formation']},
                {$matchData['away_formation']},
                {$matchData['home_shirt']},
                {$matchData['away_shirt']},
                {$matchData['home_shirt_keeper']},
                {$matchData['away_shirt_keeper']},
                {$matchData['match_cast']},
                {$matchData['protocol']},
                {$matchData['next_match']},
                {$matchData['last_edit_date']},
                {$matchData['edited_by_id']},
                {$matchData['note']},
                {$matchData['comment']},
                {$matchData['technical_defeat']},
                {$matchData['in_archive']},
                {$matchData['gallery_link']},
                {$matchData['show_stats']},
                {$matchData['show_empty_cells']},
                {$matchData['home_after_penalties']},
                {$matchData['away_after_penalties']}
            )");
            
            # Создаём чистый матч с данными которые имеются
            $matchId = $mysql->insert_id;
            if($item['is_champion']) {
                $matchId = 'null';
            }
        }

        # Создание сетки

        $item['team_home_id'] = intval($item['team_home_id']) ? (int) $item['team_home_id'] : 'null';
        $item['team_away_id'] = intval($item['team_away_id']) ? (int) $item['team_away_id'] : 'null';
        if($item['is_champion']) {
            $item['is_champion'] = 1;
        } else {
            $item['is_champion'] = 0;
        }
        
        $query = "INSERT INTO tournaments_brackets (tournament_id, tour_number, match_number, match_id, team_home_id, team_away_id, is_champion) VALUES ($tournamentId, " . intval($item['match_tour']) . ", " . intval($item['match_number']) . ", $matchId, " . intval($item['team_home_id']) . ", " . intval($item['team_away_id']) . ", " . intval($item['is_champion']) . ")";
        
        var_dump($query);
        $mysql->query($query);
        $mysql->commit();
    }
    
    $status = true;
    $message = "Сохранено";
} catch (Exception $e) {
    $mysql->rollback();

    // $status = false;
    // $message = "Ошибка: " . $e->getMessage();
}

$mysql->close();

# Берём все данные из js и заполняем ими БД
echo json_encode([
    'status' => $status,
    'message' => $message,
    'result' => $matches
]);