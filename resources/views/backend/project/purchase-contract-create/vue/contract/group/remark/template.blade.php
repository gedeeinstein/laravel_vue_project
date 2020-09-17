<script type="text/x-template" id="group-remark">
    <div class="form-group row mb-2 mb-md-3">
        <label :for="name" class="col-md-3 col-form-label">
            <span class="fw-n" :class="{ 'text-grey': isCompleted }">特記事項</span>
        </label>
        <div class="col-md">

            <!-- Heading - Start -->
            <div class="heading rounded bg-grey p-2 mb-2">
                <span class="fw-n" :class="{ 'text-grey': isCompleted }">前面道路</span>
            </div>
            <!-- Heading - End -->

            <!-- Front road checkboxes - Start -->
            <div class="row mb-2">
                <div class="col-12">
                    
                    <!-- Front Road A - Start -->
                    <div v-if="checkRoadA" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-a' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_a" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">買主は、その責任と負担において、引渡日までに本物件において建築確認済み証を取得することをとします。買主の責に帰さない事由により、建築確認済み証を取得できない場合は、本契約を解除出来るものとします。その場合、売主は受領済みの金員を全額無利息にて返還する事と致します。</span>
                        </label>
                    </div>
                    <!-- Front Road A - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road B - Start -->
                    <div v-if="checkRoadB" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-b' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_b" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、本物件の引渡日までにその責任と負担において、本物件の前面道路の他共有者全員から通行掘削同意書の署名・捺印を取得することとします。万が一、売主が取得を完了できない場合、本契約は白紙解除になるものとし、売主はその時点で受領済みの金員を、全額無利息にて買主に速やかに返還するものとします。</span>
                        </label>
                    </div>
                    <!-- Front Road B - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road C - Start -->
                    <div v-if="checkRoadC" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-c' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_c" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">買主は、本物件の引渡日までにその責任と負担において、本物件の前面道路の他共有者全員から通行掘削同意書の署名・捺印を取得することとします。万が一、買主が取得を完了できない場合、本契約は白紙解除になるものとし、売主はその時点で受領済みの金員を、全額無利息にて買主に速やかに返還するものとします。</span>
                        </label>
                    </div>
                    <!-- Front Road C - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road D - Start -->
                    <div v-if="checkRoadD" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-d' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_d" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、引渡日までに道路部分と宅地部分の分筆登記を完了させる事とします。</span>
                        </label>
                    </div>
                    <!-- Front Road C - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road E - Start -->
                    <div v-if="checkRoadE" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-e' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_e" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、引渡日までに道路部分と宅地部分の分筆登記を完了させる事としますが、その費用は買主の負担とします。</span>
                        </label>
                    </div>
                    <!-- Front Road E - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road F - Start -->
                    <div v-if="checkRoadF" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-f' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_f" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、引渡日までに狭隘協議を行った上で分筆登記を完了させる事とします。</span>
                        </label>
                    </div>
                    <!-- Front Road F - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road G - Start -->
                    <div v-if="checkRoadG" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-g' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_g" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、引渡日までに狭隘協議を行った上で分筆登記を完了させる事としますが、その費用は買主の負担とします。</span>
                        </label>
                    </div>
                    <!-- Front Road G - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road H - Start -->
                    <div v-if="checkRoadH" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-h' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_h" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、役所が指導する道路後退距離を確保するよう、引渡日までに分筆登記を完了させることとします。</span>
                        </label>
                    </div>
                    <!-- Front Road H - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road I - Start -->
                    <div v-if="checkRoadI" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-i' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_i" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、役所が指導する道路後退距離を確保するよう、引渡日までに分筆登記を完了させることとします。ただし、分筆登記にかかる費用は買主の負担とします。</span>
                        </label>
                    </div>
                    <!-- Front Road I - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road J - Start -->
                    <div v-if="checkRoadJ" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-j' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_j" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">本物件の引渡しは、本物件の使用収益開始日以降とします。したがって表記の引渡し日までに、使用収益開始が間に合わない場合は、使用収益開始日以降に引き渡しが延期になるものとします。</span>
                        </label>
                    </div>
                    <!-- Front Road J - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road K - Start -->
                    <div v-if="checkRoadK" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-k' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_k" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、引渡日までに本物件の前面道路の持分及び他共有者全員からの通行掘削同意書の署名・捺印を取得し、当該持分とその権限を買主に引き渡すものとします。尚、当該持分の価格は本物件の売買価格に含まれるものとします。万が一、売主が前面道路の持分及び通行掘削同意書の署名・捺印を取得できない場合は、本契約は白紙解除になります。</span>
                        </label>
                    </div>
                    <!-- Front Road K - End -->

                </div>
                <div class="col-12">
                    
                    <!-- Front Road L - Start -->
                    <div v-if="checkRoadL" class="icheck-cyan" v-for="name in [ prefix+ 'front-road-l' ]">
                        <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                            :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.front_road_l" />
                        <label :for="name" class="fw-n noselect w-100">
                            <span :class="{ 'text-black-50': isCompleted }">本物件の引渡日までに、買主がその責任と負担で、前面道路の持分及び他共有者全員からの通行掘削同意書の署名・捺印を取得することを本契約の停止条件とします。万が一、買主が前面道路の持分及び通行掘削同意書の署名・捺印を取得できない場合は、本契約は白紙解除になります。</span>
                        </label>
                    </div>
                    <!-- Front Road L - End -->

                </div>
            </div>
            <!-- Front road checkboxes - End -->


            <!-- Agricultural section - Start -->
            <template v-if="checkAgricultural">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">農転・地目</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Agricultural section A - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'agricultural-section-a' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.agricultural_section_a" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">本契約締結後、売主・買主は互いに協力して速やかに農地転用の手続きを行い、受理通知書又は許可証を取得することを本契約の停止条件とします。尚、当該申請にかかる費用は売主の負担とします。万が一、受理通知書又は許可証が取得できない場合、本契約は白紙解除になります。</span>
                            </label>
                        </div>
                        <!-- Agricultural section A - End -->
    
                    </div>
                    <div class="col-12">
                        
                        <!-- Agricultural section B - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'agricultural-section-b' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.agricultural_section_b" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">本契約締結後、売主・買主は互いに協力して速やかに農地転用の手続きを行い、受理通知書又は許可証を取得することを本契約の停止条件とします。尚、当該申請にかかる費用は買主の負担とします。万が一、受理通知書又は許可証が取得できない場合、本契約は白紙解除になります。</span>
                            </label>
                        </div>
                        <!-- Agricultural section B - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Agricultural section - End -->


            <!-- Development permission - Always visible - Start -->
            <template v-if="true">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">開発許可</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Development permission - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'development-permission' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.development_permission" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">買主はその責任と負担において、本契約締結後速やかに本物件における開発工事の申請を役所等に行い、引渡日までに開発許可証を取得することとします。買主の責に帰さない事由により、当該許可証が取得できない場合、本契約は白紙解除になります。また、当該許可証の取得が遅れる場合については、売主・買主が協議し双方合意することで、引渡日を延期することができます。</span>
                            </label>
                        </div>
                        <!-- Development permission - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Development permission - Always visible - End -->


            <!-- Cross border - Start -->
            <template v-if="checkCrossBorder">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">越境</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Cross border - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'cross-border' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.cross_border" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">結果、本物件隣地の構造物等が越境していることが判明した場合、売主の責任において当該越境状態の解消して引き渡すものとします。越境状態が解消できない場合には、買主は契約の解除する事が出来るものとします。その場合、売主は受領済の金員を全額無利息にて買主に返還することと致します。 ただし、越境に関する覚書で問題を解決できる場合は、覚書の署名捺印取得を本物件引渡日までに完了させる事を条件に引渡しが出来るものとします。また、引き渡し後に越境が判明した場合についても売主は同様の義務を負うものとします。</span>
                            </label>
                        </div>
                        <!-- Cross border - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Cross border - End -->


            <!-- Trading other - Start -->
            <template v-if="checkTradingOther">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">他人物売買</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Trading other - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'cross-border' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.trading_other_people" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主はその責任と負担において引渡日までに、その所有者が自己の名義となるように所有権移転登記又は相続登記を完了させ、登記簿謄本を買主に提出するものとします。</span>
                            </label>
                        </div>
                        <!-- Trading other - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Trading other - End -->


            <!-- Separate with pen - Start -->
            <template v-if="true">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">分筆</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Trading other - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'separate-with-pen-a' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.separate_with_pen_a" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は、別添分筆計画図の通り分筆登記を完了させた上で、該当範囲を買主に引き渡すものとします。尚、当該分筆登記にかかる費用は売主の負担とします。</span>
                            </label>
                        </div>
                        <!-- Trading other - End -->

                        <!-- Trading other - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'separate-with-pen-b' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.separate_with_pen_b" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は、別添分筆計画図の通り分筆登記を完了させた上で、該当範囲を買主に引き渡すものとします。尚、当該分筆登記にかかる費用は買主の負担とします。</span>
                            </label>
                        </div>
                        <!-- Trading other - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Separate with pen - End -->


            <!-- Building for merchandise - Start -->
            <template v-if="checkBuildingMerchandise">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">商品用建物</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Building for merchandise A - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'building-merchandise-a' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.building_for_merchandise_a" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、決済日までに本物件の敷地内（建物内外問わず）に放置物等が有る場合、撤去するものとします。</span>
                            </label>
                        </div>
                        <!-- Building for merchandise A - End -->

                        <!-- Building for merchandise B - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'building-merchandise-b' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.building_for_merchandise_b" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、決済日までにホームスペクションを実施し、成果物を買主に引渡しものとします。</span>
                            </label>
                        </div>
                        <!-- Building for merchandise B - End -->

                        <!-- Building for merchandise C - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'building-merchandise-c' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.building_for_merchandise_c" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は、その責任と負担において、引渡しまでの間に発覚した不具合については補修するのものとします。</span>
                            </label>
                        </div>
                        <!-- Building for merchandise C - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Building for merchandise - End -->


            <!-- Profitable property - Start -->
            <template v-if="checkProfitableProperty">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">収益物件</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Profitable property A - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'profitable-property-a' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.profitable_property_a" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は賃貸借契約者との契約内容について買主に不利な条項かつ未申告内容があった場合、違約金などの対象になる事を了承するものとします。</span>
                            </label>
                        </div>
                        <!-- Profitable property A - End -->

                        <!-- Profitable property B - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'profitable-property-b' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.profitable_property_b" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は家賃の延滞等について未申告があった場合、違約金などの対象になる事を了承するものとします。</span>
                            </label>
                        </div>
                        <!-- Profitable property B - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Profitable property - End -->


            <!-- Other remark - Always visible - Start -->
            <template v-if="true">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">その他</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">
                        
                        <!-- Other remark - Start -->
                        <div class="icheck-cyan" v-for="name in [ prefix+ 'other-remark' ]">
                            <input type="checkbox" :id="name" :name="name" data-parsley-checkmin="1"
                                :disabled="isDisabled" :true-value="true" :false-value="false" v-model="entry.remarks_other" />
                            <label :for="name" class="fw-n noselect w-100">
                                <span :class="{ 'text-black-50': isCompleted }">売主は、買主が再販売を目的として購入することを承諾し、契約日以降の販売活動を認めるものとします。</span>
                            </label>
                        </div>
                        <!-- Other remark - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Other remark - Always visible -  End -->


            <!-- Original contents - Start -->
            <template v-if="true">

                <!-- Heading - Start -->
                <div class="heading rounded bg-grey p-2 mb-2">
                    <span class="fw-n" :class="{ 'text-grey': isCompleted }">独自の項目</span>
                </div>
                <!-- Heading - End -->
    
                <!-- Checkboxes - Start -->
                <div class="row mb-2">
                    <div class="col-12">

                        <!-- Content 1 - Start -->
                        <div class="row mx-0 mb-2">
                            <div class="px-0 col">

                                @component("{$component->preset}.text")
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'model', 'entry.original_contents_text_a' )
                                    @slot( 'placeholder', "'記載事項を追加する場合は入力'" )
                                @endcomponent

                            </div>
                            <div class="px-0 col-auto">
                                <button type="button" class="btn btn-icon text-primary" :disabled="isDisabled" @click="addContent">
                                    <i class="far fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Content 1 - End -->

                        <!-- Content 1 - Start -->
                        <div class="row mx-0" v-if="secondContent">
                            <div class="px-0 col">

                                @component("{$component->preset}.text")
                                    @slot( 'disabled', 'isDisabled' )
                                    @slot( 'model', 'entry.original_contents_text_b' )
                                    @slot( 'placeholder', "'記載事項を追加する場合は入力'" )
                                @endcomponent

                            </div>
                            <div class="px-0 col-auto">
                                <button type="button" class="btn btn-icon text-danger" :disabled="isDisabled" @click="removeContent">
                                    <i class="far fa-minus-circle"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Content 1 - End -->
    
                    </div>
                </div>
                <!-- Checkboxes - Start -->

            </template>
            <!-- Original contents - End -->

        </div>
    </div>
</script>
