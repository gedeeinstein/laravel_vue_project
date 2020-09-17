<div class="toggle-controls d-print-none">
    <button type="button" class="btn btn-sm px-2" @click="$store.commit('togglePrint')"
        :class="$store.state.print ? 'btn-info': 'btn-outline-info'">
        <span v-if="$store.state.print">社員印刷画面</span>
        <span v-else>銀行用印刷画面</span>
    </button>
</div>