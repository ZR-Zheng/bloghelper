<?php
isset($_GET['id']) ? $id = $_GET['id'] : exit('error');

require '../tools/modelList.php';
require '../tools/modelTextures.php';
require '../tools/jsonCompatible.php';

$modelList = new modelList();
$modelTextures = new modelTextures();
$jsonCompatible = new jsonCompatible();

// 分割$id并转换为整数
list($modelId, $modelTexturesId) = array_map('intval', explode('-', $id) + [0, 0]);

// 获取模型名称
$modelName = $modelList->id_to_name($modelId);

// 读取模型的JSON文件
$jsonFilePath = '../model/' . $modelName . '/index.json';
$json = json_decode(file_get_contents($jsonFilePath), true);

// 如果$modelName是数组，根据$modelTexturesId选择具体的模型名称
if (is_array($modelName)) {
    $modelName = $modelTexturesId > 0 ? $modelName[$modelTexturesId - 1] : $modelName[0];
    $json = json_decode(file_get_contents($jsonFilePath), true);
} else {
    $json = json_decode(file_get_contents($jsonFilePath), true);
    if ($modelTexturesId > 0) {
        $modelTexturesName = $modelTextures->get_name($modelName, $modelTexturesId);
        if (isset($modelTexturesName)) {
            $json['textures'] = is_array($modelTexturesName) ? $modelTexturesName : [$modelTexturesName];
        }
    }
}

// 更新纹理路径
foreach ($json['textures'] as $k => $texture) {
    $json['textures'][$k] = '../model/' . $modelName . '/' . $texture;
}

// 更新模型路径
$json['model'] = '../model/' . $modelName . '/' . $json['model'];

// 更新姿势路径（如果存在）
if (isset($json['pose'])) {
    $json['pose'] = '../model/' . $modelName . '/' . $json['pose'];
}

// 更新物理路径（如果存在）
if (isset($json['physics'])) {
    $json['physics'] = '../model/' . $modelName . '/' . $json['physics'];
}

// 更新动作路径（如果存在）
if (isset($json['motions'])) {
    foreach ($json['motions'] as $k => $v) foreach($v as $k2 => $v2) foreach ($v2 as $k3 => $motion)
        if ($k3 == 'file') $json['motions'][$k][$k2][$k3] = '../model/' . $modelName . '/' . $motion;
}

// 更新表情路径（如果存在）
if (isset($json['expressions'])) {
    foreach ($json['expressions'] as $k => $v) foreach($v as $k2 => $expression)
        if ($k2 == 'file') $json['expressions'][$k][$k2] = '../model/' . $modelName . '/' . $expression;
}

header("Content-type: application/json");
echo $jsonCompatible->json_encode($json);
