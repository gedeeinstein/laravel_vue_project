
{{-- 
    This file is not included anywhere in the app.
    It is here only for markup reference when developing 
    other module with similar layout.
--}}

<!-- Request entry markup - Start -->
<div class="project-entry request-entry border-top-0">
    <div class="row mx-0">
        <div class="px-0 col-lg-150px column d-flex align-items-center">
            <div class="w-100 py-2 px-2">
                <div class="row mx-n2">
                    <div class="px-2 col-120px col-md-200px d-block d-lg-none">
                        <strong>種別</strong>
                    </div>
                    <div class="px-2 col">
                        <span>販売契約書・重説</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 col-lg-150px column column d-flex align-items-center">
            <div class="w-100 py-2 px-2">
                <div class="row mx-n2">
                    <div class="px-2 col-120px col-md-200px d-block d-lg-none">
                        <strong>リクエスト日時</strong>
                    </div>
                    <div class="px-2 col">
                        <span>2019/02/14 09:00:00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 col-md column d-flex align-items-center">
            <div class="w-100 py-2 px-2">
                <div class="row mx-n2">
                    <div class="px-2 col-120px d-block d-md-none">
                        <strong>番号</strong>
                    </div>
                    <div class="px-2 col">
                        <a href="#">1902-01#HA1902(ⅰ/ⅲ)K5/7</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 col-md column d-flex align-items-center">
            <div class="w-100 py-2 px-2">
                <div class="row mx-n2">
                    <div class="px-2 col-120px d-block d-md-none">
                        <strong>PJ名称</strong>
                    </div>
                    <div class="px-2 col">
                        <span>松田ニュータウン昭和町</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-0 col-md-280px column d-flex align-items-center">
            <div class="w-100 py-2 px-2">
                <div class="row mx-n1">
                    <div class="px-1 col d-flex align-items-center justify-content-center">
                        <div class="row mx-n1">
                            <div class="px-1 col-auto">
                                <div class="icheck-cyan">
                                    <input type="radio" data-parsley-checkmin="1" :disabled="false" :value="1" />
                                    <label class="fs-12 fw-n noselect w-100">
                                        <span>未承認</span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-1 col-auto">
                                <div class="icheck-cyan">
                                    <input type="radio" data-parsley-checkmin="1" :disabled="false" :value="2" />
                                    <label class="fs-12 fw-n noselect w-100">
                                        <span class="text-blue">承認</span>
                                    </label>
                                </div>
                            </div>
                            <div class="px-1 col-auto">
                                <div class="icheck-cyan">
                                    <input type="radio" data-parsley-checkmin="1" :disabled="false" :value="2" />
                                    <label class="fs-12 fw-n noselect w-100">
                                        <span class="text-red">非承認</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-1 col-auto d-flex align-items-center justify-content-center">
                        <button type="button" class="btn btn-sm btn-primary">
                            <span>更新</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Request entry markup - End -->