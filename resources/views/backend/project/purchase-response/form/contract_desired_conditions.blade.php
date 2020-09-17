<div class="card card-project">
    <div class="card-header">
        @lang("pj_purchase_response.desired_contract_terms")
    </div>

    <div class="card-body">

        @php
            $fields = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "an",
                            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                            "w", "x", "y", "z", "aa", "ab"
                        );
            $response_fields = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "an",
                            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
                            "w", "x", "y", "z", "aa", "am"
                        );
        @endphp

        <!-- start - radio input -->
        @foreach ($fields as $key => $field)

            <!-- start - field is not r -->
            @if ($field != "r")

                <!-- start - field is an -->
                @if ($field == "an")
                    <div v-if="purchase_doc.gathering_request_third_party" class="form-group row" style="padding: 5px;">

                        <!-- start - check gathering_request_third_party -->
                        <label v-if="purchase_doc.gathering_request_third_party == 1"
                            class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_1")
                        </label>
                        <label v-else-if="purchase_doc.gathering_request_third_party == 2"
                            class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_2")
                        </label>
                        <label v-else-if="purchase_doc.gathering_request_third_party == 3"
                            class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_3")
                        </label>
                        <!-- end - check gathering_request_third_party -->

                        <div class="col-4 row">
                            <div class="icheck-cyan d-inline">
                                <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}" id="desired_contract_terms_{{ $field }}_1" value="1">
                                <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_1">@lang("pj_purchase_response.response")</label>
                            </div>
                            <div class="icheck-cyan d-inline">
                                <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}" id="desired_contract_terms_{{ $field }}_2" value="2">
                                <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_2">@lang("pj_purchase_response.no")</label>
                            </div>
                        </div>
                    </div>
                <!-- end - field is an -->

                <!-- start - field is not an -->
                @else
                    <div v-if="purchase_doc.desired_contract_terms_{{ $field }}" class="form-group row" style="padding: 5px;">
                        <label class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}")</label>
                        <div class="col-4 row">
                            <div class="icheck-cyan d-inline">
                                <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}" id="desired_contract_terms_{{ $field }}_1" value="1">
                                @if ($field != "m")
                                    <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_1">@lang("pj_purchase_response.response")</label>
                                @elseif ($field == "m")
                                    <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_1">@lang("pj_purchase_response.not_property_occupied")</label>
                                @endif
                            </div>
                            <div class="icheck-cyan d-inline">
                                <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}" id="desired_contract_terms_{{ $field }}_2" value="2">
                                @if ($field != "m")
                                    <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_2">@lang("pj_purchase_response.no")</label>
                                @elseif ($field == "m")
                                    <label class="text-uppercase fw-n" for="desired_contract_terms_{{ $field }}_2">@lang("pj_purchase_response.property_occupied")</label>
                                @endif
                            </div>
                            @if ($field == "m")
                                <input v-if="purchase_response.desired_contract_terms_{{ $field }} == 2" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_text" class="form-control col-5 mr-3" name="desired_contract_terms_{{ $field }}_text" id="desired_contract_terms_{{ $field }}_text" type="text" data-parsley-trigger="keyup" data-parsley-maxlength="128">
                            @endif
                        </div>
                    </div>
                @endif
                <!-- end - field is not an -->
            <!-- start - field is not r -->

            <!-- start - field is r -->
            @elseif ($field == "r")
                <div v-if="purchase_doc.desired_contract_terms_{{ $field }}" class="form-group row" style="padding: 5px;">
                    <label class="col-10 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_1")</label>
                    <br>
                    <label class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_1_option")</label>
                    <div class="col-4 row">
                        <div class="icheck-cyan d-inline">
                            イ）
                            <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_1" id="desired_contract_terms_{{ $field }}_1_1" name="desired_contract_terms_{{ $field }}_1" value="1">
                            <label class="text-uppercase fw-n mr-3" for="desired_contract_terms_{{ $field }}_1_1">有</label>
                        </div>
                        <input v-if="purchase_response.desired_contract_terms_{{ $field }}_1 == 1" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_1_text" class="form-control col-5 mr-3" name="desired_contract_terms_{{ $field }}_1_text" id="desired_contract_terms_{{ $field }}_1_text" type="text" data-parsley-trigger="keyup" data-parsley-maxlength="128">
                        <div class="icheck-cyan d-inline">
                            <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_1" id="desired_contract_terms_{{ $field }}_1_2" name="desired_contract_terms_{{ $field }}_1" value="2">
                            <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_1_2">@lang("pj_purchase_response.nothing")</label>
                        </div>
                    </div>
                    <label class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_2_option")</label>
                    <br>
                    <div class="col-4 row">
                        <div class="icheck-cyan d-inline">
                            ロ）
                            <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_2" id="desired_contract_terms_{{ $field }}_2_1" name="desired_contract_terms_{{ $field }}_2" value="1">
                            <label class="text-uppercase fw-n mr-3" for="desired_contract_terms_{{ $field }}_2_1">有</label>
                        </div>
                        <input v-if="purchase_response.desired_contract_terms_{{ $field }}_2 == 1" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_2_text" class="form-control col-5 mr-3" name="desired_contract_terms_{{ $field }}_2_text" id="desired_contract_terms_{{ $field }}_2_text" type="text" data-parsley-trigger="keyup" data-parsley-maxlength="128">
                        <div class="icheck-cyan d-inline">
                            <input type="radio" v-model="purchase_response.desired_contract_terms_{{ $response_fields[$key] }}_2" id="desired_contract_terms_{{ $field }}_2_2" name="desired_contract_terms_{{ $field }}_2" value="2">
                            <label class="text-uppercase fw-n mr-5" for="desired_contract_terms_{{ $field }}_2_2">@lang("pj_purchase_response.nothing")</label>
                        </div>
                    </div>
                    <label class="col-8 fw-n col-form-label">@lang("pj_purchase_response.desired_contract_terms_{$field}_2")</label>
                </div>
            @endif
        <!-- end - field is r -->

        @endforeach
        <!-- end - radio input -->

    </div>
</div>
