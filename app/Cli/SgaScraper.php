<?php

namespace App\Cli;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * 
 */
class SgaScraper
{
    protected array $config;
    protected array $requests;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function runCli($args = '')
    {
        $output = [];
        $code = -1;
        $sga = $this->config['bin'];
        $configPath = $this->config['config_path'];
        $cmd = "$sga $args --config=\"$configPath\"";

        exec($cmd, $output, $code);

        if ($code != 0) {
            throw new \Exception("Error running sgascraper (code $code): " . implode("\n", $output));
        }

        return json_decode(implode('', $output));
    }

    protected function runRequests($requests)
    {
        if (count($requests) == 0 || !isset($requests['pedidos'])) {
            throw  new \Exception('Lista de comandos está vazia. Use sga()->usando()->...->get()');
        }

        if (!isset($requests['credenciais'])) {
            throw new \Exception('Credenciais de acesso não informadas. Use sga()->usando()->...');
        }

        $args = '';

        foreach($requests['credenciais'] as $key => $value) {
            $args .= "--$key=\"$value\" ";
        }

        $args .= implode(' ', $requests['pedidos']);

        $result = $this->runCli($args);
        return collect($result);
    }

    /**
     * Especifica como o scraper rodará para obter os dados.
     * 
     * @param array $credenciais vetor associativo com as credenciais de acesso (campos obrigatórios: usuario, senha).
     * @param string $runner runner que será executado. Disponíveis: `aluno` (portal do aluno) e `professor` (portal do professor/coordenador).
     */
    public function usando(array $credenciais, string $runner)
    {
        if (empty($runner)) {
            throw new \Exception('Nenhum runner informado. Use sga()->usando([...], "aluno"), por exemplo.');
        }
        
        if (!isset($credenciais['usuario']) || empty($credenciais['usuario'])) {
            throw new \Exception('Nenhum usuário informado nas credenciais. Use sga()->usando(["usuario" => "...", "senha" => "..."])');
        }

        if (!isset($credenciais['senha']) || empty($credenciais['senha'])) {
            throw new \Exception('Nenhuma senha informada nas credenciais. Use sga()->usando(["usuario" => "...", "senha" => "..."])');
        }

        $this->requests['credenciais'] = [
            'usuario' => $credenciais['usuario'],
            'senha' => $credenciais['senha'],
        ];

        if (isset($credenciais['matricula'])) {
            $this->requests['credenciais']['matricula'] = $credenciais['matricula'];
        }

        $this->pushPedido($runner);

        return $this;
    }

    protected function pushPedido($nome)
    {
        if (!isset($this->requests['pedidos'])) {
            $this->requests['pedidos'] = [];
        }

        $this->requests['pedidos'][] = $nome;
    }

    public function alunos()
    {
        $this->pushPedido('--alunos');
        return $this;
    }

    public function conclusoes()
    {
        $this->pushPedido('--conclusoes');
        return $this;
    }

    public function historico($pdf = false, $conclusao = false)
    {
        $this->pushPedido('--historico');

        if($pdf) {
            $this->pushPedido('--historico-pdf');
        }

        if($conclusao) {
            $this->pushPedido('--conclusao-pdf');
        }        

        return $this;
    }

    public function get()
    {
        $result = $this->runRequests($this->requests);
        $this->requests = [];

        return $result;
    }
}