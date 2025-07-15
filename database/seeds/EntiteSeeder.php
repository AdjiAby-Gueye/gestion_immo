<?php

use App\Entite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EntiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entites = [
            [
                "designation" => "UVS",
                "description" => "UVS",
                "image" => "sertem.png",
                "code" => "SCI",
            ],
            [
                "designation" => "RIDWAN",
                "description" => "RIDWAN",
                "image" => "sertem.png",
                "code" => "RID",
            ]
        ];

        foreach ($entites as $entiteData) {
            $imagePath = public_path('images/' . $entiteData['image']);
            $uploadedImage = null;

            if (file_exists($imagePath)) {
                $storagePath = 'uploads/entites/' . $entiteData['image'];
                if (!Storage::disk('public')->exists($storagePath)) {
                    Storage::disk('public')->put($storagePath, file_get_contents($imagePath));
                }
                $uploadedImage = $storagePath;
            } else {
                echo "⚠ L'image n'existe pas pour " . $entiteData['designation'] . PHP_EOL;
            }

            Entite::firstOrCreate(
                ['designation' => $entiteData['designation']],
                [
                    'description' => $entiteData['description'],
                    'code' => $entiteData['code'],
                    'image' => $uploadedImage
                ]
            );
        }
    }
}
