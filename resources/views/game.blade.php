@extends('layout')
@section('content')
<div id="tictactoe" data-id="{{ $game->id }}" data-game='@json($game->toArray())' data-next-player="{{ $nextPlayer }}">
    @{{ JSON.stringify(boardState) }}
    <div class="grid-container" :class="{show: boardState.length === 9}">
        <div
            class="grid"
            :key="forceStateUpdate + index"
            v-for="(unit, index) in boardState"
            v-on:click="play(index)"
            v-bind:disabled="!isYourTurn()"
            v-bind:class="{occupied: isOccupied(index)}"
        >
            @{{ unit }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script src="{{ url('app.js') }}"></script>
@endsection
