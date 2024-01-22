<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/main.css">
</head>

<body>
    <?
        $tournamentId = 17516;
        require_once './database.php';
        $bracket = $mysql->query("SELECT * FROM tournaments_brackets WHERE tournament_id = $tournamentId")->fetch_all(MYSQLI_ASSOC);
        $teamsCount = 0;

        foreach($bracket as $item) {
            if($item['tour_number'] == 1) {
                $teamsCount+= 2;
            }
        }

        

        $clubs = $mysql->query("SELECT club_id, name FROM clubs")->fetch_all(MYSQLI_ASSOC);
        foreach($clubs as &$club) {
            $club['name'] = str_replace(['"', "'"], '', $club['name']);

            foreach($bracket as &$item) {
                if($item['team_home_id'] == $club['club_id']) {
                    $item['home_name'] = $club['name'];
                }

                if($item['team_away_id'] == $club['club_id']) {
                    $item['away_name'] = $club['name'];
                }
            }
        }

        // dd($bracket);

        $bracketJson = json_encode($bracket);
        $clubsJson = json_encode($clubs);
    ?>
    <div style="margin-bottom:100px;">
        <button id="bracket__create" type="button">Создать сетку</button>
        <select id="selector__numbers__teams">
            <option value="2">2</option>
            <option value="4">4</option>
            <option value="8">8</option>
            <option value="16">16</option>
            <option value="32">32</option>
        </select>
    </div>

    <div id="app"></div>

    <div id="matches__list">
        <h2>Список матчей на турнире</h2>
        <?
        
        $tournamentMatches = $mysql->query("SELECT calendar.*, away_club.name as away_name, home_club.name as home_name FROM calendar LEFT JOIN clubs as home_club ON calendar.home_id = home_club.club_id LEFT JOIN clubs as away_club ON calendar.away_id = away_club.club_id WHERE calendar.tournament_id = $tournamentId")->fetch_all(MYSQLI_ASSOC);
        
        ?>

        <?foreach($tournamentMatches as $tourMatch):?>
            <div class="match__list-item">
                
                <div class="match__list-item-team">
                    <div class="flex flex-column team__section">
                        <span class="team__name"><?= $tourMatch['home_name'] ?></span>
                        <span>ID: <?= $tourMatch['home_id'] ?></span>
                    </div>
                    <div class="team__logo"></div>
                </div>

                <span class="match__list-id">Матч <br><span class="match__list-id-span"><?= $tourMatch['match_id'] ?></span></span>

                <div class="match__list-item-team">
                    <div class="team__logo team__logo__two"></div>
                    <div class="flex flex-column team__section">
                        <span class="team__name"><?= $tourMatch['away_name'] ?></span>
                        <span>ID: <?= $tourMatch['away_id'] ?></span>
                    </div>
                </div>
            </div>
        <?endforeach;?>
    </div>

    <script>
        let bracketRepeat = true
        let clubs = JSON.parse('<?= $clubsJson ?>');
        const tournamentId = <?= $tournamentId ?>;
    </script>
    <script src="./js/main.js"></script>

    <?if($teamsCount > 0):?>
        <script>
            bracketRepeat = false
            let bracket = JSON.parse('<?= $bracketJson ?>');
            tournamentBracket(<?= $teamsCount ?>, bracket)

            let matches = document.querySelectorAll('.match')

            matches.forEach(match => {
                let teams = match.querySelectorAll('.team__input')
                for(let i = 0; i < bracket.length; i++) {
                    if(!match.hasAttribute('data-champion')) {
                        match.setAttribute('data-match-id', bracket[i].match_id)
                    }
                    
                    if(parseInt(match.getAttribute('data-tour')) === parseInt(bracket[i].tour_number) && parseInt(match.getAttribute('data-number')) === parseInt(bracket[i].match_number)) {
                        teams.forEach(team => { // Установить input командам и навесить класс с атрибутами
                            if(team.hasAttribute('data-home-id')) {
                                team.setAttribute('data-home-id', bracket[i].team_home_id)
                            } else {
                                team.setAttribute('data-away-id', bracket[i].team_away_id)
                            }

                            if(team.hasAttribute('data-home-id') && bracket[i].home_name) {
                                team.value = bracket[i].home_name
                                team.classList.add('team__selected')
                                team.setAttribute('readonly', true)
                            } else if(team.hasAttribute('data-away-id') && bracket[i].away_name) {
                                team.value = bracket[i].away_name
                                team.classList.add('team__selected')
                                team.setAttribute('readonly', true)
                            }
                        })
                        break
                    }
                }
            })

            let selectedTeams = document.querySelectorAll('.team__selected')
            
            selectedTeams.forEach(selTeam => {
                selTeam.addEventListener('click', function() {
                    selTeam.classList.remove('team__selected')
                    selTeam.removeAttribute('readonly')
                    selTeam.value = ''
                    if(selTeam.hasAttribute('data-home-id')) {
                        selTeam.setAttribute('data-home-id', null)
                    } else {
                        selTeam.setAttribute('data-away-id', null)
                    }
                })
            })
            
        </script>
    <?endif?>
</body>
</html>