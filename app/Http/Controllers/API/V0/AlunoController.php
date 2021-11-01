<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use App\Cli\SgaScraper;
use Illuminate\Support\Facades\Cache;

class AlunoController extends Controller
{
    protected SgaScraper $sga;
    
    public function __construct(SgaScraper $sga)
    {
        $this->sga = $sga;
    }

    protected function hash(array $array)
    {
        return hash('sha256', json_encode($array));
    }
    
    protected function getCredenciais()
    {
        $target = 'uffs.edu.br';
        
        $user = auth()->user();
        $scraper = $user->scrapers()->where('target', $target)->first();

        if(!$scraper) {
            throw new \Exception("Usuario [$user->uid] não possui scraper [$target] registrado para uso.");
        }

        $credenciais = [
            'usuario' => $scraper->access_user,
            'senha' => $scraper->access_password,
        ];

        return $credenciais;
    }

    /**
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function historico()
    {
        $credenciais = $this->getCredenciais();

        $sga = $this->sga;
        $key = $this->hash($credenciais);
        $ttlDays = 7;

        $info = Cache::remember($key, 60 * 60 * 24 * $ttlDays, function() use ($sga, $credenciais) {
            return $sga->usando($credenciais, 'aluno')->historico()->get();
        });

        return $this->json($info);
    }
}
