<?php
include_once('../vendor/autoload.php');

// This is your pre-defined settings array
$settings = [
    'basic' => [
        'installation_type' => [
            'type' => '["single","cluster"]',
            'description' => 'bla blah',
            'readonly' => false,
            'hidden' => false,
            'trigger' => null,
            'default' => 'single'
        ],
        'db_master_host' => [
            'type' => 'ip',
            'description' => 'Database hostname or IP',
            'default' => 'localhost'
        ],
        'db_master_user' => [
            'type' => 'text',
            'description' => 'Database username',
            'default' => 'test'
        ],
        'db_master_pwd' => [
            'type' => 'secret',
            'description' => 'Database user password',
        ],
        'db_master_db' => [
            'type' => 'text',
            'description' => 'Database name',
            'default' => 'test'
        ]
    ],
    'provisioning' => [
        'snom' => [
            'snom_prov_enabled' => [
                'type' => 'switch',
                'default' => false
            ],
            'snom_m3' => [
                'snom_m3_accounts' => [
                    'type' => 'number',
                    'description' => 'bla blah',
                    'default' => '0'
                ]
            ],
            'snom_dect' => [
                'snom_dect_enabled' => [
                    'type' => 'switch',
                    'description' => 'bla blah',
                    'default' => false
                ]
            ]
        ],
        'yealink' => [
            'yealink_prov_enabled' => [
                'type' => 'switch',
                'default' => false
            ]
        ]
    ]
];

$categories = [];    // array<string, array{id: int, parent: int, name: string, order: int}>
$settingsList = [];  // array<string, array{id: int, catId: int, name: string, type: string|null, desc: string|null, readonly?: bool|null, hidden?: bool|null, trigger?: string|null, order: int}>
$values = [];        // array<string, array{id: int, setId: int, default: mixed}>

$catId = 1;
$setId = 1;
$valId = 1;

$order = 1;

/**
 * Recursively process nested config array into flat category, setting, value arrays.
 */
function processCategory(
    array $array,
    int $parentId,
    array &$categories,
    array &$settingsList,
    array &$values,
    int &$catId,
    int &$setId,
    int &$valId,
    int &$order
): void {
    foreach ($array as $key => $item) {
        if (is_array($item) && isAssoc($item)) {
            if (isset($item['type'])) {
                // setting
                $currentSetId = $setId++;
                $settingsList[$key] = [
                    'id' => $currentSetId,
                    'catId' => $parentId,
                    'name' => $key,
                    'type' => $item['type'],
                    'desc' => $item['description'] ?? null,
                    'readonly' => $item['readonly'] ?? null,
                    'hidden' => $item['hidden'] ?? null,
                    'trigger' => $item['trigger'] ?? null,
                    'order' => $order++,
                ];

                $values[$key] = [
                    'id' => $valId++,
                    'setId' => $currentSetId,
                    'default' => $item['default'] ?? null,
                ];
            } else {
                // This is a category
                $currentCatId = $catId++;
                $categories[$key] = [
                    'id' => $currentCatId,
                    'parent' => $parentId,
                    'name' => $key,
                    'order' => $order++,
                ];

                // Process child items under this category
                processCategory($item, $currentCatId, $categories, $settingsList, $values, $catId, $setId, $valId, $order);
            }
        }
    }
}

/**
 * Check if the array is associative.
 */
function isAssoc(array $arr): bool
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

/**
 * Determine if an array contains at least one sub-setting (based on 'type' or 'default').
 */
function containsSetting(array $arr): bool
{
    foreach ($arr as $val) {
        if (is_array($val) && (isset($val['type']) || isset($val['default']))) {
            return true;
        }
    }
    return false;
}

// Run your flattening
processCategory($settings, 0, $categories, $settingsList, $values, $catId, $setId, $valId, $order);

// Dumping the results
echo "--- Categories ---\n";
echo "<pre>";
print_r($categories);
echo "--- Settings ---\n";
print_r($settingsList);
echo "--- Values ---\n";
print_r($values);
echo "</pre>";
