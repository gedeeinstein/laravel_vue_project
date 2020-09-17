<template v-if="inspection">
  <div class="alert mt-3
      @if (Auth::user()->user_role->name == 'global_admin') alert-warning bg-cream @endif"
      @if (Auth::user()->user_role->name != 'global_admin') style="border: 1px solid #DBDBDB;" @endif
      role="alert" data-id="A87-5"
  >

     <p><strong>@{{ inspection.request_date }} @{{ inspection.request_hour }} @{{ inspection.user_fullname }}</strong>　
         @if (Auth::user()->user_role->name == 'global_admin')
             のリクエストをどう処理しますか?
         @endif
     </p>

     @if (Auth::user()->user_role->name == 'global_admin')
       <template v-if="inspection">
         <div class="form-check icheck-cyan form-check-inline">
            <input v-model="inspection.examination" class="form-check-input" type="radio" name="" id="S2-1_1" value="1">
            <label class="form-check-label" for="S2-1_1">未承認</label>
         </div>
         <div class="form-check icheck-cyan form-check-inline">
            <input v-model="inspection.examination" class="form-check-input" type="radio" name="" id="S2-1_2" value="2">
            <label class="form-check-label" for="S2-1_2">承認</label>
         </div>
         <div class="form-check icheck-cyan form-check-inline">
            <input v-model="inspection.examination" class="form-check-input" type="radio" name="" id="S2-1_3" value="3">
            <label class="form-check-label" for="S2-1_3">非承認</label>
         </div>
         <button @click="approvalRequest" type="submit" class="btn btn-wide btn-info px-4 mr-1">
           <i v-if="!initial.approval" class="fw-l far fa-check-circle"></i>
           <i v-else class="fas fa-spinner fa-spin"></i>
           送信
         </button>
       </template>
     @else
         <template v-if="inspection">
           <div v-for="n in 3" class="form-check icheck-cyan form-check-inline fs-22">
               <!-- start active checkbox -->
               <template v-if="inspection.examination == n && n == 1">
                   <i class="fas fa-check-circle"></i>
                   <div class="px-1 d-flex align-items-center"><span class="fs-13 fw-b">未承認</span></div>
               </template>
               <template v-if="inspection.examination == n && n == 2">
                   <i class="fas fa-check-circle"></i>
                   <div class="px-1 d-flex align-items-center"><span class="fs-13 fw-b">承認</span></div>
               </template>
               <template v-if="inspection.examination == n && n == 3">
                   <i class="fas fa-check-circle"></i>
                   <div class="px-1 d-flex align-items-center"><span class="fs-13 fw-b">非承認</span></div>
               </template>
               <!-- end active checkbox -->

               <!-- start inactive checkbox -->
               <template v-else-if="inspection.examination != n && n == 1">
                   <i class="fal fa-circle"></i>
                   <div class="px-1 d-flex align-items-center"><span class="fs-13 fw-b">未承認</span></div>
               </template>
               <template v-else-if="inspection.examination != n && n == 2">
                   <i class="fal fa-circle"></i>
                   <div class="px-1 d-flex align-items-center"><span class="fs-13 fw-b">承認</span></div>
               </template>
               <template v-else-if="inspection.examination != n && n == 3">
                   <i class="fal fa-circle"></i>
                   <div class="px-1 d-flex align-items-center"><span class="fs-13 fw-b">非承認</span></div>
               </template>
               <!-- end inactive checkbox -->
           </div>
         </template>
     @endif
  </div>
</template>
