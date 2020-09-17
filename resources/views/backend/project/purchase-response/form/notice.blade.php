<div class="card card-project">
    <div class="card-header">
        @lang("pj_purchase_response.desired_contract_terms")
    </div>

    <div class="card-body">
        @php
            $fields = array("notices_a", "notices_b", "notices_c",
                            "notices_d", "notices_e"
                        );
            $text_fields = array("notices_a_text", "notices_b_text", "notices_c_text",
                            "notices_d_text", "notices_e_text"
                            );
        @endphp

        <!-- start - radio input -->
        @foreach ($fields as $key => $field)
            <div v-if="purchase_doc.{{ $field }}" class="form-group row" style="padding: 5px;">
                <label style="font-weight: normal;" class="col-8 col-form-label">@lang("pj_purchase_response.{$field}")</label>
                <div class="col-4 row">
                    <div class="icheck-cyan d-inline">
                        <input type="radio" v-model="purchase_response.{{ $field }}" id="{{ $field }}_1" name="{{ $field }}" value="1">
                        <label style="font-weight: normal;" class="text-uppercase mr-5" for="{{ $field }}_1">@lang("pj_purchase_response.yes")</label>
                    </div>
                    <input v-if="purchase_response.{{ $field }} == 1" v-model="purchase_response.{{ $text_fields[$key] }}" class="form-control col-5 mr-3" type="text" data-parsley-trigger="keyup" data-parsley-maxlength="128">
                    <div class="icheck-cyan d-inline">
                        <input type="radio" v-model="purchase_response.{{ $field }}" id="{{ $field }}_2" name="{{ $field }}" value="2">
                        <label style="font-weight: normal;" class="text-uppercase mr-5" for="{{ $field }}_2">@lang("pj_purchase_response.nothing")</label>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- end - radio input -->

    </div>
</div>
