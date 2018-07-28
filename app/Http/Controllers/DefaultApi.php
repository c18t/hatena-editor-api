<?php

/**
 * Hatena Editor API
 * はてなダイアリーまたははてなグループをMarkdownまたはAsciiDocで更新するためのAPIです 
 *
 * OpenAPI spec version: 0.0.1
 * 
 *
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen.git
 * Do not edit the class manually.
 */


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class DefaultApi extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Operation convertFromAsciiDoc
     *
     * AsciiDocをはてな記法に変換します。.
     *
     *
     * @return Http response
     */
    public function convertFromAsciiDoc()
    {
        $asciidoc = Request::getContent();
        $hatena_lines = array();

        // 一時ファイルを作成
        $file = tmpfile();
        fwrite($file, $asciidoc);

        // 一時ファイルに対しpandocを実行して結果を取得
        $result = 0;
        $path = stream_get_meta_data($file)['uri'];
        $command = 'asciidoctor -b docbook5 -o - '.$path.' | ~/.local/bin/pandoc -f docbook -t hatena';
        exec($command, $hatena_lines, $result);
        fclose($file);

        $response_text = '';
        if ($result == 0) {
            // 変換成功
            $response_text = join("\n", $hatena_lines);
        }
        else {
            // エラー
            $response_text = 'エラーが発生しました。pandoc error code: '.$result;
        }

        return response($response_text)->header('Access-Control-Allow-Origin', '*');
    }
    /**
     * Operation convertFromMarkdown
     *
     * Markdownをはてな記法に変換します。.
     *
     *
     * @return Http response
     */
    public function convertFromMarkdown()
    {
        $markdown = Request::getContent();
        $hatena_lines = array();

        // 一時ファイルを作成
        $file = tmpfile();
        fwrite($file, $markdown);

        // 一時ファイルに対しpandocを実行して結果を取得
        $result = 0;
        $path = stream_get_meta_data($file)['uri'];
        $command = '~/.local/bin/pandoc -f markdown_github -t hatena '.$path;
        exec($command, $hatena_lines, $result);
        fclose($file);

        $response_text = '';
        if ($result == 0) {
            // 変換成功
            $response_text = join("\n", $hatena_lines);
        }
        else {
            // エラー
            $response_text = 'エラーが発生しました。pandoc error code: '.$result;
        }

        return response($response_text)->header('Access-Control-Allow-Origin', '*');;
    }
}
