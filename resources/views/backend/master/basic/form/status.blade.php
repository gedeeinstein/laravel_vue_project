<div class="nav-buttons bottom">
        <div v-if="mas_basic.status == 1"
        class="edit-status-alert alert alert-success text-center" role="alert">
            <span>仕入時に登録した上記の基本情報を修正しますか？</span>
            <button @click="is_modified()"
            class="btn btn-primary ml-4 btn-modify" type="button">修正する</button>
        </div>
        <div v-if="mas_basic.status == 2"
        class="edit-status-alert alert alert-success text-center" role="alert">
            <span>基本情報は確定されています</span>
            <button @click="is_modified()"
            class="btn btn-primary ml-4 btn-modify" type="button">修正する</button>
        </div>

        <!-- start - basic status -->
        <div class="edit-status text-center">
            <span>基本情報状況</span>
            <div class="form-check icheck-cyan form-check-inline ml-5">
                <input v-model="mas_basic.status"
                class="form-check-input" type="radio" id="basic_status_1" value="1">
                <label class="form-check-label" for="basic_status_1">未確定</label>
            </div>
            <div class="form-check icheck-cyan form-check-inline">
                <input v-model="mas_basic.status"
                class="form-check-input" type="radio" id="basic_status_2" value="2">
                <label class="form-check-label" for="basic_status_2">確定</label>
            </div>
        </div>
        <!-- end - basic status -->
    </div>
