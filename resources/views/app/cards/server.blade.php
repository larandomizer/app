<stat name="uptime" icon="clock" :title="serverUptime" subtitle="Server Uptime">
    <slot>
        <a class="dropdown-item d-flex" href="#" @click="serverRestart">
            <i class="mdi dropdown-icon mdi-autorenew"></i>
            <span class="d-inline-flex align-items-center">Restart Server</span>
        </a>
        <a class="dropdown-item d-flex" href="#" @click="serverStop">
            <i class="mdi dropdown-icon mdi-stop"></i>
            <span class="d-inline-flex align-items-center">Stop Server</span>
        </a>
    </slot>
</stat>
