<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnvironmentController extends Controller
{
    /**
     * Lista as informaçõe de ambiente de um usuário
     */
    public function index(Request $request)
    {

        $user = auth()->user();

        //get env.json from database using the user

        //data example
        $data = '[
            {
                "name": "Nome do env",
                "description": "Descrição do env",
                "live": false,
                "app": "https://practice.uffs.edu.br/forms",
                "type": "notice",
                "icon_url": "https://practice.uffs.edu.br/img/icon.png",
                "actions": [
                    {
                        "name": "Ação 1",
                        "description": "Descrição da ação 1",
                        "url": "https://practice.uffs.edu.br",
                        "method": "GET",
                        "params": {
                        }
                    },
                    {
                        "name": "Ação 1",
                        "description": "Descrição da ação 2",
                        "url": "https://practice.uffs.edu.br/teste",
                        "method": "GET",
                        "params": {
                            "foo": "bar"
                        }
                    }
                ]
            },
            {
                "name": "Nome do env 2",
                "description": "Descrição do env 2",
                "live": false,
                "app": "https://practice.uffs.edu.br/forms",
                "type": "notice",
                "icon_url": "https://practice.uffs.edu.br/img/icon.png",
                "actions": [
                    {
                        "name": "Ação 1",
                        "description": "Descrição da ação 1",
                        "url": "https://practice.uffs.edu.br",
                        "method": "GET",
                        "params": {
                        }
                    },
                    {
                        "name": "Ação 1",
                        "description": "Descrição da ação 2",
                        "url": "https://practice.uffs.edu.br/teste",
                        "method": "GET",
                        "params": {
                            "foo": "bar"
                        }
                    }
                ]
            }
        ]';

        return response(
            $data,
            Response::HTTP_OK
        );
    }
}
