function dropDownMenu() {
  const inputs = document.querySelectorAll('.team__input')
  inputs.forEach(item => {
    item.addEventListener("keyup", function() { // Добавить выпадающее меню
      let dropMenus = document.querySelectorAll('.dropDownMenu')
      dropMenus.forEach(item => {
        item.remove()
      })

      let menu = document.createElement('div')

      // Запрос на список команд
      let searchedTeams = clubs.filter(obj => obj.name.toLowerCase().includes(item.value.toLowerCase()))

      searchedTeams.forEach(club => {
        let clubSpan = document.createElement('span')
        clubSpan.innerText = 'ID: ' + club.club_id + ' ' + club.name
        clubSpan.setAttribute('data-club-id', club.club_id)
        clubSpan.setAttribute('data-club-name', club.name)
        menu.append(clubSpan)
      })

      menu.className = 'dropDownMenu'

      // // Получаем все элементы span внутри блока div
      let spans = menu.getElementsByTagName('span');

      // Навешиваем событие клика на каждый элемент span
      for (let i = 0; i < spans.length; i++) {
          spans[i].addEventListener('click', function() {
              // Добавить в атрибут input
              if(item.hasAttribute('data-home-id')) {
                item.setAttribute('data-home-id', spans[i].getAttribute('data-club-id'))
              } else {
                item.setAttribute('data-away-id', spans[i].getAttribute('data-club-id'))
              }

              item.value = spans[i].getAttribute('data-club-name')
              item.setAttribute('readonly', true)
              item.classList.add('team__selected')
              menu.remove()

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
          });
      }

      if(searchedTeams.length <= 0) {
        let notFoundClubs = document.createElement('span')
        notFoundClubs.innerText = 'Клубы не найдены'
        
        menu.append(notFoundClubs)
      }

      if(item.getAttribute('data-margin')) {
        menu.style.top = '80px'
      }
       
      item.parentNode.insertBefore(menu, item.nextSibling)

      // Добавляем обработчик события на документ для закрытия выпадающего меню
      document.addEventListener('click', function(event) {
        if ((event.target !== item && !item.contains(event.target)) && (event.target !== menu && !menu.contains(event.target))) {
          menu.remove()
        }
      })
    })
  })
}

function columnsCount(teams) {
  let exponent = Math.log(teams) / Math.log(2)
  exponent = Math.floor(exponent)
  return exponent
}

function championColumn(columns) {
  let bracket = document.querySelector('.tournament__bracket') // сетка

  let col = document.createElement('div') // колонка
  col.className = 'column'

  let match = document.createElement('article') // матч
  match.className = 'match'
  match.setAttribute('data-number', 1)
  match.setAttribute('data-tour', columns + 1)
  match.setAttribute('data-match-id', null)
  match.setAttribute('data-champion', true)

  col.appendChild(createMatchWrapper(match, true))

  let team = document.createElement('input') // input
  team.type = 'text'
  team.className = 'team__input'
  
  team.setAttribute('data-champion-id', 'null')
  team.style.borderBottomLeftRadius = '5px'
  team.style.borderBottomRightRadius = '5px'

  match.appendChild(team)
  bracket.appendChild(col)
}

function createMatchWrapper(match, lastCol = false) { // Сделать обёртку для центрирования матчей и добавить в неё матч
  let matchWrapper = document.createElement('div')
  matchWrapper.className = 'match__wrapper'
  let colorLine = "#f0f0f0"
  let widthBorderLine = 2

  let svg = document.createElementNS("http://www.w3.org/2000/svg", "svg")
  svg.setAttribute('width', '40px')
  svg.setAttribute('height', '100%')

  let line = document.createElementNS("http://www.w3.org/2000/svg", "line") // верхний столбик по вертикали
  line.setAttribute('x1', 20)
  line.setAttribute('y1', "25%")
  line.setAttribute('x2', 0)
  line.setAttribute('y2', "25%")
  line.setAttribute('stroke-width', widthBorderLine)
  line.setAttribute('stroke', colorLine)

  let line2 = document.createElementNS("http://www.w3.org/2000/svg", "line") // верхний хвостик
  line2.setAttribute('x1', 20)
  line2.setAttribute('y1', "25%")
  line2.setAttribute('x2', 20)
  line2.setAttribute('y2', '50%')
  line2.setAttribute('stroke-width', widthBorderLine)
  line2.setAttribute('stroke', colorLine)

  let line3 = document.createElementNS("http://www.w3.org/2000/svg", "line") // нижний столбик по вертикали
  line3.setAttribute('x1', 20)
  line3.setAttribute('y1', "75%")
  line3.setAttribute('x2', 0)
  line3.setAttribute('y2', "75%")
  line3.setAttribute('stroke-width', widthBorderLine)
  line3.setAttribute('stroke', colorLine)

  let line4 = document.createElementNS("http://www.w3.org/2000/svg", "line") // нижний хвостик
  line4.setAttribute('x1', 20)
  line4.setAttribute('y1', "75%")
  line4.setAttribute('x2', 20)
  line4.setAttribute('y2', '50%')
  line4.setAttribute('stroke-width', widthBorderLine)
  line4.setAttribute('stroke', colorLine)

  let line5 = document.createElementNS("http://www.w3.org/2000/svg", "line") // хвостик

  line5.setAttribute('x1', 20)
  line5.setAttribute('y1', "50%")
  line5.setAttribute('x2', "100%")
  line5.setAttribute('y2', '50%')
  line5.setAttribute('stroke-width', widthBorderLine)
  line5.setAttribute('stroke', colorLine)

  if(lastCol) {
    line.setAttribute('x1', 20)
    line.setAttribute('y1', "50%")
    line.setAttribute('x2', 0)
    line.setAttribute('y2', "50%")
    line.setAttribute('stroke-width', widthBorderLine)
    line.setAttribute('stroke', colorLine)

    line2.setAttribute('x1', 0)
    line2.setAttribute('y1', 0)
    line2.setAttribute('x2', 0)
    line2.setAttribute('y2', 0)

    line3.setAttribute('x1', 0)
    line3.setAttribute('y1', 0)
    line3.setAttribute('x2', 0)
    line3.setAttribute('y2', 0)

    line4.setAttribute('x1', 0)
    line4.setAttribute('y1', 0)
    line4.setAttribute('x2', 0)
    line4.setAttribute('y2', 0)
  }


  svg.append(line)
  svg.append(line2)
  svg.append(line3)
  svg.append(line4)
  svg.append(line5)


  matchWrapper.append(svg)
  matchWrapper.appendChild(match)
  return matchWrapper
}

function header() {
  let columns = document.querySelectorAll('.column')
  let tourNumber = 0
  columns.forEach((item) => {
    tourNumber++
      let headerCol = document.createElement('div')
      headerCol.className = 'tour__header'
      headerCol.innerText = tourNumber + ' тур'
      if(tourNumber > 1) {
        headerCol.style.paddingLeft = '40px'
      }
      if(columns.length === 2 && tourNumber === 2) {
        headerCol.style.position = 'relative'
        headerCol.style.top = '10px'
      }

      item.prepend(headerCol)
  })
}

function saveButton() {
  // Добавить кнопку сохранения
  const button = document.createElement('button')
  button.innerText = "Сохранить"
  button.addEventListener('click', function () {
    
    // Собрать все данные по матчам
    const matchesBlocks = document.querySelectorAll('.match')
    const matches = []

    // Сохранить data-home-id и data-away-id у внутренних блоков
    matchesBlocks.forEach(item => {
      let teams = item.querySelectorAll('.team__input')
      let teamHomeId = null
      let teamAwayId = null
      teams.forEach(tm => {
        if(tm.hasAttribute('data-home-id')){
          teamHomeId = tm.getAttribute('data-home-id')
        } else {
          teamAwayId = tm.getAttribute('data-away-id')
        }
      })

      let champion = false;
      let champion_id = null;
      if(item.hasAttribute('data-champion')) {
        champion = true;
        champion_id = parseInt(teamHomeId ? teamHomeId : teamAwayId)
      }

      let info = {
        match_tour: item.getAttribute('data-tour'),
        match_number: item.getAttribute('data-number'),
        match_id: item.getAttribute('data-match-id'),
        team_home_id: teamHomeId,
        team_away_id: teamAwayId,
        is_champion: champion,
      }

      matches.push(info)
    })

    confirmResult = false
    if(bracketRepeat) {
      let deleteMatches = confirm("Желаете так же удалить матчи ? (Если нет - то удалится только сетка, а сами матчи останутся в системе)");

      // Проверяем, какой ответ выбрал пользователь
      if (deleteMatches) {
          confirmResult = true
      }
    }

    // Отправить fetch на php скрипт
    let url = '/action.php';
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        matches: matches,
        tournament_id: tournamentId,
        confirm_delete: confirmResult
      })
    })
    .then(response => response.json())
    .then(data => console.log(data))
  })

  document.getElementById('app').append(button)
  
  bracketRepeat = false
}

function deleteButton() {
  const button = document.createElement('button')
  button.innerText = "Удалить"
  button.addEventListener('click', function () {
    let deleteMatches = confirm("Желаете так же удалить матчи ? (Если нет - то удалится только сетка, а сами матчи останутся в системе)");
    let confirmResult = false

    // Проверяем, какой ответ выбрал пользователь
    if (deleteMatches) {
        confirmResult = true
    }

    fetch('/deleteBracket.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        tournament_id: tournamentId,
        confirm_delete: confirmResult
      })
    })
    .then(response => response.json())
    .then(data => console.log(data))
  })

  document.getElementById('app').append(button)
}

const btnCreate = document.getElementById('bracket__create')

btnCreate.addEventListener('click', function() {
  tournamentBracket()
  bracketRepeat = true
})


function tournamentBracket(init = false, data = false) {
  let selectorNumbers = document.getElementById('selector__numbers__teams').value
  if(init) {
    selectorNumbers = init
  }
  
  let setcionClassName = 'tournament__bracket'
  let section =  document.querySelector('.' + setcionClassName)

  if(section) {
    app.innerHTML = ''
  }

  let bracket = document.createElement('section')
  bracket.className = setcionClassName
  let columns = columnsCount(selectorNumbers)
  let matches = selectorNumbers

  for(let i = 0; i < columns; i++) {
    let col = document.createElement('div')
    col.className = 'column'

    if(i == 0) {
      col.className += ' first__stage'

      if(columns == 1) {
        col.style.justifyContent = 'center'
      }
    }

    matches = matches / 2

    for(let j = 0; j < matches; j++) {
      let match = document.createElement('article')
      match.className = 'match'
      match.setAttribute('data-number', j + 1)
      match.setAttribute('data-tour', i + 1)
      match.setAttribute('data-match-id', null)

      for(let k = 0; k < 2; k++) {
        let team = document.createElement('input')
        team.type = 'text'
        team.className = 'team__input'
        
        if(k <= 0) {
          team.setAttribute('data-home-id', null)
        } else {
          team.setAttribute('data-away-id', null)
          team.setAttribute('data-margin', 1)
        }

        match.appendChild(team)
      }   

      if(i != 0) {
        col.appendChild(createMatchWrapper(match))
      } else {
        col.appendChild(match)
      }
    }

    bracket.appendChild(col)
  }
  
  app.appendChild(bracket)
  championColumn(columns)

  header()
  dropDownMenu()
  saveButton()
  deleteButton()
}
