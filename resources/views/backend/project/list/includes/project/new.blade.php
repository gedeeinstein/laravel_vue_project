<transition name="paste-in">
    <template v-if="!$store.state.print">
        <form method="post" @submit.prevent="newProject">
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <div class="row">
            
                        <div class="col-md-auto d-flex align-items-center">
                            <label class="m-0" for="new-project">物件名称</label>
                        </div>
                        <div class="col-sm mt-2 mt-md-0">
                            <input type="text" id="new-project" class="form-control" v-model="entry.title" :disabled="status.creating" />
                        </div>
                        <div class="col-sm-auto mt-2 mt-md-0">
                            <button type="submit" class="btn btn-block btn-info" :disabled="status.creating || emptyTitle">
                                <div class="row mx-n1 justify-content-center">
                                    <div class="px-1 col-auto d-flex align-items-center">
                                        <i v-if="status.creating" class="fw-l far fa-cog fa-spin"></i>
                                        <i v-else class="fw-l far fa-plus-circle"></i>
                                    </div>
                                    <div class="px-1 col-auto">新規プロジェクト作成</div>
                                </div>
                            </button>
                        </div>
            
                    </div>
                </div>
            </div>
        </form>
    </template>
</transition>