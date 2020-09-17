<script type="text/x-template" id="important-note-earth-and-sand">
    <div class="form-group row">
        <label for="" class="col-3 col-form-label" style="font-weight: normal;">土砂災害防止策推進法</label>
        <div class="col-9">
            <div class="row mb-1">
                <div class="col-4">土砂災害警戒区域</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.earth_and_sand_vigilance" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="earth_and_sand_vigilance_1" value="1" data-id="A1311-58">
                        <label class="form-check-label" for="earth_and_sand_vigilance_1">外</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.earth_and_sand_vigilance" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="earth_and_sand_vigilance_2" value="2">
                        <label class="form-check-label" for="earth_and_sand_vigilance_2">内</label>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-4">土砂災害特別警戒区域</div>
                <div class="col-8">
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.earth_and_sand_special_vigilance" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="earth_and_sand_special_vigilance_1" value="1" data-id="A1311-59">
                        <label class="form-check-label" for="earth_and_sand_special_vigilance_1">外</label>
                    </div>
                    <div class="form-check form-check-inline icheck-cyan">
                        <input v-model="entry.earth_and_sand_special_vigilance" :disabled="isDisabled || isCompleted"
                        class="form-check-input" type="radio" id="earth_and_sand_special_vigilance_2" value="2">
                        <label class="form-check-label" for="earth_and_sand_special_vigilance_2">内</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
</script>
