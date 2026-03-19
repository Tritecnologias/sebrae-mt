<?php

namespace App\Services;

use Exception;
use SoapClient;
use SoapFault;

class CepService
{
    private const CORREIOS_WSDL = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
    private const VIACEP_URL    = 'https://viacep.com.br/ws/%s/json/';

    /**
     * Consulta CEP tentando primeiro o SOAP dos Correios,
     * com fallback automático para o ViaCEP.
     */
    public function consultarCep(string $cep): array
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            throw new Exception('CEP inválido. Informe 8 dígitos.');
        }

        try {
            return $this->consultarCorreiosSoap($cep);
        } catch (Exception) {
            // Correios indisponível — tenta ViaCEP
            return $this->consultarViaCep($cep);
        }
    }

    private function consultarCorreiosSoap(string $cep): array
    {
        $client = new SoapClient(self::CORREIOS_WSDL, [
            'stream_context' => stream_context_create([
                'http' => [
                    'protocol_version' => '1.0',
                    'header'           => 'Connection: Close',
                ],
            ]),
            'exceptions'         => true,
            'connection_timeout' => 5,
        ]);

        $result = $client->consultaCEP(['cep' => $cep]);
        $dados  = $result->return;

        return [
            'cep'    => $cep,
            'rua'    => $dados->end    ?? '',
            'bairro' => $dados->bairro ?? '',
            'cidade' => $dados->cidade ?? '',
            'estado' => $dados->uf     ?? '',
        ];
    }

    private function consultarViaCep(string $cep): array
    {
        $url      = sprintf(self::VIACEP_URL, $cep);
        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception('CEP não encontrado. Verifique o número informado.');
        }

        $dados = json_decode($response, true);

        if (isset($dados['erro']) && $dados['erro']) {
            throw new Exception('CEP não encontrado. Verifique o número informado.');
        }

        return [
            'cep'    => $cep,
            'rua'    => $dados['logradouro'] ?? '',
            'bairro' => $dados['bairro']     ?? '',
            'cidade' => $dados['localidade'] ?? '',
            'estado' => $dados['uf']         ?? '',
        ];
    }
}
