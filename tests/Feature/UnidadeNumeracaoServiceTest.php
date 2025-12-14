<?php

namespace Tests\Unit;

use App\Service\UnidadeNumeracaoService;
use Tests\TestCase;

class UnidadeNumeracaoServiceTest extends TestCase
{
   public function test_sem_prefixo_comeca_com_zero()
{
    $service = new UnidadeNumeracaoService();

    $this->assertEquals(
        ['01','02','03','04','11','12','13','14'],
        $service->gerarNumeracao('01-04', 2)
    );
}

public function test_prefixo_1()
{
    $service = new UnidadeNumeracaoService();


    $this->assertEquals(
        ['101','102','103','104','111','112','113','114'],
        $service->gerarNumeracao('1/01-04', 2)
    );
}

public function test_prefixo_10()
{
    $service = new UnidadeNumeracaoService();

    $this->assertEquals(
        ['1001','1002','1003','1004','1011','1012','1013','1014'],
        $service->gerarNumeracao('10/01-04', 2)
    );
}



}
