<div v-if="!projectStatus && !offerDate" class="px-2 py-2">
    <span>-</span>
</div>
<div v-else class="px-2 py-2">
    <div :class="projectStatus.color">@{{ projectStatus.label }}</div>
    <div>@{{ offerDate }}</div>
</div>