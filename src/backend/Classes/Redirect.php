<?php

namespace SToyokura\Classes;

class Redirect
{
    private $to_pages_folder_absolute_path = __DIR__ . "/../../service/pages";

    /**
     * pagesフォルダ内の情報を配列にして返す
     * @param void
     * @return array $page_list_arr pagesフォルダ内ファイル名をキー、ファイルまでの絶対パスをバリューとした配列
     */
    public function getPageListArr() {
        $files_absolute_path = glob($this->to_pages_folder_absolute_path . "/*.php");
        $document_root_path  = $_SERVER["DOCUMENT_ROOT"];
        foreach($files_absolute_path as $path) {
            $page_name                 = pathinfo($path, PATHINFO_FILENAME);
            $for_header_location_path  = str_replace($document_root_path, "", $path);
            $page_list_arr[$page_name] = $for_header_location_path;
        }
        return $page_list_arr;
    }

    /**
     * 指定されたページに遷移する関数
     * @param string $page_name pagesフォルダ内のファイル名（拡張子除く）
     * @return void
     */
    public function go($page_name) {
        $page_list_arr = $this->getPageListArr();
        header("Location: {$page_list_arr[$page_name]}");
        exit();
    }
}
