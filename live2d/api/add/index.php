<?php

require '../tools/modelList.php';
require '../tools/modelTextures.php';

// 创建 modelList 和 modelTextures 的实例
$modelListInstance = new modelList();
$modelTexturesInstance = new modelTextures();

// 获取模型列表
$modelList = $modelListInstance->get_list();
$modelList = $modelList['models'];

// 遍历模型列表
foreach ($modelList as $modelName) {
    // 检查模型名称是否为数组以及 textures.cache 文件是否存在
    if (!is_array($modelName) && file_exists('../model/'.$modelName.'/textures.cache')) {
        updateTexturesCache($modelName, $modelTexturesInstance);
    }
}

/**
 * 更新模型的 textures.cache 文件
 *
 * @param string $modelName 模型名称
 * @param object $modelTexturesInstance modelTextures 实例
 */
function updateTexturesCache($modelName, $modelTexturesInstance) {
    $textures = $texturesNew = array();

    // 获取模型的纹理列表
    $modelTexturesList = $modelTexturesInstance->get_list($modelName);
    $modelNameTextures = $modelTexturesInstance->get_textures($modelName);

    // 将纹理列表转换为 JSON 格式并存储在数组中
    if (is_array($modelTexturesList)) {
        foreach ($modelTexturesList['textures'] as $texture) {
            $textures[] = str_replace('\/', '/', json_encode($texture));
        }
    }

    // 将模型名称的纹理转换为 JSON 格式并存储在数组中
    if (is_array($modelNameTextures)) {
        foreach ($modelNameTextures as $texture) {
            $texturesNew[] = str_replace('\/', '/', json_encode($texture));
        }
    }

    // 计算新旧纹理的差异
    $texturesDiff = array_diff($texturesNew, $textures);

    // 如果旧纹理为空，直接返回
    if (empty($textures)) {
        return;
    } elseif (empty($texturesDiff)) {
        // 如果没有差异，输出无更新信息
        echo '<p>'.$modelName.' / textures.cache / No Update.</p>';
    } else {
        // 合并新旧纹理并去重，然后更新 textures.cache 文件
        $texturesMerge = array_values(array_unique(array_merge($textures, $texturesNew)));
        file_put_contents('../model/'.$modelName.'/textures.cache', str_replace('\/', '/', json_encode($texturesMerge)));
        echo '<p>'.$modelName.' / textures.cache / Updated.</p>';
    }
}
