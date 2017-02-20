<stat name="prizes" icon="trophy-variant-outline" :title="prizesStatus" subtitle="Prizes Available">
    <slot>
        <a class="dropdown-item d-flex" href="#" @click="prizeNew">
            <i class="mdi dropdown-icon mdi-plus"></i>
            <span class="d-inline-flex align-items-center">Add New Prize</span>
        </a>
        <a class="dropdown-item d-flex" href="#" @click="prizeWinner">
            <i class="mdi dropdown-icon mdi-trophy-variant-outline"></i>
            <span class="d-inline-flex align-items-center">New Winner</span>
        </a>
        <a class="dropdown-item d-flex" href="#" @click="prizeReset">
            <i class="mdi dropdown-icon mdi-refresh"></i>
            <span class="d-inline-flex align-items-center">Reset Prizes</span>
        </a>
    </slot>
</stat>
