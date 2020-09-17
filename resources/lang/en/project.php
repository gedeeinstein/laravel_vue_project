<?php return [
    // ----------------------------------------------------------------------
    // Project Language Lines - English
    // ----------------------------------------------------------------------
    'manager'                          => [
        'page'                         => [
            'title'                    => 'Project Sheet Manager'
        ],
        'form'                         => [
            'phrase'                   => [
                'delete'               => [
                    'confirm'          => 'Are you sure to proceed?'
                ],
                'update'               => [
                    'success'          => 'Your changes have been successfully saved!',
                    'error'            => 'Sorry, your update could not be saved'
                ]
            ]
        ]
    ],
    'base'                             => [
        'tabs'                         => [
            'sheet'                    => 'PJ Sheet',
            'assist'                   => [
                'a'                    => 'Assist A',
                'b'                    => 'Assist B',
            ],
            'purchase'                 => [
                'index'                => 'Purchase',
                'sales'                => 'Purchase Sales',
                'contract'             => 'Purchase Contract'
            ],
            'expense'                  => 'Expense',
            'ledger'                   => 'Transaction Ledger'
        ]
    ],
    'sheet'                            => [
        'title'                        => 'PJ Sheet & Checklist',
        'status'                       => [
            'loading'                  => 'Loading'
        ],
        'tabs'                         => [
            'basic'                    => [
                'info'                 => 'Basic Information',
                'qna'                  => 'Basic Q&A'
            ],
            'group'                    => [
                'checklist'            => 'Checklist',
                'expense'              => 'Purchasing Department',
                'sale'                 => 'Sales Department'
            ],
            'options'                  => [
                'duplicate'            => 'Duplicate',
                'delete'               => 'Delete',
                'new'                  => 'New'
            ]
        ],
        'label'                        => [
            'overall_area'             => 'Overall Area',
            'asset_tax'                => 'Asset Tax / m<sup>2</sup>',
            'building_area'            => 'Building Area',
            'area_type'                => 'Area Type',
            'building_ratio'           => 'Building Coverage Ratio',
            'floor_ratio'              => 'Floor Area Ratio',
            'closing_date'             => 'Estimated Closing Date',
            'building_restriction'     => 'Building Restrictions',
            'minimum_area'             => 'Minimum Area',
            'retaining_wall'           => [
                'location'             => 'Retaining Wall (Location)',
                'breakage'             => 'Retaining Wall (Breakage)',
            ],
            'water'                    => [
                'supply'               => 'Supply',
                'meter'                => 'Meter',
                'unit'                 => 'mm'
            ],
            'other'                    => 'Other'
        ],
        'question'                     => [
            'soil_contamination'       => 'Potential soil contamination',
            'cultural_property'        => 'Are you in the cultural property reserve area?',
            'district_planning'        => 'Do you have a district plan?',
            'building_restriction'     => '(If any) Building usage restrictions and minimum area',
            'elevation'                => 'What is the difference in elevation with the neighboring area?',
            'retaining_wall'           => 'Is there a retaining wall near the area?',
            'water'                    => 'Is there water service in the residential?',
            'water_capacity'           => '(If any) How many millimeters',
            'sewage'                   => 'Is sewage properly drained in the residential?',
            'water_extended'           => 'Please check the applicable items for water and sewage.',
            'obstructive_pole'         => 'Are there any obstructive telephone poles inside or outside the property area?',
            'obstructive_pole_any'     => '(If any)',
            'road'                     => 'Main road width',
            'positive'                 => 'Positive values',
            'negative'                 => 'Negative values',
            'survey'                   => 'Scheduled survey and area renewal registration',
            'survey_season'            => '※ Survey periode',
            'survey_reason'            => '※ Reason for not implementing',
            'requirement'              => 'Do all blocks meet the requirements?'
        ],
        'choices'                      => [
            'yes'                      => 'Yes',
            'no'                       => 'No',
            'almost_none'              => 'Almost None',
            'unknown'                  => 'Unknown',
            'included'                 => 'Included',
            'not_included'             => 'Not Included',
            'unconfirmed'              => 'Unconfirmed',
            'elevation'                => [
                'under_half'           => '0 ~ 0.5m',
                'half_one'             => '0.5 ~ 1m',
                'one_two'              => '1 ~ 2m',
                'above_two'            => 'Above 2m'
            ],
            'retaining_wall'           => [
                'current_area'         => 'Current Area',
                'neighbouring_land'    => 'Neighbouring Land',
                'damaged'              => 'Damaged',
                'no_damage'            => 'No Damage'
            ],
            'water'                    => [
                'private_pipe'         => 'Private Pipe',
                'crossing_others'      => 'Crossing Other Land',
                'insufficient'         => 'Insufficient Capacity'
            ],
            'pole'                     => [
                'movable'              => 'Can be Moved',
                'not_movable'          => 'Cannot be Moved',
                'unknown'              => 'Unknown',
                'expensive'            => 'Can be Expensive'
            ],
            'road'                     => [
                'under_four'           => 'Under 4m',
                'four_six'             => '4m to 6m',
                'above_six'            => 'Above 6m'
            ],
            'positive'                 => [
                'popular'              => 'Popular Area',
                'value'                => 'High Value'
            ],
            'negative'                 => [
                'sale'                 => 'Difficult Sale',
                'landslide'            => 'Landslide or Other',
                'defect'               => 'Psychological Defect'
            ],
            'survey'                   => [
                'sellers'              => 'Seller\'s Burden',
                'buyers'               => 'Buyers\'s Burden',
                'completed'            => 'Completed',
                'not_implemented'      => 'Not Implemented',
                'season'               => [
                    'after_earthquake' => 'After Earthquake',
                    'before_heisei'    => 'Before Earthquake (Heisei)',
                    'before_showa'     => 'Before Earthquake (Showa)',
                    'unconfirmed'      => 'Unconfirmed'
                ],
                'reason'               => [
                    'restoration'      => 'Boundary Restoration Only',
                    'not_required'     => 'Not Required (Land Readjustment)'
                ]
            ],
            'requirement'              => [
                'yes'                  => 'Yes They Do',
                'no'                   => 'They Do Not'
            ]
        ],
        'checklist'                    => [
            'group'                    => [
                'division'             => 'Division',
                'demolition'           => 'Demolition work',
                'construction'         => [
                    'non_development'  => [
                        'heading'      => 'Construction (Without development)',
                        'subtitle'     => 'Please answer the following questions.'
                    ],
                    'development'      => 'Construction (Development)'
                ],
                'driveway'             => 'In case it involves accessing other\'s private road'

            ],
            'label'                    => [
                'breakthrough'         => 'Breakthrough Rate',
                'effective_area'       => 'Effective Area',
                'loan'                 => 'Loan Amount',
                'brokerage_fee'        => 'Brokerage Fee',
                'resale_fee'           => 'Resale Brokerage Fee',
                'realistic_division'   => 'Is the ward division a realistic way to enter the building?',
                'building_type'        => 'What kind of building?',
                'asbestos'             => 'Presence of asbestos',
                'cost_factor'          => 'What is the cost factor?	',
                'water_access'         => 'How many location can you access the water source from',
                'location'             => 'Location',
                'road'                 => [
                    'type'             => 'What kind of road does the property have?',
                    'dimension'        => 'What is the road dimension?',
                    'gutter'           => 'Does it have gutters?',
                    'gutter_length'    => 'What is the length of the gutter?',
                    'embankment'       => 'What is the threshold of embankment?'
                ],
                'retaining_wall'       => [
                    'construction'     => 'Retaining wall construction',
                    'height'           => 'Retaining wall height (m)',
                    'length'           => 'Retaining wall length'
                ],
                'development'          => [
                    'cost'             => 'Estimated development costs'
                ],
                'driveway'             => [
                    'road_sharing'     => 'Does road sharing required?',
                    'road_consent'     => 'Can I get a traffic drill agreement?'
                ]
            ],
            'option'                   => [
                'fee'                  => [
                    'expense'          => 'Expense',
                    'revenue'          => 'Revenue',
                    'none'             => 'None'
                ],
                'sales_area'           => [
                    'single'           => 'Property to be sold in one block',
                    'multi'            => 'Property to be sold in two or more blocks' 
                ],
                'demolition'           => [
                    'building'         => 'Involves building demolition work',
                    'retaining_wall'   => 'Involves retaining walls demolition work'
                ],
                'construction'         => [
                    'none'             => 'No construction work',
                    'development'      => 'Construction work - Development',
                    'non_development'  => 'Construction work - Non-development',
                    'cost'             => [
                        'flat'         => 'Flat ground',
                        'one'          => 'Height difference is ~ 1m',
                        'two'          => 'Height difference is ~ 2m',
                        'above_two'    => 'Height difference is above 2m'
                    ],
                    'distant'          => 'Distant main infrastructurea'
                ],
                'driveway'             => 'Involves other\'s private road',
                'realistic_division'   => [
                    'yes'              => 'Yes',
                    'no'               => 'No'
                ],
                'building_type'        => [
                    'wood'             => 'Wooden',
                    'steel'            => 'Steel frame'
                ],
                'asbestos'             => [
                    'yes'              => 'There some',
                    'no'               => 'None'
                ],
                'cost_factor'          => [
                    'obstacles'        => 'Many trees, stones, fences, etc',
                    'storeroom'        => 'Big Storeroom',
                    'accessibility'    => 'Difficult access for heavy machinery'
                ],
                'road'                 => [
                    'designated'       => 'Designated road',
                    'private'          => 'Private road',
                    'none'             => 'None',
                    'width'            => 'Width',
                    'length'           => 'Length',
                    'gutter'           => [
                        'one_side'     => 'One side',
                        'both_sides'   => 'Both sides',
                        'none'         => 'None'
                    ],
                    'embankment'       => [
                        'none'         => 'Without embankment'
                    ],
                    'sharing'          => [
                        'yes'          => 'Yes',
                        'no'           => 'No'
                    ],
                    'consent'          => [
                        'yes'          => 'Yes',
                        'no'           => 'None'
                    ]
                ],
                'retaining_wall'       => [
                    'construction'     => [
                        'yes'          => 'Build a new retaining wall',
                        'no'           => 'Do not build a retaining wall'
                    ],
                    'more'             => 'More'
                    
                ]
            ]
        ],
        'expense'                      => [
            'heading'                  => [
                'budget'               => 'Budget',
                'total'                => 'Total',
                'registration'         => 'Registration-related Expenses',
                'finance'              => 'Loan-related Expenses',
                'tax'                  => 'Taxes',
                'construction'         => 'Construction-related Expenses',
                'survey'               => 'Survey-related Expenses',
                'other'                => 'Other Expenses'
            ],
            'label'                    => [
                'additional'           => 'Add Row',
                'auto'                 => '<i class"fas fa-calculator"></i>',
                'loan'                 => [
                    'amount'           => 'Amount',
                    'expected'         => 'Expected'
                ],
                'tsubo_price'          => 'Price',
                'settlement'           => [
                    'date'             => 'Date',
                    'schedule'         => 'Schedule'
                ],
                'purchase'             => [
                    'price'            => 'Expected Purchase Price',
                    'commission'        => 'Broker Commission on Purchase'
                ],
                'registration'         => [
                    'total'            => 'Registration Expenses',
                    'ownership'        => 'Ownership Transfer',
                    'mortage'          => 'Mortage Registration',
                    'asset_tax'        => 'Fixed City Tax',
                    'loss'             => 'Loss Registration'
                ],
                'finance'              => [
                    'total'            => 'Loan Expenses',
                    'interest'         => 'Intereset Rate',
                    'banking'          => 'Banking Fee',
                    'stamp'            => 'Stamp (Bill)',
                    'rate'             => 'Rate',
                    'rate_alt'         => 'Rate'
                ],
                'tax'                  => [
                    'total'            => 'Taxes',
                    'acquisition'      => 'Property Acquisition Tax',
                    'acquisition_note' => '※ Property acquisition tax is not reflected in the expenditure section',
                    'annual'           => 'The Following Year Tax'
                ],
                'construction'         => [
                    'total'            => 'Construction Expenses',
                    'demolition'       => [
                        'building'     => 'Building Demolition',
                        'wall'         => 'Retaining Wall Demolition'
                    ],
                    'pole_relocation'  => 'Obstructive Pole Relocation',
                    'plumbing'         => 'Water and Sewage Works',
                    'embankment'       => 'Embankment Construction',
                    'retaining_wall'   => 'Retaining Wall Construction',
                    'road'             => 'Road Construction',
                    'gutter'           => 'Gutter Construction',
                    'workset'          => 'Construction Work Set',
                    'location'         => 'Location Designation Fee',
                    'commission'       => 'Development Commission',
                    'cultural'         => 'Cultural Property Fee'
                ],
                'survey'               => [
                    'total'            => 'Survey Expenses',
                    'fixed'            => 'Fixed Survey',
                    'divisional'       => 'Divisional Registration',
                    'boundry'          => 'Boundry Restoration'
                ],
                'other'                => [
                    'total'            => 'Other Expenses',
                    'referral'         => 'Referral Fee',
                    'eviction'         => 'Eviction Fee',
                    'water'            => 'Prepaid Water Subscription'
                ],
                'total'                => [
                    'budget'           => 'Total Budget',
                    'amount'           => 'Total Decision',
                    'tsubo'            => 'Per Tsubo'
                ]
            ],
            'option'                   => [
                'purchase'             => [
                    'commission'        => [
                        'income'       => 'Income',
                        'expense'      => 'Expense',
                        'none'         => 'None'
                    ]
                ]
            ]
        ],
        'sale'                         => [
            
        ]
    ]
    // ----------------------------------------------------------------------
];
