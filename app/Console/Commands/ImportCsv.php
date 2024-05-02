<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportCsv extends Command
{
  /**
   * コンソール コマンドの名前と署名
   *
   * @var string
   */
  protected $signature = 'app:import-csv';

  /**
   * コンソールコマンドの説明
   *
   * @var string
   */
  protected $description = 'CSVファイルのインポート';

  /**
   * コンソールコマンドを実行します
   */
  public function handle()
  {
    // ファイル名の配列
    $tables = [
      'users',
      'articles_of_recipe',
      'materials',
      'recipe_steps',
      'articles_of_item',
      'items',
      'reports',
      'restaurants'
    ];

    DB::transaction(function () use ($tables) {
      foreach ($tables as $table) {
        Log::debug($table);
        // CSVファイルを開く
        if (($handle = fopen("database/dummy/$table.csv", "r")) !== FALSE) {
          // CSVの1行目（カラム名）を読み込む
          $columns = fgetcsv($handle, 1000, ",");

          $countRow = 0;
          // CSVファイルの2行目移行の各行を読み込む
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $countRow++;
            Log::debug("row: $countRow");
            if ($data) {
              Log::debug(count($columns));
              Log::debug(count($data));
              // カラム名(キー)とデータ(値)を組み合わせて連想配列を作成
              $record = array_combine($columns, $data);

              // データベースに新しいレコードを挿入
              DB::table($table)->insert($record);
            }
          }
          // CSVファイルを閉じる
          fclose($handle);
        }
      }
    });
  }
}
