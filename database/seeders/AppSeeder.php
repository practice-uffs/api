<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\App;

class AppSeeder extends Seeder
{
    /**
     * Roda o seeder para criar as apps oficiais do programa
     *
     * @return void
     */
    public function run()
    {
        App::create([
            'name' => 'Practice Mural',
            'secret' => 'base64:DRblYPj36Ga4hSGexZC7pk9F1odpb72A9RWBq0INcQ8=',
            'api_url' => 'http://localhost:8000/api/',
            'slug' => 'mural',
            'description' => 'Solicitação de serviços ao programa.',
            'domain' => 'practice.uffs.edu.br',
        ]);

        App::create([
            'name' => 'Practice Maker',
            'secret' => 'base64:DRblYPj36Ga4hSGexZC7pk9F1odpb72A9RWBq0INcQ8=',
            'api_url' => 'http://localhost:8000/api/',
            'slug' => 'maker',
            'description' => 'Criação de conteúdo digital.',
            'domain' => 'practice.uffs.edu.br',
        ]); 

        App::create([
            'name' => 'Practice Forms',
            'secret' => 'base64:DRblYPj36Ga4hSGexZC7pk9F1odpb72A9RWBq0INcQ8=',
            'api_url' => 'http://localhost:8000/api/',
            'slug' => 'forms',
            'description' => 'Questionários e quiz online.',
            'domain' => 'practice.uffs.edu.br',
        ]);        
    }
}
