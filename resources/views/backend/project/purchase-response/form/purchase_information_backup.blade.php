{{-- looping with contractor --}}
{{----------------------------------------------------------------------------}}
<div class="row pt-1">
   <div class="col-12">
      <div class="card card-project">
         <div class="card-header">@lang('pj_purchase_response.purchase_information')</div>
         <div class="form-group row p-1 mb-0">
            <div class="col-12">
               <div class="card-subheader01">
                  <div class="form-check icheck-cyan form-check-inline">
                     <strong>@lang('pj_purchase_response.purchase_no'){{ $purchase_target->purchase_number }}</strong>
                  </div>
               </div>
               <div>
                  <table class="table table-bordered table-small table-parcel-list mt-0">
                     <thead>
                        <tr>
                           <th>@lang('pj_purchase_response.contractor')</th>
                           <th>@lang('pj_purchase_response.owner')</th>
                           <th>@lang('pj_purchase_response.classification')</th>
                           <th>@lang('pj_purchase_response.location')</th>
                           <th>@lang('pj_purchase_response.equity')</th>
                           <th>@lang('pj_purchase_response.type')</th>
                           <th>@lang('pj_purchase_response.land_area')</th>
                           <th>@lang('pj_purchase_response.purchase_equity')</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($purchase_target_contractors_group_by_name as $key => $purchase_target_contractors)
                        <tr>
                           <td @if ($purchase_target_contractors['rowspan'] > 1) rowspan="{{ $purchase_target_contractors['rowspan'] }}" @endif>
                             <div class="form-group">
                                <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $purchase_target_contractors[0]->contractor->name }}" readonly="readonly">
                             </div>
                           </td>
                           @foreach ($purchase_target_contractors as $key => $purchase_target_contractor)
                           {{-- residential --}}
                           {{-------------------------------------------------}}
                           @if ($purchase_target_contractor['residential'] != null)
                           @foreach ($purchase_target_contractor['residential']->residential_owners as $key => $residential_owner)
                           <td>
                              <div class="form-group">
                                 <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $residential_owner->property_owner->name }}" readonly="readonly">
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="宅地" readonly="readonly">
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <input class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['residential']->parcel_city ." ". $purchase_target_contractor['residential']->parcel_city_extra ." ". $purchase_target_contractor['residential']->parcel_town ." ". $purchase_target_contractor['residential']->parcel_number_first ." ". $purchase_target_contractor['residential']->parcel_number_second }}" readonly="readonly">
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $residential_owner->share_denom }}" readonly="readonly">
                                 分の
                                 <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $residential_owner->share_number }}" readonly="readonly">
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly">
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['residential']->parcel_size }}" readonly="readonly"> m<sup>2</sup>
                              </div>
                           </td>
                           <td>
                              <div class="form-group">
                                 <div class="form-check icheck-cyan form-check-inline">
                                    <input class="form-check-input" type="radio" name="" id="" value="1" disabled="disabled" @if ($purchase_target_contractor['doc']->purchase_equity == 1) checked @endif>
                                    <label class="form-check-label" for="">全部</label>
                                 </div>
                                 <div class="form-check icheck-cyan form-check-inline">
                                    <input class="form-check-input" type="radio" name="" id="" value="2" disabled="disabled" @if ($purchase_target_contractor['doc']->purchase_equity == 2) checked @endif>
                                    <label class="form-check-label" for="">一部</label>
                                 </div>
                              </div>
                              @if ($purchase_target_contractor['doc']->purchase_equity == 2)
                              <input class="form-control form-control-w-xl form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['doc']->purchase_equity_text }}" readonly="readonly">
                              @endif
                           </td>
                           @endforeach
                           @endif
                           {{-------------------------------------------------}}
                        @if ($purchase_target_contractors['rowspan'] > 1) </tr> @endif
                        {{-- road --}}
                        {{----------------------------------------------------}}
                        @if ($purchase_target_contractor['road'] != null)
                        @foreach ($purchase_target_contractor['road']->road_owners as $key => $road_owner)
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $road_owner->property_owner->name }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="道路" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['road']->parcel_city ." ". $purchase_target_contractor['road']->parcel_city_extra ." ". $purchase_target_contractor['road']->parcel_town ." ". $purchase_target_contractor['road']->parcel_number_first ." ". $purchase_target_contractor['road']->parcel_number_second }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $road_owner->share_denom }}" readonly="readonly">
                              分の
                              <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $road_owner->share_number }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['road']->parcel_size }}" readonly="readonly"> m<sup>2</sup>
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <div class="form-check icheck-cyan form-check-inline">
                                 <input class="form-check-input" type="radio" name="" id="" value="1" disabled="disabled" @if ($purchase_target_contractor['doc']->purchase_equity == 1) checked @endif>
                                 <label class="form-check-label" for="">全部</label>
                              </div>
                              <div class="form-check icheck-cyan form-check-inline">
                                 <input class="form-check-input" type="radio" name="" id="" value="2" disabled="disabled" @if ($purchase_target_contractor['doc']->purchase_equity == 2) checked @endif>
                                 <label class="form-check-label" for="">一部</label>
                              </div>
                           </div>
                           @if ($purchase_target_contractor['doc']->purchase_equity == 2)
                           <input class="form-control form-control-w-xl form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['doc']->purchase_equity_text }}" readonly="readonly">
                           @endif
                        </td>
                        @endforeach
                        @endif
                        @if ($purchase_target_contractors['rowspan'] > 1) </tr> @endif
                        {{----------------------------------------------------}}
                        {{-- building --}}
                        {{----------------------------------------------------}}
                        @if ($purchase_target_contractor['building'] != null)
                        @foreach ($purchase_target_contractor['building']->building_owners as $key => $building_owner)
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $building_owner->property_owner->name }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="[建物" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-xxxl form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['building']->parcel_city ." ". $purchase_target_contractor['building']->parcel_city_extra ." ". $purchase_target_contractor['building']->parcel_town ." ". $purchase_target_contractor['building']->parcel_number_first ." ". $purchase_target_contractor['building']->parcel_number_second ." (". $purchase_target_contractor['building']->parcel_number_second ." ". $purchase_target_contractor['building']->parcel_number_second ." ". $purchase_target_contractor['building']->parcel_number_second .")" }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $building_owner->share_denom }}" readonly="readonly">
                              分の
                              <input class="form-control form-control-w-xs form-control-sm" name="" type="text" value="{{ $building_owner->share_number }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['building']->building_usetype }}" readonly="readonly">
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <input class="form-control form-control-w-lg form-control-sm" name="" type="text" value="{{ number_format($purchase_target_contractor['building']->building_floors[0]->floor_size / $purchase_target_contractor['building']->building_floor_count, 4, '.', ',') }}" readonly="readonly"> m<sup>2</sup>
                           </div>
                        </td>
                        <td>
                           <div class="form-group">
                              <div class="form-check icheck-cyan form-check-inline">
                                 <input class="form-check-input" type="radio" name="" id="" value="1" disabled="disabled" @if ($purchase_target_contractor['doc']->purchase_equity == 1) checked @endif>
                                 <label class="form-check-label" for="">全部</label>
                              </div>
                              <div class="form-check icheck-cyan form-check-inline">
                                 <input class="form-check-input" type="radio" name="" id="" value="2" disabled="disabled" @if ($purchase_target_contractor['doc']->purchase_equity == 2) checked @endif>
                                 <label class="form-check-label" for="">一部</label>
                              </div>
                           </div>
                           @if ($purchase_target_contractor['doc']->purchase_equity == 2)
                           <input class="form-control form-control-w-xl form-control-sm" name="" type="text" value="{{ $purchase_target_contractor['doc']->purchase_equity_text }}" readonly="readonly">
                           @endif
                        </td>
                        @endforeach
                        @endif
                        {{----------------------------------------------------}}
                        @if ($purchase_target_contractors['rowspan'] > 1) </tr> @endif
                        @endforeach
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <!--border border-info-->
            </div>
            <!--col-12-->
         </div>
         <!--row-->
      </div>
      <!--card-->
   </div>
</div>
{{----------------------------------------------------------------------------}}
