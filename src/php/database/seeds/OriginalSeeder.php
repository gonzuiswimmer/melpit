<?php

use Illuminate\Database\Seeder;
use App\Models\Original;

class OriginalSeeder extends Seeder
{
  /**
   * File name to import
   */
  public $filename = 'train.tsv';
  /**
   * Delimiter to split a line
   */
  public $delimiter = "\t";

  /**
   * Run the database seeds.
   * @param void
   */
  public function run()
  {
    // メモリの上限を変更
    ini_set("memory_limit", "512M");

    if(empty($this->filename)) throw new Exception('$filename not specified.');
    if(empty($this->delimiter)) throw new Exception('$delimiter not specified.');

    $file = new SplFileObject(__DIR__.'/data/'.$this->filename);
    $file->setFlags(
      \SplFileObject::READ_AHEAD |
      \SplFileObject::SKIP_EMPTY |
      \SplFileObject::DROP_NEW_LINE
    );
    $lists = [];
    $inserts = 0;

    foreach($file as $lineNumber => $line){
      $row = explode($this->delimiter,$line);
      if($lineNumber > 0){
        $lists[] = [
          'name' => $row[1],
          'condition_id' => $row[2],
          'category_name' => $row[3],
          'brand' => $row[4],
          'price' => $row[5],
          'shipping' => $row[6],
          'description' => $row[7],
        ];
        // データが1000件になったらクエリを発行し、$listsを初期化する
        if(count($lists) >= 1000){
          $inserts += count($lists);
          DB::table('originals')->insert($lists);
          unset($lists);
          $lists = [];
        }
      }
    }
    $inserts += count($lists);

    // 余った行のBULK INSERT SQLを発行
    DB::table('originals')->insert($lists);

    // 実際にINSERTした行数とcsvの行数を確認
    $this->command->info("inserts:".$inserts);
  }
}
