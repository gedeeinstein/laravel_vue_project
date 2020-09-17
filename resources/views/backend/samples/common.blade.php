@extends('backend._base.content_default')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fas fa-tachometer-alt"></i> @lang('label.dashboard')</a></li>
        <li class="breadcrumb-item active">Sample Page</li>
    </ol>
@endsection

@section('content')
<div class="card">
    <div class="card-header">入力サンプル</div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <!-- Start - Input Integer -->
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label">Input Integer</label>
                    <input class="form-control col-6" v-money="input.int" name="" type="text" value="">
                </div>
                <!-- End - Input Integer -->

                <!-- Start - Input Decimal -->
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label">Input Decimal</label>
                    <div class="input-group col-6 px-0">
                        <input class="form-control" v-money="input.decimal" name="" type="text" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">坪</div>
                        </div>
                    </div>
                </div>
                <!-- End - Input Decimal -->

                <!-- Start - Input Money -->
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label">Input Money</label>
                    <div class="input-group col-6 px-0">
                        <input class="form-control" v-money="input.money" name="" type="text" value="">
                        <div class="input-group-append">
                            <div class="input-group-text">坪</div>
                        </div>
                    </div>
                </div>
                <!-- End - Input Money -->

                <!-- Start - Input Readonly -->
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label">Readonly</label>
                    <input class="form-control col-6" name="" type="text" value="" readonly="readonly">
                </div>
                <!-- End - Input Readonly -->
            </div>
            <div class="col-6">
                <!-- Start - Input Addable -->
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label">Addable</label>
                    <div class="col-9">
                        <div v-for="(item, index) in form.addable" class="row" :class="[ (index != 0) ? 'pt-1' : '' ]">
                            <div class="col-8">
                                <input class="form-control" v-model="item.model" name="addable" type="text">
                            </div>
                            <div v-if="index == 0" class="col-1 pt-2">
                                <span><i @click="addInput" class="add_button fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="所有者追加"></i></span>
                            </div>
                            <div v-else class="col-1 pt-2">
                                <span><i @click="removeInput(index)" class="remove_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="所有者削除"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End - Input Addable -->

                <!-- Start - Input Datepicker -->
                <div class="form-group row">
                    <label for="" class="col-3 col-form-label">Datepicker</label>
                    <div class="col-6">
                        <date-picker v-model="input.datepicker" type="date" class="w-100" input-class="form-control form-control-reset"
                            :editable="false" format="YYYY/MM/DD">
                        </date-picker>
                    </div>
                </div>
                <!-- End - Input Datepicker -->

            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">入力サンプル</div>
    <div class="card-body">
        <div class="card-subheader01">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="" id="" value="1" data-id="A42-1">
                <label class="form-check-label" for="">宅地 該当</label>
            </div>
            <div class="totals">
                <div class="box">
                    <label>合計</label>
                    <div class="value" data-id="A42-19">00<span class="unit">筆</span></div>
                </div>
                <div class="box">
                    <label>登記</label>
                    <div class="value" data-id="A42-19">00.00<span class="unit">m<sup>2</sup></span></div>
                </div>
                <div class="box">
                    <label>実測</label>
                    <div class="value" data-id="A42-19">00.00<span class="unit">m<sup>2</sup></span></div>
                </div>
            </div>
        </div>


        <table class="table table-hover table-bordered table-small table-parcel-list">
            <thead>
                <tr>
                    <th class="parcel_address">所在</th>
                    <th class="parcel_number">地番</th>
                    <th class="parcel_land_category">地目</th>
                    <th class="parcel_use_district">用途地域</th>
                    <th class="parcel_build_ratio">建ぺい率</th>
                    <th class="parcel_floor_ratio">容積率</th>
                    <th class="parcel_size">地積(登記)</th>
                    <th class="parcel_survey_size">地積(実測)</th>
                    <th class="parcel_project_owner">所有者/持分</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(table, tb_index) in form.table.data">
                    <td>
                        <div class="form-group">
                            <select v-model="table.column1.input1" class="form-control form-control-sm" name="">
                                <option value="0">市区町村</option>
                                <option value="1">仙台市青葉区</option>
                                <option value="2">仙台市宮城野区</option>
                                <option value="3">その他</option>
                                <option value="4">地域マスタより宮城の地域取得</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input v-model="table.column1.input2" class="form-control form-control-w-xl form-control-sm" name="" type="text" placeholder="その他市区町村">
                        </div>
                        <div class="form-group">
                            <input v-model="table.column1.input3" class="form-control form-control-w-xl form-control-sm" name="" type="text" placeholder="町域">
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <span>
                                <input v-model="table.column2.input1" class="form-control form-control-w-xs form-control-sm" maxlength="3" name="" type="text">
                            </span>
                            <span>番</span>
                            <span>
                                <input v-model="table.column2.input2" class="form-control form-control-w-xs form-control-sm" maxlength="3" name="" type="text">
                            </span>
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <select v-model="table.column3.input1" class="form-control form-control-1btn form-control-sm" name="">
                                <option value="" selected="selected"></option>
                                <option value="1">宅地</option>
                                <option value="6">公衆用道路</option>
                                <option value="7">マスタデータより取得</option>
                            </select>
                        </div>
                    </td>

                    <td>
                        <div v-for="(input, index) in table.column4.inputAddable" class="form-group">
                            <select v-model="input.model" class="form-control form-control-w-md form-control-sm" name="">
                                <option value="" selected="selected"></option>
                                <option value="1">第一種低層住居専用地域</option>
                                <option value="11">第二種低層住居専用地域</option>
                                <option value="12">マスタデータより取得</option>
                            </select>
                            <span v-if="index == 0">
                                <i @click="addField(table.column4.inputAddable)" class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="用途地域追加"></i>
                            </span>
                            <span v-else>
                                <i @click="removeField(table.column4.inputAddable,index)" class="remove_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="所有者削除"></i>
                            </span>
                            <span v-if="index != 0">
                                <i @click="copyField(table.column4.inputAddable,index)" class="copy_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip" title="" data-original-title="所在コピー"></i>
                            </span>
                        </div>
                    </td>

                    <td>
                        <div v-for="(input, index) in table.column5.inputAddable" class="form-group my-3">
                            <div class="row mx-0 flex-nowrap">
                                <div class="px-0 col">
                                    <input v-model="input.model" class="form-control form-control-w-sm form-control-sm" name="" type="text">
                                </div>
                                <div class="px-0 col-auto d-flex flex-column justify-content-center my-n1">
                                    <div class="px-1" v-if="index == 0">
                                        <i @click="addField(table.column5.inputAddable)" class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="用途地域追加"></i>
                                    </div>
                                    <div class="px-1" v-else>
                                        <i @click="removeField(table.column5.inputAddable,index)" class="remove_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="所有者削除"></i>
                                    </div>
                                    <div class="px-1" v-if="index != 0">
                                        <i @click="copyField(table.column5.inputAddable,index)" class="copy_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip" title="" data-original-title="所在コピー"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div v-for="(input, index) in table.column6.inputAddable" class="form-group my-3">
                            <div class="row mx-0 flex-nowrap">
                                <div class="px-0 col">
                                    <input v-model="input.model" class="form-control form-control-w-sm form-control-sm" name="" type="text">
                                </div>
                                <div class="px-0 col-auto d-flex flex-column justify-content-center my-n1">
                                    <div class="px-1" v-if="index == 0">
                                        <i @click="addField(table.column6.inputAddable)" class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="用途地域追加"></i>
                                    </div>
                                    <div class="px-1" v-else>
                                        <i @click="removeField(table.column6.inputAddable,index)" class="remove_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="所有者削除"></i>
                                    </div>
                                    <div class="px-1" v-if="index != 0">
                                        <i @click="copyField(table.column6.inputAddable,index)" class="copy_button far fa-copy cur-pointer text-secondary" data-toggle="tooltip" title="" data-original-title="所在コピー"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <span>
                                <input v-model="table.column7.input1" class="form-control form-control-w-sm form-control-sm" name="" type="text">
                            </span>
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <span>
                                <input v-model="table.column8.input1" class="form-control form-control-w-sm form-control-sm" name="" type="text">
                            </span>
                        </div>
                    </td>

                    <td>
                        <div v-for="(input, index) in table.column9" class="form-group">
                            <select v-model="input.input1" class="form-control form-control-w-lg form-control-sm" name="">
                                <option value="" selected="selected"></option>
                                <option value="1">鈴木一郎</option>
                                <option value="11">鈴木二郎</option>
                                <option value="12">登録所有者より取得</option>
                            </select>
                            <span>
                                <input v-model="input.input2" class="form-control form-control-w-xs form-control-sm" name="" type="text">
                            </span>
                            <span>分の</span>
                            <span>
                                <input v-model="input.input3" class="form-control form-control-w-xs form-control-sm" name="" type="text">
                            </span>
                            <span v-if="index == 0">
                                <i @click="addField(table.column9)" class="fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="用途地域追加"></i>
                            </span>
                            <span v-else>
                                <i @click="removeField(table.column9,index)" class="remove_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="所有者削除"></i>
                            </span>
                        </div>

                        <div v-if="tb_index == 0" class="row-control-buttons">
                            <i @click="addTable" class="add_row_button fa fa-plus-circle cur-pointer text-primary" data-toggle="tooltip" title="" data-original-title="行を追加"></i>
                        </div>
                        <div v-else class="row-control-buttons">
                            <i @click="removeTable(tb_index)" class="remove_row_button fa fa-minus-circle cur-pointer text-danger" data-toggle="tooltip" title="" data-original-title="行を削除"></i>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</div>


@endsection

@push('vue-scripts')
<script>
mixin = {
    data: function () {
        return {
            form: {
                addable: [
                    { model: '' }
                ],
                table:{
                    name: '',
                    data: [
                        {
                            column1: {
                                input1: 0,
                                input2: '',
                                input3: ''
                            },
                            column2: {
                                input1: '',
                                input2: ''
                            },
                            column3: {
                                input1: ''
                            },
                            column4: {
                                inputAddable: [
                                    { model: '' }
                                ]
                            },
                            column5: {
                                inputAddable: [
                                    { model: '' }
                                ]
                            },
                            column6: {
                                inputAddable: [
                                    { model: '' }
                                ]
                            },
                            column7: {
                                input1: ''
                            },
                            column8: {
                                input1: ''
                            },
                            column9: [
                                {
                                    input1: '',
                                    input2: '',
                                    input3: ''
                                }
                            ]
                        }
                    ]
                }
            },
            input: {
                int: {
                    thousands: ',',
                    precision: 0
                },
                decimal: {
                    thousands: '',
                    decimal: '.',
                    precision: 2
                },
                money: {
                    thousands: '.',
                    decimal: ',',
                    precision: 2
                },
                datepicker: ''
            },
        }
    },
    methods: {
        // addable input method
        addInput: function() {
            this.form.addable.push({ model : '' });
        },
        removeInput: function(index) {
            this.form.addable.splice(index, 1);
            $('[data-toggle="tooltip"]').tooltip('hide');
        },

        // addable table
        addTable: function() {
            let vm = this;
            let data = JSON.parse(JSON.stringify({column1:{input1:0,input2:'',input3:''},column2:{input1:'',input2:''},column3:{input1:''},column4:{inputAddable:[{model:''}]},column5:{inputAddable:[{model:''}]},column6:{inputAddable:[{model:''}]},column7:{input1:''},column8:{input1:''},column9:[{input1:'',input2:'',input3:''}]}));
            this.form.table.data.push(data);
        },
        removeTable: function(index) {
            this.form.table.data.splice(index, 1);
        },
        addField: function(input) {
            console.log(input)
            input.push({model : ''});
        },
        removeField: function(input, index) {
            console.log(input)
            input.splice(index, 1);
        },
        copyField: function(column, index) {
            let prevValue = column[index-1];
            column[index].model = prevValue.model;
        },


    }
}
</script>
@endpush
