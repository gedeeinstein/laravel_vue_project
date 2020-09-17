<div class="form-controls mx-n2 mx-sm-0 mb-3">
  <div class="row mx-n1">
      @php
        $columnClass = 'px-1 col-sm-6 col-md-auto my-1';
        $buttonClass = 'btn btn-block btn-primary fs-14';
        $rowClass = 'row mx-n1 justify-content-center'
      @endphp
    <div class="{{ $columnClass }}">
      <button @click.prevent="formSubmited(0)" type="submit"  class="{{ $buttonClass }}" data-id="A87-1">
          <i v-if="!initial.submited[0]" class="fas fa-save"></i>
          <i v-else class="fas fa-spinner fa-spin"></i>
          保存
      </button>
    </div>
    <div class="{{ $columnClass }}">
      <button @click.prevent="formSubmited(1)" type="submit"  class="{{ $buttonClass }}" data-id="A87-2">
          <i v-if="!initial.submited[1]" class="fas fa-print"></i>
          <i v-else class="fas fa-spinner fa-spin"></i>
          買付証明書印刷
      </button>
    </div>
    <div class="{{ $columnClass }}">
      <button @click.prevent="formSubmited(2)" type="submit"  class="{{ $buttonClass }}"  data-id="A87-3">
          <i v-if="!initial.submited[2]" class="fas fa-print"></i>
          <i v-else class="fas fa-spinner fa-spin"></i>
          チェックリスト
      </button>
    </div>
    <div class="{{ $columnClass }}">
      <button @click.prevent="formSubmited(3)" type="submit" class="{{ $buttonClass }}" data-id="A87-4">
        <i v-if="!initial.submited[3]" class="fas fa-save"></i>
        <i v-else class="fas fa-spinner fa-spin"></i>
        承認リクエスト
      </button>
    </div>
  </div>
</div>
