let totalPoints = 100
let batsmenCount = 0
let bowlersCount = 0
let allRoundersCount = 0
const maxBatsmen = 5
const maxBowlers = 4
const maxAllRounders = 2
const maxPlayers = 11

function selectPlayer (playerId, points, type) {
  const checkbox = document.getElementById(playerId)
  if (checkbox.checked) {
    if (totalPoints - points < 0) {
      alert('You do not have enough points.')
      checkbox.checked = false
      return
    }
    if (batsmenCount + bowlersCount + allRoundersCount >= maxPlayers) {
      alert('You can select a maximum of 11 players.')
      checkbox.checked = false
      return
    }
    if (type === 'batsman' && batsmenCount >= maxBatsmen) {
      alert('You can select a maximum of 5 batsmen.')
      checkbox.checked = false
      return
    }
    if (type === 'bowler' && bowlersCount >= maxBowlers) {
      alert('You can select a maximum of 4 bowlers.')
      checkbox.checked = false
      return
    }
    if (type === 'allrounder' && allRoundersCount >= maxAllRounders) {
      alert('You can select a maximum of 2 all-rounders.')
      checkbox.checked = false
      return
    }

    totalPoints -= points
    if (type === 'batsman') batsmenCount++
    if (type === 'bowler') bowlersCount++
    if (type === 'allrounder') allRoundersCount++
  } else {
    totalPoints += points
    if (type === 'batsman') batsmenCount--
    if (type === 'bowler') bowlersCount--
    if (type === 'allrounder') allRoundersCount--
  }

  document.getElementById('pointsLeft').innerText = totalPoints
  document.getElementById('teammembers').innerText = batsmenCount + bowlersCount + allRoundersCount
}

function validateTeam () {
  if (batsmenCount + bowlersCount + allRoundersCount !== maxPlayers || totalPoints < 0) {
    alert('You must select exactly 11 players and stay within the points limit.')
    return false
  }
  return true
}
