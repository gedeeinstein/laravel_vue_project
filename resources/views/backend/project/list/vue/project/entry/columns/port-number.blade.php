<div class="px-2 py-2">
    <div v-if="entry.port_contract_number">@{{ entry.port_contract_number }}</div>
    <div v-else-if="entry.port_pj_info_number">@{{ entry.port_pj_info_number }}</div>
</div>
