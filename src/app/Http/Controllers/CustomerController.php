<?php

namespace App\Http\Controllers;

class CustomerController extends Controller {

    private $customers;

    public function __construct(){
        //Abrir o arquivo JSON dos customers e deixa disponível para todos os métodos
        $jsonPath = storage_path() . "/json/customers.json";
        $this->customers = json_decode(file_get_contents($jsonPath), true);
    }


    /**
     * Separar os parametros principais, como paginação e offset
     */
    public function initParametros(){
        //Caso nenhuma pagina seja passada, considerar 1
        $page = @$_GET["page"] ?? 1;
        $page--;
        if($page < 0){
            $page = 0;
        }
        //Caso nenhum offset for passado, considerar o minimo, 3
        $limit = @$_GET["limit"] ?? 3;

        if($limit < 3){//Caso alguem tente passar menos que 3 de offset, resetar para 3
            $limit = 3;
        } else if($limit > 10){//Caso alguem tente passar mais que 10 de offset, resetar para 10
            $limit = 10;
        }

        return [
            'page' => $page,
            'limit' => $limit
        ];
    }

    /**
     * Criar os filtros e faz a ordenação conforme forem utilizados no frontend
     */
    public function initFiltros(){

        //Primeiro vamos checar se alguma data foi informada, para entao depois filtrar
        $dataInicioFim = @$_GET["dataInicioFim"];
        if(!empty($dataInicioFim) && is_array($dataInicioFim) && count($dataInicioFim) == 2){
            $inicio = strtotime($dataInicioFim[0]);
            $fim = strtotime($dataInicioFim[1]);
            if($inicio < $fim){//Vamos continuar apenas se a data de inicio informada for menor que a data de fim
                $this->customers = array_filter($this->customers, function($c) use($inicio, $fim) {
                    $reg = strtotime($c['registered']);
                    return $reg > $inicio && $reg < $fim;
                });
            }
        }


        //Filtros dos botoes principais
        $filtro = @$_GET["filtro"];

        //Vamos remover antecipadamente tudo o que nao for numero e ponto do budget para poder ordenar posteriormente
        if($filtro == 'maior-budget' || $filtro == 'menor-budget'){
            foreach($this->customers as &$c){
                $c['budgetFiltro'] = (double)preg_replace("/[^0-9\.]/", "", $c['budget']);
            }
        }

        if(!empty($filtro)){
            switch($filtro){
                //Ordenação de maior budget
                case 'maior-budget':
                    usort($this->customers, function($a, $b) {
                        if ($a['budgetFiltro'] == $b['budgetFiltro']) {
                            return 0;
                        }
                        return ($a['budgetFiltro'] > $b['budgetFiltro']) ? -1 : 1;
                    });
                    break;

                //Ordenação de menor budget
                case 'menor-budget':
                    usort($this->customers, function($a, $b) {
                        if ($a['budgetFiltro'] == $b['budgetFiltro']) {
                            return 0;
                        }
                        return ($a['budgetFiltro'] < $b['budgetFiltro']) ? -1 : 1;
                    });
                    break;

                //Ordenação A-Z
                case 'asc':
                    usort($this->customers, function($a, $b) {
                        return strcmp($a['name']['first'], $b['name']['first']);
                    });
                    break;
                //Ordenação Z-A
                case 'desc':
                    usort($this->customers, function($a, $b) {
                        return strcmp($b['name']['first'], $a['name']['first']);
                    });
                    break;

                default:
                    break;
            }
        }

    }

    /**
     * Obtem os parametros iniciais, faz a filtragem, ordenação e paginação necessárias e retorna um array de customers
     *
     * @return array
     */
    public function get() {
        //PArametros de pagina e limit
        list('page' => $page, 'limit' => $limit) = $this->initParametros();

        //Filtros
        $this->initFiltros();

        return [
            'customers' => array_slice($this->customers, ($page*$limit), $limit),//Já paginado
            'totalPaginas' => ceil(count($this->customers)/$limit),
        ];
    }
}
