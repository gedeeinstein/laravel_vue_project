<div class="card card-project">
    <div class="card-header">
        @lang("pj_purchase_response.optional_items")
    </div>

    <div class="card-body">

        @php
            $doc_fields = array("optional_items_a", "optional_items_b", "optional_items_c",
                                "optional_items_d", "optional_items_e", "optional_items_f",
                                "optional_items_g", "optional_items_h", "optional_items_i",
                                "optional_items_j", "optional_items_k"
                            );

            $fields = array("desired_contract_terms_ab", "desired_contract_terms_ac", "desired_contract_terms_ad",
                            "desired_contract_terms_ae", "desired_contract_terms_af", "desired_contract_terms_ag",
                            "desired_contract_terms_ah", "desired_contract_terms_ai", "desired_contract_terms_aj",
                            "desired_contract_terms_ak", "desired_contract_terms_al", "desired_contract_terms_am"
                            );
        @endphp

        <!-- start - radio input -->
        @foreach ($doc_fields as $key => $doc_field)
            <div v-if="purchase_doc.{{ $doc_field }}" class="form-group row" style="padding: 5px;">
                <label style="font-weight: normal;" class="col-8 col-form-label">@lang("pj_purchase_response.{$fields[$key + 1]}")</label>
                <div class="col-4 row">
                    <div class="icheck-cyan d-inline">
                        <input type="radio" v-model="purchase_response.{{ $fields[$key] }}" id="{{ $fields[$key] }}_1" name="{{ $fields[$key] }}" value="1">
                        <label style="font-weight: normal;" class="text-uppercase mr-5" for="{{ $fields[$key] }}_1">@lang("pj_purchase_response.response")</label>
                    </div>
                    <div class="icheck-cyan d-inline">
                        <input type="radio" v-model="purchase_response.{{ $fields[$key] }}" id="{{ $fields[$key] }}_2" name="{{ $fields[$key] }}" value="2">
                        <label style="font-weight: normal;" class="text-uppercase mr-5" for="{{ $fields[$key] }}_2">@lang("pj_purchase_response.no")</label>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- end - radio input -->

    </div>
</div>
