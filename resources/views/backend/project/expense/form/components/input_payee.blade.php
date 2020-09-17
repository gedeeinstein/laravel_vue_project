<multiselect v-model="{{ $model }}"
    :options="master.payee" placeholder="サジェスト機能"
    class="expense-suggest"
    :close-on-select="true" select-label="" deselect-label label="name"
    selected-label="" track-by="name"
>
</multiselect>

{{ $slot }}