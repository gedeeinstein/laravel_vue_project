<div class="px-2 py-2">
    <div>@{{ entry.id }}</div>
    <div v-if="buyerStaffs && buyerStaffs.length" class="row mx-n1 fs-12">
        <div class="px-1 col-auto" v-for="staff in [ buyerStaffs[0] ]">
            <span>@{{ staff.nickname }}</span>
        </div>
    </div>
</div>