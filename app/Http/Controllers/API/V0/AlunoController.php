<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use App\Cli\SgaScraper;

class AlunoController extends Controller
{
    protected SgaScraper $sga;
    
    public function __construct(SgaScraper $sga)
    {
        $this->sga = $sga;
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
        $info = $this->sga->usando($credenciais)->historico()->get();

        return $this->json($info);
    }
}
