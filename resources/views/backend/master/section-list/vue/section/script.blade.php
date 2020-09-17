<script> @minify
// --------------------------------------------------------------------------
(function( $, io, document, window, undefined ){
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    var toast = {
        heading: 'error', icon: 'error',
        stack: false, hideAfter: 3000,
        position: { right: 18, top: 68 }
    };
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Section entry component
    // ----------------------------------------------------------------------
    Vue.component( 'section-entry', {
        // ------------------------------------------------------------------
        props: [ 'value', 'index' ],
        template: '#section-entry',
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // On mounted
        // ------------------------------------------------------------------
        mounted: function(){
            $('[data-toggle="tooltip"]').tooltip();
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Reactive data
        // ------------------------------------------------------------------
        data: function(){
            // --------------------------------------------------------------
            var data = {
                entry: this.value,
                status: { loading: false },
                project: this.value.project || {},
                repayments: []
            };
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Repayments
            // --------------------------------------------------------------
            var entry = data.entry;
            var units = io.get( data.project, 'finance.units' );
            // --------------------------------------------------------------
            if( units && units.length ) $.each( units, function( u, unit ){
                if( entry.section_number && unit.repayments ){
                    // ------------------------------------------------------
                    var repayments = io.filter( unit.repayments, function(o){
                        return o.section_number == entry.section_number;
                    });
                    // ------------------------------------------------------
                    if( repayments && repayments.length ){
                        data.repayments = data.repayments.concat( repayments );
                    }
                    // ------------------------------------------------------
                }
            });
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // console.log( data );
            return data;
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Computed properties
        // ------------------------------------------------------------------
        computed: {
            // --------------------------------------------------------------
            // Disabled state
            // --------------------------------------------------------------
            isDisabled: function(){ return this.status.loading },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Project Title
            // --------------------------------------------------------------
            projectTitle: function(){
                return io.get( this.project, 'title' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // PJ Sheet URL
            // --------------------------------------------------------------
            sheetURL: function(){
                return io.get( this.project, 'url.sheet' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Organizer company abbreviation
            // https://bit.ly/37RJJWE 
            // https://bit.ly/3en9aS5 - company.kind_In_house_abbreviation (G128-1)
            // --------------------------------------------------------------
            organizerAbbr: function(){
                return io.get( this.project, 'purchase_sale.organizer.kind_in_house_abbreviation' );
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Sales person / Buyer staffs
            // https://bit.ly/3fXUNnZ
            // --------------------------------------------------------------
            buyerStaffs: function(){
                // ----------------------------------------------------------
                var result = [];
                var staffs = io.get( this.project, 'purchase_sale.buyer_staffs' );
                // ----------------------------------------------------------
                if( staffs && staffs.length ) $.each( staffs, function( i, staff ){
                    var user = io.get( staff, 'user' );
                    if( user ) result.push( user );
                }); 
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // Section staffs
            // https://bit.ly/3fXUNnZ
            // --------------------------------------------------------------
            sectionStaffs: function(){
                var staff = io.get( this.entry, 'sale.staff' );
                return staff ? [ staff ] : [];
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Remaining loan days from the earliest loan date
            // https://bit.ly/3ezdGgq
            // --------------------------------------------------------------
            loanDays: function(){
                // ----------------------------------------------------------
                var loanDates = []; var today = moment();
                var earliest = null; var range = null;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get loan date collection
                // ----------------------------------------------------------
                var units = io.get( this.project, 'finance.units' );
                if( units ) $.each( units, function( u, unit ){
                    // ------------------------------------------------------
                    var moneys = io.get( unit, 'moneys' );
                    if( moneys ) $.each( moneys, function( m, money ){
                        if( money.loan_date ) loanDates.push( money.loanDate );
                    });
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get the earliest date
                // ----------------------------------------------------------
                $.each( loanDates, function( d, date ){
                    date = moment( date );
                    if( !earliest ){ earliest = date; return }
                    // ------------------------------------------------------
                    if( earliest && date.isSameOrAfter( earliest )) earliest = date;
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                // Count days from the earliest date to current date
                // ----------------------------------------------------------
                if( earliest ) range = today.diff( earliest, 'days' );
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                return range;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Tsubo of section-sale area-size
            // https://bit.ly/3imrSfg
            // --------------------------------------------------------------
            tsuboArea: function(){
                // ----------------------------------------------------------
                var result = null;
                var area = io.get( this.entry, 'sale.area_size' );
                // ----------------------------------------------------------
                if( area ) result = Vue.filter('tsubo')( area );
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Book price
            // https://bit.ly/3g9dVPU
            // --------------------------------------------------------------
            bookPrice: function(){
                var price = io.get( this.entry, 'book_price' );
                return price;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Sale contract price
            // https://bit.ly/3g6NnhX
            // --------------------------------------------------------------
            contractPrice: function(){
                var price = io.get( this.entry, 'contract.contract_price' );
                return price;
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Loan balance
            // https://bit.ly/3icoMKM
            // --------------------------------------------------------------
            loanBalance: function(){
                var result = null;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Process I
                // ----------------------------------------------------------
                var proceed = true;
                $.each( this.repayments, function( r, repayment ){
                    if( repayment.money && 3 === repayment.status ){
                        proceed = false; return false;
                    }
                }); if( !proceed ) return result;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Process II
                // ----------------------------------------------------------
                var loanMoney = 0; // A
                var repaymentMoney = 0; // B
                // ----------------------------------------------------------
                // Get total of loan-money (B23-4)
                // ----------------------------------------------------------
                var units = io.get( this.project, 'finance.units' );
                if( units ) $.each( units, function( u, unit ){
                    // ------------------------------------------------------
                    var moneys = io.get( unit, 'moneys' );
                    if( moneys ) $.each( moneys, function( m, money ){
                        var status = money.loan_status;
                        if( 4 === status || 5 === status ) loanMoney += money.loan_money;
                    });
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
                // Get total of repayment money (B23-21)
                // ----------------------------------------------------------
                $.each( this.repayments, function( r, repayment ){
                    if( 3 === repayment.status ) repaymentMoney += repayment.money;
                });
                // ----------------------------------------------------------
                // Get number of section with repayment status dees not equal to 3 
                // ----------------------------------------------------------
                var counter = 0; // B
                var projectSections = io.get( this, 'project.sections' );
                var projectRepayments = io.get( this, 'project.repayments' );
                // ----------------------------------------------------------
                if( projectSections ) $.each( projectSections, function( s, section ){
                    // ------------------------------------------------------
                    var done = true;
                    var repayments = io.filter( projectRepayments, { section_number: section.section_number });
                    // ------------------------------------------------------
                    if( repayments ) $.each( repayments, function( r, repayment ){
                        if( 3 !== repayment.status ){
                            done = false; return false;
                        }
                    }); if( !done ) counter++;
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------
                if( counter && loanMoney && repaymentMoney ){
                    result = ( loanMoney - repaymentMoney ) / counter;
                    return result;
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Process III
                // ----------------------------------------------------------
                var exit = false;
                var units = io.get( this.project, 'finance.units' );
                if( units ) $.each( units, function( u, unit ){
                    // ------------------------------------------------------
                    var moneys = io.get( unit, 'moneys' );
                    if( moneys ) $.each( moneys, function( m, money ){
                        var status = money.loan_status;
                        if( 4 !== status && 5 !== status ){
                            result = 0; exit = true;
                            return false;
                        }
                    });
                    // ------------------------------------------------------
                    if( exit ) return false;
                });
                // ----------------------------------------------------------
                if( exit ) return result;
                // ----------------------------------------------------------
                
                // ----------------------------------------------------------
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Profit value
            // C23-2 + C25-4 + C26-6 - B15-12
            // https://bit.ly/2B8FI40
            // --------------------------------------------------------------
            profit: function(){
                // ----------------------------------------------------------
                var result = null, section = this.entry;
                var contractPrice = this.contractPrice; // C23-2
                var bookPrice = this.bookPrice; // B15-12
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Collect all mediations
                // ----------------------------------------------------------
                var mediations = io.get( section, 'contract.mediations' ) || [];
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Get total of mediation reward
                // ----------------------------------------------------------
                var totalReward = 0; // total of C25-4
                $.each( mediations, function( m, mediation ){
                    // ------------------------------------------------------
                    var reward = mediation.reward;
                    var status = mediation.status;
                    var balance = mediation.balance;
                    // ------------------------------------------------------
                    if( 3 !== status ) return;
                    if( 2 !== balance || 3 !== balance ) return;
                    // ------------------------------------------------------
                    reward = Math.abs( reward );
                    if( 2 === balance || 3 === balance ) totalReward -= reward;
                    else totalReward += reward;
                    // ------------------------------------------------------
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Sale fee and prices
                // ----------------------------------------------------------
                var totalPrice = 0; // total of C26-6
                var saleFee = io.get( section, 'contract.fee' );
                if( saleFee ){
                    // ------------------------------------------------------
                    var feePrices = io.get( saleFee, 'prices' );
                    if( feePrices && feePrices.length ) $.each( feePrices, function( f, feePrice ){
                        // --------------------------------------------------
                        var status = feePrice.status;
                        var balance = saleFee.balance;
                        var price = feePrice.price;
                        // --------------------------------------------------
                        if( 3 !== status ) return;
                        if( 2 !== balance || 3 !== balance ) return;
                        // --------------------------------------------------
                        price = Math.abs( price );
                        if( 2 === balance || 3 === balance ) totalPrice -= price;
                        else totalPrice += price;
                        // --------------------------------------------------
                    });
                    // ------------------------------------------------------
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Calculate the result
                // C23-2 + C25-4 + C26-6 - B15-12
                // ----------------------------------------------------------
                result = contractPrice + totalReward + totalPrice - bookPrice;
                // console.log( contractPrice, totalReward, totalPrice, bookPrice, result );
                return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Financing (Pre)
            // https://bit.ly/322NxDn
            // --------------------------------------------------------------
            financing: function(){
                // ----------------------------------------------------------
                var result = null;
                var entry = this.entry;
                var units = this.project.units;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                $.each( units, function( unitIndex, unit ){
                    // ------------------------------------------------------
                    var unitChecking = false;
                    // ------------------------------------------------------
                    // Find if unit has finished loan-money
                    // ------------------------------------------------------
                    var finishedLoans = io.filter( unit.moneys, function(o){
                        return 4 === o.loan_status || 5 === o.loan_status;
                    });
                    // ------------------------------------------------------
                    if( !finishedLoans ){
                        unitChecking = true;
                        var nextUnit = units[ unitIndex +1 ]; // Next unit
                        if( nextUnit ) return;
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // B23-25 checking
                    // ------------------------------------------------------
                    if( !unitChecking ){
                        var repayment = io.find( unit.repayments, function(o){
                            return o.section_number == entry.section_number;
                        });
                        var status = io.get( repayment, 'status' );
                        if( 3 === status ){ result = null; return false }
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // [A] Total loan processing
                    // ------------------------------------------------------
                    var totalLoan = 0;
                    var moneys = unit.moneys;
                    // ------------------------------------------------------
                    $.each( moneys, function( m, money ){
                        var status = money.loan_status;
                        if( 4 !== status && 5 !== status ) totalLoan += money.loan_money;
                    }); if( !totalLoan ){ result = null; return false }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Number of section with unfinished repayment under the same project
                    // ------------------------------------------------------
                    var numberSection = 0;
                    var sections = io.get( this.project, 'sections' );
                    $.each( sections, function( s, section ){
                        var repayment = io.filter( this.project, 'repayments', function(o){
                            return o.section_number == section.section_number;
                        });
                        var status = io.get( repayment, 'status' );
                        if( 3 !== status ) numberSection++;
                    }); if( !numberSection ){ result = null; return false }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // Calculate total result
                    // ------------------------------------------------------
                    if( totalLoan && numberSection ){
                        result = totalLoan / numberSection;
                        return false;
                    }
                    // ------------------------------------------------------
                }); return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Repayment (Preliminary)
            // https://bit.ly/2Zi66BK
            // --------------------------------------------------------------
            repayment: function(){
                // ----------------------------------------------------------
                var entry = this.entry;
                var units = this.project.units;
                var exit = false; var result = null;
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Checking section repayment money
                // ----------------------------------------------------------
                $.each( units, function( unitIndex, unit ){
                    var repayment = io.find( unit.repayments, function(o){
                        return o.section_number == entry.section_number;
                    });
                    var money = io.get( repayment, 'money' );
                    if( !money ){
                        // --------------------------------------------------
                        // Checking next unit
                        // --------------------------------------------------
                        var nextUnit = units[ unitIndex +1 ]; // Next unit
                        if( nextUnit ) return;
                        else { result = 0; return false }
                        // --------------------------------------------------
                    }
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    // If repyament money is inputted
                    // ------------------------------------------------------
                    var status = io.get( repayment, 'status' );
                    if( 3 === status ) result = null;
                    else result = money;
                    // ------------------------------------------------------

                    // ------------------------------------------------------
                    return false;
                    // ------------------------------------------------------
                }); return result;
                // ----------------------------------------------------------
            },
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Section sale statuses
            // --------------------------------------------------------------
            saleNet: function(){ return io.get( this.entry, 'sale.section_net' )},
            saleMediation: function(){ return io.get( this.entry, 'sale.section_mediation' )},
            saleCondition: function(){ return io.get( this.entry, 'sale.section_condition' )},
            saleSignboard: function(){ return io.get( this.entry, 'sale.section_signboard' )},
            saleRank: function(){ return io.get( this.entry, 'sale.section_staff' )},
            // --------------------------------------------------------------


            // --------------------------------------------------------------
            // URL to Section Payoff
            // --------------------------------------------------------------
            sectionPayoffURL: function(){
                var entry = io.get( this, 'entry.id' );
                var project = io.get( this, 'project.id' );
                return '/master/' +project+ '/section/' +entry+ '/payoff';
            }
            // --------------------------------------------------------------
        },
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Component methods
        // ------------------------------------------------------------------
        methods: {}
        // ------------------------------------------------------------------
    });
    // ----------------------------------------------------------------------
}( jQuery, _, document, window ));
// --------------------------------------------------------------------------
@endminify </script>