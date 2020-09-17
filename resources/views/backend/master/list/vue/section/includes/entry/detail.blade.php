<div class="entry-detail">
    <div class="row mx-0">
        <div class="px-0 col-lg-12 column">
            <div class="row mx-0">
                <div class="px-0 col">
                    <div class="px-2 py-2">

                        <!-- Project memos - Start -->
                        <ul class="project-memo fs-14 mb-0 pl-4 my-n2">
                            <template v-if="entry.memos && entry.memos.length" v-for="( memoEntry, memoIndex ) in entry.memos">

                                <!-- Project memo - Start -->
                                <project-memo :edit="memo.edit" v-model="memoEntry" @created="created"></project-memo>
                                <!-- Project memo - End -->

                            </template>
                        </ul>
                        <!-- Project memos - End -->

                    </div>
                </div>
                <div class="px-0 col-lg-auto column border-right-0">
                    <div class="px-2 py-2">
                        <div class="row mx-n1">

                            <!-- Add memo button - Start -->
                            <div v-if="memo.edit" class="px-1 col-sm col-lg-auto mb-2 mb-sm-0" @click="toggleCreateMemo">
                                <button type="button" class="btn btn-block btn-sm btn-success px-2">
                                    <i class="fw-l far fa-plus"></i>
                                </button>
                            </div>
                            <!-- Add memo button - End -->

                            <!-- Toggle edit memo button - Start -->
                            <div class="px-1 col-sm col-lg-auto" @click="toggleEditMemo">
                                <button type="button" class="btn btn-block btn-sm px-2" :class="memo.edit ? 'btn-outline-info': 'btn-default'">
                                    <span>メモ</span>
                                </button>
                            </div>
                            <!-- Toggle edit memo button - End -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>