<div class="card card-project">
    <div class="card-header">
        @lang("pj_purchase_response.request_permission")
    </div>
    <div class="card-body">
        @php
            $fields = array("request_permission_a", "request_permission_b",
                            "request_permission_c", "request_permission_d",
                            "request_permission_e"
                          );
        @endphp

        <!-- start - radio input -->
        @foreach ($fields as $key => $field)
            <div v-if="purchase_doc.{{ $field }}" class="form-group row" style="padding: 5px;">
                <label style="font-weight: normal;" class="col-8 col-form-label">@lang("pj_purchase_response.{$field}")</label>
                <div class="col-4 row">
                    <div class="icheck-cyan d-inline">
                        <input type="radio" v-model="purchase_response.{{ $field }}" id="{{ $field }}_1" name="{{ $field }}" value="1">
                        <label style="font-weight: normal;" class="text-uppercase mr-5" for="{{ $field }}_1">@lang("pj_purchase_response.response")</label>
                    </div>
                    <div class="icheck-cyan d-inline">
                        <input type="radio" v-model="purchase_response.{{ $field }}" id="{{ $field }}_2" name="{{ $field }}" value="2">
                        <label style="font-weight: normal;" class="text-uppercase mr-5" for="{{ $field }}_2">@lang("pj_purchase_response.no")</label>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- end - radio input -->

    </div>
</div>
