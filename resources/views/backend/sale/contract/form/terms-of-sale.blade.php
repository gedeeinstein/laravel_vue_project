<div class="card mb-1">
    <div class="card-header">販売条件</div>
    <div class="card-body">
        <table class="table table-bordered table-small w-auto">

            <!-- start - mas_setting.condition_mediation -->
            <tr>
                <th class="bg-light-gray w-15">
                    <div data-id="C21-1">仲介条件</div>
                </th>
                <td class="w-15">@{{ setting.condition_mediation }}</td>
                <td v-if="setting.condition_mediation == 2">
                    @{{ setting.condition_mediation_contents }}
                </td>
            </tr>
            <!-- end - mas_setting.condition_mediation -->

            <!-- start - mas_setting.condition_build -->
            <tr>
                <th class="bg-light-gray">
                    <div data-id="C21-2">建築条件</div>
                </th>
                <td>@{{ setting.condition_build }}</td>
                <td v-if="setting.condition_build == 2">
                    @{{ setting.condition_build_contents }}
                </td>
            </tr>
            <!-- end - mas_setting.condition_build -->

        </table>
    </div>
</div>
