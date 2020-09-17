<?php

use Illuminate\Database\Seeder;
use App\Models\OtherAdditionalQaCheck;
use App\Models\OtherAdditionalQaCategory;
use Carbon\Carbon;

class QAManagerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $other_additional_qa_categories = array(
            array('name' => '共通項目',
                  'order' => '1',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),
            array('name' => 'カテゴリ名2',
                  'order' => '2',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),
            array('name' => 'カテゴリ名3',
                  'order' => '3',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now())
        );
        $qa_manager_category = new OtherAdditionalQaCategory();
        $qa_manager_category->insert($other_additional_qa_categories);

        
        $other_additional_qa_checks = array(
            array('category_id' => '1',
                  'question' => '売り主負担での確定測量及び地積更生登記を条件としているか？',
                  'input_type' => '1',
                  'choices' => '',
                  'status' => '1',
                  'order' => '1',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),
            array('category_id' => '1',
                  'question' => '質問票は回収しているか？',
                  'input_type' => '1',
                  'choices' => '',
                  'status' => '1',
                  'order' => '2',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),
            array('category_id' => '1',
                  'question' => '地区計画はあるか？',
                  'input_type' => '1',
                  'choices' => '',
                  'status' => '1',
                  'order' => '3',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),

            array('category_id' => '2',
                  'question' => '線路から50m以上離れているか？',
                  'input_type' => '1',
                  'choices' => '',
                  'status' => '1',
                  'order' => '1',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),
            array('category_id' => '2',
                  'question' => '無効状態の質問はどのように表示されますか？',
                  'input_type' => '1',
                  'choices' => '',
                  'status' => '1',
                  'order' => '2',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),

            array('category_id' => '3',
                  'question' => '無効状態の質問はどのように表示されますか？',
                  'input_type' => '2',
                  'choices' => '',
                  'status' => '1',
                  'order' => '1',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now()),
            array('category_id' => '3',
                  'question' => '質問票は回収しているか？',
                  'input_type' => '2',
                  'choices' => '',
                  'status' => '1',
                  'order' => '2',
                  'created_at' => Carbon::now(),
                  'updated_at' => Carbon::now())
        );
        $qa_manager = new OtherAdditionalQaCheck();
        $qa_manager->insert($other_additional_qa_checks);

        OtherAdditionalQaCategory::all()->each( function( $category ){
            factory( OtherAdditionalQaCheck::class, 6 )->create([ 'category_id' => $category->id ]);

            $questions = OtherAdditionalQaCheck::where( 'category_id', $category->id )->get();
            $questions->each( function( $question, $key ){
                  $question->order = $key +1;
                  $question->save();
            });
        });
    }
}
