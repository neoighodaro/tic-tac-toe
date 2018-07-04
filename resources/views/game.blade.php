@extends('layout')
@section('content')
<div id="tictactoe" data-game='@json($game->toArray())' data-status='@json($status)'>
    <div class="alert" v-if="gameStatus.status !== 'IN_PROGRESS'">
        <div :class="{success: (gameStatus.status === 'GAME_OVER'), tie: (gameStatus.status === 'TIE')}">
            <span v-if="gameStatus.status === 'TIE'">It's a tie</span>
            <span v-else>Player "@{{ gameStatus.winner }}" is the winner</span>
        </div>
    </div>
    <div class="grid-container" :class="{show: boardState.length === 9}">
        <div
            class="grid"
            :key="forceUpdate + index"
            v-for="(unit, index) in boardState"
            v-on:click="play(index)"
            v-bind:disabled="!isYourTurn()"
            v-bind:class="{occupied: isOccupied(index)}"
        >
            @{{ unit }}
        </div>
    </div>
    <a href="/game/new" class="btn" v-if="gameStatus.status !== 'IN_PROGRESS'">New game</a>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script src="{{ url('app.js') }}"></script>
@endsection
