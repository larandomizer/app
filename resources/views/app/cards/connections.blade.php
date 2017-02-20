<stat name="connections" icon="account-multiple" :title="numConnections" subtitle="Connections">
    <slot>
        <a class="dropdown-item d-flex" href="#" @click="disconnectSpectators">
            <i class="mdi mdi-power dropdown-icon"></i>
            <span class="d-inline-flex align-items-center">Disconnect Spectators</span>
        </a>
        <a class="dropdown-item d-flex" href="#" @click="disconnectPlayers">
            <i class="mdi mdi-power dropdown-icon"></i>
            <span class="d-inline-flex align-items-center">Disconnect Players</span>
        </a>
        <a class="dropdown-item d-flex" href="#" @click="disconnect">
            <i class="mdi mdi-power dropdown-icon"></i>
            <span class="d-inline-flex align-items-center">Disconnect All</span>
        </a>
    </slot>
</stat>