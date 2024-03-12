<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseSeeder extends Seeder
{
  public function run()
  {
    $supabaseUrl = env('SUPABASE_BACKET_URL');
    $apiKey = env('SUPABASE_ANON_KEY');
    $bucketName = env('SUPABASE_BUCKET');
    Log::debug($supabaseUrl);
    Log::debug($apiKey);
    Log::debug($bucketName);

    // アップロードするファイルのパス
    // $filePaths = [
    //   storage_path('app/public/file1.jpg'),
    //   storage_path('app/public/file2.jpg'),
    //   // 他のファイルのパスも追加
    // ];

    $filePaths = [];
    for ($i = 0; $i < 10; $i++) {
      $filePaths[] = storage_path('app/public/victoria-shes-4MEL9XS-3JQ-unsplash.jpg');
    };

    // 各ファイルをアップロード
    foreach ($filePaths as $key => $filePath) {
      // /storage/app/public/example.jpg ➞ example.jpg
      // $fileName = basename($filePath);
      $fileName = 'file_' . $key . '.jpg';

      $response = Http::withHeaders([
        // 一般的な認証方式の1つで、トークンベースの認証を行う場合に使用される
        'Authorization' => 'Bearer ' . $apiKey,

        // バイナリデータを含む任意のファイルを表す汎用的なMIMEタイプ
        // つまり、どのような形式のデータでも含むことができるという意味
        // 具体的なファイルの種類や形式を特定できない場合に使用されるらしい
        'Content-Type' => 'application/octet-stream',
        // ])->attach('file', file_get_contents($filePath), $fileName)->post($supabaseUrl . 'object/' . $bucketName . '/recipes/thumbnail/' . $fileName);
      ])->get($supabaseUrl . 'object/' . $bucketName . '/recipes/thumbnail/demo_tumbnail.png');
      // Log::debug($supabaseUrl . '/object/public/' . $bucketName . '/recipes/thumbnail/' . $fileName);

      if ($response->successful()) {
        $this->command->info("File '{$fileName}' uploaded successfully.");
        Log::debug($response->json());
      } else {
        $this->command->error("Failed to upload file '{$fileName}'. Status code: {$response->status()}. Error: {$response->body()}");
        Log::debug($response->json());
      }
    }
  }
}
