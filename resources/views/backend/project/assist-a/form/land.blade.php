<div class="row">
    <div class="col-5">
        <!-- project_title -->
        <div class="form-group row">
            <label for="project_title" class="col-3 col-form-label">物件名称</label>
            <input id="project_title"
                   v-model="project.title" class="form-control col-6" type="text"
                   readonly="readonly">
        </div>
        <!-- school_primary -->
        <div class="form-group row">
            <label for="school_primary" class="col-3 col-form-label">小学校</label>
            <input id="school_primary"
                   v-model="property.school_primary" class="form-control col-6" type="text"
                   :disabled="!initial.editable">
            <div class="input-group col-3 input-integer">
                <template>
                    <currency-input v-model="property.school_primary_distance"
                        class="form-control" placeholder="距離"
                        :currency="null"
                        :precision="{ min: 0, max: 0 }"
                        :allow-negative="false"
                        :disabled="!initial.editable" />
                </template>
                <div class="input-group-append">
                    <div class="input-group-text">m</div>
                </div>
            </div>
        </div>
        <!-- school_juniorhigh -->
        <div class="form-group row">
            <label for="school_juniorhigh" class="col-3 col-form-label">中学校</label>
            <input id="school_juniorhigh"
                   v-model="property.school_juniorhigh" class="form-control col-6" type="text"
                   :disabled="!initial.editable">
            <div class="input-group col-3 input-integer">
                <template>
                    <currency-input v-model="property.school_juniorhigh_distance"
                        class="form-control" placeholder="距離"
                        :currency="null"
                        :precision="{ min: 0, max: 0 }"
                        :allow-negative="false"
                        :disabled="!initial.editable" />
                </template>
                <div class="input-group-append">
                    <div class="input-group-text">m</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-7 pl-4">
        <div class="form-group row">
            <label for="registry_size" class="col-3 col-form-label">登記総面積</label>
            <!-- registry_size -->
            <div class="col-3">
                <div class="input-group">
                    <input id="registry_size"
                           :value="calculated_registry_size | numeralFormat(2)" class="form-control border-decimal" type="text"
                           readonly="readonly">
                    <div class="input-group-append">
                        <div class="input-group-text">m<sup>2</sup></div>
                    </div>
                </div>
            </div>
            <!-- registry_size_tsubo -->
            <div class="col-3">
                <div class="input-group">
                    <input id="registry_size_tsubo"
                           :value="property.registry_size | tsubo | numeralFormat(2)" class="form-control border-decimal" type="text"
                           readonly="readonly">
                    <div class="input-group-append">
                        <div class="input-group-text">坪</div>
                    </div>
                </div>
            </div>
            <!-- registry_size_status -->
            <div class="col-3">
                <fieldset class="form-group form-text" :disabled="!initial.editable">
                    <div class="icheck-cyan d-inline">
                        <input class="form-check-input"
                               v-model="property.registry_size_status" type="radio"
                               name="registry_size_status" id="registry_size_status2"
                               value="2">
                        <label class="form-check-label" for="registry_size_status2">確</label>
                    </div>
                    <div class="form-check icheck-cyan d-inline">
                        <input class="form-check-input"
                               v-model="property.registry_size_status" type="radio"
                               name="registry_size_status" id="registry_size_status1"
                               value="1">
                        <label class="form-check-label" for="registry_size_status1">予</label>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="form-group row">
            <label for="survey_size" class="col-3 col-form-label">実測総面積</label>
            <!-- survey_size -->
            <div class="col-3">
                <div class="input-group">
                    <input id="survey_size"
                           :value="calculated_survey_size | numeralFormat(2)" class="form-control border-decimal" type="text"
                           readonly="readonly">
                    <div class="input-group-append">
                        <div class="input-group-text">m<sup>2</sup></div>
                    </div>
                </div>
            </div>
            <!-- survey_size_tsubo -->
            <div class="col-3">
                <div class="input-group">
                    <input id="survey_size_tsubo"
                           :value="property.survey_size | tsubo | numeralFormat(2)" class="form-control border-decimal" type="text"
                           readonly="readonly">
                    <div class="input-group-append">
                        <div class="input-group-text">坪</div>
                    </div>
                </div>
            </div>
            <!-- survey_size_status -->
            <div class="col-3">
                <fieldset class="form-group form-text" :disabled="!initial.editable">
                    <div class="form-check icheck-cyan d-inline">
                        <input class="form-check-input"
                               v-model="property.survey_size_status" type="radio"
                               name="survey_size_status" id="survey_size_status2"
                               value="2">
                        <label class="form-check-label" for="survey_size_status2">確</label>
                    </div>
                    <div class="form-check icheck-cyan d-inline">
                        <input class="form-check-input"
                               v-model="property.survey_size_status" type="radio"
                               name="survey_size_status" id="survey_size_status1"
                               value="1">
                        <label class="form-check-label" for="survey_size_status1">予</label>
                    </div>
                    <div class="form-check icheck-cyan d-inline">
                        <input class="form-check-input"
                               v-model="property.survey_size_status" type="radio"
                               name="survey_size_status" id="survey_size_status0"
                               value="0">
                        <label class="form-check-label" for="survey_size_status0">無</label>
                    </div>
                </fieldset>
            </div>
        </div>
        <!-- fixed_asset_tax_route_value -->
        <div class="form-group row">
            <div class="col-3 col-form-label py-0 d-flex align-items-center">
                <label for="fixed_asset_tax_route_value">固定資産税路線価/m2</label>
            </div>
            <div class="col-3">
                <div class="input-group input-money input-static">
                    <currency-input id="fixed_asset_tax_route_value" v-model="property.fixed_asset_tax_route_value"
                        class="form-control" placeholder="距離"
                        :currency="null"
                        :precision="{ min: 0, max: 2 }"
                        :allow-negative="false"
                        :disabled="true">
                    </currency-input>
                    <div class="input-group-append">
                        <div class="input-group-text">円</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-text text-muted mx-2">
                    <a href="{{ route('project.sheet', $project->id ) }}" target="_blank" title="PJシートを開く">
                        固定資産税路線価の修正 <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- property_owner_name -->
        <div class="form-group row">
            <label for="property_owner_name" class="col-3 col-form-label">所有者</label>
            <div class="col-9">
                <div v-for="(owner, index) in property_owners" class="row" :class="[ (index != 0) ? 'pt-1' : '' ]">
                    <div class="col-6">
                        <input v-model="owner.name" type="text"
                               class="form-control owner-name"
                               data-parsley-notequalto=".owner-name"
                               data-parsley-trigger="change focusout"
                               :disabled="!initial.editable">
                    </div>
                    <template v-if="initial.editable">
                        <div v-if="index == 0" class="col-1 pt-2">
                            <span><i @click="addPropertyOwners" class="add_button fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="所有者追加"></i></span>
                        </div>
                        <div v-else class="col-1 pt-2">
                            <span><i @click="removePropertyOwners(index)" class="remove_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="所有者削除"></i></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
