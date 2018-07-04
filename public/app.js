new Vue({
    el: '#tictactoe',
    data: {
        boardState: [],
        gameId: undefined,
        playerUnit: undefined,
        otherPlayerUnit: undefined,
        nextMoveUnit: undefined,
        forceStateUpdate: 'u', // https://github.com/vuejs/Discussion/issues/356#issuecomment-336060875
    },
    methods: {
        play(index) {
            if (this.isOccupied(index)) return
            if (!this.isYourTurn()) return alert('Not your turn to play')

            this.boardState[index] = this.playerUnit
            this.nextMoveUnit = this.otherPlayerUnit

            let baseApiUrl = `/api/game/${this.gameId}`

            axios.post(baseApiUrl, {position: index+1})
                .then(res => {
                    if (res.data.status.status === "IN_PROGRESS") {
                        axios.post(baseApiUrl + '/autoplay').then(resBot => {
                            let lastPlay = resBot.data.game.history.slice(-1)[0]

                            this.nextMoveUnit = this.playerUnit
                            this.boardState[lastPlay.position - 1] = this.otherPlayerUnit
                        }).catch(error => console.error({error}) )
                    }
                }).catch(e => console.error(e))
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
        this.playerUnit = game.unit
        this.boardState = _.flatten(game.state)
        this.otherPlayerUnit = (game.unit == 'X' ? 'O' : 'X')
        this.nextMoveUnit = elem.getAttribute('data-next-player')
    },
})
