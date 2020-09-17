<?php
// --------------------------------------------------------------------------
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
// --------------------------------------------------------------------------
class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     * @return void
     */
    // ----------------------------------------------------------------------
    public function run(){
        // ------------------------------------------------------------------
        /** Clear Uploads File **/
        $path = public_path('uploads');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $file = new Filesystem;
        if( !$file->exists( $path )){ $file->makeDirectory( $path );}
        $file->cleanDirectory( public_path('uploads') );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Preset master values
        // ------------------------------------------------------------------
        $this->call( MasterValuesSeeder::class );
        $this->call( MasterRegionsSeeder::class );
        // ------------------------------------------------------------------
        $this->call( UserRoleSeeder::class );
        $this->call( CompanySeeder::class );
        $this->call( UserSeeder::class );
        // ------------------------------------------------------------------
        // $this->call(NewsSeeder::class);
        // ------------------------------------------------------------------
        // $this->call(LogActivitySeeder::class);
        $this->call( QAManagerTableSeeder::class );
        // ------------------------------------------------------------------
        $this->call( PjSheetCalculateSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Projcet Seeder
        // ------------------------------------------------------------------
        $this->call( ProjectSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Memo
        // ------------------------------------------------------------------
        $this->call( PjMemoSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Basic and Additional Q&A Seeders
        // ------------------------------------------------------------------
        $this->call( PjBasicQaSeeder::class );
        $this->call( PjAdditionalQaSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Sheet Seeders
        // ------------------------------------------------------------------
        $this->call( PjSheetSeeder::class );
        $this->call( PjSheetChecklistSeeder::class );
        $this->call( PjSheetStockSeeder::class );
        $this->call( PjSheetSaleSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Assist A Seeders
        // ------------------------------------------------------------------
        $this->call( PjAssistASeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Purchase Seeder
        // ------------------------------------------------------------------
        // $this->call( PjPurchaseSaleSeeder::class );
        $this->call( PjPurchaseSaleSeeder::class );
        $this->call( PjLotPurchaseSeeder::class );
        $this->call( PjPurchaseSeeder::class );
        // $this->call( PjPurchaseTargetSeeder::class );
        // $this->call( PjPurchaseTargetBuildingSeeder::class );
        // $this->call( PjPurchaseDocSeeder::class );
        // $this->call( PjPurchaseContractSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Project Expense Seeder
        // ------------------------------------------------------------------
        $this->call( PjExpenseSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Master Setting Seeder
        // ------------------------------------------------------------------
        $this->call( MasterSettingSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Master Section Seeder
        // ------------------------------------------------------------------
        $this->call( MasterSectionSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Master Basic and Mas Lot Seeder
        // ------------------------------------------------------------------
        $this->call( MasterBasicSeeder::class );
        $this->call( MasLotBuildingSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Section Sale Seeder
        // ------------------------------------------------------------------
        $this->call( SectionSaleSeeder::class );        
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Sale Contract, Mediation, Fee, Deposit Seeder
        // Purchase and Product Residence Seeder
        // ------------------------------------------------------------------
        $this->call( SaleContractSeeder::class );
        $this->call( SaleMediationSeeder::class );
        $this->call( SaleFeeSeeder::class );
        $this->call( SaleContractDepositSeeder::class );
        $this->call( SalePurchaseSeeder::class );
        $this->call( SaleProductResidenceSeeder::class );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Request inspection seeder
        // ------------------------------------------------------------------
        // $this->call( RequestInspectionSeeder::class );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
