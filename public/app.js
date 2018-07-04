new Vue({
    el: '#tictactoe',
    data: {
        boardState: [],
        gameId: undefined,
        playerUnit: undefined,
        gameStatus: undefined,
        otherPlayerUnit: undefined,
        nextMoveUnit: undefined,
        forceUpdate: 'u', // https://github.com/vuejs/Discussion/issues/356#issuecomment-336060875
    },
    methods: {
        play(index) {
            if (this.isOccupied(index)) return
            if (!this.isYourTurn()) return alert('Not your turn to play')

            this.boardState[index] = this.playerUnit
            this.nextMoveUnit = this.otherPlayerUnit

            let makePlay = (url, options, callback) => {
                axios.post(url, options).then(res => {
                    switch (res.data.status.status) {
                        case "TIE":
                        case "GAME_OVER":
                            let play = res.data.game.history.slice(-1)[0]
                            this.gameStatus = res.data.status
                            this.boardState[play.position - 1] = play.unit
                            this.forceUpdate = play.position - 1
                            break;
                        default:
                            callback(res.data)
                            break;
                    }
                })
            }

            let baseApiUrl = `/api/game/${this.gameId}`

            makePlay(baseApiUrl, {position: index+1}, (response) => {
                makePlay(baseApiUrl + "/autoplay", {}, (responseBot) => {
                    let play = responseBot.game.history.slice(-1)[0]
                    this.nextMoveUnit = this.playerUnit
                    this.boardState[play.position - 1] = play.unit
                })
            })
        },

        // --- Validators -----

        isOccupied(index) {
            return this.boardState[index] !== ''
        },

        isYourTurn() {
            return this.nextMoveUnit === this.playerUnit
        },
    },
    created() {
        let elem = document.getElementById('tictactoe')
        let game = JSON.parse(elem.getAttribute('data-game'))

        this.gameId = game.id
        this.gameStatus = JSON.parse(elem.getAttribute('data-status'))
        this.playerUnit = game.unit
        this.boardState = _.flatten(game.state)
        this.otherPlayerUnit = (game.unit == 'X' ? 'O' : 'X')
        this.nextMoveUnit = this.gameStatus.next_player
    },
})
