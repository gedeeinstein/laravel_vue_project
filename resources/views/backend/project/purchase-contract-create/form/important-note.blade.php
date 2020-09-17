<div class="tab-pane fade" id="tab-note" role="tabpanel" aria-labelledby="profile-tab">
    <important-note-real-estate v-model="entry" :target="target"></important-note-real-estate>

    <important-note-seller-and-occupancy v-model="entry" :target="target"
    :contractors_name="contractors_name" :contractors_and_owners_name="contractors_and_owners_name">
    </important-note-seller-and-occupancy>

    <important-note-directly-related v-model="entry" :purchase_sale="purchase_sale"
    :master_value="master_value" :residentials="residentials" :roads="roads">
    </important-note-directly-related>

    <important-note-infrastructure v-model="entry" :target="target"></important-note-infrastructure>

    <important-note-transaction v-model="entry" :contract="contract"></important-note-transaction>

    <important-note-other v-model="entry"></important-note-other>
</div>
